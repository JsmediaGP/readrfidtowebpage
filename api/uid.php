<?php
// api/uid.php

header('Content-Type: application/json');

$file = 'uid.txt'; // File where UID is stored

if (file_exists($file)) {
    $uid = file_get_contents($file);
    echo json_encode([
        'success' => true,
        'uid' => $uid
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'UID not found'
    ]);
}
