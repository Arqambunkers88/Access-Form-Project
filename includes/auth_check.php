<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Security: Prevent accessing other roles' folders
$current_path = $_SERVER['PHP_SELF'];
$user_role = $_SESSION['role'];

if (strpos($current_path, '/admin/') !== false && $user_role !== 'Admin') {
    echo "<script>alert('Unauthorized Access!'); window.location.href='../index.php';</script>";
    exit();
}
if (strpos($current_path, '/creator/') !== false && $user_role !== 'Form Creator') {
    echo "<script>alert('Unauthorized Access!'); window.location.href='../index.php';</script>";
    exit();
}
if (strpos($current_path, '/respondent/') !== false && $user_role !== 'Respondent') {
    echo "<script>alert('Unauthorized Access!'); window.location.href='../index.php';</script>";
    exit();
}

// --- NEW: Visual Persistence Logic ---
$body_class = "";
$inline_style = "";

// 1. Check High Contrast
if (isset($_SESSION['high_contrast']) && $_SESSION['high_contrast'] == 1) {
    $body_class = "dark-mode";
}

// 2. Check Font Size
if (isset($_SESSION['font_size'])) {
    $size = $_SESSION['font_size'];
    $px = "16px"; // default
    if ($size == "Large") $px = "20px";
    if ($size == "Extra Large") $px = "24px";
    $inline_style = "style='--base-font-size: $px;'";
}
?>