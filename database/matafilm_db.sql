-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Bulan Mei 2024 pada 13.10
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `matafilm_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `favorit`
--

CREATE TABLE `favorit` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `favorit`
--

INSERT INTO `favorit` (`id`, `user_id`, `film_id`) VALUES
(40, 6, 3),
(42, 6, 11),
(45, 6, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `film`
--

CREATE TABLE `film` (
  `film_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `rating` decimal(3,1) NOT NULL,
  `sinopsis` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `direktur` varchar(255) NOT NULL,
  `pemeran` varchar(255) NOT NULL,
  `tahun` int(11) NOT NULL,
  `favorit` int(11) NOT NULL DEFAULT 0,
  `penonton` decimal(8,4) NOT NULL,
  `penulis` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `film`
--

INSERT INTO `film` (`film_id`, `judul`, `rating`, `sinopsis`, `gambar`, `direktur`, `pemeran`, `tahun`, `favorit`, `penonton`, `penulis`) VALUES
(2, 'KKN di Desa Penari', 5.9, 'Enam mahasiswa yang harus melaksanakan KKN di desa terpencil diimbau untuk tidak melewati batas gerbang terlarang menuju tempat misterius yang mungkin berkaitan dengan sosok penari cantik yang mulai mengganggu mereka.', 'KKN.jpg', 'Awi Suryadi', 'Tissa Biani Azzahra, Adinda Thomas, Achmad Megantara', 2022, 1, 10.0610, 'Gerald Mamahit, Lele Laila, SimpleMan'),
(3, 'Agak Laen', 8.1, 'Seorang lelaki tua meninggal dalam perjalanan rumah hantu yang gagal. Operator menguburkan jenazahnya di lokasi, mengubahnya menjadi atraksi populer.', 'AgakLaen.jpg', 'Muhadkly Acho', 'Bene Dion Rajagukguk, Oki Rengga, Indra Jegel', 2024, 1, 9.1251, 'Muhadkly Acho'),
(4, 'Warkop DKI Reborn: Jangkrik Boss! Part 1', 6.4, 'Dono, Kasino, dan Indro kembali beraksi. Kini mereka bergabung dengan lembaga swasta bernama CHIPS. Meski semangatnya mengabdi pada masyarakat, mereka juga terus menimbulkan masalah karena ulahnya yang konyol dan lucu.', 'WDKI_reborn.jpg', 'Anggy Umbara', 'Abimana Aryasatya, Vino G. Bastian, Tora Sudiro', 2016, 1, 6.8586, 'Arie Kriting, Bene Dion Rajagukguk, Anggy Umbara'),
(5, 'Pengabdi Setan 2: Communion', 6.7, 'Setelah pindah dari rumah mereka ke sebuah gedung apartemen, teror baru menanti. Saat badai hebat melanda, bukan badai yang harus ditakuti oleh keluarga tersebut, tetapi orang-orang dan entitas non-manusia yang mengincar mereka.', 'ps2.jpeg', 'Joko Anwar', 'Tara Basro, Endy Arfian, Nasar Annuz', 2022, 0, 6.3919, 'Joko Anwar'),
(6, 'Dilan 1990', 7.0, 'Milea bertemu dengan Dilan di sebuah sekolah menengah atas di Bandung pada tahun 1990. Perkenalan yang tidak biasa membawa Milea untuk mengetahui keunikan Dilan yang cerdas, baik, dan romantis di matanya.', 'd1990.jpg', 'Pidi Baiq, Fajar Bustomi', 'Iqbaal Dhiafakhri Ramadhan, Vanesha Prescilla, Sissy Priscillia', 2018, 0, 6.3156, 'Pidi Baiq, Titien Wattimena, Dani Rahman Fauzi'),
(7, 'Miracle in Cell No. 7', 7.9, 'Seorang pria dengan gangguan mental menghadapi konsekuensi dari politisi korup di Indonesia karena dia dituduh secara salah melakukan pembunuhan, dan yang dia inginkan hanyalah melihat putrinya lagi.', 'micno7.jpg', 'Hanung Bramantyo', 'Vino G. Bastian, Graciella Abigail, Mawar Eva de Jongh', 2022, 0, 5.8609, 'Alim Sudio, Hwan-kyung Lee'),
(8, 'Vina: Sebelum 7 Hari', 5.3, 'Vina, korban kekejaman geng motor di Cirebon, menolak menerima kematiannya yang dilabeli sebagai kecelakaan. Rohnya ikut campur dalam tujuh hari sebelum insiden untuk mengungkap kebenaran di balik apa yang sebenarnya terjadi.', 'vina.jpg', 'Anggy Umbara', 'Delia Husein, Yusuf Mahardika, Lydia Kandou', 2024, 0, 5.5029, 'Dirmawan Hatta, Bounty Umbara'),
(9, 'Dilan 1991', 6.5, 'Dilan dan Milea resmi berpacaran. Tapi Dilan terancam dikeluarkan dari sekolah karena terlibat dalam perkelahian geng. Suatu hari, saat dia berencana untuk berkelahi lagi, Milea memintanya untuk keluar dari geng motor atau hubungan mereka berakhir.', 'd1991.jpg', 'Pidi Baiq, Fajar Bustomi', 'Iqbaal Dhiafakhri Ramadhan, Vanesha Prescilla, Ira Wibowo', 2019, 0, 5.2534, 'Pidi Baiq, Titien Wattimena'),
(10, 'Sewu Dino', 6.0, 'Sri ditugaskan untuk melakukan ritual pembersihan untuk Dela Atmojo, seorang gadis yang tidak sadarkan diri yang menderita kutukan 1000 hari. Teror dimulai ketika temannya mengabaikan untuk menyelesaikan ritual tersebut. Gagal sampai hari ke-1000 akan berakibat fatal bagi mereka.', 'SewuDino.jpg', 'Kimo Stamboel', 'Mikha Tambayong, Rio Dewanto, Givina Lukita', 2023, 0, 4.8916, 'Agasyah Karim, Khalid Kashogi, SimpleMan'),
(11, 'Laskar Pelangi', 7.9, 'Pada tahun 1970-an, sekelompok 10 siswa berjuang dengan kemiskinan dan mengembangkan harapan untuk masa depan di Desa Gantong di pulau pertanian dan penambangan timah Belitung di lepas pantai timur Sumatra.', 'lp.jpg', 'Riri Riza', 'Cut Mini Theo, Zulfanny, Ikranagara', 2008, 1, 4.7194, 'Salman Aristo, Andrea Hirata, Mira Lesmana'),
(12, 'Habibie & Ainun', 7.6, 'Film ini didasarkan pada memoar yang ditulis oleh Presiden ketiga Indonesia dan salah satu insinyur terkenal di dunia, B.J. Habibie tentang istrinya, Hasri Ainun Habibie.', 'habibie.jpg', 'Faozan Rizal', 'Reza Rahadian, Bunga Citra Lestari, Tio Pakusadewo', 2012, 0, 4.6012, 'B.J. Habibie, Ifan Ismail, Gina S. Noer'),
(13, 'Pengabdi Setan', 6.5, 'Setelah meninggal karena penyakit aneh yang dideritanya selama 3 tahun, seorang ibu kembali ke rumah untuk menjemput anak-anaknya.', 'ps.jpg', 'Joko Anwar', 'Tara Basro, Bront Palarae, Dimas Aditya', 2017, 0, 4.2061, 'Joko Anwar, Sisworo Gautama Putra, Naryono Prayitno'),
(14, 'Warkop DKI Reborn: Jangkrik Boss! Part 2', 5.5, 'Petualangan Dono, Kasino, dan Indro berlanjut. Mereka harus mencari harta karun itu untuk membayar utangnya. Mereka melakukan perjalanan ke Malaysia sebagai tujuan pertama mereka, namun tas dengan kode harta karun tersebut ditukar dengan milik wanita Malaysia.', 'warkopreborn2.jpg', 'Anggy Umbara\r\n\r\n', 'Abimana Aryasatya, Vino G. Bastian, Tora Sudiro', 2017, 0, 4.0831, 'Arie Kriting, Bene Dion Rajagukguk, Anggy Umbara'),
(15, 'Badarawuhi Di Desa Penari', 6.3, 'Desa ini masih menyimpan banyak misteri. Sepotong demi sepotong misteri terungkap, termasuk teror dari entitas yang paling ditakuti yakni Badarawuhi.', 'badarawuhi.jpg', 'Kimo Stamboel', 'Aulia Sarah, Maudy Effrosina, Jourdy Pranata', 2024, 0, 4.0101, 'Lele Laila, SimpleMan'),
(16, 'Siksa Kubur', 7.1, 'Menceritakan tentang siksa kubur yang terjadi setelah seseorang dikuburkan.', 'siksakubur.jpg', 'Joko Anwar', 'Faradina Mufti, Reza Rahadian, Widuri Puteri', 2024, 0, 4.0008, 'Joko Anwar'),
(17, 'Ayat-ayat Cinta', 6.9, 'Seorang pria yang mencoba melewati hubungan rumit dengan cara Islami.', 'ayatcinta.jpg', 'Hanung Bramantyo', 'Fedi Nuril, Rianti Cartwright, Carissa Putri', 2008, 0, 3.6761, 'Salman Aristo, Habiburrahman El Shirazy, Gina S. Noer'),
(18, 'Ada Apa dengan Cinta? 2', 7.3, '14 tahun setelah percintaan mereka dimulai di sekolah menengah, Rangga dan Cinta bersatu kembali di Yogyakarta untuk mengakhiri hubungan mereka setelah Rangga meninggalkan Cinta tanpa penjelasan bertahun-tahun sebelumnya.', 'aadc2.jpg', 'Riri Riza', 'Nicholas Saputra, Dian Sastrowardoyo, Titi Kamal', 2016, 0, 3.6655, 'Mira Lesmana, Prima Rusdi'),
(19, 'Suzzanna: Bernapas dalam Kubur', 5.5, 'Setelah seorang wanita hamil dibunuh, arwahnya berusaha membalas dendam terhadap para pembunuh yang semakin ketakutan, yang bertekad menghabisinya selamanya.', 'suzanna.jpg', 'Rocky Soraya', 'Luna Maya, Herjunot Ali, T. Rifnu Wikana', 2018, 0, 3.3461, 'Ferry Lesmana, Bene Dion Rajagukguk, Sunil Soraya'),
(20, 'Di Ambang Kematian', 6.1, 'Nadia, satu-satunya yang selamat dari nasib tragis keluarganya, bergulat dengan ancaman pengorbanan ayahnya saat dia menghadapi kehidupan yang berada di ujung tanduk.', 'ambangkematian.jpg', 'Azhar Kinoi Lubis', 'Taskya Namya, T. Rifnu Wikana, Wafda Saifan Lubis', 2023, 0, 3.3020, 'Erwanto Alphadullah, JeroPoint'),
(21, 'Milea: Suara dari Dilan', 6.2, 'Keputusan berpisah dengan Dilan diambil Milea sebagai peringatan agar Dilan menjauhi geng motor. Namun perpisahan yang tadinya hanya gertakan bagi Milea menjadi perpisahan yang bertahan hingga mereka lulus kuliah dan beranjak dewasa.', 'milea.jpg', 'Fajar Bustomi, Pidi Baiq', 'Iqbaal Dhiafakhri Ramadhan, Vanesha Prescilla, Ira Wibowo', 2020, 0, 3.1578, 'Pidi Baiq, Titien Wattimena');

-- --------------------------------------------------------

--
-- Struktur dari tabel `genre`
--

CREATE TABLE `genre` (
  `id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `genre`
--

INSERT INTO `genre` (`id`, `film_id`, `genre_id`) VALUES
(1, 3, 2),
(2, 2, 1),
(3, 2, 3),
(4, 4, 4),
(5, 4, 2),
(6, 5, 5),
(7, 5, 1),
(8, 5, 6),
(9, 6, 7),
(12, 6, 5),
(13, 7, 2),
(14, 7, 5),
(15, 7, 8),
(16, 8, 5),
(17, 8, 1),
(18, 9, 5),
(19, 9, 7),
(20, 10, 1),
(21, 10, 3),
(22, 11, 4),
(23, 11, 5),
(24, 11, 8),
(25, 12, 9),
(26, 12, 5),
(27, 12, 10),
(28, 13, 5),
(29, 13, 1),
(30, 13, 6),
(31, 14, 4),
(32, 14, 2),
(33, 15, 1),
(34, 15, 6),
(35, 15, 3),
(36, 16, 5),
(37, 16, 1),
(38, 16, 3),
(39, 17, 5),
(40, 17, 7),
(41, 19, 5),
(42, 19, 1),
(43, 19, 11),
(44, 18, 5),
(45, 18, 7),
(46, 20, 5),
(47, 20, 10),
(48, 20, 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `genres`
--

CREATE TABLE `genres` (
  `genre_id` int(11) NOT NULL,
  `genre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `genres`
--

INSERT INTO `genres` (`genre_id`, `genre`) VALUES
(1, 'Horror'),
(2, 'Comedy'),
(3, 'Thriller'),
(4, 'Adventure'),
(5, 'Drama'),
(6, 'Mystery'),
(7, 'Romance'),
(8, 'Family'),
(9, 'Biography'),
(10, 'History'),
(11, 'Fantasy');

-- --------------------------------------------------------

--
-- Struktur dari tabel `komentar`
--

CREATE TABLE `komentar` (
  `komentar_id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL,
  `komentar` text NOT NULL,
  `tanggal_komentar` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_rating` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `komentar`
--

INSERT INTO `komentar` (`komentar_id`, `film_id`, `komentar`, `tanggal_komentar`, `user_rating`, `user_id`) VALUES
(3, 2, 'keren', '2024-05-27 08:59:26', 8, 3),
(4, 2, 'bagus', '2024-05-27 09:01:06', 5, 4),
(7, 4, 'sadasd', '2024-05-27 14:25:22', 10, 6),
(8, 4, 'Bagus bro', '2024-05-27 14:28:31', 7, 3),
(9, 2, 'sadasdasd', '2024-05-28 04:31:13', 10, 6),
(10, 3, 'keren', '2024-05-29 05:21:26', 8, 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `nama`) VALUES
(3, 'admin', '$2y$10$jzOUsqyjEJ2RcnYd6nM/r.Znovj.VjzZVuZ9ssDcwsTmT9GrBUCOS', 'Admin'),
(4, 'admin1', '$2y$10$So0jDABohixT9v.UVTDO9.lKZ/rkSweTyL0xzGNkfl/S8QY7DCZLa', 'Admin1'),
(6, 'vino', '$2y$10$EgBFRsrpS7SsGVeFojesWuPLUu9VOoN0fJIqhd6AKdX9vJh1TNsOS', 'Alvino'),
(8, 'baron', '$2y$10$57oyJBtCydeLVfuJjqYTuusoUCtqu2RXQhkhlPk94e2yklMkBc6Hu', 'akbar');

-- --------------------------------------------------------

--
-- Struktur dari tabel `watchlist`
--

CREATE TABLE `watchlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `watchlist`
--

INSERT INTO `watchlist` (`id`, `user_id`, `film_id`) VALUES
(44, 6, 4),
(49, 6, 5),
(53, 6, 2);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `favorit`
--
ALTER TABLE `favorit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `film_id` (`film_id`);

--
-- Indeks untuk tabel `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`film_id`);

--
-- Indeks untuk tabel `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id`),
  ADD KEY `film_id` (`film_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indeks untuk tabel `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genre_id`);

--
-- Indeks untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD PRIMARY KEY (`komentar_id`),
  ADD KEY `film_id` (`film_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `watchlist`
--
ALTER TABLE `watchlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `film_id` (`film_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `favorit`
--
ALTER TABLE `favorit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT untuk tabel `film`
--
ALTER TABLE `film`
  MODIFY `film_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `genre`
--
ALTER TABLE `genre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `genres`
--
ALTER TABLE `genres`
  MODIFY `genre_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `komentar`
--
ALTER TABLE `komentar`
  MODIFY `komentar_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `watchlist`
--
ALTER TABLE `watchlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `favorit`
--
ALTER TABLE `favorit`
  ADD CONSTRAINT `favorit_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `favorit_ibfk_2` FOREIGN KEY (`film_id`) REFERENCES `film` (`film_id`);

--
-- Ketidakleluasaan untuk tabel `genre`
--
ALTER TABLE `genre`
  ADD CONSTRAINT `genre_ibfk_1` FOREIGN KEY (`film_id`) REFERENCES `film` (`film_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `genre_ibfk_2` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `komentar`
--
ALTER TABLE `komentar`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `komentar_ibfk_1` FOREIGN KEY (`film_id`) REFERENCES `film` (`film_id`);

--
-- Ketidakleluasaan untuk tabel `watchlist`
--
ALTER TABLE `watchlist`
  ADD CONSTRAINT `watchlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `watchlist_ibfk_2` FOREIGN KEY (`film_id`) REFERENCES `film` (`film_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
