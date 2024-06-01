<?php
require 'function.php';

//koneksi
$conn = connectDatabase();
$result10teratas = mysqli_query($conn, "SELECT * FROM film ORDER BY penonton DESC");
$result10 = mysqli_query($conn, "SELECT * FROM film ORDER BY penonton DESC");
$genres = mysqli_query($conn, "SELECT * FROM genres");
$resultfavorit = mysqli_query($conn, "SELECT * FROM film ORDER BY favorit DESC");

//cari
if(isset($_GET['keyword'])){
    $keyword = $_GET['keyword'];
    header("location: search.php?keyword=$keyword");
}

//mengambil session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
if($username){
    $nama = getUsername($conn, $username)['nama'];
    $user_id = getUsername($conn, $username)['id'];
    $watchlist = getWatchlistFilms($user_id);
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

    <title>About Page</title>
</head>

<body class=" font-ibm " data-theme="business">
    <!-- navbar -->
    <div class="navbar flex justify-between bg-base-100 fixed top-0 left-0 w-full z-50 shadow-lg px-10">
        <div class="w-10/12 ">
            <a class="flex items-center " href="index.php">
                <img src="img/logo_matafilm.png" alt="Logo" class="h-16">
            </a>
            <div class="flex-none  ">
                <ul class="menu menu-horizontal px-1">
                    <li>
                        <details>
                            <summary class="text-lg">
                                Menu
                            </summary>
                            <ul class="p-2 bg-base-100 rounded-t-none ">
                                <li><a>Donation</a></li>
                                <li><a href="about.php">About Us</a></li>
                            </ul>
                        </details>
                    </li>
                </ul>
            </div>
            <div class="flex-grow  mx-auto w-full">
                <div class="form-control mx-auto w-auto ">
                    <form action="search.php" method="get" class="search">
                        <input type="text" placeholder="Cari MataFilm" class="input input-ed w-full bg-neutral-50" name="keyword" autocomplete="off"/>
                    </form>
                </div>
            </div>
        </div>

        <div class="w-2/10 ">
            <div class=" w-full ">
                <ul class="menu menu-horizontal px-1 mx-auto">
                    <div class="flex items-center ">
                        <?php
                            if(!isset($username)){
                                echo    "<a href='login.php'>
                                            <div class='flex items-center h-10 my-auto mx-auto'>
                                                <li>Login</li>
                                            </div>
                                        </a>";
                            } else {

                                echo    "<a href='profil.php?user_id={$user_id}'>
                                            <div class='flex items-center h-10 my-auto mr-10'>
                                                <li>{$nama}</li>
                                            </div>
                                        </a>";

                                echo    '<form action="" method="post">
                                            <div class="flex items-center h-10 my-auto ml-10">
                                                <button type="submit" name="logout">Logout</button>
                                            </div>
                                        </form>';
                            }
                            ?>
                    </div>
                </ul>
            </div>
        </div>
    </div>
    <!-- navbar ends -->

        <div class="w-10/12 mx-auto py-32 font-montserrat">
            <div class="text-3xl font-bold mb-5">Tentang MataFilm</div>
            <div class="text-justify text-lg">
            MataFilm adalah platform daring yang didedikasikan untuk mengulas dan memberikan rating pada film-film Indonesia. MataFilm memungkinkan pengguna untuk menilai dan memberikan ulasan jujur tentang film-film lokal. Dengan fokus utama untuk memajukan perfilman Indonesia, MataFilm menyediakan rekomendasi film-film Indonesia terbaik berdasarkan penilaian dan ulasan dari komunitas pengguna.

            </div>
            <div class="text-xl font-bold mb-2 mt-10">Visi</div>
            <div class="text-justify text-lg">
            Menjadi platform terdepan yang menghubungkan penonton dengan film-film Indonesia berkualitas, serta mendorong pertumbuhan dan apresiasi terhadap industri perfilman Indonesia.

            </div>
            <div class="text-xl font-bold mb-2 mt-10 ">Misi</div>
            <div class="ml-10">
            <div class="text-justify text-lg mb-1">
            1. ⁠Meningkatkan Kesadaran dan Apresiasi: Memperkenalkan dan mempromosikan film-film Indonesia kepada audiens yang lebih luas, baik di dalam maupun luar negeri, melalui ulasan dan rating yang jujur dan informatif.

            </div>
            <div class="text-justify text-lg mb-1">
            2. ⁠Menyediakan Rekomendasi Berkualitas: Memberikan rekomendasi film-film Indonesia terbaik yang didasarkan pada penilaian dan ulasan dari komunitas pengguna, memastikan penonton menemukan film yang sesuai dengan selera dan preferensi mereka.

                dan terlibat aktif dalam masyarakat.
            </div>
            <div class="text-justify text-lg mb-1">
            3.⁠ ⁠Mendukung Kreator Film Lokal: Menciptakan ruang di mana kreator film Indonesia dapat menerima feedback konstruktif dari penonton, sehingga dapat meningkatkan kualitas karya mereka di masa depan.
            </div>
            </div>
            <div class="mt-10">
                <div class="text-xl font-bold mb-2 mt-10 w-fit mx-auto">Pendiri</div>
                <div class="w-6/12 mx-auto flex mt-5">
                    <div class="w-4/12">
                        <div class="w-11/12">
                            <img src="img/asti.jpeg" alt="Asti Syafitri" class="h-32 max-w-30 mx-auto rounded-lg" />
                            <div class="mx-auto w-fit">Asti Syafitri</div>
                        </div>
                    </div>
                    <div class="w-4/12">
                        <div class="w-11/12 ">
                            <img src="img/vino.jpeg" alt="Alvino Idma" class="h-32 w-32 object-cover mx-auto rounded-lg" />
                            <div class="mx-auto w-fit">Alvino Idma</div>
                        </div>
                    </div>
                    <div class="w-4/12">
                        <div class="w-11/12">
                            <img src="img/eufra.jpeg" alt="Brigita Mery" class="h-32 w-32 object-cover mx-auto rounded-lg" />
                            <div class="mx-auto w-fit">Brigita Mery</div>
                        </div>
                    </div>
                    <div class="w-4/12">
                        <div class="w-11/12">
                            <img src="img/baron.jpeg" alt="Akbar Fauzan" class="h-32 w-32 object-cover mx-auto rounded-lg" />
                            <div class="mx-auto w-fit">Akbar Fauzan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</body>
<footer class="footer footer-center p-4 bg-base-300 text-base-content">
  <aside>
    <p>Copyright © 2024 - All right reserved by baron fc</p>
  </aside>
</footer>
</html>