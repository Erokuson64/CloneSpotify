<?php
include 'db/config.php';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate inputs
    if (strlen($username) < 5 || strlen($username) > 25 || !preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        $errors['username'] = "Your username must be between 5 and 25 characters and can only contain letters and numbers.";
    }

    if (strlen($firstName) < 2 || strlen($firstName) > 25) {
        $errors['firstName'] = "Your first name must be between 2 and 25 characters.";
    }

    if (strlen($lastName) < 2 || strlen($lastName) > 25) {
        $errors['lastName'] = "Your last name must be between 2 and 25 characters.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Your email is invalid.";
    }

    if ($password !== $confirmPassword) {
        $errors['password'] = "Your passwords do not match.";
    } elseif (strlen($password) < 5 || strlen($password) > 30 || preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors['password'] = "Your password must be between 5 and 30 characters and can only contain letters and numbers.";
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO users (username, firstName, lastName, email, password) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$username, $firstName, $lastName, $email, $hashedPassword])) {
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/register.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Register</title>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($username ?? '') ?>">
                <small class="text-danger"><?= $errors['username'] ?? '' ?></small>
            </div>
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" name="firstName" class="form-control" value="<?= htmlspecialchars($firstName ?? '') ?>">
                <small class="text-danger"><?= $errors['firstName'] ?? '' ?></small>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" name="lastName" class="form-control" value="<?= htmlspecialchars($lastName ?? '') ?>">
                <small class="text-danger"><?= $errors['lastName'] ?? '' ?></small>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email ?? '') ?>">
                <small class="text-danger"><?= $errors['email'] ?? '' ?></small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control">
                <small class="text-danger"><?= $errors['password'] ?? '' ?></small>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" name="confirmPassword" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>
    </div>
</body>
</html>