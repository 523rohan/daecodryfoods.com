<?php
// Simple GitHub Auto-Deployment Webhook Script
// This script bypasses Hostinger's broken "Project directory is not empty" UI check
// and securely, directly interacts with the server terminal.

// 1. A secure token so only GitHub can trigger this file
$secretToken = "daeco_secure_deploy_2026";

// 2. Validate the token from the URL
if (!isset($_GET['token']) || $_GET['token'] !== $secretToken) {
    http_response_code(403);
    die("Unauthorized: Invalid token.");
}

// 3. Pull the latest code securely
$command = "git fetch origin 2>&1 && git reset --hard origin/main 2>&1";
$output = shell_exec($command);

// 4. Save a log file so we can debug deployments later if necessary
$logFile = 'deploy_log.txt';
$logEntry = "--- Deploy Triggered at " . date('Y-m-d H:i:s') . " ---\n" . $output . "\n\n";
file_put_contents($logFile, $logEntry, FILE_APPEND);

// 5. Send success response back to GitHub
echo "Deployed Successfully!\n\n";
echo "Command Output:\n" . htmlspecialchars($output);
?>