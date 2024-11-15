<?php
require_once('includes/load.php');

try {
    if (!$session->logout()) {
        throw new Exception("Logout failed");
    }

    // Logout successful
    echo '<script>';
    echo 'alert("Logout successful!");';
    echo 'window.location.href = "index.php";';
    echo '</script>';
    // No need to use the redirect function
} catch (Exception $e) {
    // Handle the exception
    echo '<script>';
    echo 'alert("Logout failed: ' . $e->getMessage() . '");';
    echo 'window.location.href = "index.php";';
    echo '</script>';
    // No need to use the redirect function
}
?>
