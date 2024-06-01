<?php
require 'function.php';

//koneksi
$conn = connectDatabase();
$result10teratas = mysqli_query($conn, "SELECT * FROM film ORDER BY penonton DESC LIMIT 10");
$result = mysqli_query($conn, "SELECT * FROM film");
$genres = mysqli_query($conn, "SELECT * FROM genres");
$resultfavorit = mysqli_query($conn, "SELECT * FROM film ORDER BY favorit DESC");
$result2024 = mysqli_query($conn, "SELECT * FROM film WHERE tahun = 2024 ORDER BY judul ASC");

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
    //cek favorit
    while ($row = mysqli_fetch_assoc($result)) {
        $film_id = $row['film_id']; // Ambil ID film dari hasil query
        // Cek apakah film sudah difavoritkan oleh pengguna
        $is_favorited[$film_id] = checkIfFavorited($user_id, $film_id);
        // Cek apakah film sudah ada di watchlist pengguna
        $is_in_watchlist[$film_id] = checkIfInWatchlist($user_id, $film_id);
    }
}

//user_rating
$queryUR = "SELECT film_id, AVG(user_rating) AS avg_rating FROM komentar GROUP BY film_id";
$resultUR = mysqli_query($conn, $queryUR);

$average_ratings = array(); // Inisialisasi array untuk menyimpan rata-rata rating

while ($row = mysqli_fetch_assoc($resultUR)) {
    $film_id = $row['film_id'];
    $avg_rating = $row['avg_rating'];

    // Menyimpan hasil ke dalam array dengan film_id sebagai kunci
    $average_ratings[$film_id] = round($avg_rating, 2);
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
    <link rel="icon" href="img/logo2.png" type="image/png">
    <title>Document</title>
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
                    <form action="" method="get" class="search">
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
    
    <div class=" mx-auto mt-20 ">
        <!-- genres ends -->


        <!-- Hero Banner start -->
        <div class="bg-base-100 w-screen p-5 m-0 flex justify-between items-center bg-cover bg-center flex-col"
            style="background-image: url('img/bghero.png');">
            <div class="flex-none justify-between  px-20">
                <ul class="menu menu-horizontal px-1 mx-auto flex justify-between ">
                    <?php
                        while($genre = mysqli_fetch_assoc($genres)){
                            echo "<li><a class='genre-link' href=genre.php?genre_id={$genre['genre_id']}>{$genre['genre'] }</a></li>";
                        }
                    ?>
                </ul>
            </div>
            <div class="w-screen flex justify-center flex-col">
                <div class="text-center mt-48 font-bold my-1 text-7xl">
                    WELCOME TO <span style="color: cornflowerblue;">MATA</span>FILM
                </div>

                <div class="text-center mt-0 text-4xl my-3">Your Ultimate Destination for Indonesian Film Reviews and
                    Ratings!</div>
                <div class="text-center my-10 w-8/12 mx-auto">Discover the best of Indonesian cinema with honest
                    reviews
                    and ratings from fellow movie enthusiasts. Whether you're looking for the latest blockbuster or a
                    hidden
                    gem, MataFilm is your go-to platform for all things related to Indonesian films.</div>
                <div class="my-auto h-screen"></div>
            </div>
        </div >

        <!-- Hero Banner ends -->


        <!-- list tontonan start -->
        <div class=" h-[5rem]" id="bmark" ></div>
    <div class="">
        <div class="flex flex-col px-10 ">
            <div class="w-6/12 text-2xl border-l-4 border-yellow-500 px-3" style="color: cornflowerblue;">
                Nonton apa hari ini?
            </div>
        </div>

        <div class="px-10">Dari List Tontonan Anda</div>

        <?php if ($username): ?>
            <?php if (count($watchlist)): ?>
                <div class="carousel w-screen my-10 p-10 pt-5">
                    <?php foreach ($watchlist as $film): ?>
                        <a href="detail.php?id=<?php echo $film['film_id']?>">
                            <div class="carousel-item">
                                <div class="flex flex-col">
                                    <div class="mx-10 border border-neutral-700 card w-72 py-3 bg-base-100 h-[37rem] px-3 shadow-2xl transition transform hover:scale-105 hover:bg-opacity-90 duration-200">
                                        <div>
                                        <div class="w-full flex text-sm">
                                            <div class="w-6/12 flex mr-1 items-center  ">
                                                <div class="flex p-2">
                                                    <img src="img/star.png" alt="star" class=" h-5 mx-2">
                                                    <div class="font-bold text-lg"><?php echo $film['rating']?></div>
                                                    <div class="text-sm pt-2">/10</div>
                                                </div>
                                            </div>
                                            <div class="w-6/12 flex flex-col items-center">
                                                <div class="flex p-2">
                                                    <img src="img/mafilmstar.png" alt="star" class=" h-5 mx-2">
                                                    <div class="font-bold text-lg">
                                                    <?php 
                                                        if(isset($average_ratings[$film['film_id']])) {
                                                            echo $average_ratings[$film['film_id']];
                                                        } else {
                                                            echo "0";
                                                        }
                                                    ?>
                                                    </div>
                                                    <div class="text-sm pt-2">/10</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        <figure class="border w-64 h-80 overflow-hidden">
                                            <img src="img/<?php echo $film['gambar']; ?>" alt="film" />
                                        </figure>
                                        <div class="card-body -mt-5 px-0 py-5">
                                            <h2 class="card-title"><?php echo $film["judul"]; ?></h2>
                                            <div class="card-actions justify-end">
                                                <div class="badge badge-outline ml-auto"><?php echo $film["tahun"]; ?></div>
                                            </div>
                                        </div>
                                        <form method="post" action="watchlist_index.php">
                                            <input type="hidden" name="film_id" value="<?php echo htmlspecialchars($film['film_id']); ?>">
                                            <?php if ($is_in_watchlist[$film['film_id']]): ?>
                                                <button type="submit" name="remove_watchlist" class="flex  hover:bg-blue-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                            <div class="w-full flex-col">
                                                <div class="font-bold text-sm mx-auto">Hapus dari Watchlist</div>
                                            </div>
                                        </button>
                                            <?php else: ?>
                                                <button class="flex bg-blue-600 hover:bg-blue-800 p-2 rounded-md w-full mt-2 transition-colors duration-300" name="add_watchlist" type="submit">
                                                    <div class="my-auto mx-1">
                                                        <img src="img/plussign.png" alt="">
                                                    </div>
                                                    <div class="w-full flex-col">
                                                        <div class="font-bold text-sm mx-auto">Tambahkan ke List Tontonan</div>
                                                    </div>
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                        <?php if ($username):?>
                                            <form method="post" action="favorite_index.php">
                                                <input type="hidden" name="film_id" value="<?php echo htmlspecialchars($film['film_id']); ?>">
                                                <?php if ($is_favorited[$film['film_id']]): ?>
                                                    <button type="submit" name="unfavorite" class="flex  hover:bg-red-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                                <div class="w-full flex-col">
                                                    <div class="font-bold text-sm mx-auto">Hapus Favorit</div>
                                                </div>
                                            </button>
                                                <?php else: ?>
                                                    <button type="submit" name="favorite" class="flex bg-red-600 hover:bg-red-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                                        <div class="my-auto mx-1">
                                                            <img src="img/plussign.png" alt="">
                                                        </div>
                                                        <div class="w-full flex-col">
                                                            <div class="font-bold text-sm mx-auto">Tambahkan ke List Favorit</div>
                                                        </div>
                                                    </button>
                                                <?php endif; ?>
                                            </form>
                                        <?php else:?>
                                            <a href="login.php" class="flex bg-red-600 hover:bg-red-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                                <div class="my-auto mx-1">
                                                    <img src="img/plussign.png" alt="">
                                                </div>
                                                <div class="w-full flex-col">
                                                    <div class="font-bold text-sm mx-auto">Tambahkan ke List Favorit</div>
                                                </div>
                                            </a>
                                        <?php endif;?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="flex w-screen my-20 flex-col">
                    <div class="mx-auto"><img src="img/logobookmark.png" alt="Logo" class="h-12"></div>
                    <div class="mx-auto my-5">Tidak ada film di watchlist</div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <a href="login.php">
                <div class="flex w-screen my-20 flex-col">
                    <div class="mx-auto"><img src="img/logobookmark.png" alt="Logo" class="h-12"></div>
                    <div class="mx-auto my-5">Simpan acara dan film untuk melacak apa yang ingin Anda Tonton.</div>
                </div>
            </a>
        <?php endif;?>
    </div>
        <!-- list tontonan ends -->


        <!-- top 10 start -->
    <div class="">
        <div class="flex flex-col px-10">
            <div class="w-6/12 text-2xl border-l-4 border-yellow-500 px-3">10 Film Teratas di MataFilm</div>
            <div class="text-lg text-gray-500">Tontonan dengan penonton terbanyak</div>
        </div>

        <div class="carousel w-screen my-10 p-10 pt-5">
            <?php
            while($row = mysqli_fetch_assoc($result10teratas)): ?>
                <a href = "detail.php?id=<?php echo $row['film_id'];?>">
                    <div class="carousel-item">
                        <div class="flex flex-col">
                            <div class=" mx-10 border border-neutral-700 card w-72 py-3 bg-base-100 h-[37rem] px-3 shadow-2xl transition transform hover:scale-105 hover:bg-opacity-90 duration-200">
                                <div>
                                    <div class="w-full flex text-sm">
                                        <div class="w-6/12 flex mr-1 items-center  ">
                                            <div class="flex p-2">
                                                <img src="img/star.png" alt="star" class=" h-5 mx-2">
                                                <div class="font-bold text-lg"><?php echo $row['rating']?></div>
                                                <div class="text-sm pt-2">/10</div>
                                            </div>
                                        </div>
                                        <div class="w-6/12 flex flex-col items-center">
                                            <div class="flex p-2">
                                                <img src="img/mafilmstar.png" alt="star" class=" h-5 mx-2">
                                                <div class="font-bold text-lg">
                                                    <?php 
                                                        if(isset($average_ratings[$row['film_id']])) {
                                                            echo $average_ratings[$row['film_id']];
                                                        } else {
                                                            echo "0";
                                                        }
                                                    ?>
                                                </div>
                                                <div class="text-sm pt-2">/10</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <figure class="border w-64 h-80 overflow-hidden">
                                    <img src="img/<?php echo $row['gambar']; ?>" alt="film" />
                                </figure>

                                <div class="card-body -mt-5 px-0 py-5">
                                    <h2 class="card-title"><?php echo $row['judul']; ?></h2>
                                </div>
                                <div class="card-actions justify-end">
                                    <div class="badge badge-outline ml-auto"><?php echo $row['tahun']; ?></div>
                                </div>

                                <!--watchlist-->
                                <?php if ($username):?>
                                    <form method="post" action="watchlist_index.php">
                                        <input type="hidden" name="film_id" value="<?php echo htmlspecialchars($row['film_id']); ?>">
                                        <?php if ($is_in_watchlist[$row['film_id']]): ?>
                                            <button type="submit" name="remove_watchlist" class="flex  hover:bg-blue-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                            <div class="w-full flex-col">
                                                <div class="font-bold text-sm mx-auto">Hapus dari Watchlist</div>
                                            </div>
                                        </button>
                                        <?php else: ?>
                                            <button class="flex bg-blue-600 hover:bg-blue-800 p-2 rounded-md w-full mt-2 transition-colors duration-300" name="add_watchlist" type="submit">
                                                <div class="my-auto mx-1">
                                                    <img src="img/plussign.png" alt="">
                                                </div>
                                                <div class="w-full flex-col">
                                                    <div class="font-bold text-sm mx-auto">Tambahkan ke List Tontonan</div>
                                                </div>
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                <?php else:?>
                                    <a href="login.php" class="flex bg-blue-600 hover:bg-blue-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                            <div class="my-auto mx-1">
                                                <img src="img/plussign.png" alt="">
                                            </div>
                                            <div class="w-full flex-col">
                                                <div class="font-bold text-sm mx-auto">Tambahkan ke List Tontonan</div>
                                            </div>
                                        </a>
                                <?php endif; ?>
                                
                                <!--favorit-->
                                <?php if ($username):?>
                                    <form method="post" action="favorite_index.php">
                                        <input type="hidden" name="film_id" value="<?php echo htmlspecialchars($row['film_id']); ?>">
                                        <?php if ($is_favorited[$row['film_id']]): ?>
                                            <button type="submit" name="unfavorite" class="flex  hover:bg-red-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                                <div class="w-full flex-col">
                                                    <div class="font-bold text-sm mx-auto">Hapus Favorit</div>
                                                </div>
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" name="favorite" class="flex bg-red-600 hover:bg-red-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                            <div class="my-auto mx-1">
                                                <img src="img/plussign.png" alt="">
                                            </div>
                                            <div class="w-full flex-col">
                                                <div class="font-bold text-sm mx-auto">Tambahkan ke List Favorit</div>
                                            </div>
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                <?php else:?>
                                    <a href="login.php" class="flex bg-red-600 hover:bg-red-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                        <div class="my-auto mx-1">
                                            <img src="img/plussign.png" alt="">
                                        </div>
                                        <div class="w-full flex-col">
                                            <div class="font-bold text-sm mx-auto">Tambahkan ke List Favorit</div>
                                        </div>
                                    </a>
                                <?php endif;?>
                            </div>

                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- top 10 ends -->


        <!-- film favorit -->
    <div class="">
        <div class="flex flex-col px-10">
            <div class="w-6/12 text-2xl border-l-4 border-yellow-500 px-3">Film Favorit Penonton</div>
            <div class="text-lg text-gray-500">Tontonan dengan favorit terbanyak</div>
        </div>


        <div class="carousel w-screen my-10 p-10 pt-5">

        <?php while($row = mysqli_fetch_assoc($resultfavorit)): ?>
            <a href = "detail.php?id=<?php echo $row['film_id'];?>">
                    <div class="carousel-item">
                        <div class="flex flex-col">
                            <div class=" mx-10 border border-neutral-700 card w-72 py-3 bg-base-100 h-[37rem] px-3 shadow-2xl transition transform hover:scale-105 hover:bg-opacity-90 duration-200">
                                <div>
                                    <div class="w-full flex text-sm">
                                        <div class="w-6/12 flex mr-1 items-center  ">
                                            <div class="flex p-2">
                                                <img src="img/star.png" alt="star" class=" h-5 mx-2">
                                                <div class="font-bold text-lg"><?php echo $row['rating']?></div>
                                                <div class="text-sm pt-2">/10</div>
                                            </div>
                                        </div>
                                        <div class="w-6/12 flex flex-col items-center">
                                            <div class="flex p-2">
                                                <img src="img/mafilmstar.png" alt="star" class=" h-5 mx-2">
                                                <div class="font-bold text-lg">
                                                    <?php 
                                                        if(isset($average_ratings[$row['film_id']])) {
                                                            echo $average_ratings[$row['film_id']];
                                                        } else {
                                                            echo "0";
                                                        }
                                                    ?>
                                                </div>
                                                <div class="text-sm pt-2">/10</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <figure class="border w-64 h-80 overflow-hidden">
                                    <img src="img/<?php echo $row['gambar']; ?>" alt="film" />
                                </figure>

                                <div class="card-body -mt-5 px-0 py-5">
                                    <h2 class="card-title"><?php echo $row['judul']; ?></h2>
                                </div>
                                <div class="card-actions justify-end">
                                    <div class="badge badge-outline ml-auto"><?php echo $row['tahun']; ?></div>
                                </div>

                                <!--watchlist-->
                                <?php if ($username):?>
                                    <form method="post" action="watchlist_index.php">
                                        <input type="hidden" name="film_id" value="<?php echo htmlspecialchars($row['film_id']); ?>">
                                        <?php if ($is_in_watchlist[$row['film_id']]): ?>
                                            <button type="submit" name="remove_watchlist" class="flex  hover:bg-blue-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                            <div class="w-full flex-col">
                                                <div class="font-bold text-sm mx-auto">Hapus dari Watchlist</div>
                                            </div>
                                        </button>
                                        <?php else: ?>
                                            <button class="flex bg-blue-600 hover:bg-blue-800 p-2 rounded-md w-full mt-2 transition-colors duration-300" name="add_watchlist" type="submit">
                                                <div class="my-auto mx-1">
                                                    <img src="img/plussign.png" alt="">
                                                </div>
                                                <div class="w-full flex-col">
                                                    <div class="font-bold text-sm mx-auto">Tambahkan ke List Tontonan</div>
                                                </div>
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                <?php else:?>
                                    <a href="login.php" class="flex bg-blue-600 hover:bg-blue-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                            <div class="my-auto mx-1">
                                                <img src="img/plussign.png" alt="">
                                            </div>
                                            <div class="w-full flex-col">
                                                <div class="font-bold text-sm mx-auto">Tambahkan ke List Tontonan</div>
                                            </div>
                                        </a>
                                <?php endif; ?>
                                
                                <!--favorit-->
                                <?php if ($username):?>
                                    <form method="post" action="favorite_index.php">
                                        <input type="hidden" name="film_id" value="<?php echo htmlspecialchars($row['film_id']); ?>">
                                        <?php if ($is_favorited[$row['film_id']]): ?>
                                            <button type="submit" name="unfavorite" class="flex  hover:bg-red-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                                <div class="w-full flex-col">
                                                    <div class="font-bold text-sm mx-auto">Hapus Favorit</div>
                                                </div>
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" name="favorite" class="flex bg-red-600 hover:bg-red-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                            <div class="my-auto mx-1">
                                                <img src="img/plussign.png" alt="">
                                            </div>
                                            <div class="w-full flex-col">
                                                <div class="font-bold text-sm mx-auto">Tambahkan ke List Favorit</div>
                                            </div>
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                <?php else:?>
                                    <a href="login.php" class="flex bg-red-600 hover:bg-red-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                        <div class="my-auto mx-1">
                                            <img src="img/plussign.png" alt="">
                                        </div>
                                        <div class="w-full flex-col">
                                            <div class="font-bold text-sm mx-auto">Tambahkan ke List Favorit</div>
                                        </div>
                                    </a>
                                <?php endif;?>
                            </div>

                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="">
        <div class="flex flex-col px-10">
            <div class="w-6/12 text-2xl border-l-4 border-yellow-500 px-3">Film Tahun 2024</div>
            <div class="text-lg text-gray-500">Tontonan yang keluar di tahun 2024</div>
        </div>

        <div class="carousel w-screen my-10 p-10 pt-5">
            <?php
            while($row = mysqli_fetch_assoc($result2024)): ?>
                <a href = "detail.php?id=<?php echo $row['film_id'];?>">
                    <div class="carousel-item">
                        <div class="flex flex-col">
                            <div class=" mx-10 border border-neutral-700 card w-72 py-3 bg-base-100 h-[37rem] px-3 shadow-2xl transition transform hover:scale-105 hover:bg-opacity-90 duration-200">
                                <div>
                                    <div class="w-full flex text-sm">
                                        <div class="w-6/12 flex mr-1 items-center  ">
                                            <div class="flex p-2">
                                                <img src="img/star.png" alt="star" class=" h-5 mx-2">
                                                <div class="font-bold text-lg"><?php echo $row['rating']?></div>
                                                <div class="text-sm pt-2">/10</div>
                                            </div>
                                        </div>
                                        <div class="w-6/12 flex flex-col items-center">
                                            <div class="flex p-2">
                                                <img src="img/mafilmstar.png" alt="star" class=" h-5 mx-2">
                                                <div class="font-bold text-lg">
                                                    <?php 
                                                        if(isset($average_ratings[$row['film_id']])) {
                                                            echo $average_ratings[$row['film_id']];
                                                        } else {
                                                            echo "0";
                                                        }
                                                    ?>
                                                </div>
                                                <div class="text-sm pt-2">/10</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <figure class="border w-64 h-80 overflow-hidden">
                                    <img src="img/<?php echo $row['gambar']; ?>" alt="film" />
                                </figure>

                                <div class="card-body -mt-5 px-0 py-5">
                                    <h2 class="card-title"><?php echo $row['judul']; ?></h2>
                                </div>
                                <div class="card-actions justify-end">
                                    <div class="badge badge-outline ml-auto"><?php echo $row['tahun']; ?></div>
                                </div>

                                <!--watchlist-->
                                <?php if ($username):?>
                                    <form method="post" action="watchlist_index.php">
                                        <input type="hidden" name="film_id" value="<?php echo htmlspecialchars($row['film_id']); ?>">
                                        <?php if ($is_in_watchlist[$row['film_id']]): ?>
                                            <button type="submit" name="remove_watchlist" class="flex  hover:bg-blue-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                            <div class="w-full flex-col">
                                                <div class="font-bold text-sm mx-auto">Hapus dari Watchlist</div>
                                            </div>
                                        </button>
                                        <?php else: ?>
                                            <button class="flex bg-blue-600 hover:bg-blue-800 p-2 rounded-md w-full mt-2 transition-colors duration-300" name="add_watchlist" type="submit">
                                                <div class="my-auto mx-1">
                                                    <img src="img/plussign.png" alt="">
                                                </div>
                                                <div class="w-full flex-col">
                                                    <div class="font-bold text-sm mx-auto">Tambahkan ke List Tontonan</div>
                                                </div>
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                <?php else:?>
                                    <a href="login.php" class="flex bg-blue-600 hover:bg-blue-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                            <div class="my-auto mx-1">
                                                <img src="img/plussign.png" alt="">
                                            </div>
                                            <div class="w-full flex-col">
                                                <div class="font-bold text-sm mx-auto">Tambahkan ke List Tontonan</div>
                                            </div>
                                        </a>
                                <?php endif; ?>
                                
                                <!--favorit-->
                                <?php if ($username):?>
                                    <form method="post" action="favorite_index.php">
                                        <input type="hidden" name="film_id" value="<?php echo htmlspecialchars($row['film_id']); ?>">
                                        <?php if ($is_favorited[$row['film_id']]): ?>
                                            <button type="submit" name="unfavorite" class="flex  hover:bg-red-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                                <div class="w-full flex-col">
                                                    <div class="font-bold text-sm mx-auto">Hapus Favorit</div>
                                                </div>
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" name="favorite" class="flex bg-red-600 hover:bg-red-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                            <div class="my-auto mx-1">
                                                <img src="img/plussign.png" alt="">
                                            </div>
                                            <div class="w-full flex-col">
                                                <div class="font-bold text-sm mx-auto">Tambahkan ke List Favorit</div>
                                            </div>
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                <?php else:?>
                                    <a href="login.php" class="flex bg-red-600 hover:bg-red-800 p-2 rounded-md w-full mt-2 transition-colors duration-300">
                                        <div class="my-auto mx-1">
                                            <img src="img/plussign.png" alt="">
                                        </div>
                                        <div class="w-full flex-col">
                                            <div class="font-bold text-sm mx-auto">Tambahkan ke List Favorit</div>
                                        </div>
                                    </a>
                                <?php endif;?>
                            </div>

                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    </div>

</body>
<footer class="footer footer-center p-4 bg-base-300 text-base-content">
  <aside>
    <p>Copyright © 2024 - All right reserved by baron fc</p>
  </aside>
</footer>
</html>
