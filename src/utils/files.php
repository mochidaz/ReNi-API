<?php

function uploadImage($file, $targetDir, $maxFileSize = 2097152, $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'])
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'File upload error'];
    }

    if ($file['size'] > $maxFileSize) {
        return ['success' => false, 'message' => 'File size exceeds the maximum limit'];
    }

    $fileType = mime_content_type($file['tmp_name']);
    if (!in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'message' => 'Invalid file type'];
    }

    $fileName = uniqid() . '_' . basename($file['name']);
    $targetFilePath = rtrim($targetDir, '/') . '/' . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        // remove "../"
        $targetFilePath = str_replace('..', '', $targetFilePath);
        return ['success' => true, 'message' => 'File uploaded successfully', 'file_path' => $targetFilePath];
    } else {
        return ['success' => false, 'message' => 'Failed to move uploaded file'];
    }
}


?>