<?php
require __DIR__ . '/vendor/autoload.php';
session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$webrtcUser = $_ENV['WEBRTCUSER'];
$webrtcPass = $_ENV['WEBRTCPASS'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>FireVision</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Existing scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.20/lodash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/async/3.2.0/async.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/inferencejs"></script>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
</head>
<body>
    <!-- Open Dashboard Button -->
    <a href="login.php" class="btn btn-dark" style="position: fixed; top: 10px; left: 10px; z-index: 1001;">Open Dashboard</a>

    <!-- Main Content -->
    <div id="content" style="height: 100vh">
        <!-- Include existing FireVision video interface content here -->
				<video id="video" autoplay playsinline muted style="width: 100%; height: 100%; object-fit: contain;"></video>
				<!--<iframe id="video-iframe" src="/cctv/" width="640" height="480" style="width: 100%; height: 100%"></iframe>-->
        <div id="fps"></div>
    </div>


    <div id="fireModal">
        <h2>🔥 Fire Detected!</h2>
        <p>Please check the situation immediately.</p>
        <button id="resumeBtn" style="margin-top:1em; padding:0.5em 1em;">Resume Detection</button>
</div>
	<script>
		const username = "<?php echo $webrtcUser; ?>";
		const password = "<?php echo $webrtcPass; ?>";
	</script>
	<script src="camera.js"></script>
	<script src="main.js"></script>
</body>
</html>
