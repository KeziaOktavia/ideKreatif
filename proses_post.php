<?php
// Menghubungkan file konfigurasi database
include 'config.php';

// Memulai sesi php
session_start();

// Mendapatkan ID pengguna dari sesi
$userId = $_SESSION["user_id"];

// Menangani form untuk menambahkan postingan baru
if (isset($_POST['simpan'])) {
    // Mnedapatkan data dari form 
    $postTitle = $_POST["post_title"]; //Judul postingan
    $content = $_POST["content"]; // Konten postingan
    $categoryId = $_POST["category_id"]; // ID kategori 

    // Mengatur direktori penyimpanan file gambar
    $imageDir = "assets/img/uploads";
    $imageName = $_FILES["image"]["name"]; // Nama file gambar
    $imagePath = $imageDir . basename($imageName); // Path lengkap gambar

    // Memindahkan file gambar yang diunggah ke direktori tujuan
    if (move_uploaded_file($_FILES["image"]["tmp_name"],$imagePath)) {
        // Jika unggahan berhasil, masukkan
        // data postingan ke dalam database
        $query = "INSERT INTO posts (post_title, content,
        created_at, category_id, user_id, image_path) VALUES
        ('$postTitle', '$content', NOW(), $$categoryId, $userId, '$imagePath')";

        if ($conn->query($query) === TRUE) {
            // Notifikasi berhasil jika postingan berhasil ditambahkan
            $_SESSION['notification'] =[
                'type' => 'primary',
                'message' => 'Post successfully added.'
            ];
        } else {
            // Notifikasi error jika gagal menambahkan postingan
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Error adding post: ' . $conn->error
            ];
        }
    } else {
        // Notifikasi error jika unggahan gambar gagal
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Failed to upload image.'
        ];
    }
   
    // Arahkan ke halaman dashboard setelah selesai
    header('Location: dashboard.php');
    exit();
}

// Proses penghapusan postingan
if (isset($_POST['delete'])) {
    // Mengambil ID post dari parameter URL
    $postID = $_POST['postID'];

    // Query untuk menghapus post berdasarkan ID
    $$exec = mysqli_query($conn, "DELETE FROM posts WHERE id_post='$postID'");

    // Menyimpan notifikasi keberhasilan atau kegagalan ke dalam session
    if ($exec) {
        $_SESSION['notification'] = [
            'type' => 'primary',
            'message' => 'Post successfully deleted.'
        ];
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'mesaage' => 'Error deleting post: ' . mysqli_error($conn)
        ];
    }

    // Redirect kembali ke halaman dashboard
    header('Location: dashboard.php');
    exit();
}