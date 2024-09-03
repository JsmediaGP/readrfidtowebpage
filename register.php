<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "readrfid";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$rfid_uid = $_POST['rfid_uid'];
$name = $_POST['name'];
$email = $_POST['email'];


// Insert data into the database
$sql = "INSERT INTO users (rfid_uid, name, email) VALUES ('$rfid_uid', '$name', '$email')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

