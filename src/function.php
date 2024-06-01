<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Fungsi untuk membuat koneksi ke database
function connectDatabase() {
    $conn = mysqli_connect("localhost", "root", "password", "matafilm_db");

    // Memeriksa koneksi
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    return $conn;
}

// Fungsi untuk melakukan pencarian film berdasarkan judul
function searchFilm($keyword) {
    $conn = connectDatabase();

    // Template query
    $template = "SELECT * FROM film WHERE judul LIKE ? ORDER BY judul ASC";
    $stmt = mysqli_prepare($conn, $template);

    // Wildcard untuk pencarian
    $keyword = "%{$keyword}%";

    // Menambahkan statement
    mysqli_stmt_bind_param($stmt, "s", $keyword);

    // Eksekusi
    mysqli_stmt_execute($stmt);

    // Mengambil hasil
    $result = mysqli_stmt_get_result($stmt);

    // Menutup statement
    mysqli_stmt_close($stmt);

    // Menutup koneksi
    mysqli_close($conn);

    return $result;
}

// Fungsi untuk logout
function logout() {
    // Hapus semua data sesi
    $_SESSION = array();

    // Jika menggunakan cookie sesi, hapus cookie-nya juga
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Hancurkan sesi
    session_destroy();
}

// Cek apakah tombol logout ditekan
if (isset($_POST['logout'])) {
    logout();
    // Redirect ke halaman lain setelah logout (jika diperlukan)
    // header("Location: halaman_lain.php");
    // exit();
}

// Fungsi untuk mengambil nama pengguna berdasarkan username dari session
function getUsername($conn, $username) {
    $result = mysqli_query($conn, "SELECT * FROM user WHERE username ='$username'");
    return mysqli_fetch_assoc($result);
}

function getUsername_id($conn, $user_id) {
    $result = mysqli_query($conn, "SELECT * FROM user WHERE id ='$user_id'");
    return mysqli_fetch_assoc($result);
}

function addFavorite($user_id, $film_id) {
    $conn = connectDatabase();

    // Cek apakah user sudah memfavoritkan film ini
    $check_query = $conn->prepare("SELECT * FROM favorit WHERE user_id = ? AND film_id = ?");
    $check_query->bind_param("ii", $user_id, $film_id);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result->num_rows == 0) {
        // Tambah hitungan favorit di tabel film
        $update_film_query = $conn->prepare("UPDATE film SET favorit = favorit + 1 WHERE film_id = ?");
        $update_film_query->bind_param("i", $film_id);
        $update_film_query->execute();

        // Masukkan ke tabel favorit
        $insert_favorite_query = $conn->prepare("INSERT INTO favorit (user_id, film_id) VALUES (?, ?)");
        $insert_favorite_query->bind_param("ii", $user_id, $film_id);
        $insert_favorite_query->execute();

        if ($update_film_query->affected_rows > 0 && $insert_favorite_query->affected_rows > 0) {
            return "Film berhasil ditambahkan ke favorit!";
        } else {
            return "Gagal menambahkan film ke favorit.";
        }
    } else {
        return "Film sudah ada di daftar favorit.";
    }
}

//hapus favorit
function removeFavorite($user_id, $film_id) {
    $conn = connectDatabase();

    // Hapus dari tabel favorit
    $delete_favorite_query = $conn->prepare("DELETE FROM favorit WHERE user_id = ? AND film_id = ?");
    $delete_favorite_query->bind_param("ii", $user_id, $film_id);
    $delete_favorite_query->execute();

    // Kurangi hitungan favorit di tabel film
    $update_film_query = $conn->prepare("UPDATE film SET favorit = favorit - 1 WHERE film_id = ?");
    $update_film_query->bind_param("i", $film_id);
    $update_film_query->execute();

    if ($delete_favorite_query->affected_rows > 0 && $update_film_query->affected_rows > 0) {
        return "Film berhasil dihapus dari favorit!";
    } else {
        return "Gagal menghapus film dari favorit.";
    }
}

//cek favorit
function checkIfFavorited($user_id, $film_id) {
    $conn = connectDatabase();;

    $query = $conn->prepare("SELECT * FROM favorit WHERE user_id = ? AND film_id = ?");
    $query->bind_param("ii", $user_id, $film_id);
    $query->execute();
    $result = $query->get_result();

    return $result->num_rows > 0;
}

//tambah watchlist
function addToWatchlist($user_id, $film_id) {
    $conn = connectDatabase();;

    // Periksa apakah film_id ada di tabel film
    $film_check_query = $conn->prepare("SELECT * FROM film WHERE film_id = ?");
    $film_check_query->bind_param("i", $film_id);
    $film_check_query->execute();
    $film_result = $film_check_query->get_result();

    if ($film_result->num_rows == 0) {
        return "Film ID tidak ditemukan.";
    }

    // Cek apakah user sudah menambahkan film ini ke watchlist
    $check_query = $conn->prepare("SELECT * FROM watchlist WHERE user_id = ? AND film_id = ?");
    $check_query->bind_param("ii", $user_id, $film_id);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result->num_rows == 0) {
        // Masukkan ke tabel watchlist
        $insert_watchlist_query = $conn->prepare("INSERT INTO watchlist (user_id, film_id) VALUES (?, ?)");
        $insert_watchlist_query->bind_param("ii", $user_id, $film_id);
        $insert_watchlist_query->execute();

        if ($insert_watchlist_query->affected_rows > 0) {
            return "Film berhasil ditambahkan ke watchlist!";
        } else {
            return "Gagal menambahkan film ke watchlist.";
        }
    } else {
        return "Film sudah ada di watchlist.";
    }
}

function removeFromWatchlist($user_id, $film_id) {
    $conn = connectDatabase();;

    // Hapus dari tabel watchlist
    $delete_watchlist_query = $conn->prepare("DELETE FROM watchlist WHERE user_id = ? AND film_id = ?");
    $delete_watchlist_query->bind_param("ii", $user_id, $film_id);
    $delete_watchlist_query->execute();

    if ($delete_watchlist_query->affected_rows > 0) {
        return "Film berhasil dihapus dari watchlist!";
    } else {
        return "Gagal menghapus film dari watchlist.";
    }
}

//cek watchlist
function checkIfInWatchlist($user_id, $film_id) {
    $conn = connectDatabase();;

    $query = $conn->prepare("SELECT * FROM watchlist WHERE user_id = ? AND film_id = ?");
    $query->bind_param("ii", $user_id, $film_id);
    $query->execute();
    $result = $query->get_result();

    return $result->num_rows > 0;
}

function getFavoriteFilms($user_id) {
    $conn = connectDatabase();;

    $query = $conn->prepare("
        SELECT *
        FROM favorit
        JOIN film ON favorit.film_id = film.film_id
        WHERE favorit.user_id = ?
    ");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();

    $films = [];
    while ($row = $result->fetch_assoc()) {
        $films[] = $row;
    }

    return $films;
}

function getWatchlistFilms($user_id) {
    $conn = connectDatabase();;

    $query = $conn->prepare("
        SELECT *
        FROM watchlist
        JOIN film ON watchlist.film_id = film.film_id
        WHERE watchlist.user_id = ?
    ");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();

    $films = [];
    while ($row = $result->fetch_assoc()) {
        $films[] = $row;
    }

    return $films;
}

?>
