<?php
require 'function.php';

// Koneksi
$conn = connectDatabase();

if(isset($_POST['submit'])){
    // Periksa apakah pengguna sudah login
    if(!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit;
    }

    // Ambil user_id dari session username
    $username = $_SESSION['username'];
    $user_id = getUsername($conn, $username)['id'];

    // Ambil data dari form
    $film_id = $_POST['film_id'];
    $komentar = $_POST['isi_komentar'];
    $rating = $_POST['rating'];
    $tanggal_komentar = date("Y-m-d H:i:s"); // Waktu komentar

    // Masukkan komentar dan rating ke dalam tabel
    $query = "INSERT INTO komentar (film_id, komentar, user_rating, tanggal_komentar, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isiss", $film_id, $komentar, $rating, $tanggal_komentar, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    // Redirect kembali ke halaman detail film
    header("Location: detail.php?id=$film_id");
    exit();
}


?>
