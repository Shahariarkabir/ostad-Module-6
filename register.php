<?php
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validate form inputs
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $profilePicture = $_FILES['profilePicture'] ?? '';

    if (empty($name) || empty($email) || empty($password) || empty($profilePicture)) {
        $errorMsg = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg = "Invalid email format!";
    } else {

        // Save profile picture to server
        $fileExtension = strtolower(pathinfo($profilePicture['name'], PATHINFO_EXTENSION));
        $newFilename = date('YmdHis') . '_' . uniqid() . '.' . $fileExtension;
        $targetFile = 'uploads/' . $newFilename;
        if (!move_uploaded_file($profilePicture['tmp_name'], $targetFile)) {
            $errorMsg = "Error uploading file.";
        } else {

            // Save user data to CSV file
            $userData = array($name, $email, $newFilename);
            if (($fp = fopen('users.csv', 'a')) !== false) {
                fputcsv($fp, $userData);
                fclose($fp);
            }

            // Set session and cookie for user
            $_SESSION['name'] = $name;
            setcookie('user', $name, time() + (86400 * 30), '/');

            // Redirect to welcome page
            header('Location: welcome.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Registration Form</title>
</head>

<body>

    <h1>Registration Form</h1>

    <?php if (isset($errorMsg)) { ?>
        <p style="color: red;"><?php echo $errorMsg; ?></p>
    <?php } ?>

    <form action="" method="post" enctype="multipart/form-data">
        <p>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>
        </p>
        <p>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </p>
        <p>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </p>
        <p>
            <label for="profilePicture">Profile Picture:</label>
            <input type="file" name="profilePicture" id="profilePicture" accept="image/*" required>
        </p>
        <p>
            <input type="submit" value="Submit">
        </p>
    </form>

</body>

</html>
