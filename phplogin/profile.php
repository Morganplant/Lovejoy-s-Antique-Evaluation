<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}
$DATABASE_HOST = '127.0.0.1';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
$stmt = $con->prepare('SELECT password, email, admin FROM accounts WHERE id = ?');
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email, $admin);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Profile Page</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
</head>

<body class="loggedin">
    <?php include 'navbar.php';?>
    <div class="content">
        <h2>Profile Page</h2>
        <div>
            <p>Your account details are below:</p>
            <table>
                <tr>
                    <td>Username:</td>
                    <td>
                        <?= $_SESSION['name'] ?>
                    </td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td>
                        <?= $password ?>
                    </td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>
                        <?= $email ?>
                    </td>
                </tr>
                <?php if ($admin == 1): ?>
                    <tr>
                        <td>Administrator:</td>
                        <td>True</td>
                    </tr>
                    <tr>
                        <td>Session Data:</td>
                        <td><?= var_dump($_SESSION); ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>

</html>