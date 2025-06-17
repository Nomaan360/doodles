<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Replace with your bot token
$BOT_TOKEN = '8132654571:AAFMS86FdYzV5qi8rBHYuaq2d77famNEocQ';
$TELEGRAM_API_URL = "https://api.telegram.org/bot$BOT_TOKEN";

$chatId = "656737887";

// Send a welcome message back to the user
$message = "656737887";

sendMessageCallback($chatId, $message);

// Function to send a message back to the user
function sendMessageCallback($chatId, $message) {
    global $TELEGRAM_API_URL;

    $url = $TELEGRAM_API_URL . "/sendMessage";

    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For dev only
    $response = curl_exec($ch);
    curl_close($ch);
}
?>