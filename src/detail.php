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

//mengambil film id
$film_id = $_GET['id'];
if(!$film_id){
    header("Location: index.php");
    exit();
}

//mengambil session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
if($username){
    $nama = getUsername($conn, $username)['nama'];
    $user_id = getUsername($conn, $username)['id'];
    $watchlist = getWatchlistFilms($user_id);
    //cek favorit
    $is_favorited = checkIfFavorited($user_id, $film_id);
    //cek watchlist
    $is_in_watchlist = checkIfInWatchlist($user_id, $film_id);
}


//mengambil film sesuai film id
$result = mysqli_query($conn, "SELECT * FROM film WHERE film_id = $film_id");
if(mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}
$row = mysqli_fetch_assoc($result);

//mengambil komentar yang ada
$komentar = mysqli_query($conn, "SELECT * FROM komentar WHERE film_id = $film_id");
$jumlah_komentar = mysqli_num_rows($komentar);

//mengambil genre
$genres = mysqli_query($conn, "SELECT genre_id FROM genre WHERE film_id = $film_id");

//rata-rata user rating
$avg_rating_result = mysqli_query($conn, "SELECT AVG(user_rating) as avg_rating FROM komentar WHERE film_id = $film_id");
$avg_rating_row = mysqli_fetch_assoc($avg_rating_result);
$avg_rating = round($avg_rating_row['avg_rating'], 2); 




//memecah nama pemeran
$pemeran = explode(',', $row['pemeran']);
$penulis = explode(',', $row['penulis']);
$direktur = explode(',', $row['direktur']);

// Menghitung jumlah user yang memasukkan film ke dalam watch list
$query = "SELECT COUNT(DISTINCT user_id) AS jumlah_watchlist FROM watchlist WHERE film_id = $film_id";
$resultWatchList = mysqli_query($conn, $query);
$rowWatchList = mysqli_fetch_assoc($resultWatchList);
$jumlah_watchlist = $rowWatchList['jumlah_watchlist'];

// Fungsi untuk mengonversi angka ke format singkat
function formatShortNumber($num) {
    if ($num >= 1000) {
        $num = floor($num / 100) / 10;
        return $num . 'k';
    }
    return $num;
}
$jumlah_watchlist_formatted = formatShortNumber($jumlah_watchlist);

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

    <div class="flex flex-col justify-center mt-20">
        <div class="flex max-h-40 mt-20 mb-10 mx-10">

            <!--judul dan tahun-->
            <div class="w-8/12 flex flex-col">
                <div class="text-4xl"><?php echo $row["judul"]; ?></div>
                <div class="text-base"><?php echo $row["tahun"]?></div>
            </div>

            <div class="w-4/12 flex">

                <!--rating imdb-->
                <div class="w-6/12 flex flex-col items-center  ">
                    <div class="text-sm">RATING IMDb</div>
                    <div class="flex p-2">
                        <img src="img/star.png" alt="star" class="w-5 h-5 mx-2">
                        <div class="font-bold text-lg"><?php echo $row["rating"]?></div>
                        <div class="text-sm pt-2">/10</div>
                    </div>
                </div>

                <!--rating user-->
                <div class="w-6/12 flex flex-col items-center  ">
                    <div class="text-sm">RATING MataFilm</div>
                    <div class="flex p-2">
                        <img src="img/mafilmstar.png" alt="star" class="w-auto h-5 mx-2">
                        <div class="font-bold text-lg"><?php echo $avg_rating?></div>
                        <div class="text-sm pt-2">/10</div>
                    </div>
                </div>
            </div>

        </div>


        <div class="flex w-screen justify-between mt-10 mb-40 ">

            <!--gambar-->
            <div class="w-4/12 flex items-center ">
                <img src ="img/<?php echo $row["gambar"];?>" alt="<?php echo $row["judul"]; ?>" class="w-80 mx-auto">
            </div>
            <div class="w-8/12 flex flex-col mr-32">

                <!--genre-->
                <div class="flex">
                    <?php
                    while ($genre = mysqli_fetch_assoc($genres)) {
                        $genre_id = $genre['genre_id'];
                        $genre_name_result = mysqli_query($conn, "SELECT * FROM genres WHERE genre_id = $genre_id");
                        $genre_name = mysqli_fetch_assoc($genre_name_result);
                        echo    "<a href = 'genre.php?genre_id={$genre_id}'>
                                    <div class='rounded-full border border-gray-400 px-4 py-2 text-center inline-block mx-2'>
                                        {$genre_name['genre']}
                                    </div>
                                </a>";
                    }
                    ?>
                </div>

                <!--sinopsis-->
                <div class="ml-2  text-justify   pb-2 mt-5"><?php echo $row["sinopsis"]?></div>


                <div class="flex flex-col mr-40">

                    <!--direktur-->
                    <div class="ml-2  mt-3 border-b-neutral-500 border-y py-2 flex">
                        <div class="w-2/12">Director</div>
                        <div class="flex">
                            <?php
                            // Menggunakan count() untuk mendapatkan total elemen dalam array
                            $totalDirektur = count($direktur);
                            // Loop melalui array penulis
                            foreach ($direktur as $index => $p):
                                // Periksa apakah ini adalah elemen terakhir dalam array
                                $isLast = ($index === $totalDirektur - 1);
                            ?>
                                <div class="w-10/12 mr-5" style="color: cornflowerblue;"><?php echo htmlspecialchars(trim($p)); ?></div>
                                <?php if (!$isLast): ?>
                                    <div class="mr-5" style="color: white;">,</div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!--penulis-->
                    <div class="ml-2 mt-3 border-b-neutral-500 border-b pb-2 flex">
                        <div class="w-2/12">Penulis</div>
                        <div class="flex">
                            <?php
                            // Menggunakan count() untuk mendapatkan total elemen dalam array
                            $totalPenulis = count($penulis);
                            // Loop melalui array penulis
                            foreach ($penulis as $index => $p):
                                // Periksa apakah ini adalah elemen terakhir dalam array
                                $isLast = ($index === $totalPenulis - 1);
                            ?>
                                <div class="w-10/12 mr-5" style="color: cornflowerblue;"><?php echo htmlspecialchars(trim($p)); ?></div>
                                <?php if (!$isLast): ?>
                                    <div class="mr-5" style="color: white;">,</div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!--pemeran-->
                    <div class="ml-2 mt-3 border-b-neutral-500 border-b pb-2 flex">
                        <div class="w-2/12">Pemeran</div>
                        <div class="flex">
                            <?php
                            // Menggunakan count() untuk mendapatkan total elemen dalam array
                            $totalPemeran = count($pemeran);
                            // Loop melalui array pemeran
                            foreach ($pemeran as $index => $p):
                                // Periksa apakah ini adalah elemen terakhir dalam array
                                $isLast = ($index === $totalPemeran - 1);
                            ?>
                                <div class="w-10/12 mr-5" style="color: cornflowerblue;"><?php echo htmlspecialchars(trim($p)); ?></div>
                                <?php if (!$isLast): ?>
                                    <div class="mr-5" style="color: white;">,</div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>

                <!--watchlist-->
                <?php if ($username):?>
                    <form method="post" action="watchlist.php">
                    <input type="hidden" name="film_id" value="<?php echo htmlspecialchars($film_id); ?>">
                    <?php if ($is_in_watchlist): ?>
                        <button type="submit" name="remove_watchlist" class="flex hover:bg-blue-800 p-2 rounded-xl w-4/12 mt-10 transition-colors duration-300">
                            <div class="w-full flex-col">
                                <div class="font-bold text-sm mx-auto">Hapus dari Watchlist</div>
                            </div>
                        </button>
                    <?php else: ?>
                        <button type="submit" name="add_watchlist" class="flex bg-blue-500 hover:bg-blue-700 p-2 rounded-md w-5/12 mt-10 transition-colors duration-300">
                            <div class="my-auto mx-2 w-10">
                                <img src="img/plussign.png" alt="">
                            </div>
                            <div class="w-auto flex-col">
                                <div class="font-bold">Tambahkan ke List Tontonan Anda</div>
                                <div class="flex">
                                    <div class="mr-1">Telah ditambahkan</div>
                                    <div class="mr-1"><?php echo $jumlah_watchlist_formatted?></div>
                                    <div class="mr-1">orang</div>
                                </div>
                            </div>
                        </button>
                    <?php endif; ?>
                </form>
                <?php else:?>
                    <a href="login.php" class="flex bg-blue-500 hover:bg-blue-700 p-2 rounded-md w-5/12 mt-10 transition-colors duration-300">
                            <div class="my-auto mx-2 w-10">
                                <img src="img/plussign.png" alt="">
                            </div>
                            <div class="w-auto flex-col">
                                <div class="font-bold">Tambahkan ke List Tontonan Anda</div>
                                <div class="flex">
                                    <div class="mr-1">Telah ditambahkan</div>
                                    <div class="mr-1"><?php echo $jumlah_watchlist_formatted?></div>
                                    <div class="mr-1">orang</div>
                                </div>
                            </div>
                        </a>
                <?php endif; ?>

                
                <!--favorit-->
                <?php if ($username):?>
                <form method="post" action="favorite.php">
                    <input type="hidden" name="film_id" value="<?php echo htmlspecialchars($film_id); ?>">
                    <?php if ($is_favorited): ?>
                        <button type="submit" name="unfavorite" class="flex hover:bg-red-800 p-2 rounded-xl w-4/12 mt-3 transition-colors duration-300">
                            <div class="w-full flex-col">
                                <div class="font-bold text-sm mx-auto">Hapus Favorit</div>
                            </div>
                        </button>
                    <?php else: ?>
                        <button type="submit" name="favorite" class="flex bg-red-600 hover:bg-red-800 p-2 rounded-xl w-4/12 mt-3 transition-colors duration-300">
                            <div class="my-auto mx-2 w-10">
                                <img src="img/plussign.png" alt="Plus Sign">
                            </div>
                            <div class="w-auto flex-col">
                                <div class="font-bold">Tambahkan ke List Favorit</div>
                            </div>
                        </button>
                    <?php endif; ?>
                </form>
                <?php else:?>
                    <a href="login.php" class="flex bg-red-600 hover:bg-red-800 p-2 rounded-xl w-4/12 mt-3 transition-colors duration-300">
                        <div class="my-auto mx-2 w-10">
                            <img src="img/plussign.png" alt="Plus Sign">
                        </div>
                        <div class="w-auto flex-col">
                            <div class="font-bold">Tambahkan ke List Favorit</div>
                        </div>
                    </a>
                <?php endif;?>
            </div>
        </div>


        <!--komentar-->
        <div class="bg-gray-800 rounded-xl -mt-5 flex flex-col px-20" style="background-color:#808080">
            <div class="text-3xl text-white font-bold my-10 mt-20 m-"><?php echo $jumlah_komentar?> Komentar</div>


            <div class="flex flex-col">

                <?php
                // Mengambil semua komentar dan menampilkannya
                while ($komen = mysqli_fetch_assoc($komentar)) {
                    $user_id = mysqli_real_escape_string($conn, $komen['user_id']); // Melakukan escap string pada user_id untuk mencegah SQL Injection
                    $user_query = mysqli_query($conn, "SELECT * FROM user WHERE id = '$user_id'");
                    $user_data = mysqli_fetch_assoc($user_query);
                    
                    // Menampilkan nama pengguna, komentar, tanggal komentar, dan rating pengguna
                    echo '<div class="flex">';
                    echo '<div class="mx-10 pb-10 flex flex-col px-5 text-white">';
                    echo '<div class="flex items-end">';
                    echo '<div class="font-bold text-lg text-white mr-3">' . $user_data['nama'] . '</div>';
                    echo '<div class="text-sm mr-3">' . $komen['tanggal_komentar'] . '</div>';
                    echo '<div class="flex">';
                    echo '<img src="img/star.png" alt="star" class="h-6">';
                    echo '<div class="font-bold mx-2">' . $komen['user_rating'] . '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="">' . $komen['komentar'] . '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>

                <!--Memberi rating dan komentar-->
                <div class="mx-20">
                    <div class=" font-bold text-white text-xl">Rating dan Ulasan untuk Perfilman Indonesia</div>
                    <form onsubmit="submitRating(event)" class=" p-2 px-5 rounded" action="tambah_komentar.php" method="post">
                        <input type="hidden" name="film_id" value="<?php echo $film_id; ?>">
                        <div class="rating" required>
                            <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400 mr-2" value="1" />
                            <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400 mr-2" value="2" />
                            <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400 mr-2" value="3" />
                            <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400 mr-2" value="4" />
                            <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400 mr-2" value="5" />
                            <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400 mr-2" value="6" />
                            <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400 mr-2" value="7" />
                            <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400 mr-2" value="8" />
                            <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400 mr-2" value="9" />
                            <input type="radio" name="rating" class="mask mask-star-2 bg-orange-400 mr-2" value="10"
                                checked />
                        </div>
                        <div class="bg-gray-400 p-4 rounded mb-4 flex">
                            <textarea id="comment" placeholder="Tulis Komentar" name="isi_komentar" rows="4" class="bg-gray-300 w-full p-2  rounded"></textarea>
                            <button type="submit" class="hover:bg-gray-500 mt-4 px-4 py-2 text-white rounded" name="submit">
                                <img src="img/mail.png" alt="">
                            </button>
                        </div>
                    </form>
                </div>
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