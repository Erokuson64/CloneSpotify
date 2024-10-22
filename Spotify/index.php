<?php
session_start();
include 'db/config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

try {
    $stmt = $pdo->query("SELECT * FROM songs ORDER BY uploaded_at DESC");
    $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching songs: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Home</title>
</head>
<body>
    <div class="container">
        <h2>Welcome to Your Music Player</h2>
        <a href="upload.php" class="btn btn-primary mb-3">Upload a New Song</a>
        <form action="" method="GET">
            <input type="text" name="search" placeholder="Search songs..." class="form-control mb-3">
            <button type="submit" class="btn btn-secondary">Search</button>
        </form>

        <div class="song-list">
            <h2>Uploaded Songs</h2>
            <?php if (empty($songs)): ?>
                <p>No songs uploaded yet.</p>
            <?php else: ?>
                <?php foreach ($songs as $song): ?>
                    <div class="song-item mb-3">
                        <p><?= htmlspecialchars($song['title']) ?></p>
                        <audio controls>
                            <source src="<?= htmlspecialchars($song['file_path']) ?>" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>