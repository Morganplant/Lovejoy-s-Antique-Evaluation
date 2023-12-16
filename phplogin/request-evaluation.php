<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Request Evaluation Page</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        .content {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        .content h2 {
            text-align: center;
        }

        .content form {
            display: flex;
            flex-direction: column;
        }

        .content form label {
            margin-bottom: 10px;
        }

        .content form input[type="text"],
        .content form textarea {
            padding: 10px;
            margin-bottom: 20px;
        }

        .content form select {
            padding: 10px;
            margin-bottom: 20px;
        }

        .content form input[type="submit"] {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .content form input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body class="loggedin">
    <?php
    include 'navbar.php';
    ?>
    <div class="content">
        <h2>Request Evaluation</h2>
        <form action="submit-evaluation.php" method="post">
            <label for="item_name">Item Name:</label>
            <input type="text" name="item_name" id="item_name" required>
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
            <label for="contact_method">Preferred Contact Method:</label>
            <select name="contact_method" id="contact_method" required>
                <option value="phone">Phone</option>
                <option value="email">Email</option>
            </select>
            <input type="submit" value="Submit Request">
        </form>
    </div>
</body>

</html>
