-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 29 Tem 2020, 23:42:24
-- Sunucu sürümü: 10.4.13-MariaDB
-- PHP Sürümü: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `fileshare`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `uniqueid` text COLLATE utf8_turkish_ci NOT NULL,
  `name` text COLLATE utf8_turkish_ci NOT NULL,
  `description` text COLLATE utf8_turkish_ci NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` text COLLATE utf8_turkish_ci NOT NULL,
  `subcategory` text COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `categories`
--

INSERT INTO `categories` (`id`, `uniqueid`, `name`, `description`, `created_date`, `created_by`, `subcategory`) VALUES
(13, '5ef2babcd59b0', 'Ödevler', 'Ödevlerin olduğu bir kategori.', '2020-06-24 05:30:20', 'admin', '0'),
(14, '5ef33ffb3fb7d', 'Wallpapers', 'Masaüstü arkaplan resimleri.', '2020-06-24 14:58:51', 'admin', '0'),
(15, '5ef36a66dc22e', 'Matematik', 'matematik ile ilgili sorular', '2020-06-24 17:58:58', 'admin', '5ef2babcd59b0'),
(18, '5ef3c1d9a4fb6', 'Fizik', 'fizik ile ilgili sorular', '2020-06-25 00:12:57', 'admin', '5ef2babcd59b0'),
(19, '5ef3c2df01e13', 'Sayısal Bilgisayar', 'Sayısal Bilgisayar ile ilgili sorular.', '2020-06-25 00:17:19', 'admin', '5ef2babcd59b0'),
(21, '5ef3c3ebf379e', 'Manzaralar', 'Güzel manzara resimleri', '2020-06-25 00:21:47', 'admin', '5ef33ffb3fb7d'),
(46, '5f21e8ff511be', 'Arabalar', 'Güzel araba resimleri', '2020-07-30 00:24:15', 'admin', '5ef33ffb3fb7d'),
(47, '5f21e92fdcc2a', 'sayfalama testi', '8 den fazla dosya olacak', '2020-07-30 00:25:03', 'admin', '5ef2babcd59b0');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `uniqueid` text COLLATE utf8_turkish_ci NOT NULL,
  `file_name` text COLLATE utf8_turkish_ci NOT NULL,
  `file_description` text COLLATE utf8_turkish_ci NOT NULL,
  `file_size` text COLLATE utf8_turkish_ci NOT NULL,
  `upload_date` datetime NOT NULL,
  `upload_by` text COLLATE utf8_turkish_ci NOT NULL,
  `uploader_ip` text COLLATE utf8_turkish_ci NOT NULL,
  `download_count` int(15) NOT NULL,
  `download_lastdate` datetime NOT NULL,
  `publicid` text COLLATE utf8_turkish_ci NOT NULL,
  `category_id` text COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `files`
--

INSERT INTO `files` (`id`, `uniqueid`, `file_name`, `file_description`, `file_size`, `upload_date`, `upload_by`, `uploader_ip`, `download_count`, `download_lastdate`, `publicid`, `category_id`) VALUES
(41, '5f21e863d9dea', 'bir dosya.txt', 'bu bir text dosyası', '14', '2020-07-30 00:21:39', 'admin', '::1', 0, '0000-00-00 00:00:00', 'b83b713a434aa575d96861c4990bdc46', '5ef36a66dc22e'),
(42, '5f21e88331440', 'bir excel.xlsx', 'bu aslında bir text dosyası ama uzantısını değiştirdim', '14', '2020-07-30 00:22:11', 'admin', '::1', 0, '0000-00-00 00:00:00', '64ca6a8d0a327f96fe98a23e409fcbe7', '5ef36a66dc22e'),
(43, '5f21e89647487', 'bir zip.zip', 'test', '14', '2020-07-30 00:22:30', 'admin', '::1', 0, '0000-00-00 00:00:00', 'c10d2a945dcbde4e7b5fa0ff02d1fd8a', '5ef36a66dc22e'),
(44, '5f21e8a419e81', 'bir excel.xlsx', 'test', '14', '2020-07-30 00:22:44', 'admin', '::1', 0, '0000-00-00 00:00:00', '07be66cbd1eb9ef9bcb83b87c2309226', '5ef3c1d9a4fb6'),
(45, '5f21e8aa84710', 'bir word.docx', 'testt', '14', '2020-07-30 00:22:50', 'admin', '::1', 0, '0000-00-00 00:00:00', '43e35131dac3b31063255f9bbd767aa1', '5ef3c1d9a4fb6'),
(46, '5f21e8b7bfb40', 'bir resim.jpg', 'resim test', '14', '2020-07-30 00:23:03', 'admin', '::1', 0, '0000-00-00 00:00:00', '30d34f9309e80826cd2c398214784df4', '5ef3c2df01e13'),
(47, '5f21e8ca258ef', 'bir resim - Copy.png', 'teesstt', '14', '2020-07-30 00:23:22', 'admin', '::1', 0, '0000-00-00 00:00:00', '8a4db85131cfe7e39b3a279b93139423', '5ef3c2df01e13'),
(48, '5f21e8debe12a', 'bir resim.jpg', 'testt', '14', '2020-07-30 00:23:42', 'admin', '::1', 0, '0000-00-00 00:00:00', 'bc9b0e4ccaf5dd9cfef56e2325943a66', '5ef3c3ebf379e'),
(49, '5f21e8e4f3c10', 'bir zip.zip', 'testt', '14', '2020-07-30 00:23:48', 'admin', '::1', 0, '0000-00-00 00:00:00', 'fe3116a8edc89543ecfca73fee6d1d33', '5ef3c3ebf379e'),
(50, '5f21e907812d5', 'bir resim - Copy.png', 'tt', '14', '2020-07-30 00:24:23', 'admin', '::1', 0, '0000-00-00 00:00:00', '9247f9b290bcdd5fe46d25fb459a4e06', '5f21e8ff511be'),
(51, '5f21e90eb09e0', 'bir resim.jpg', 'test', '14', '2020-07-30 00:24:30', 'admin', '::1', 0, '0000-00-00 00:00:00', 'bfc49b0dc5572d17c53be00b22854d07', '5f21e8ff511be'),
(52, '5f21e93786182', 'bir resim.jpg', '1', '14', '2020-07-30 00:25:11', 'admin', '::1', 0, '0000-00-00 00:00:00', '39970b304265c9450aa3590f44b57403', '5f21e92fdcc2a'),
(53, '5f21e93cc418a', 'bir resim.jpg', '2', '14', '2020-07-30 00:25:16', 'admin', '::1', 0, '0000-00-00 00:00:00', '9ef17fbfc552fd4621c00d0c797363a3', '5f21e92fdcc2a'),
(54, '5f21e94251b27', 'bir pdf.pdf', '3', '14', '2020-07-30 00:25:22', 'admin', '::1', 0, '0000-00-00 00:00:00', 'cb222384380de37a419dbf4cea8e393e', '5f21e92fdcc2a'),
(55, '5f21e94a40b27', 'bir zip.zip', '4', '14', '2020-07-30 00:25:30', 'admin', '::1', 0, '0000-00-00 00:00:00', '61c5e2b88d35377f8cf2ee33cd1949be', '5f21e92fdcc2a'),
(56, '5f21e9506ed5c', 'bir excel.xlsx', '5', '14', '2020-07-30 00:25:36', 'admin', '::1', 0, '0000-00-00 00:00:00', '02d85241bed50f5e25d4b7f72c695de9', '5f21e92fdcc2a'),
(57, '5f21e95742f3d', 'bir excel.xlsx', '6', '14', '2020-07-30 00:25:43', 'admin', '::1', 0, '0000-00-00 00:00:00', 'eda797db74f06905415bcf7f6e61df88', '5f21e92fdcc2a'),
(58, '5f21e9619bc80', 'bir dosya.txt', '7', '14', '2020-07-30 00:25:53', 'admin', '::1', 0, '0000-00-00 00:00:00', 'fac7c6f6705765429a629e6c1c5b8705', '5f21e92fdcc2a'),
(59, '5f21e96976f5d', 'bir zip.zip', '8', '14', '2020-07-30 00:26:01', 'admin', '::1', 19, '2020-07-30 00:34:50', 'd4a31445659c9e992c93abf3bb4dca0d', '5f21e92fdcc2a'),
(60, '5f21e971aa588', 'bir zip.zip', '9', '14', '2020-07-30 00:26:09', 'admin', '::1', 2, '2020-07-30 00:29:45', '80e6a651885deaa35f5a2720a8b0c8d3', '5f21e92fdcc2a'),
(61, '5f21e9dd200c1', 'bir zip.zip', '10', '14', '2020-07-30 00:27:57', 'admin', '::1', 2, '2020-07-30 00:29:35', '0196f2e0226a918edea0e8c431fdbdc2', '5f21e92fdcc2a'),
(62, '5f21e9e560b03', 'bir resim - Copy.png', '11', '14', '2020-07-30 00:28:05', 'admin', '::1', 1, '2020-07-30 00:29:26', 'e9299f1e9cfffa7c65f2f1419562a27b', '5f21e92fdcc2a');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `files_reported`
--

CREATE TABLE `files_reported` (
  `id` int(11) NOT NULL,
  `uniqueid` text COLLATE utf8_turkish_ci NOT NULL,
  `publicid` text COLLATE utf8_turkish_ci NOT NULL,
  `name` text COLLATE utf8_turkish_ci NOT NULL,
  `username` text COLLATE utf8_turkish_ci NOT NULL,
  `reason` text COLLATE utf8_turkish_ci NOT NULL,
  `message` text COLLATE utf8_turkish_ci NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `files_reported`
--

INSERT INTO `files_reported` (`id`, `uniqueid`, `publicid`, `name`, `username`, `reason`, `message`, `date`) VALUES
(2, '5ef7679bda43a', '9c62293f103fb6a9763b765859277a64', 'testtt', '0', 'Telif hakları ihlali/İllegal dosya', 'byyt', '2020-06-27 18:36:59'),
(8, '151555', '9c62293f103fb6a9763b765859277a64', '0', 'turkoglu98', 'Telif hakları ihlali/İllegal dosya', 'bu dosya yanlış', '2020-06-27 18:36:59');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uniqueid` text COLLATE utf8_turkish_ci NOT NULL,
  `username` text COLLATE utf8_turkish_ci NOT NULL,
  `password` text COLLATE utf8_turkish_ci NOT NULL,
  `name` text COLLATE utf8_turkish_ci NOT NULL,
  `surname` text COLLATE utf8_turkish_ci NOT NULL,
  `email` text COLLATE utf8_turkish_ci NOT NULL,
  `role` int(1) NOT NULL,
  `quota` text COLLATE utf8_turkish_ci NOT NULL,
  `date` datetime NOT NULL,
  `password_reset` text COLLATE utf8_turkish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `uniqueid`, `username`, `password`, `name`, `surname`, `email`, `role`, `quota`, `date`, `password_reset`) VALUES
(1, '5ef088a856e15', 'admin', '1234', 'Admin', '', 'abdulah.emre@gmail.com', 1, '0', '0000-00-00 00:00:00', '0'),
(2, '5ef5537467f1b', 'turkoglu98', '1234', 'Abdullah Emre', 'Türkoğlu', 'abdulah.emre@gmail.com', 0, '309715200', '2020-06-26 04:46:28', '0'),
(6, '5f207d280fa85', 'test', '1c59ouzh', 'Abdullah', 'Türkoğlu', 'abdulah.emre@gmail.com', 0, '209715200', '2020-07-28 22:31:52', '0');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `files_reported`
--
ALTER TABLE `files_reported`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Tablo için AUTO_INCREMENT değeri `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Tablo için AUTO_INCREMENT değeri `files_reported`
--
ALTER TABLE `files_reported`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
