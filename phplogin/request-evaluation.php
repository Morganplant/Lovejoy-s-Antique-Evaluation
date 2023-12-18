<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

$upload_success = false;
$preview_image = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $contact_method = $_POST['contact_method'];

    // Check if a file was uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['photo'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];

        // Check if the uploaded file is a photo
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_extensions) || exif_imagetype($file_tmp) === false) {
            echo 'Error: Only JPG, JPEG, and PNG files are allowed.';
            exit;
        }

        // Generate a unique file name
        $random_component = bin2hex(random_bytes(4)); // Generate a random component
        $user_name = $_SESSION['name'];
        $sanitized_item_name = preg_replace("/[^a-zA-Z0-9]+/", "", $item_name);
        $new_file_name = "{$sanitized_item_name}-{$user_name}-{$random_component}.{$file_extension}";

        // Move the uploaded file to the 'uploads' directory
        $upload_directory = 'uploads/';
        $destination = $upload_directory . $new_file_name;

        // Check the file size
        $max_file_size = 5 * 1024 * 1024; // 5MB
        if ($file_size > $max_file_size) {
            echo 'Error: The file size exceeds the maximum limit.';
            exit;
        }

        // Create the upload directory if it doesn't exist
        if (!is_dir($upload_directory)) {
            mkdir($upload_directory, 0755, true);
        }

        // Move the uploaded file to the destination directory
        if (!move_uploaded_file($file_tmp, $destination)) {
            echo 'Error: Failed to move the uploaded file.';
            exit;
        }

        // File upload successful
        $upload_success = true;
        $preview_image = $destination;

        // Connect to the database
        $servername = "127.0.0.1";
        $username = "root";
        $password = "";
        $dbname = "phplogin";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO requests (item_name, description, preferred_contact, file_name, linked_user_id) VALUES (?, ?, ?, ?, ?)");

        // Bind the parameters and execute the statement
        $stmt->bind_param("ssssi", $item_name, $description, $contact_method, $new_file_name, $_SESSION['id']);
        $stmt->execute();

        // Close the statement and database connection
        $stmt->close();
        $conn->close();
    } else {
        echo 'Error: No file was uploaded.';
        exit;
    }
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
            display: <?php echo $upload_success ? 'none' : 'flex'; ?>;
            flex-direction: column;
        }

        .content form label {
            font-weight: bold;
            color: #4a536e;
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

        .content form input[type="file"] {
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

        .preview-image {
            max-width: 100%;
            margin-bottom: 20px;
        }

        .success-message {
            text-align: center;
        }

        .success-message p {
            font-weight: bold;
            color: green;
            margin-bottom: 10px;
        }

        .success-message button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .success-message button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body class="loggedin">
    <?php include 'navbar.php';?>
    <div class="content">
        <h2>Request Evaluation</h2>
        <?php if ($upload_success) : ?>
            <div class="success-message">
                <p>File uploaded successfully!</p>
                <img class="preview-image" src="<?php echo htmlspecialchars($preview_image); ?>" alt="Preview">
                <button onclick="location.href='request-evaluation.php'">Request Another Evaluation</button>
            </div>
        <?php else : ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <label for="item_name">Item Name:</label>
                <input type="text" name="item_name" id="item_name" required>
                <label for="description">Description:</label>
                <textarea name="description" id="description" required></textarea>
                <label for="contact_method">Preferred Contact Method:</label>
                <select name="contact_method" id="contact_method" required>
                    <option value="phone">Phone</option>
                    <option value="email">Email</option>
                </select>
                <label for="photo">Photo of the Object:</label>
                <input type="file" name="photo" id="photo" accept="image/jpeg, image/png">
                <input type="submit" value="Submit Request">
            </form>
        <?php endif; ?>
    </div>
</body>

</html>