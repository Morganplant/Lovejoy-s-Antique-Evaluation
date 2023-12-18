<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

// Connect to the database
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "phplogin";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all requests with associated user information
$sql = "SELECT requests.*, accounts.name AS user_name, accounts.email AS user_email
        FROM requests
        INNER JOIN accounts ON requests.linked_user_id = accounts.id";
$result = $conn->query($sql);

// Store the fetched data in an array
$requests = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>View Requests</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
        integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
        }

        .content {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .description {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .image-preview {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body class="loggedin">
    <?php include 'navbar.php'; ?>
    <div class="content">
        <h2>View Requests</h2>
        <table>
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Preferred Contact</th>
                    <th>Image Preview</th>
                    <th>User Name</th>
                    <th>User Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request) : ?>
                    <tr>
                        <td><?php echo $request['id']; ?></td>
                        <td><?php echo $request['item_name']; ?></td>
                        <td>
                            <div class="description" title="<?php echo $request['description']; ?>">
                                <?php echo strlen($request['description']) > 100 ? substr($request['description'], 0, 100) . '...' : $request['description']; ?>
                            </div>
                        </td>
                        <td><?php echo $request['preferred_contact']; ?></td>
                        <td>
                            <?php if ($request['file_name']) : ?>
                                <img class="image-preview" src="uploads/<?php echo $request['file_name']; ?>" alt="Image Preview">
                            <?php else : ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td><?php echo $request['user_name']; ?></td>
                        <td><?php echo $request['user_email']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>