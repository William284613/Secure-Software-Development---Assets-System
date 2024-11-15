<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once('includes/load.php');

try {
    // Function to check login flood prevention
    function isLoginFlooded($username, $limitInSeconds = 10, $additionalDelay = 10) {
        if (isset($_SESSION['login_attempts'][$username])) {
            $lastAttemptTime = $_SESSION['login_attempts'][$username];
            $currentTime = time();

            // Check if the time difference is less than the limit
            if (($currentTime - $lastAttemptTime) < $limitInSeconds) {
                // Calculate the remaining time before allowing another login attempt
                $remainingTime = $limitInSeconds - ($currentTime - $lastAttemptTime);
                
                if ($remainingTime <= $additionalDelay) {
                    return true; // Flooded
                }
            }
        }

        // Update the timestamp for the login attempt
        $_SESSION['login_attempts'][$username] = time();
        return false; // Not flooded
    }

    // Check if login is flooded
    if (isLoginFlooded($_POST['username'])) {
        throw new Exception('Too many login attempts. Please wait before trying again.');
    }

    $req_fields = array('username', 'password');
    validate_fields($req_fields);

    $username = remove_junk($_POST['username']);
    $password = remove_junk($_POST['password']);

    if (empty($errors)) {
        $user = authenticate($username, $password);

        if ($user) {
            // Check user level
            if ($user['user_level'] == 1) {
                // Admin user, redirect to admin.php
                $session->login($user['id']);
                updateLastLogIn($user['id']);
                $session->msg("s", "Welcome to Inventory Management System");
                unset($_SESSION['login_attempts'][$username]); // Reset login attempt timestamp
                redirect('admin.php', false);
            } else {
                // Regular user, redirect to home.php
                $session->login($user['id']);
                updateLastLogIn($user['id']);
                $session->msg("s", "Welcome to Inventory Management System");
                unset($_SESSION['login_attempts'][$username]); // Reset login attempt timestamp
                redirect('home.php', false);
            }
        } else {
            throw new Exception("Sorry Username/Password incorrect.");
        }
    } else {
        if (!is_array($errors)) {
            $errors = array($errors); // Convert to array if it's not
        }
        $error_message = is_array($errors) ? implode(" ", $errors) : $errors;
        throw new Exception($error_message);
    }
} catch (Exception $e) {
    $session->msg("d", $e->getMessage());
    sleep($additionalDelay); // Sleep for additional delay before redirecting
    redirect('index.php', false);
}
?>
