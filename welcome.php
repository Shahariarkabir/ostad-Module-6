<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['name']) || empty($_SESSION['name'])) {
    header("Location: register.php");
    exit();
}

// Read user data from CSV file
$userData = array();
if (($fp = fopen("users.csv", "r")) !== false) {
    while (($data = fgetcsv($fp)) !== false) {
        $userData[] = $data;
    }
    fclose($fp);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>User Data</title>
</head>

<body>

    <h1>Welcome, <?php echo $_SESSION['name']; ?>!</h1>

    <table border="1">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Profile Picture</th>
        </tr>
        <?php foreach ($userData as $data) { ?>
            <tr>
                <td><?php echo $data[0]; ?></td>
                <td><?php echo $data[1]; ?></td>
                <td><?php echo '<img src="uploads/' . $data[2] . '">' . $data[2]; ?></td>
            </tr>
        <?php } ?>
    </table>

</body>

</html>
