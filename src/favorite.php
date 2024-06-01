<?php
require 'function.php';
$conn = connectDatabase();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $film_id = $_POST['film_id'];
    $username = $_SESSION['username'];
    $user_id = getUsername($conn, $username)['id'];

    if ($film_id > 0) {
        if (isset($_POST['favorite'])) {
            $message = addFavorite($user_id, $film_id);
        } elseif (isset($_POST['unfavorite'])) {
            $message = removeFavorite($user_id, $film_id);
        } else {
            $message = "Aksi tidak valid.";
        }
        echo $message;
    } else {
        echo "Film ID tidak valid.";
    }
    header("Location: detail.php?id=$film_id");
    exit();
}
?>
