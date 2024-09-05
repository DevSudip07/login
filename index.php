<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "login";

// Create a new mysqli object with error reporting enabled
$conn = new mysqli($server, $username, $password, $database);
$conn->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;

// Check connection
if ($conn->connect_error) {
    throw new Exception("Error: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate user input data
    $name = trim($_POST['name']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    // $course = trim($_POST['course']);

    if (empty($name) || empty($mobile) || empty($email) || empty($password)) {
        echo "Error: Please fill in all fields.";
        exit;
    }

    // Check if user already exists
    $stmt = $conn->prepare("SELECT 1 FROM `login_users` WHERE `email` = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Error: User with this email already exists.";
        exit;
    }

    // Hash password
    $password = password_hash($password, PASSWORD_BCRYPT);

    // Insert user data
    $stmt = $conn->prepare("INSERT INTO `login_users` (`FULL NAME`, `MOBILE NO`, `EMAIL`, `PASSWORD`) VALUES ('$name', '$mobile', '$email', '$password')");
    // $stmt->bind_param("ssss", $name, $mobile, $email, $password);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "User created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=P, initial-scale=1.0">
    <title>LOG IN PAGE</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body{
            background: white;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>SIGN UP FORM</h1>
        <form action="index.php" method="post">
            <input type="text" name="name" id="name" placeholder="Full Name">
            <input type="number" name="mobile" id="mobile" placeholder="Mobile no.">
            <input type="email" name="email" id="email" placeholder="Email">
            <input type="password" name="password" id="password" placeholder="Password">
            <input type="Password" placeholder="Confirm Password">
            <select name="course" id="course">
                <option value="0">Select Your Course</option>
                <option value="1">Web Design</option>
                <option value="2">Web Development</option>
                <option value="3">Video Editing(Mobile)</option>
            </select>
            <button id="btn">Submit</button>
        </form>
    </div>
</body>
</html>