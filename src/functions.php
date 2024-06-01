<?php

// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "kabut123", "matafilm_db");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function registrasi($data) {
    global $conn;

    $nama = mysqli_real_escape_string($conn, $data["nama"]);
    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);

    // cek password
    if ($password !== $password2) {
        echo "<script>
                alert('Konfirmasi password tidak sesuai!');
              </script>";
        return false;
    }

    // cek apakah username sudah ada
    $result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");
    $result2 = mysqli_query($conn, "SELECT username FROM user WHERE nama = '$nama'");
    if (mysqli_fetch_assoc($result)) {
        echo "<script>
                alert('Username sudah terdaftar!');
              </script>";
        return false;
    } else if(mysqli_fetch_assoc($result2)){
        echo "<script>
                alert('Nama sudah ada!');
              </script>";
        return false;
    }

    // enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // tambahkan user baru ke database
    $query = "INSERT INTO user (username, password, nama) VALUES ('$username', '$password','$nama')";
    if (mysqli_query($conn, $query)) {
        return mysqli_affected_rows($conn);
    } else {
        echo "<script>
                alert('User gagal ditambahkan!');
              </script>";
        return false;
    }
}
?>