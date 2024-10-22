<?php
session_start();
include 'db/config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $targetDir = "uploads/"; 
    $targetFile = $targetDir . basename($_FILES["song_file"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $allowedTypes = ['mp3', 'wav', 'ogg', 'm4a'];
    
    if (!in_array($fileType, $allowedTypes)) {
        $errors[] = "Sorry, only MP3, WAV, OGG & M4A files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["song_file"]["tmp_name"], $targetFile)) {
            $stmt = $pdo->prepare("INSERT INTO songs (title, file_path) VALUES (?, ?)");
            if ($stmt->execute([$title, $targetFile])) {
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Error uploading song to the database.";
            }
        } else {
            $errors[] = "Sorry, there was an error uploading your file.";
        }
    }
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p class='text-danger'>" . htmlspecialchars($error) . "</p>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Upload Song</title>
</head>
<body>
    <div class="container">
        <h2>Upload Song</h2>

        <!-- Song upload form -->
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Song Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="song_file">Choose Song File</label>
                <input type="file" name="song_file" class="form-control" accept="audio/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
</body>
</html>