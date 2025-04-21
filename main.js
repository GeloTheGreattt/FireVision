/*jshint esversion:6*/

$(function () {
    const { InferenceEngine, CVImage } = inferencejs;
    const inferEngine = new InferenceEngine();

    const video = $("video")[0];
    var workerId;

    // --- Merged Variables from JS1 ---
    let detectionStartTime = null;      // Tracks the first hazard detection
    let secondCountdownStart = null;    // Tracks when the second countdown begins
    let lastFireDetectedTime = null;    // Tracks when hazard was last seen (renamed for clarity)
    const firstCountdownDuration = 6000; // 4 Seconds
    const secondCountdownDuration = 6000;// 5 seconds
    const gracePeriodDuration = 1500;   // 1 second grace period
    let detectionPaused = false;        // State variable for pausing detection
    // --- End Merged Variables ---

    // --- Merged Modal Functions from JS1 ---
    function showFireModal() {
        const modal = document.getElementById("fireModal");
        modal.style.display = "flex"; // Shows the modal as a flex container
        modal.classList.add("show");
        detectionPaused = true;       // Pause detection when modal is shown
    }

    $(document).ready(function () {
        // Resume button click handler from JS1
        $("#resumeBtn").on("click", function () {
            closeFireModal(); // Call close function
        });
    });

    function closeFireModal() {
        const modal = document.getElementById("fireModal");
        if (modal) {
            modal.style.display = "none"; // Hide modal
            detectionPaused = false;     // Resume detection
        }
    }
    // --- End Merged Modal Functions ---

    // JS2's Video Stream Promise (waits for loadeddata)
    const startVideoStreamPromise = new Promise(function (resolve) {
        if (video.readyState >= 2) { // Check if video metadata is already loaded
            console.log("Video is already loaded.");
            video.play().then(resolve).catch(e => console.error("Error playing video:", e)); // Ensure play starts
        } else {
            $(video).on("loadeddata", function () {
                console.log("Video loaded after waiting.");
                 video.play().then(resolve).catch(e => console.error("Error playing video:", e)); // Ensure play starts
            });
        }
    });


    // JS2's Model Loading
    const loadModelPromise = new Promise(function (resolve, reject) {
        inferEngine
            .startWorker("cctv-fire-detection", "6", "rf_zT1FguF8F9ZuVjXPhmLEyuu026G3") // JS2's model
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
    });

    var canvas, ctx;
    const font = "16px sans-serif";

    // JS2's videoDimensions function
    function videoDimensions(video) {
        var videoRatio = video.videoWidth / video.videoHeight;
        var width = video.offsetWidth,
            height = video.offsetHeight;
        var elementRatio = width / height;

        if (elementRatio > videoRatio) {
            width = height * videoRatio;
        } else {
            height = width / videoRatio;
        }

        return { width: width, height: height };
    }

	$(window).resize(function () {
		resizeCanvas();
});	
    // JS2's resizeCanvas function (kept as is)
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

    function renderPredictions(predictions) {
        var scale = 1;
        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);

        let isFireDetected = false;

        predictions.forEach(function (prediction) {
            const fireConfidenceThreshold = 0.65;
            if (prediction.class === "fire" && prediction.confidence >= fireConfidenceThreshold) {
                isFireDetected = true;
            }

            const x = prediction.bbox.x;
            const y = prediction.bbox.y;
            const width = prediction.bbox.width;
            const height = prediction.bbox.height;

            ctx.strokeStyle = prediction.color;
            ctx.lineWidth = 4;
            ctx.strokeRect(
                (x - width / 2) / scale,
                (y - height / 2) / scale,
                width / scale,
                height / scale
            );

            ctx.fillStyle = prediction.color;
            const textWidth = ctx.measureText(prediction.class).width;
            const textHeight = parseInt(font, 10);
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

        handleFireDetection(isFireDetected);
    }

    function handleFireDetection(isFireDetected) {
        if (detectionPaused) return; // Pause detection when modal is open
        const currentTime = Date.now();

        if (isFireDetected) {
            if (!detectionStartTime) {
                detectionStartTime = currentTime;
            }
            lastFireDetectedTime = currentTime; // Update last detection time
        }
    
        if (detectionStartTime) {
            let elapsed = currentTime - detectionStartTime;
    
            if (elapsed >= firstCountdownDuration) {
                if (isFireDetected && !secondCountdownStart) {
                    secondCountdownStart = currentTime;
                }
    
                if (secondCountdownStart) {
                    let secondElapsed = currentTime - secondCountdownStart;
    
                    if (!isFireDetected) {
                        // Only reset if fire has been gone for the full grace period
                        if (currentTime - lastFireDetectedTime >= gracePeriodDuration) {
                            resetCountdown();
                            return;
                        }
                    }
    
                    if (secondElapsed >= secondCountdownDuration) {
                        sendAlert({ class: "fire", confidence: 1.0, snapshot: captureSnapshot() });
                        showFireModal();
                        resetCountdown();
                    } else {
                        drawCountdown(`ALERT IN: ${(secondCountdownDuration - secondElapsed) / 1000}s`);
                    }
                } else {
                    drawCountdown(`WAITING FOR CONFIRMATION: ${(firstCountdownDuration - elapsed) / 1000}s`);
                }
            } else {
                drawCountdown(`COUNTDOWN: ${(firstCountdownDuration - elapsed) / 1000}s`);
            }
        }
    
        // Reset if fire disappears and grace period ends
        if (!isFireDetected && detectionStartTime && !secondCountdownStart) {
            if (currentTime - lastFireDetectedTime >= gracePeriodDuration) {
                resetCountdown();
            }
        }
    }

    function resetCountdown() {
        detectionStartTime = null;
        secondCountdownStart = null;
        inCountdownPhase = false;
    }

    function drawCountdown(text) {
        ctx.fillStyle = "white";
        ctx.font = "20px Arial";
        ctx.fillText(text, 10, 30);
    }

    var prevTime;
    var pastFrameTimes = [];

    function detectFrame() {
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
                    var total = pastFrameTimes.reduce((acc, t) => acc + t / 1000, 0);
                    var fps = pastFrameTimes.length / total;
                    $("#fps").text(Math.round(fps));
                }
                prevTime = Date.now();
            })
            .catch(function (e) {
                console.log("CAUGHT", e);
                requestAnimationFrame(detectFrame);
            });
    }

    function captureSnapshot() {
        const snapshotCanvas = document.createElement("canvas");
        snapshotCanvas.width = video.videoWidth;
        snapshotCanvas.height = video.videoHeight;
        const snapshotCtx = snapshotCanvas.getContext("2d");
        snapshotCtx.drawImage(video, 0, 0, snapshotCanvas.width, snapshotCanvas.height);
        return snapshotCanvas.toDataURL("image/jpeg", 0.8); // returns Base64 image
    }

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