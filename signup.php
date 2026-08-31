<?php
session_start();

$host = "localhost";
$port = "3306";
$db   = "db";
$user = "root";
$pass = "";

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error_msg = '';

if (isset($_POST['signup'])) { 

    $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, trim($_POST['username'])) : '';
    $email    = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($username)) {
        $error_msg = "Please enter your username";
    } else if (empty($email)) {
        $error_msg = "Please enter your email";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = "Please enter a valid email";
    } else if (empty($password)) {
        $error_msg = "Please enter your password";
    } else if (strlen($password) < 6) {
        $error_msg = "Password must be at least 6 characters";
    } else {
        // email esist or not
        $check_sql = "SELECT users_id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $check_result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error_msg = "Email already registered";
        } else {
           
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (users_name, email, users_password, user_role) 
                    VALUES (?, ?, ?, 'customer')";
            
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hash);

            if (mysqli_stmt_execute($stmt)) {
              
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['user_email'] = $email;
                $_SESSION['username'] = $username;
                $_SESSION['user_role'] = 'customer';
                
                header("Location: index.php");
                exit;
            } else {
                $error_msg = "Registration failed: " . mysqli_error($conn);
            }
        }
    }
}

if (isset($_POST['login'])) {
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    if (empty($email)) {
        $error_msg = "Please enter your email";
    } else if (empty($password)) {
        $error_msg = "Please enter your password";
    } else {
        $sql = "SELECT users_id, users_name, users_password, user_role 
                FROM users 
                WHERE email = ? 
                LIMIT 1";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['users_password'])) {
                $_SESSION['user_id'] = $row['users_id'];
                $_SESSION['user_email'] = $email;
                $_SESSION['username'] = $row['users_name'];
                $_SESSION['user_role'] = $row['user_role'];

                if ($row['user_role'] == 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit;
            } else {
                $error_msg = "Wrong password";
            }
        } else {
            $error_msg = "User not found";
        }
    }
}

mysqli_close($conn);

if (!empty($error_msg)) {
    $_SESSION['error_msg'] = $error_msg;
    header("Location: index.php?error=" . urlencode($error_msg));
    exit;
}
?> 