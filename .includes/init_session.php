<?php
session_start();

// Periksa apakah kunci "name" dan "role" ada dalam sesi sebelum mengaksesnya
$name = $_SESSION["name"];
$role = $_SESSION["role"];

// Ambil notifikasi jika ada, kemudian hapus dari sesi
$notification = $_SESSION['notification'] ?? null;
if ($notification) {
    unset($_SESSION['notification']);
}