<?php

$file_path = './usb0.1.zip';

$file_name = basename($file_path);

if (file_exists($file_path)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . $file_name);
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));

    readfile($file_path);

    exit;
} else {
    echo 'File Not Found';
}
?>
