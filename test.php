<?php
if (isset($_GET['uid'])) {
    echo "Received UID: " . htmlspecialchars($_GET['uid']);
} else {
    echo "No UID received";
}





