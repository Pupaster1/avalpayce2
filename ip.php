<?php

echo '<pre>';
echo "REMOTE_ADDR: " . ($_SERVER['REMOTE_ADDR'] ?? '') . "\n";
echo "HTTP_X_FORWARDED_FOR: " . ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? '') . "\n";
echo "HTTP_X_REAL_IP: " . ($_SERVER['HTTP_X_REAL_IP'] ?? '') . "\n";
echo '</pre>';
