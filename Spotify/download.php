<?php
if (isset($_GET['file'])) {
    $file = urldecode($_GET['file']);
    $file = basename($file); 

    $filePath = "uploads/" . $file;

    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        flush();
        readfile($filePath); 
        exit;
    } else {
        echo "File does not exist.";
    }
}
?>