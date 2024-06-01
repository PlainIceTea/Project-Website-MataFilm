<?php
session_start();

require 'functions.php';

if (isset($_POST["login"])){

        $username = $_POST["username"];
        $password = $_POST["password"];

        $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");

        //cek username
        if (mysqli_num_rows($result) === 1) {
            //cek password
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row ["password"])){

                //buat session
                $_SESSION["username"]= $username;
                header("Location: index.php");
                exit;
                
            } 
        }        
}   
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./output.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="text/javascript" src="yeah.js"></script>

    <title>Login Page</title>
</head>
<body class="flex font-ibm " data-theme="business">

    <div class="w-3/12 mx-auto my-40 flex flex-col border  p-5 rounded shadow-lg bg-white">
        <img src="img/logo2.png" alt="" class="w-28 mx-auto mb-5">
        <div class="text-2xl font-semibold text-black text-center mb-5">
            Masuk
        </div>
        <form action="" method="POST" class="flex flex-col space-y-4" id="loginForm" onsubmit="return validateForm()">
            <div class="flex flex-col">
                <label for="username" class="mb-2 text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="border border-gray-300 p-2 rounded text-black focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
            </div>
            <div class="flex flex-col">
                <label for="password" class="mb-2 text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="border border-gray-300 p-2 rounded focus:outline-none text-black bg-white focus:ring-2 focus:ring-blue-500">
            </div>
            <button name="login" type="submit" class="bg-blue-500 text-white py-2 rounded hover:bg-blue-600  transition duration-200">Login</button>
        </form>

        <a href="registrasi.php" class="mt-5 bg-green-500 text-white py-2 rounded hover:bg-green-600 transition duration-200 flex items-center justify-center">
            <span>Daftar</span>
        </a>
    </div>





    <script>
    function validateForm() {
        // Mendapatkan nilai input
        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;

        // Memeriksa apakah semua kolom telah diisi
        if (username === "" || password === "") {
            alert("Harap isi semua kolom.");
            return false; // Menghentikan pengiriman formulir
        }
        return true; // Mengizinkan pengiriman formulir jika semua validasi berhasil
    }
    </script>
    
</body>
</html>