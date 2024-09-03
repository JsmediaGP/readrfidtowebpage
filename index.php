
<!--?php
// index.php
//working fine
// Read UID from the file
// $uid = '';
// if (file_exists('api/uid.txt')) {
//     $uid = file_get_contents('api/uid.txt');
// }

// // HTML for registration form
//
// <!DOCTYPE html>
// <html lang="en">
// <head>
//     <meta charset="UTF-8">
//     <meta name="viewport" content="width=device-width, initial-scale=1.0">
//     <title>Registration Form</title>
//     <link rel="stylesheet" href="style.css">
// </head>
// <body>
//     <h1>Registration Form</h1>
//     <form action="process_registration.php" method="post">
//         <label for="uid">RFID UID:</label>
//         <input type="text" id="uid" name="uid" value="<!?php echo htmlspecialchars($uid); ?>" readonly><br><br>
//         <-- Add other form fields here -->
//         <!--label for="name">Name:</label>
//     <input type="text" id="name" name="name"><br><br>

//     <label for="email">Email:</label>
//     <input type="email" id="email" name="email"><br><br>
//         <input type="submit" value="Register">
//     </form>
// </body>
// </html-->


<?php
// index.php

// HTML for registration form
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="style.css">
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            function fetchUid() {
                fetch('api/uid.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('uid').value = data.uid;
                        } else {
                            console.log('UID not found or error occurred');
                        }
                    })
                    .catch(error => console.error('Error fetching UID:', error));
            }

            // Fetch UID initially and then every 1 second
            fetchUid();
            setInterval(fetchUid, 1000); // Adjust the interval as needed
        });
    </script>
</head>
<body>
    <h1>Registration Form</h1>
    <form action="process_registration.php" method="post">
        <label for="uid">RFID UID:</label>
        <input type="text" id="uid" name="uid" readonly>
        <!-- Add other form fields here -->
        <input type="submit" value="Register">
    </form>
</body>
</html>
