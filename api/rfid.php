<?php 
// api/rfid.php
//working fine this just send response back to the esp32
// header('Content-Type: application/json');

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $data = file_get_contents('php://input');
//     $decoded = json_decode($data, true);

//     if (isset($decoded['uid'])) {
//         $uid = $decoded['uid'];
//         echo json_encode([
//             'success' => true,
//             'message' => 'Hey there I just received the UID ',
//             'uid' => $uid
//         ]);
//     } else {
//         echo json_encode([
//             'success' => false,
//             'message' => 'UID not provided'
//         ]);
//     }
// } else {
//     echo json_encode([
//         'success' => false,
//         'message' => 'Invalid request method'
//     ]);
// }



// api/rfid.php
//this keeps the rfid and send it to my registration form working fine 
//this reads the rfid uid from the rfid module through ESP32 and saves it in a txt file 
//where it is being read from to populate my form

header('Content-Type: application/json');

$file = 'uid.txt'; // File to store UID

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $decoded = json_decode($data, true);

    if (isset($decoded['uid'])) {
        $uid = $decoded['uid'];
        
        // Store UID in a file
        file_put_contents($file, $uid);
        
        echo json_encode([
            'success' => true,
            'message' => 'UID received',
            'uid' => $uid
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'UID not provided'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}

