<?php
header('Content-Type: application/json');

$sid  = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_GET['s'] ?? '');
if (!$sid) { echo json_encode(['status' => 'unknown']); exit; }

$file = sys_get_temp_dir() . '/jelpit2_sessions/' . $sid . '.json';
if (!file_exists($file)) { echo json_encode(['status' => 'unknown']); exit; }

echo file_get_contents($file);
