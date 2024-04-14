-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-02-23 05:28:33
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `gs_smcb`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `gs_keireki1`
--

CREATE TABLE `gs_keireki1` (
  `id` int(11) NOT NULL,
  `kaishi` year(4) NOT NULL,
  `owari` year(4) NOT NULL,
  `belongs` varchar(256) NOT NULL,
  `naiyou` text DEFAULT NULL,
  `item1` varchar(256) DEFAULT NULL,
  `item2` varchar(256) DEFAULT NULL,
  `item3` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='1人目の経歴書';

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `gs_keireki1`
--
ALTER TABLE `gs_keireki1`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `gs_keireki1`
--
ALTER TABLE `gs_keireki1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
