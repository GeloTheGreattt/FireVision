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
    if (!$data || !isset($data['class'], $data['confidence'])) {
        throw new Exception("Invalid data format.");
    }

    // Insert alert into the database
    $stmt = $pdo->prepare("INSERT INTO alerts (type, confidence) VALUES (?, ?)");
    $stmt->execute([$data['class'], $data['confidence']]);


    // Fetch all users with the "user" role from the database
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $message = "ALERT: {$data['class']} Detected!\n Confidence: {$data['confidence']}";

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

        // Get the response from your server
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