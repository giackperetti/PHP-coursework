<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkAuth()
{
    // if (!isset($_SESSION['user_id'])) {
    if (!isset($_COOKIE['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

function alertMessage($message)
{
    echo "
    <script>
        alert('{$message}');
        window.location.href = 'index.php';
    </script>";
    exit();
}

define("HOST", "localhost");
define("USERNAME", "test");
define("PASSWORD", "test");

define("DB_NAME", "5f_nextcar");

$conn = new mysqli(HOST, USERNAME, PASSWORD, DB_NAME);
