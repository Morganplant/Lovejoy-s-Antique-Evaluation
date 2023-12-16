<?php
session_start();
$DATABASE_HOST = '127.0.0.1';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if (isset($_POST['name'], $_POST['password'], $_POST['email'], $_POST['phone'])) {
    if ($stmt = $con->prepare('INSERT INTO accounts (name, password, email, telephone) VALUES (?, ?, ?, ?)')) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt->bind_param('ssss', $_POST['name'], $password, $_POST['email'], $_POST['phone']);
        if ($stmt->execute()) {
            header('Location: index.html');
            exit;
        } else {
            echo 'Could not execute statement: ' . mysqli_error($con);
        }
    } else {
        echo 'Could not prepare statement: ' . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration Page</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body>
    <div class="login">
        <h1>User Registration</h1>
        <form action="register.php" method="post">
            <label for="name">
                <i class="fas fa-user"></i>
            </label>
            <input type="text" name="name" placeholder="Name" id="name" required>
            <label for="password">
                <i class="fas fa-lock"></i>
            </label>
            <input type="password" name="password" placeholder="Password" id="password" required>
            <label for="email">
                <i class="fas fa-envelope"></i>
            </label>
            <input type="text" name="email" placeholder="Email Address" id="email" required>
            <label for="telephone">
                <i class="fas fa-phone"></i>
            </label>
            <input type="tel" id="phone" name="phone" placeholder="+441234567890" pattern="^\+[1-9]\d{1,14}$" required />

            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
