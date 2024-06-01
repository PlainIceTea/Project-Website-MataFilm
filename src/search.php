<?php
require 'function.php';
$conn = connectDatabase();

// Mengecek apakah ada kata kunci pencarian yang dikirim melalui query string
if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    $result = searchFilm($keyword);
} else {
    //header("location: index.php");
    //exit();
}

if (isset($_GET['cari'])){
    $result = searchFilm($keyword);
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
if($username){
    $nama = getUsername($conn, $username)['nama'];
    $user_id = getUsername($conn, $username)['id'];
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
    <title>Hasil Pencarian Film</title>
</head>
<body class=" font-ibm " data-theme="business">
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


    <div class="flex flex-col justify-start mt-20 w-full min-h-screen">

        <div class="flex w-auto mt-10 ">
            <div class="ml-10 mr-1 text-3xl font-semibold">Search "</div>
            <div class="mr-1 text-3xl font-semibold"><?php echo strtoupper($_GET['keyword']); ?></div>
            <div class="mr-1 text-3xl font-semibold">"</div>
        </div>

        <div class=" mx-10 bg-gray-800 rounded-xl mt-5 flex flex-col px-20" style="background-color:#808080">


            <!--LOOP REKOMENDASI-->
            <?php if (isset($result) && mysqli_num_rows($result) > 0): ?>
                <?php foreach ($result as $film): ?>
                    <div class="flex flex-col">
                        <div class="flex border-b border-gray-500">
                            <a href="detail.php?id=<?php echo $film['film_id']; ?>">
                                <img src="img/<?php echo $film['gambar'];?>" alt=" " class="max-w-32  py-3">
                            </a>
                            <div class="flex flex-col p-2 ">
                                <a  href="detail.php?id=<?php echo $film['film_id']; ?>" class="font-bold text-xl"><?php echo $film['judul']; ?></a>
                                <div class="text-sm"><?php echo $film['tahun']?></div>
                                <div class="flex">
                                    <img src="img/star.png" alt="star" class="h-6 w-auto mr-1">
                                    <div><?php echo $film['rating']?></div>
                                    <img src="img/mafilmstar.png" alt="star" class="h-6 w-auto mr-1 ml-3">
                                    <div>
                                        <?php 
                                            if(isset($average_ratings[$film['film_id']])) {
                                                echo $average_ratings[$film['film_id']];
                                            } else {
                                                echo "0";
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class=""><?php echo $film['sinopsis']?></div>
                                <div class="py-5 flex">
                                    <div class="mr-1">Direktur</div>
                                    <div class="mr-1 " style="color: cornflowerblue;"><?php echo $film['direktur']?></div>
                                    <div class="mr-1">Pemeran</div>
                                    <div class="mr-3" style="color: cornflowerblue;"><?php echo $film['pemeran']?></div>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Tidak ada hasil yang ditemukan.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
<footer class="footer footer-center p-4 bg-base-300 text-base-content">
  <aside>
    <p>Copyright © 2024 - All right reserved by baron fc</p>
  </aside>
</footer>
</html>
