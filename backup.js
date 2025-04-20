/*jshint esversion:6*/

$(function () {
    const { InferenceEngine, CVImage } = inferencejs;
    const inferEngine = new InferenceEngine();

		const video = $("video")[0];
		var workerId;

		// Variables for countdown logic
		let detectionStartTime = null; // Tracks when detection first started
		const detectionDelay = 5000; // 5 seconds delay (in milliseconds)

		const startVideoStreamPromise = new Promise(function (resolve) {
			if (video.readyState >= 2) {
				console.log("Video is already loaded.");
				resolve();
			} else {
				$(video).on("loadeddata", function () {
					console.log("Video loaded after waiting.");
					resolve();
				});
			}
		});

		/*
		const startVideoStreamPromise = navigator.mediaDevices
				.getUserMedia({
						audio: false,
						video: {
								facingMode: cameraMode
						}
				})
				.then(function (stream) {
						return new Promise(function (resolve) {
								video.srcObject = stream;
								video.onloadeddata = function () {
										video.play();
										resolve();
								};
						});
				});
		*/

		const loadModelPromise = new Promise(function (resolve, reject) {
				inferEngine
						.startWorker("smokeeye", "4", "rf_p9SWCMVndgWykOzhJ52FWRJkYIo2")
						.then(function (id) {
								workerId = id;
								resolve();
						})
						.catch(reject);
		});

		Promise.all([startVideoStreamPromise, loadModelPromise]).then(function () {
				$("body").removeClass("loading");
				resizeCanvas();
				detectFrame();
		}).catch(function (err) {
			console.error("Initialization failed:", err);
		});;

		var canvas, ctx;
		const font = "16px sans-serif";

		function videoDimensions(video) {
				// Ratio of the video's intrisic dimensions
				var videoRatio = video.videoWidth / video.videoHeight;

				// The width and height of the video element
				var width = video.offsetWidth,
						height = video.offsetHeight;

				// The ratio of the element's width to its height
				var elementRatio = width / height;

				// If the video element is short and wide
				if (elementRatio > videoRatio) {
						width = height * videoRatio;
				} else {
						// It must be tall and thin, or exactly equal to the original ratio
						height = width / videoRatio;
				}

				return {
						width: width,
						height: height
				};
		}

		$(window).resize(function () {
				resizeCanvas();
		});

		const resizeCanvas = function () {
				$("canvas").remove();

				canvas = $("<canvas/>");

				ctx = canvas[0].getContext("2d");

				var dimensions = videoDimensions(video);

				console.log(
						video.videoWidth,
						video.videoHeight,
						video.offsetWidth,
						video.offsetHeight,
						dimensions
				);

				canvas[0].width = video.videoWidth;
				canvas[0].height = video.videoHeight;

				canvas.css({
						width: dimensions.width,
						height: dimensions.height,
						left: ($(window).width() - dimensions.width) / 2,
						top: ($(window).height() - dimensions.height) / 2
				});

				$("body").append(canvas);
		};

		const renderPredictions = function (predictions) {
				var scale = 1;

				ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);

				let isfireDetected = false;
				let isSmokeDetected = false;

				predictions.forEach(function (prediction) {
						// Adjust confidence threshold to render
						const fireConfidenceThreshold = 0.65;
						const smokeConfidenceThreshold = 0.53;

						if (prediction.class === "fire" && prediction.confidence >= fireConfidenceThreshold) {
								isfireDetected = true;
						} else if (prediction.class === "Smoke" && prediction.confidence >= smokeConfidenceThreshold) {
								isSmokeDetected = true;
						}

						const x = prediction.bbox.x;
						const y = prediction.bbox.y;
						const width = prediction.bbox.width;
						const height = prediction.bbox.height;

						// Draw the bounding box.
						ctx.strokeStyle = prediction.color;
						ctx.lineWidth = 4;
						ctx.strokeRect(
								(x - width / 2) / scale,
								(y - height / 2) / scale,
								width / scale,
								height / scale
						);

						// Draw the label background.
						ctx.fillStyle = prediction.color;
						const textWidth = ctx.measureText(prediction.class).width;
						const textHeight = parseInt(font, 10); // base 10
						ctx.fillRect(
								(x - width / 2) / scale,
								(y - height / 2) / scale,
								textWidth + 8,
								textHeight + 4
						);

						ctx.font = font;
						ctx.textBaseline = "top";
						ctx.fillStyle = "#000000";
						ctx.fillText(
								prediction.class,
								(x - width / 2) / scale + 4,
								(y - height / 2) / scale + 1
						);
				});

				// Countdown logic
				if (isfireDetected || isSmokeDetected) {
						if (!detectionStartTime) {
								// Start the countdown if it hasn't already started
								detectionStartTime = Date.now();
						} else {
								// Check if the detection has persisted for 5 seconds
								const currentTime = Date.now();
								if (currentTime - detectionStartTime >= detectionDelay) {
										// Send alert after 5 seconds
										sendAlert({
												class: isfireDetected ? "fire" : "Smoke",
												confidence: isfireDetected ? predictions.find(p => p.class === "fire").confidence : predictions.find(p => p.class === "Smoke").confidence
										});

										// Reset the timer after sending the alert
										detectionStartTime = null;
								}
						}
				} else {
						// Reset the timer if no fire/smoke is detected
						detectionStartTime = null;
				}

				if (detectionStartTime) {
						const remainingTime = detectionDelay - (Date.now() - detectionStartTime);
						if (remainingTime > 0) {
								ctx.fillStyle = "white";
								ctx.font = "20px Arial";
								ctx.fillText(`Alert in: ${(remainingTime / 1000).toFixed(1)}s`, 10, 30);
						}
				}
		};

		var prevTime;
		var pastFrameTimes = [];
		const detectFrame = function () {
				if (!workerId) return requestAnimationFrame(detectFrame);

				const image = new CVImage(video);
				inferEngine
						.infer(workerId, image)
						.then(function (predictions) {
								requestAnimationFrame(detectFrame);
								renderPredictions(predictions);

								if (prevTime) {
										pastFrameTimes.push(Date.now() - prevTime);
										if (pastFrameTimes.length > 30) pastFrameTimes.shift();

										var total = 0;
										_.each(pastFrameTimes, function (t) {
												total += t / 1000;
										});

										var fps = pastFrameTimes.length / total;
										$("#fps").text(Math.round(fps));
								}
								prevTime = Date.now();
						})
						.catch(function (e) {
								console.log("CAUGHT", e);
								requestAnimationFrame(detectFrame);
						});
		};

		// New function: Send alert to server using PHP
		function sendAlert(prediction) {
				fetch("alert.php", {
						method: "POST",
						body: JSON.stringify(prediction),
						headers: { "Content-Type": "application/json" }
				})
				.then(response => response.json())
				.then(data => console.log("Alert response:", data))
				.catch(error => console.error("Error sending alert:", error));
		}
});
