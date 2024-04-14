-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-02-24 03:50:06
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
-- テーブルの構造 `gs_MC100000`
--

CREATE TABLE `gs_MC100000` (
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
-- テーブルのデータのダンプ `gs_MC100000`
--

INSERT INTO `gs_MC100000` (`id`, `kaishi`, `owari`, `belongs`, `naiyou`, `item1`, `item2`, `item3`) VALUES
(1, '2012', '2019', '水事業部', '国内外事業投資先管理、事業戦等係数対応', '事業戦', '水事業', '会社設立');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `gs_MC100000`
--
ALTER TABLE `gs_MC100000`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `gs_MC100000`
--
ALTER TABLE `gs_MC100000`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
