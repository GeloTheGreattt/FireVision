<?php
// alert.php

header("Content-Type: application/json");

require 'db.php';
require 'vendor/autoload.php'; 



try {
    // Read input data
    $json = file_get_contents('php://input');
    if (!$json) {
        throw new Exception("No data received.");
    }

    $data = json_decode($json, true);
    if (!$data || !isset($data['class'], $data['confidence'], $data['snapshot'])) {
        throw new Exception("Invalid data format.");
    }


    $snapshotData = $data['snapshot'];
    $snapshotPath = null;
    
    if (preg_match('/^data:image\/(\w+);base64,/', $snapshotData, $type)) {
        $snapshotData = substr($snapshotData, strpos($snapshotData, ',') + 1);
        $type = strtolower($type[1]);
    
        if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
            throw new Exception("Invalid image type.");
        }
    
        $snapshotData = base64_decode($snapshotData);
        if ($snapshotData === false) {
            throw new Exception("Base64 decode failed.");
        }
    
        $filename = 'snapshots/' . uniqid('snapshot_', true) . '.' . $type;
        file_put_contents($filename, $snapshotData);
        $snapshotPath = $filename;
    }

    // Insert alert into the database
    $stmt = $pdo->prepare("INSERT INTO alerts (type, confidence, snapshot) VALUES (?, ?, ?)");
    $stmt->execute([$data['class'], $data['confidence'], $snapshotPath]);

    

    // Fetch all users with the "user" role from the database
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $message = "ALERT: Fire has been detected around the school premise! Please evacuate the building immediately!";

    // if (!$users['phone'] || empty($users['phone'])) {
    //     $_SESSION['status'] = "No Phone Numbers Found!";
    //     $_SESSION['status-code'] = "error";
    //     header("Location: " . $_SERVER['HTTP_REFERER']);
    //     exit();
    // }

    $successCount = 0; // Counter for successful messages
    $errorCount = 0; // Counter for failed messages

    foreach ($users as $usercpnum) {
        $url = "https://itemnest.org/hwebit_app/index.php?cp_num=" . urlencode($usercpnum['phone']) . "&message=" . urlencode($message);

        // Initialize cURL session
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Get the response from the server
        $response = curl_exec($ch);
        curl_close($ch);

        // Handle the response
        if ($response === false || $response !== 'success') {
            $errorCount++;
        } else {
            $successCount++;
        }
    }

    // Set session message based on success/failure
    if ($successCount > 0) {
        echo json_encode([
            "status" => "success",
            "message" => "Alerts sent to all users with the 'user' role."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to sent message to all users with the 'user' role."
        ]);
    }

    

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Failed to send alerts: " . $e->getMessage()
    ]);
}
?>