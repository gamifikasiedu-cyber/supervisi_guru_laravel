-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 23, 2026 at 02:01 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `supervisi_guru`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('admin@assessment.com|127.0.0.1', 'i:1;', 1789471347),
('admin@assessment.com|127.0.0.1:timer', 'i:1789471347;', 1789471347),
('akuagusiki@gmail.com|127.0.0.1', 'i:1;', 1789383954),
('akuagusiki@gmail.com|127.0.0.1:timer', 'i:1789383954;', 1789383954),
('oden@gmail.com|127.0.0.1', 'i:1;', 1789474797),
('oden@gmail.com|127.0.0.1:timer', 'i:1789474797;', 1789474797);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_packages`
--

CREATE TABLE `document_packages` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instruments`
--

CREATE TABLE `instruments` (
  `id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED DEFAULT NULL,
  `teacher_id` bigint UNSIGNED DEFAULT NULL,
  `supervisor_id` bigint UNSIGNED DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `class_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observation_date` date NOT NULL,
  `items` json NOT NULL,
  `total_skor` int UNSIGNED NOT NULL DEFAULT '0',
  `max_skor` int UNSIGNED NOT NULL DEFAULT '0',
  `score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instruments`
--

INSERT INTO `instruments` (`id`, `period_id`, `teacher_id`, `supervisor_id`, `subject_id`, `class_name`, `observation_date`, `items`, `total_skor`, `max_skor`, `score`, `created_at`, `updated_at`) VALUES
(6, 1, 23, 1, 11, 'xii tjkt 5', '2026-09-21', '[{\"no\": 1, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"A. KEGIATAN AWAL\", \"catatan\": \"\", \"dimensi\": \"Berkesadaran\", \"indikator\": \"Guru membangun suasana belajar yang aman, nyaman, menghargai murid, dan menyiapkan murid secara mental untuk belajar.\"}, {\"no\": 2, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"A. KEGIATAN AWAL\", \"catatan\": \"\", \"dimensi\": \"Bermakna\", \"indikator\": \"Guru mengaitkan tujuan/materi dengan pengalaman, kebutuhan, lingkungan, budaya, dunia kerja, atau fenomena nyata.\"}, {\"no\": 3, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"A. KEGIATAN AWAL\", \"catatan\": \"\", \"dimensi\": \"Menggembirakan\", \"indikator\": \"Guru membangun rasa ingin tahu melalui apersepsi, pertanyaan pemantik, demonstrasi, atau aktivitas yang menarik.\"}, {\"no\": 4, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"A. KEGIATAN AWAL\", \"catatan\": \"\", \"dimensi\": \"Tujuan belajar\", \"indikator\": \"Guru menyampaikan tujuan dan kriteria keberhasilan yang dipahami murid.\"}, {\"no\": 5, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"A. KEGIATAN AWAL\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Tujuan/aktivitas belajar memuat kegiatan menemukan informasi, mengintegrasi/menginterpretasi, atau mengevaluasi/merefleksi informasi.\"}, {\"no\": 6, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"A. KEGIATAN AWAL\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Tujuan/aktivitas belajar memuat aktivitas numerasi sesuai konteks: memahami, menerapkan, menalar/menyelesaikan masalah, atau mengambil keputusan berbasis data.\"}, {\"no\": 7, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Memahami\", \"indikator\": \"Guru memfasilitasi murid membangun pemahaman konsep secara aktif dari berbagai sumber.\"}, {\"no\": 8, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Berkesadaran\", \"indikator\": \"Murid terlibat aktif: berpikir, bertanya, berdiskusi, mendengarkan, dan memantau pemahamannya.\"}, {\"no\": 9, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Bermakna\", \"indikator\": \"Materi dikaitkan dengan pengalaman, lingkungan, budaya, kebutuhan, atau dunia kerja.\"}, {\"no\": 10, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Menggembirakan\", \"indikator\": \"Metode/media/sumber belajar mendorong antusiasme, rasa ingin tahu, dan partisipasi murid.\"}, {\"no\": 11, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Murid membaca/menelaah berbagai sumber (teks, artikel, infografik, grafik, media digital) sebagai dasar belajar.\"}, {\"no\": 12, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Guru memfasilitasi murid menemukan informasi penting dan membedakan informasi yang relevan/tidak relevan.\"}, {\"no\": 13, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Murid mengintegrasi dan menginterpretasi informasi dari satu atau lebih sumber untuk membangun pemahaman.\"}, {\"no\": 14, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Guru menggunakan data/angka/tabel/diagram/bagan/grafik atau representasi lain yang relevan dengan materi.\"}, {\"no\": 15, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Murid memahami makna angka, besaran, pola, satuan, data, atau representasi matematika dalam konteks pembelajaran.\"}, {\"no\": 16, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Murid membandingkan, mengelompokkan, memperkirakan, atau menafsirkan data/informasi kuantitatif.\"}, {\"no\": 17, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Mengaplikasi\", \"indikator\": \"Murid menerapkan konsep pada penyelesaian masalah nyata, kontekstual, atau terkait dunia kerja.\"}, {\"no\": 18, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Berkesadaran\", \"indikator\": \"Murid bekerja mandiri/kolaboratif dengan tanggung jawab, mengelola strategi, waktu, dan sumber belajar.\"}, {\"no\": 19, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Bermakna\", \"indikator\": \"Aktivitas menghasilkan solusi, produk, layanan, keputusan, atau karya yang bermanfaat.\"}, {\"no\": 20, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Menggembirakan\", \"indikator\": \"Guru memberikan tantangan/interaksi yang memotivasi murid mencoba, mengeksplorasi, dan memperbaiki solusi.\"}, {\"no\": 21, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Murid menggunakan informasi dari teks/sumber untuk menentukan strategi penyelesaian masalah.\"}, {\"no\": 22, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Murid menganalisis, mengevaluasi kredibilitas/relevansi informasi, dan menghubungkan informasi dengan konteks masalah.\"}, {\"no\": 23, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Terdapat pemecahan masalah kontekstual yang melibatkan menghitung, mengukur, membandingkan, memperkirakan, menafsirkan, atau mengambil keputusan.\"}, {\"no\": 24, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Murid menggunakan data, tabel, grafik, diagram, rumus, alat ukur, atau representasi matematika yang sesuai.\"}, {\"no\": 25, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Murid menjelaskan strategi/langkah penyelesaian dan alasan pemilihan strategi.\"}, {\"no\": 26, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Murid menggunakan alat bantu digital/non-digital secara tepat untuk mengolah informasi numerik.\"}, {\"no\": 27, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"D. KEGIATAN INTI – MEREFLEKSI\", \"catatan\": \"\", \"dimensi\": \"Merefleksi\", \"indikator\": \"Guru memfasilitasi murid merefleksi proses dan hasil belajar.\"}, {\"no\": 28, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"D. KEGIATAN INTI – MEREFLEKSI\", \"catatan\": \"\", \"dimensi\": \"Berkesadaran\", \"indikator\": \"Murid mengenali kekuatan, kesulitan, kesalahan, dan strategi perbaikan belajarnya.\"}, {\"no\": 29, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"D. KEGIATAN INTI – MEREFLEKSI\", \"catatan\": \"\", \"dimensi\": \"Bermakna\", \"indikator\": \"Murid menjelaskan manfaat hasil belajar bagi diri, masyarakat, lingkungan, atau dunia kerja.\"}, {\"no\": 30, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"D. KEGIATAN INTI – MEREFLEKSI\", \"catatan\": \"\", \"dimensi\": \"Menggembirakan\", \"indikator\": \"Umpan balik guru membangun motivasi, rasa percaya diri, dan kemauan untuk memperbaiki diri.\"}, {\"no\": 31, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"D. KEGIATAN INTI – MEREFLEKSI\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Murid menuliskan/mengomunikasikan kembali informasi atau hasil belajar dalam bentuk rangkuman, laporan, jurnal, peta konsep, atau refleksi.\"}, {\"no\": 32, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"D. KEGIATAN INTI – MEREFLEKSI\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Murid mengevaluasi dan merefleksi informasi/argumen serta menyampaikan alasan atau bukti pendukung.\"}, {\"no\": 33, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"D. KEGIATAN INTI – MEREFLEKSI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Murid memeriksa kewajaran hasil, menafsirkan makna hasil, dan merefleksi strategi numerasi yang digunakan.\"}, {\"no\": 34, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"D. KEGIATAN INTI – MEREFLEKSI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Murid mengomunikasikan hasil analisis data/perhitungan secara lisan, tulisan, visual, atau digital.\"}, {\"no\": 35, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"E. KEGIATAN PENUTUP\", \"catatan\": \"\", \"dimensi\": \"Penguatan\", \"indikator\": \"Guru dan murid menyimpulkan pembelajaran berdasarkan bukti hasil belajar.\"}, {\"no\": 36, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"E. KEGIATAN PENUTUP\", \"catatan\": \"\", \"dimensi\": \"Umpan balik\", \"indikator\": \"Guru memberikan penguatan terhadap pencapaian murid dan umpan balik yang spesifik.\"}, {\"no\": 37, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"E. KEGIATAN PENUTUP\", \"catatan\": \"\", \"dimensi\": \"Tindak lanjut\", \"indikator\": \"Guru memberikan tindak lanjut kontekstual untuk memperdalam atau memperbaiki pembelajaran.\"}, {\"no\": 38, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"E. KEGIATAN PENUTUP\", \"catatan\": \"\", \"dimensi\": \"Suasana positif\", \"indikator\": \"Guru menutup pembelajaran secara positif, apresiatif, dan memotivasi.\"}, {\"no\": 39, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"E. KEGIATAN PENUTUP\", \"catatan\": \"\", \"dimensi\": \"Literasi & Numerasi\", \"indikator\": \"Asesmen/tindak lanjut memuat kesempatan memperkuat literasi dan/atau numerasi sesuai kebutuhan murid.\"}, {\"no\": 40, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"F. ASESMEN TERINTEGRASI\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Guru melakukan asesmen yang mengukur menemukan informasi; mengintegrasi/menginterpretasi; serta mengevaluasi/merefleksi.\"}, {\"no\": 41, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"F. ASESMEN TERINTEGRASI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Guru melakukan asesmen yang mengukur memahami; menerapkan; menalar/memecahkan masalah; serta merefleksi.\"}, {\"no\": 42, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"F. ASESMEN TERINTEGRASI\", \"catatan\": \"\", \"dimensi\": \"Autentik\", \"indikator\": \"Asesmen menggunakan teks, data, grafik, tabel, situasi nyata, produk, kinerja, atau masalah kontekstual yang relevan.\"}, {\"no\": 43, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"F. ASESMEN TERINTEGRASI\", \"catatan\": \"\", \"dimensi\": \"Umpan balik\", \"indikator\": \"Guru memberikan umpan balik berdasarkan hasil asesmen dan memanfaatkannya untuk memperbaiki strategi penguatan literasi/numerasi.\"}, {\"no\": 44, \"skor\": 4, \"bukti\": \"\", \"tahap\": \"F. ASESMEN TERINTEGRASI\", \"catatan\": \"\", \"dimensi\": \"Tindak lanjut\", \"indikator\": \"Guru merancang tindak lanjut/remedial/pengayaan berdasarkan kebutuhan literasi dan numerasi murid.\"}]', 147, 176, 83.52, '2026-09-21 13:40:37', '2026-09-21 13:40:37'),
(7, 1, 27, 1, 12, 'xii tjkt 2', '2026-09-23', '[{\"no\": 1, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"A. KEGIATAN AWAL\", \"catatan\": \"\", \"dimensi\": \"Berkesadaran\", \"indikator\": \"Guru membangun suasana belajar yang aman, nyaman, menghargai murid, dan menyiapkan murid secara mental untuk belajar.\"}, {\"no\": 2, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"A. KEGIATAN AWAL\", \"catatan\": \"\", \"dimensi\": \"Bermakna\", \"indikator\": \"Guru mengaitkan tujuan/materi dengan pengalaman, kebutuhan, lingkungan, budaya, dunia kerja, atau fenomena nyata.\"}, {\"no\": 3, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"A. KEGIATAN AWAL\", \"catatan\": \"\", \"dimensi\": \"Menggembirakan\", \"indikator\": \"Guru membangun rasa ingin tahu melalui apersepsi, pertanyaan pemantik, demonstrasi, atau aktivitas yang menarik.\"}, {\"no\": 4, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"A. KEGIATAN AWAL\", \"catatan\": \"\", \"dimensi\": \"Tujuan belajar\", \"indikator\": \"Guru menyampaikan tujuan dan kriteria keberhasilan yang dipahami murid.\"}, {\"no\": 5, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"A. KEGIATAN AWAL\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Tujuan/aktivitas belajar memuat kegiatan menemukan informasi, mengintegrasi/menginterpretasi, atau mengevaluasi/merefleksi informasi.\"}, {\"no\": 6, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"A. KEGIATAN AWAL\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Tujuan/aktivitas belajar memuat aktivitas numerasi sesuai konteks: memahami, menerapkan, menalar/menyelesaikan masalah, atau mengambil keputusan berbasis data.\"}, {\"no\": 7, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Memahami\", \"indikator\": \"Guru memfasilitasi murid membangun pemahaman konsep secara aktif dari berbagai sumber.\"}, {\"no\": 8, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Berkesadaran\", \"indikator\": \"Murid terlibat aktif: berpikir, bertanya, berdiskusi, mendengarkan, dan memantau pemahamannya.\"}, {\"no\": 9, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Bermakna\", \"indikator\": \"Materi dikaitkan dengan pengalaman, lingkungan, budaya, kebutuhan, atau dunia kerja.\"}, {\"no\": 10, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Menggembirakan\", \"indikator\": \"Metode/media/sumber belajar mendorong antusiasme, rasa ingin tahu, dan partisipasi murid.\"}, {\"no\": 11, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Murid membaca/menelaah berbagai sumber (teks, artikel, infografik, grafik, media digital) sebagai dasar belajar.\"}, {\"no\": 12, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Guru memfasilitasi murid menemukan informasi penting dan membedakan informasi yang relevan/tidak relevan.\"}, {\"no\": 13, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Murid mengintegrasi dan menginterpretasi informasi dari satu atau lebih sumber untuk membangun pemahaman.\"}, {\"no\": 14, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Guru menggunakan data/angka/tabel/diagram/bagan/grafik atau representasi lain yang relevan dengan materi.\"}, {\"no\": 15, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Murid memahami makna angka, besaran, pola, satuan, data, atau representasi matematika dalam konteks pembelajaran.\"}, {\"no\": 16, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"B. KEGIATAN INTI – MEMAHAMI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Murid membandingkan, mengelompokkan, memperkirakan, atau menafsirkan data/informasi kuantitatif.\"}, {\"no\": 17, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Mengaplikasi\", \"indikator\": \"Murid menerapkan konsep pada penyelesaian masalah nyata, kontekstual, atau terkait dunia kerja.\"}, {\"no\": 18, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Berkesadaran\", \"indikator\": \"Murid bekerja mandiri/kolaboratif dengan tanggung jawab, mengelola strategi, waktu, dan sumber belajar.\"}, {\"no\": 19, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Bermakna\", \"indikator\": \"Aktivitas menghasilkan solusi, produk, layanan, keputusan, atau karya yang bermanfaat.\"}, {\"no\": 20, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Menggembirakan\", \"indikator\": \"Guru memberikan tantangan/interaksi yang memotivasi murid mencoba, mengeksplorasi, dan memperbaiki solusi.\"}, {\"no\": 21, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Murid menggunakan informasi dari teks/sumber untuk menentukan strategi penyelesaian masalah.\"}, {\"no\": 22, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Murid menganalisis, mengevaluasi kredibilitas/relevansi informasi, dan menghubungkan informasi dengan konteks masalah.\"}, {\"no\": 23, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Terdapat pemecahan masalah kontekstual yang melibatkan menghitung, mengukur, membandingkan, memperkirakan, menafsirkan, atau mengambil keputusan.\"}, {\"no\": 24, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Murid menggunakan data, tabel, grafik, diagram, rumus, alat ukur, atau representasi matematika yang sesuai.\"}, {\"no\": 25, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Murid menjelaskan strategi/langkah penyelesaian dan alasan pemilihan strategi.\"}, {\"no\": 26, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"C. KEGIATAN INTI – MENGAPLIKASI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Murid menggunakan alat bantu digital/non-digital secara tepat untuk mengolah informasi numerik.\"}, {\"no\": 27, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"D. KEGIATAN INTI – MEREFLEKSI\", \"catatan\": \"\", \"dimensi\": \"Merefleksi\", \"indikator\": \"Guru memfasilitasi murid merefleksi proses dan hasil belajar.\"}, {\"no\": 28, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"D. KEGIATAN INTI – MEREFLEKSI\", \"catatan\": \"\", \"dimensi\": \"Berkesadaran\", \"indikator\": \"Murid mengenali kekuatan, kesulitan, kesalahan, dan strategi perbaikan belajarnya.\"}, {\"no\": 29, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"D. KEGIATAN INTI – MEREFLEKSI\", \"catatan\": \"\", \"dimensi\": \"Bermakna\", \"indikator\": \"Murid menjelaskan manfaat hasil belajar bagi diri, masyarakat, lingkungan, atau dunia kerja.\"}, {\"no\": 30, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"D. KEGIATAN INTI – MEREFLEKSI\", \"catatan\": \"\", \"dimensi\": \"Menggembirakan\", \"indikator\": \"Umpan balik guru membangun motivasi, rasa percaya diri, dan kemauan untuk memperbaiki diri.\"}, {\"no\": 31, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"D. KEGIATAN INTI – MEREFLEKSI\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Murid menuliskan/mengomunikasikan kembali informasi atau hasil belajar dalam bentuk rangkuman, laporan, jurnal, peta konsep, atau refleksi.\"}, {\"no\": 32, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"D. KEGIATAN INTI – MEREFLEKSI\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Murid mengevaluasi dan merefleksi informasi/argumen serta menyampaikan alasan atau bukti pendukung.\"}, {\"no\": 33, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"D. KEGIATAN INTI – MEREFLEKSI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Murid memeriksa kewajaran hasil, menafsirkan makna hasil, dan merefleksi strategi numerasi yang digunakan.\"}, {\"no\": 34, \"skor\": 3, \"bukti\": \"\", \"tahap\": \"D. KEGIATAN INTI – MEREFLEKSI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Murid mengomunikasikan hasil analisis data/perhitungan secara lisan, tulisan, visual, atau digital.\"}, {\"no\": 35, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"E. KEGIATAN PENUTUP\", \"catatan\": \"\", \"dimensi\": \"Penguatan\", \"indikator\": \"Guru dan murid menyimpulkan pembelajaran berdasarkan bukti hasil belajar.\"}, {\"no\": 36, \"skor\": 1, \"bukti\": \"\", \"tahap\": \"E. KEGIATAN PENUTUP\", \"catatan\": \"\", \"dimensi\": \"Umpan balik\", \"indikator\": \"Guru memberikan penguatan terhadap pencapaian murid dan umpan balik yang spesifik.\"}, {\"no\": 37, \"skor\": 1, \"bukti\": \"\", \"tahap\": \"E. KEGIATAN PENUTUP\", \"catatan\": \"\", \"dimensi\": \"Tindak lanjut\", \"indikator\": \"Guru memberikan tindak lanjut kontekstual untuk memperdalam atau memperbaiki pembelajaran.\"}, {\"no\": 38, \"skor\": 1, \"bukti\": \"\", \"tahap\": \"E. KEGIATAN PENUTUP\", \"catatan\": \"\", \"dimensi\": \"Suasana positif\", \"indikator\": \"Guru menutup pembelajaran secara positif, apresiatif, dan memotivasi.\"}, {\"no\": 39, \"skor\": 1, \"bukti\": \"\", \"tahap\": \"E. KEGIATAN PENUTUP\", \"catatan\": \"\", \"dimensi\": \"Literasi & Numerasi\", \"indikator\": \"Asesmen/tindak lanjut memuat kesempatan memperkuat literasi dan/atau numerasi sesuai kebutuhan murid.\"}, {\"no\": 40, \"skor\": 1, \"bukti\": \"\", \"tahap\": \"F. ASESMEN TERINTEGRASI\", \"catatan\": \"\", \"dimensi\": \"Literasi\", \"indikator\": \"Guru melakukan asesmen yang mengukur menemukan informasi; mengintegrasi/menginterpretasi; serta mengevaluasi/merefleksi.\"}, {\"no\": 41, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"F. ASESMEN TERINTEGRASI\", \"catatan\": \"\", \"dimensi\": \"Numerasi\", \"indikator\": \"Guru melakukan asesmen yang mengukur memahami; menerapkan; menalar/memecahkan masalah; serta merefleksi.\"}, {\"no\": 42, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"F. ASESMEN TERINTEGRASI\", \"catatan\": \"\", \"dimensi\": \"Autentik\", \"indikator\": \"Asesmen menggunakan teks, data, grafik, tabel, situasi nyata, produk, kinerja, atau masalah kontekstual yang relevan.\"}, {\"no\": 43, \"skor\": 1, \"bukti\": \"\", \"tahap\": \"F. ASESMEN TERINTEGRASI\", \"catatan\": \"\", \"dimensi\": \"Umpan balik\", \"indikator\": \"Guru memberikan umpan balik berdasarkan hasil asesmen dan memanfaatkannya untuk memperbaiki strategi penguatan literasi/numerasi.\"}, {\"no\": 44, \"skor\": 2, \"bukti\": \"\", \"tahap\": \"F. ASESMEN TERINTEGRASI\", \"catatan\": \"\", \"dimensi\": \"Tindak lanjut\", \"indikator\": \"Guru merancang tindak lanjut/remedial/pengayaan berdasarkan kebutuhan literasi dan numerasi murid.\"}]', 88, 176, 50.00, '2026-09-23 11:58:33', '2026-09-23 11:58:33');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_07_203041_create_subjects_table', 2),
(5, '2026_09_07_205235_create_supervisions_table', 3),
(6, '2026_09_07_210000_create_teaching_documents_table', 4),
(10, '2026_09_07_210001_create_observations_table', 5),
(11, '2026_09_07_210002_add_approval_to_supervisions_table', 5),
(12, '2026_09_07_220000_create_user_roles_table', 6),
(15, '2026_09_10_100000_create_document_packages_table', 8),
(16, '2026_09_10_000000_create_school_settings_table', 9),
(17, '2026_09_14_120000_create_settings_table', 10),
(18, '2026_09_14_130000_create_periods_table', 11),
(19, '2026_09_14_140000_add_period_id_to_data_tables', 12),
(21, '2026_09_15_000000_create_instruments_table', 13),
(22, '2026_09_22_000000_create_pre_observations_table', 14),
(23, '2026_09_23_000000_create_post_supervisions_table', 15),
(24, '2026_09_24_000000_create_pre_observation_konferensis_table', 16),
(25, '2026_09_24_000001_add_dokumentasi_foto_to_konferensis_table', 17),
(26, '2026_09_24_000002_add_jenis_observasi_to_post_supervisions_table', 18);

-- --------------------------------------------------------

--
-- Table structure for table `observations`
--

CREATE TABLE `observations` (
  `id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED DEFAULT NULL,
  `supervision_id` bigint UNSIGNED DEFAULT NULL,
  `teacher_id` bigint UNSIGNED NOT NULL,
  `supervisor_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `observation_date` date NOT NULL,
  `score_planning` decimal(5,2) DEFAULT NULL,
  `score_delivery` decimal(5,2) DEFAULT NULL,
  `score_management` decimal(5,2) DEFAULT NULL,
  `score_assessment` decimal(5,2) DEFAULT NULL,
  `total_score` decimal(5,2) DEFAULT NULL,
  `observation_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `recommendations` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('draft','submitted','approved') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `observations`
--

INSERT INTO `observations` (`id`, `period_id`, `supervision_id`, `teacher_id`, `supervisor_id`, `subject_id`, `class_name`, `observation_date`, `score_planning`, `score_delivery`, `score_management`, `score_assessment`, `total_score`, `observation_notes`, `feedback`, `recommendations`, `status`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(3, NULL, 5, 25, 21, 11, 'xii tjkt 2', '2026-09-18', 85.00, 90.00, 95.00, 95.00, 91.25, 'ok', 'ok', 'ok', 'approved', 22, '2026-09-14 13:13:42', '2026-09-14 13:06:05', '2026-09-14 13:13:42'),
(5, NULL, 7, 26, 21, 11, 'xii tjkt 4', '2026-09-17', 95.00, 100.00, 95.00, 100.00, 97.50, 'ok', 'okk', 'okk', 'approved', 22, '2026-09-15 13:31:50', '2026-09-15 13:29:49', '2026-09-15 13:31:50');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `periods`
--

CREATE TABLE `periods` (
  `id` bigint UNSIGNED NOT NULL,
  `tahun_ajaran` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` enum('Ganjil','Genap') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `periods`
--

INSERT INTO `periods` (`id`, `tahun_ajaran`, `semester`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '2026/2027', 'Ganjil', '2026-07-01', '2026-12-31', 1, '2026-09-14 12:26:32', '2026-09-14 12:26:32'),
(3, '2025/2026', 'Ganjil', '2025-07-01', '2025-12-31', 1, '2026-09-14 12:36:29', '2026-09-14 12:36:29'),
(6, '2025/2026', 'Genap', '2026-01-01', '2026-06-30', 1, '2026-09-15 13:39:13', '2026-09-15 13:39:13');

-- --------------------------------------------------------

--
-- Table structure for table `post_supervisions`
--

CREATE TABLE `post_supervisions` (
  `id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED DEFAULT NULL,
  `teacher_id` bigint UNSIGNED DEFAULT NULL,
  `supervisor_id` bigint UNSIGNED DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `class_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observation_date` date NOT NULL,
  `bagian` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_observasi` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `items` json NOT NULL,
  `total_skor` int UNSIGNED DEFAULT NULL,
  `max_skor` int UNSIGNED DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_supervisions`
--

INSERT INTO `post_supervisions` (`id`, `period_id`, `teacher_id`, `supervisor_id`, `subject_id`, `class_name`, `observation_date`, `bagian`, `jenis_observasi`, `items`, `total_skor`, `max_skor`, `score`, `created_at`, `updated_at`) VALUES
(1, 1, 21, 1, 11, 'xii tjkt 1', '2026-09-21', 'asesmen-formatif', NULL, '[{\"no\": 1, \"skor\": 4, \"bukti\": \"\", \"tindakan\": \"\", \"indikator\": \"Pemahaman konsep peserta didik terhadap materi\"}, {\"no\": 2, \"skor\": 4, \"bukti\": \"\", \"tindakan\": \"\", \"indikator\": \"Keterampilan proses / unjuk kerja peserta didik\"}, {\"no\": 3, \"skor\": 4, \"bukti\": \"\", \"tindakan\": \"\", \"indikator\": \"Partisipasi, sikap, dan kolaborasi peserta didik\"}, {\"no\": 4, \"skor\": 4, \"bukti\": \"\", \"tindakan\": \"\", \"indikator\": \"Penguatan literasi dan numerasi dalam proses\"}]', 16, 16, 100.00, '2026-09-22 06:07:56', '2026-09-22 06:07:56'),
(2, 1, 21, 1, 11, 'xii tjkt 1', '2026-09-22', 'asesmen-sumatif', NULL, '[{\"no\": 1, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"indikator\": \"TP 1 — capaian tujuan pembelajaran 1\"}, {\"no\": 2, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"indikator\": \"TP 2 — capaian tujuan pembelajaran 2\"}, {\"no\": 3, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"indikator\": \"TP 3 — capaian tujuan pembelajaran 3\"}]', 10, 12, 83.33, '2026-09-22 06:08:38', '2026-09-22 06:08:38'),
(3, 1, 21, 1, 11, 'xii tjkt 1', '2026-09-22', 'refleksi-dialog', NULL, '[{\"no\": 1, \"tahap\": \"Refleksi\", \"catatan\": \"baik\", \"pertanyaan\": \"Apa yang sudah berjalan baik dalam pembelajaran?\"}, {\"no\": 2, \"tahap\": \"Refleksi\", \"catatan\": \"ok\", \"pertanyaan\": \"Apa kendala yang dihadapi selama pembelajaran?\"}, {\"no\": 3, \"tahap\": \"Dialog\", \"catatan\": \"yes\", \"pertanyaan\": \"Apa rencana perbaikan yang disepakati?\"}, {\"no\": 4, \"tahap\": \"Dialog\", \"catatan\": \"benar\", \"pertanyaan\": \"Dukungan apa yang dibutuhkan guru?\"}]', NULL, NULL, NULL, '2026-09-22 06:34:46', '2026-09-22 06:34:46'),
(4, 1, 23, 1, 11, 'xii tjkt 1', '2026-09-22', 'refleksi-dialog', NULL, '[{\"no\": 1, \"tahap\": \"Refleksi\", \"catatan\": \"mantap\", \"pertanyaan\": \"Apa yang sudah berjalan baik dalam pembelajaran?\"}, {\"no\": 2, \"tahap\": \"Refleksi\", \"catatan\": \"baik\", \"pertanyaan\": \"Apa kendala yang dihadapi selama pembelajaran?\"}, {\"no\": 3, \"tahap\": \"Dialog\", \"catatan\": \"baik\", \"pertanyaan\": \"Apa rencana perbaikan yang disepakati?\"}, {\"no\": 4, \"tahap\": \"Dialog\", \"catatan\": \"baik\", \"pertanyaan\": \"Dukungan apa yang dibutuhkan guru?\"}]', NULL, NULL, NULL, '2026-09-22 07:39:44', '2026-09-22 07:39:44'),
(7, 1, 27, 1, 11, 'XII TJKT 1', '2026-09-23', 'observasi-3m', NULL, '[{\"no\": 1, \"skor\": 4, \"bukti\": \"\", \"indikator\": \"Murid mengetahui tujuan pembelajaran\", \"pengalaman_belajar\": \"Memahami\"}, {\"no\": 2, \"skor\": 3, \"bukti\": \"\", \"indikator\": \"Murid menghubungkan pengetahuan awal dengan materi\", \"pengalaman_belajar\": \"Memahami\"}, {\"no\": 3, \"skor\": 4, \"bukti\": \"\", \"indikator\": \"Murid memperoleh kesempatan mengeksplorasi konsep\", \"pengalaman_belajar\": \"Memahami\"}, {\"no\": 4, \"skor\": 3, \"bukti\": \"\", \"indikator\": \"Murid melakukan pengecekan pemahaman\", \"pengalaman_belajar\": \"Memahami\"}, {\"no\": 5, \"skor\": 3, \"bukti\": \"\", \"indikator\": \"Murid menerapkan pengetahuan/keterampilan\", \"pengalaman_belajar\": \"Mengaplikasi\"}, {\"no\": 6, \"skor\": 3, \"bukti\": \"\", \"indikator\": \"Murid menyelesaikan masalah/kasus\", \"pengalaman_belajar\": \"Mengaplikasi\"}, {\"no\": 7, \"skor\": 3, \"bukti\": \"\", \"indikator\": \"Murid melakukan praktik/unjuk kerja\", \"pengalaman_belajar\": \"Mengaplikasi\"}, {\"no\": 8, \"skor\": 4, \"bukti\": \"\", \"indikator\": \"Murid menghasilkan karya/produk/solusi\", \"pengalaman_belajar\": \"Mengaplikasi\"}, {\"no\": 9, \"skor\": 2, \"bukti\": \"\", \"indikator\": \"Murid merefleksikan proses belajarnya\", \"pengalaman_belajar\": \"Merefleksi\"}, {\"no\": 10, \"skor\": 2, \"bukti\": \"\", \"indikator\": \"Murid mengidentifikasi kesulitan yang dialami\", \"pengalaman_belajar\": \"Merefleksi\"}, {\"no\": 11, \"skor\": 2, \"bukti\": \"\", \"indikator\": \"Murid menentukan hal yang perlu diperbaiki\", \"pengalaman_belajar\": \"Merefleksi\"}, {\"no\": 12, \"skor\": 3, \"bukti\": \"\", \"indikator\": \"Guru menggunakan hasil refleksi untuk tindak lanjut\", \"pengalaman_belajar\": \"Merefleksi\"}]', 36, 48, 75.00, '2026-09-23 12:57:28', '2026-09-23 12:57:28'),
(8, 1, 27, 1, 11, 'xii tjkt 2', '2026-09-23', 'observasi-3m', NULL, '[{\"no\": 1, \"skor\": 4, \"bukti\": \"\", \"indikator\": \"Murid mengetahui tujuan pembelajaran\", \"pengalaman_belajar\": \"Memahami\"}, {\"no\": 2, \"skor\": 3, \"bukti\": \"\", \"indikator\": \"Murid menghubungkan pengetahuan awal dengan materi\", \"pengalaman_belajar\": \"Memahami\"}, {\"no\": 3, \"skor\": 4, \"bukti\": \"\", \"indikator\": \"Murid memperoleh kesempatan mengeksplorasi konsep\", \"pengalaman_belajar\": \"Memahami\"}, {\"no\": 4, \"skor\": 4, \"bukti\": \"\", \"indikator\": \"Murid melakukan pengecekan pemahaman\", \"pengalaman_belajar\": \"Memahami\"}, {\"no\": 5, \"skor\": 3, \"bukti\": \"\", \"indikator\": \"Murid menerapkan pengetahuan/keterampilan\", \"pengalaman_belajar\": \"Mengaplikasi\"}, {\"no\": 6, \"skor\": 3, \"bukti\": \"\", \"indikator\": \"Murid menyelesaikan masalah/kasus\", \"pengalaman_belajar\": \"Mengaplikasi\"}, {\"no\": 7, \"skor\": 4, \"bukti\": \"\", \"indikator\": \"Murid melakukan praktik/unjuk kerja\", \"pengalaman_belajar\": \"Mengaplikasi\"}, {\"no\": 8, \"skor\": 3, \"bukti\": \"\", \"indikator\": \"Murid menghasilkan karya/produk/solusi\", \"pengalaman_belajar\": \"Mengaplikasi\"}, {\"no\": 9, \"skor\": 2, \"bukti\": \"\", \"indikator\": \"Murid merefleksikan proses belajarnya\", \"pengalaman_belajar\": \"Merefleksi\"}, {\"no\": 10, \"skor\": 3, \"bukti\": \"\", \"indikator\": \"Murid mengidentifikasi kesulitan yang dialami\", \"pengalaman_belajar\": \"Merefleksi\"}, {\"no\": 11, \"skor\": 3, \"bukti\": \"\", \"indikator\": \"Murid menentukan hal yang perlu diperbaiki\", \"pengalaman_belajar\": \"Merefleksi\"}, {\"no\": 12, \"skor\": 2, \"bukti\": \"\", \"indikator\": \"Guru menggunakan hasil refleksi untuk tindak lanjut\", \"pengalaman_belajar\": \"Merefleksi\"}]', 38, 48, 79.17, '2026-09-23 13:51:00', '2026-09-23 13:51:00');

-- --------------------------------------------------------

--
-- Table structure for table `pre_observations`
--

CREATE TABLE `pre_observations` (
  `id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED DEFAULT NULL,
  `teacher_id` bigint UNSIGNED DEFAULT NULL,
  `supervisor_id` bigint UNSIGNED DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `class_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observation_date` date NOT NULL,
  `items` json NOT NULL,
  `total_skor` int UNSIGNED NOT NULL DEFAULT '0',
  `max_skor` int UNSIGNED NOT NULL DEFAULT '0',
  `score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pre_observations`
--

INSERT INTO `pre_observations` (`id`, `period_id`, `teacher_id`, `supervisor_id`, `subject_id`, `class_name`, `observation_date`, `items`, `total_skor`, `max_skor`, `score`, `created_at`, `updated_at`) VALUES
(3, 1, NULL, 27, NULL, NULL, '2026-09-23', '[{\"no\": 1, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\", \"indikator\": \"Identitas pembelajaran, fase/kelas, alokasi waktu, dan konteks pembelajaran ditulis jelas dan konsisten.\", \"keterkaitan\": \"Kesiapan pembelajaran dan konteks bermakna\", \"rekomendasi\": \"\"}, {\"no\": 2, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\", \"indikator\": \"CP/kompetensi yang menjadi acuan pembelajaran tercantum dan relevan dengan materi.\", \"keterkaitan\": \"Keselarasan CP–TP\", \"rekomendasi\": \"\"}, {\"no\": 3, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\", \"indikator\": \"Tujuan pembelajaran dirumuskan spesifik, terukur, relevan, dan berorientasi pada kemampuan murid.\", \"keterkaitan\": \"Tujuan sebagai arah pengalaman belajar mendalam\", \"rekomendasi\": \"\"}, {\"no\": 4, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\", \"indikator\": \"Tujuan pembelajaran memuat tuntutan literasi yang relevan: menemukan informasi, mengintegrasi/menginterpretasi, mengevaluasi/merefleksi.\", \"keterkaitan\": \"Literasi\", \"rekomendasi\": \"\"}, {\"no\": 5, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\", \"indikator\": \"Tujuan pembelajaran memuat tuntutan numerasi yang relevan: memahami, menerapkan, menalar/memecahkan masalah, atau mengambil keputusan.\", \"keterkaitan\": \"Numerasi\", \"rekomendasi\": \"\"}, {\"no\": 6, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\", \"indikator\": \"8 Dimensi Profil lulusan\", \"keterkaitan\": \"8 Dimensi Profil lulusan\", \"rekomendasi\": \"\"}, {\"no\": 7, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"B. PERANCANGAN PEMBELAJARAN MENDALAM\", \"indikator\": \"RPP/RPM menunjukkan prinsip Berkesadaran: tujuan, kriteria keberhasilan, kesiapan belajar, pilihan/agensi murid, dan kesadaran terhadap proses belajar.\", \"keterkaitan\": \"BBM – Berkesadaran\", \"rekomendasi\": \"\"}, {\"no\": 8, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"B. PERANCANGAN PEMBELAJARAN MENDALAM\", \"indikator\": \"RPP/RPM menunjukkan prinsip Bermakna: materi/aktivitas dikaitkan dengan pengalaman, kehidupan nyata, lingkungan, budaya, atau dunia kerja.\", \"keterkaitan\": \"BBM – Bermakna\", \"rekomendasi\": \"\"}, {\"no\": 9, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"B. PERANCANGAN PEMBELAJARAN MENDALAM\", \"indikator\": \"RPP/RPM menunjukkan prinsip Menggembirakan: aktivitas menantang, interaktif, aman, positif, dan memotivasi murid.\", \"keterkaitan\": \"BBM – Menggembirakan\", \"rekomendasi\": \"\"}, {\"no\": 10, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"B. PERANCANGAN PEMBELAJARAN MENDALAM\", \"indikator\": \"Alur kegiatan dirancang melalui pengalaman belajar Memahami → Mengaplikasi → Merefleksi.\", \"keterkaitan\": \"3M\", \"rekomendasi\": \"\"}, {\"no\": 11, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"B. PERANCANGAN PEMBELAJARAN MENDALAM\", \"indikator\": \"Aktivitas murid lebih dominan daripada aktivitas guru dan memberi ruang berpikir, bertanya, berdiskusi, mencoba, serta berkarya.\", \"keterkaitan\": \"Pembelajaran berpusat pada murid\", \"rekomendasi\": \"\"}, {\"no\": 12, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"C. TAHAP MEMAHAMI\", \"indikator\": \"Kegiatan awal/inti memberi kesempatan murid mengakses berbagai sumber informasi dan membangun pemahaman konsep.\", \"keterkaitan\": \"3M – Memahami + Literasi\", \"rekomendasi\": \"\"}, {\"no\": 13, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"C. TAHAP MEMAHAMI\", \"indikator\": \"Tersedia aktivitas membaca/menelaah teks, gambar, infografik, grafik, tabel, data, video, atau sumber digital yang relevan.\", \"keterkaitan\": \"Literasi\", \"rekomendasi\": \"\"}, {\"no\": 14, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"C. TAHAP MEMAHAMI\", \"indikator\": \"Pertanyaan/tugas mendorong murid menemukan informasi penting, menghubungkan informasi, dan menafsirkan makna.\", \"keterkaitan\": \"Literasi kritis\", \"rekomendasi\": \"\"}, {\"no\": 15, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"C. TAHAP MEMAHAMI\", \"indikator\": \"Tersedia data/angka/tabel/diagram/pola atau konteks kuantitatif yang relevan dengan materi.\", \"keterkaitan\": \"Numerasi\", \"rekomendasi\": \"\"}, {\"no\": 16, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"C. TAHAP MEMAHAMI\", \"indikator\": \"Aktivitas mendorong murid memahami informasi kuantitatif sebelum melakukan perhitungan/pemecahan masalah.\", \"keterkaitan\": \"Numerasi – Memahami\", \"rekomendasi\": \"\"}, {\"no\": 17, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"D. TAHAP MENGAPLIKASI\", \"indikator\": \"Murid menerapkan konsep pada masalah nyata, kontekstual, autentik, atau situasi dunia kerja.\", \"keterkaitan\": \"3M – Mengaplikasi + Bermakna\", \"rekomendasi\": \"\"}, {\"no\": 18, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"D. TAHAP MENGAPLIKASI\", \"indikator\": \"Murid menggunakan informasi dari berbagai sumber untuk menyelesaikan tugas/masalah.\", \"keterkaitan\": \"Literasi\", \"rekomendasi\": \"\"}, {\"no\": 19, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"D. TAHAP MENGAPLIKASI\", \"indikator\": \"Murid mengolah, membandingkan, menafsirkan, atau mengevaluasi informasi sebelum mengambil kesimpulan/keputusan.\", \"keterkaitan\": \"Literasi\", \"rekomendasi\": \"\"}, {\"no\": 20, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"D. TAHAP MENGAPLIKASI\", \"indikator\": \"Murid menggunakan perhitungan, estimasi, pengukuran, data, tabel, grafik, diagram, pola, atau representasi matematis sesuai kebutuhan.\", \"keterkaitan\": \"Numerasi\", \"rekomendasi\": \"\"}, {\"no\": 21, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"D. TAHAP MENGAPLIKASI\", \"indikator\": \"Murid menjelaskan strategi penyelesaian masalah dan alasan atas jawaban/keputusan yang dibuat.\", \"keterkaitan\": \"Numerasi – Menalar\", \"rekomendasi\": \"\"}, {\"no\": 22, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"D. TAHAP MENGAPLIKASI\", \"indikator\": \"RPP/RPM menyediakan aktivitas mandiri dan/atau kolaboratif dengan pembagian peran dan tanggung jawab yang jelas.\", \"keterkaitan\": \"Berkesadaran + Kolaborasi\", \"rekomendasi\": \"\"}, {\"no\": 23, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"E. TAHAP MEREFLEKSI\", \"indikator\": \"Murid merefleksikan proses, strategi, hasil, kesulitan, dan rencana perbaikan belajar.\", \"keterkaitan\": \"3M – Merefleksi\", \"rekomendasi\": \"\"}, {\"no\": 24, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"E. TAHAP MEREFLEKSI\", \"indikator\": \"Refleksi mengajak murid menilai kualitas informasi/sumber yang digunakan dan manfaat hasil belajar.\", \"keterkaitan\": \"Literasi – Evaluasi/Refleksi\", \"rekomendasi\": \"\"}, {\"no\": 25, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"E. TAHAP MEREFLEKSI\", \"indikator\": \"Refleksi dapat meminta murid menafsirkan hasil perhitungan/data dan menilai kewajaran hasil atau keputusan.\", \"keterkaitan\": \"Numerasi – Refleksi\", \"rekomendasi\": \"\"}, {\"no\": 26, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"E. TAHAP MEREFLEKSI\", \"indikator\": \"Tersedia pertanyaan reflektif yang menghubungkan pembelajaran dengan kehidupan nyata/dunia kerja.\", \"keterkaitan\": \"Bermakna\", \"rekomendasi\": \"\"}, {\"no\": 27, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"F. STRATEGI PENGUATAN LITERASI\", \"indikator\": \"Sumber belajar mencakup teks/media yang beragam, relevan, kredibel, dan sesuai tingkat kemampuan murid.\", \"keterkaitan\": \"Literasi\", \"rekomendasi\": \"\"}, {\"no\": 28, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"F. STRATEGI PENGUATAN LITERASI\", \"indikator\": \"Ada kegiatan membaca/menelaah sebelum diskusi atau pemecahan masalah.\", \"keterkaitan\": \"Literasi\", \"rekomendasi\": \"\"}, {\"no\": 29, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"F. STRATEGI PENGUATAN LITERASI\", \"indikator\": \"Murid mengolah informasi dalam bentuk catatan, rangkuman, peta konsep, laporan, presentasi, atau produk komunikasi.\", \"keterkaitan\": \"Literasi\", \"rekomendasi\": \"\"}, {\"no\": 30, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"F. STRATEGI PENGUATAN LITERASI\", \"indikator\": \"Murid mengomunikasikan pemahaman/hasil analisis secara lisan, tulisan, visual, atau digital.\", \"keterkaitan\": \"Literasi\", \"rekomendasi\": \"\"}, {\"no\": 31, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"G. STRATEGI PENGUATAN NUMERASI\", \"indikator\": \"Sumber belajar memuat data atau informasi kuantitatif yang autentik sesuai konteks pembelajaran.\", \"keterkaitan\": \"Numerasi\", \"rekomendasi\": \"\"}, {\"no\": 32, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"G. STRATEGI PENGUATAN NUMERASI\", \"indikator\": \"Ada masalah kontekstual yang menuntut murid menggunakan konsep numerasi untuk memecahkan masalah.\", \"keterkaitan\": \"Numerasi\", \"rekomendasi\": \"\"}, {\"no\": 33, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"G. STRATEGI PENGUATAN NUMERASI\", \"indikator\": \"Murid menggunakan alat bantu digital/non-digital yang sesuai untuk mengolah atau menyajikan data/informasi.\", \"keterkaitan\": \"Numerasi\", \"rekomendasi\": \"\"}, {\"no\": 34, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"G. STRATEGI PENGUATAN NUMERASI\", \"indikator\": \"Murid menyajikan atau mengomunikasikan hasil pengolahan data/perhitungan dengan alasan yang logis.\", \"keterkaitan\": \"Numerasi\", \"rekomendasi\": \"\"}, {\"no\": 35, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"H. ASESMEN\", \"indikator\": \"Asesmen selaras dengan CP/TP dan mengukur kompetensi yang benar-benar ditargetkan.\", \"keterkaitan\": \"Asesmen selaras\", \"rekomendasi\": \"\"}, {\"no\": 36, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"H. ASESMEN\", \"indikator\": \"Asesmen formatif dirancang untuk memperoleh informasi proses belajar dan memberi umpan balik.\", \"keterkaitan\": \"Asesmen untuk pembelajaran\", \"rekomendasi\": \"\"}, {\"no\": 37, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"H. ASESMEN\", \"indikator\": \"Asesmen memuat bukti kemampuan literasi yang relevan dengan tujuan pembelajaran.\", \"keterkaitan\": \"Asesmen Literasi\", \"rekomendasi\": \"\"}, {\"no\": 38, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"H. ASESMEN\", \"indikator\": \"Asesmen memuat bukti kemampuan numerasi yang relevan dengan tujuan pembelajaran.\", \"keterkaitan\": \"Asesmen Numerasi\", \"rekomendasi\": \"\"}, {\"no\": 39, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"H. ASESMEN\", \"indikator\": \"Kriteria/rubrik keberhasilan jelas dan memungkinkan penilaian autentik terhadap proses dan produk/kinerja.\", \"keterkaitan\": \"Asesmen autentik\", \"rekomendasi\": \"\"}, {\"no\": 40, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"H. ASESMEN\", \"indikator\": \"Hasil asesmen dirancang untuk menjadi dasar tindak lanjut, remedial, pengayaan, atau perbaikan strategi pembelajaran.\", \"keterkaitan\": \"Tindak lanjut\", \"rekomendasi\": \"\"}, {\"no\": 41, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"I. DIFERENSIASI, MEDIA, DAN LINGKUNGAN BELAJAR\", \"indikator\": \"RPP/RPM mempertimbangkan kesiapan, kebutuhan, minat, atau karakteristik murid.\", \"keterkaitan\": \"Pembelajaran adaptif\", \"rekomendasi\": \"\"}, {\"no\": 42, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"I. DIFERENSIASI, MEDIA, DAN LINGKUNGAN BELAJAR\", \"indikator\": \"Media/LKM/LKPD mendukung murid memahami, mengolah, dan mengomunikasikan informasi serta data.\", \"keterkaitan\": \"Literasi-Numerasi\", \"rekomendasi\": \"\"}, {\"no\": 43, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"I. DIFERENSIASI, MEDIA, DAN LINGKUNGAN BELAJAR\", \"indikator\": \"Lingkungan belajar dirancang aman, inklusif, kolaboratif, dan memberi ruang bagi murid untuk aktif.\", \"keterkaitan\": \"Berkesadaran + Menggembirakan\", \"rekomendasi\": \"\"}, {\"no\": 44, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"J. KESINAMBUNGAN DAN KESIAPAN OBSERVASI\", \"indikator\": \"Alokasi waktu realistis untuk menjalankan Memahami, Mengaplikasi, dan Merefleksi.\", \"keterkaitan\": \"Keterlaksanaan 3M\", \"rekomendasi\": \"\"}, {\"no\": 45, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"J. KESINAMBUNGAN DAN KESIAPAN OBSERVASI\", \"indikator\": \"Langkah pembelajaran, asesmen, media, dan sumber belajar konsisten satu sama lain.\", \"keterkaitan\": \"Koherensi perencanaan\", \"rekomendasi\": \"\"}, {\"no\": 46, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"J. KESINAMBUNGAN DAN KESIAPAN OBSERVASI\", \"indikator\": \"Terdapat bukti bahwa pembelajaran dirancang untuk menghasilkan pengalaman belajar yang mendalam, bukan sekadar penyampaian materi.\", \"keterkaitan\": \"Hakikat Pembelajaran Mendalam\", \"rekomendasi\": \"\"}]', 168, 184, 91.30, '2026-09-23 02:19:53', '2026-09-23 02:19:53'),
(4, 1, NULL, 27, NULL, NULL, '2026-09-23', '[{\"no\": 1, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\", \"indikator\": \"Identitas pembelajaran, fase/kelas, alokasi waktu, dan konteks pembelajaran ditulis jelas dan konsisten.\", \"keterkaitan\": \"Kesiapan pembelajaran dan konteks bermakna\", \"rekomendasi\": \"\"}, {\"no\": 2, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\", \"indikator\": \"CP/kompetensi yang menjadi acuan pembelajaran tercantum dan relevan dengan materi.\", \"keterkaitan\": \"Keselarasan CP–TP\", \"rekomendasi\": \"\"}, {\"no\": 3, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\", \"indikator\": \"Tujuan pembelajaran dirumuskan spesifik, terukur, relevan, dan berorientasi pada kemampuan murid.\", \"keterkaitan\": \"Tujuan sebagai arah pengalaman belajar mendalam\", \"rekomendasi\": \"\"}, {\"no\": 4, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\", \"indikator\": \"Tujuan pembelajaran memuat tuntutan literasi yang relevan: menemukan informasi, mengintegrasi/menginterpretasi, mengevaluasi/merefleksi.\", \"keterkaitan\": \"Literasi\", \"rekomendasi\": \"\"}, {\"no\": 5, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\", \"indikator\": \"8 Dimensi Profil lulusan\", \"keterkaitan\": \"8 Dimensi Profil lulusan\", \"rekomendasi\": \"\"}, {\"no\": 6, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"B. PERANCANGAN PEMBELAJARAN MENDALAM\", \"indikator\": \"RPP/RPM menunjukkan prinsip Berkesadaran: tujuan, kriteria keberhasilan, kesiapan belajar, pilihan/agensi murid, dan kesadaran terhadap proses belajar.\", \"keterkaitan\": \"BBM – Berkesadaran\", \"rekomendasi\": \"\"}, {\"no\": 7, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"B. PERANCANGAN PEMBELAJARAN MENDALAM\", \"indikator\": \"RPP/RPM menunjukkan prinsip Bermakna: materi/aktivitas dikaitkan dengan pengalaman, kehidupan nyata, lingkungan, budaya, atau dunia kerja.\", \"keterkaitan\": \"BBM – Bermakna\", \"rekomendasi\": \"\"}, {\"no\": 8, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"B. PERANCANGAN PEMBELAJARAN MENDALAM\", \"indikator\": \"RPP/RPM menunjukkan prinsip Menggembirakan: aktivitas menantang, interaktif, aman, positif, dan memotivasi murid.\", \"keterkaitan\": \"BBM – Menggembirakan\", \"rekomendasi\": \"\"}, {\"no\": 9, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"B. PERANCANGAN PEMBELAJARAN MENDALAM\", \"indikator\": \"Alur kegiatan dirancang melalui pengalaman belajar Memahami → Mengaplikasi → Merefleksi.\", \"keterkaitan\": \"3M\", \"rekomendasi\": \"\"}, {\"no\": 10, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"B. PERANCANGAN PEMBELAJARAN MENDALAM\", \"indikator\": \"Aktivitas murid lebih dominan daripada aktivitas guru dan memberi ruang berpikir, bertanya, berdiskusi, mencoba, serta berkarya.\", \"keterkaitan\": \"Pembelajaran berpusat pada murid\", \"rekomendasi\": \"\"}, {\"no\": 11, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"C. TAHAP MEMAHAMI\", \"indikator\": \"Kegiatan awal/inti memberi kesempatan murid mengakses berbagai sumber informasi dan membangun pemahaman konsep.\", \"keterkaitan\": \"3M – Memahami + Literasi\", \"rekomendasi\": \"\"}, {\"no\": 12, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"C. TAHAP MEMAHAMI\", \"indikator\": \"Tersedia aktivitas membaca/menelaah teks, gambar, infografik, grafik, tabel, data, video, atau sumber digital yang relevan.\", \"keterkaitan\": \"Literasi\", \"rekomendasi\": \"\"}, {\"no\": 13, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"C. TAHAP MEMAHAMI\", \"indikator\": \"Pertanyaan/tugas mendorong murid menemukan informasi penting, menghubungkan informasi, dan menafsirkan makna.\", \"keterkaitan\": \"Literasi kritis\", \"rekomendasi\": \"\"}, {\"no\": 14, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"D. TAHAP MENGAPLIKASI\", \"indikator\": \"Murid menerapkan konsep pada masalah nyata, kontekstual, autentik, atau situasi dunia kerja.\", \"keterkaitan\": \"3M – Mengaplikasi + Bermakna\", \"rekomendasi\": \"\"}, {\"no\": 15, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"D. TAHAP MENGAPLIKASI\", \"indikator\": \"Murid menggunakan informasi dari berbagai sumber untuk menyelesaikan tugas/masalah.\", \"keterkaitan\": \"Literasi\", \"rekomendasi\": \"\"}, {\"no\": 16, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"D. TAHAP MENGAPLIKASI\", \"indikator\": \"Murid mengolah, membandingkan, menafsirkan, atau mengevaluasi informasi sebelum mengambil kesimpulan/keputusan.\", \"keterkaitan\": \"Literasi\", \"rekomendasi\": \"\"}, {\"no\": 17, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"D. TAHAP MENGAPLIKASI\", \"indikator\": \"RPP/RPM menyediakan aktivitas mandiri dan/atau kolaboratif dengan pembagian peran dan tanggung jawab yang jelas.\", \"keterkaitan\": \"Berkesadaran + Kolaborasi\", \"rekomendasi\": \"\"}, {\"no\": 18, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"E. TAHAP MEREFLEKSI\", \"indikator\": \"Murid merefleksikan proses, strategi, hasil, kesulitan, dan rencana perbaikan belajar.\", \"keterkaitan\": \"3M – Merefleksi\", \"rekomendasi\": \"\"}, {\"no\": 19, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"E. TAHAP MEREFLEKSI\", \"indikator\": \"Refleksi mengajak murid menilai kualitas informasi/sumber yang digunakan dan manfaat hasil belajar.\", \"keterkaitan\": \"Literasi – Evaluasi/Refleksi\", \"rekomendasi\": \"\"}, {\"no\": 20, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"E. TAHAP MEREFLEKSI\", \"indikator\": \"Tersedia pertanyaan reflektif yang menghubungkan pembelajaran dengan kehidupan nyata/dunia kerja.\", \"keterkaitan\": \"Bermakna\", \"rekomendasi\": \"\"}, {\"no\": 21, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"F. STRATEGI PENGUATAN LITERASI\", \"indikator\": \"Sumber belajar mencakup teks/media yang beragam, relevan, kredibel, dan sesuai tingkat kemampuan murid.\", \"keterkaitan\": \"Literasi\", \"rekomendasi\": \"\"}, {\"no\": 22, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"F. STRATEGI PENGUATAN LITERASI\", \"indikator\": \"Ada kegiatan membaca/menelaah sebelum diskusi atau pemecahan masalah.\", \"keterkaitan\": \"Literasi\", \"rekomendasi\": \"\"}, {\"no\": 23, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"F. STRATEGI PENGUATAN LITERASI\", \"indikator\": \"Murid mengolah informasi dalam bentuk catatan, rangkuman, peta konsep, laporan, presentasi, atau produk komunikasi.\", \"keterkaitan\": \"Literasi\", \"rekomendasi\": \"\"}, {\"no\": 24, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"F. STRATEGI PENGUATAN LITERASI\", \"indikator\": \"Murid mengomunikasikan pemahaman/hasil analisis secara lisan, tulisan, visual, atau digital.\", \"keterkaitan\": \"Literasi\", \"rekomendasi\": \"\"}, {\"no\": 25, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"H. ASESMEN\", \"indikator\": \"Asesmen selaras dengan CP/TP dan mengukur kompetensi yang benar-benar ditargetkan.\", \"keterkaitan\": \"Asesmen selaras\", \"rekomendasi\": \"\"}, {\"no\": 26, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"H. ASESMEN\", \"indikator\": \"Asesmen formatif dirancang untuk memperoleh informasi proses belajar dan memberi umpan balik.\", \"keterkaitan\": \"Asesmen untuk pembelajaran\", \"rekomendasi\": \"\"}, {\"no\": 27, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"H. ASESMEN\", \"indikator\": \"Asesmen memuat bukti kemampuan literasi yang relevan dengan tujuan pembelajaran.\", \"keterkaitan\": \"Asesmen Literasi\", \"rekomendasi\": \"\"}, {\"no\": 28, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"H. ASESMEN\", \"indikator\": \"Kriteria/rubrik keberhasilan jelas dan memungkinkan penilaian autentik terhadap proses dan produk/kinerja.\", \"keterkaitan\": \"Asesmen autentik\", \"rekomendasi\": \"\"}, {\"no\": 29, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"H. ASESMEN\", \"indikator\": \"Hasil asesmen dirancang untuk menjadi dasar tindak lanjut, remedial, pengayaan, atau perbaikan strategi pembelajaran.\", \"keterkaitan\": \"Tindak lanjut\", \"rekomendasi\": \"\"}, {\"no\": 30, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"I. DIFERENSIASI, MEDIA, DAN LINGKUNGAN BELAJAR\", \"indikator\": \"RPP/RPM mempertimbangkan kesiapan, kebutuhan, minat, atau karakteristik murid.\", \"keterkaitan\": \"Pembelajaran adaptif\", \"rekomendasi\": \"\"}, {\"no\": 31, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"I. DIFERENSIASI, MEDIA, DAN LINGKUNGAN BELAJAR\", \"indikator\": \"Media/LKM/LKPD mendukung murid memahami, mengolah, dan mengomunikasikan informasi serta data.\", \"keterkaitan\": \"Literasi-Numerasi\", \"rekomendasi\": \"\"}, {\"no\": 32, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"I. DIFERENSIASI, MEDIA, DAN LINGKUNGAN BELAJAR\", \"indikator\": \"Lingkungan belajar dirancang aman, inklusif, kolaboratif, dan memberi ruang bagi murid untuk aktif.\", \"keterkaitan\": \"Berkesadaran + Menggembirakan\", \"rekomendasi\": \"\"}, {\"no\": 33, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"J. KESINAMBUNGAN DAN KESIAPAN OBSERVASI\", \"indikator\": \"Alokasi waktu realistis untuk menjalankan Memahami, Mengaplikasi, dan Merefleksi.\", \"keterkaitan\": \"Keterlaksanaan 3M\", \"rekomendasi\": \"\"}, {\"no\": 34, \"skor\": 4, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"J. KESINAMBUNGAN DAN KESIAPAN OBSERVASI\", \"indikator\": \"Langkah pembelajaran, asesmen, media, dan sumber belajar konsisten satu sama lain.\", \"keterkaitan\": \"Koherensi perencanaan\", \"rekomendasi\": \"\"}, {\"no\": 35, \"skor\": 3, \"bukti\": \"\", \"catatan\": \"\", \"komponen\": \"J. KESINAMBUNGAN DAN KESIAPAN OBSERVASI\", \"indikator\": \"Terdapat bukti bahwa pembelajaran dirancang untuk menghasilkan pengalaman belajar yang mendalam, bukan sekadar penyampaian materi.\", \"keterkaitan\": \"Hakikat Pembelajaran Mendalam\", \"rekomendasi\": \"\"}]', 128, 140, 91.43, '2026-09-23 02:32:06', '2026-09-23 02:32:06');

-- --------------------------------------------------------

--
-- Table structure for table `pre_observation_konferensis`
--

CREATE TABLE `pre_observation_konferensis` (
  `id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED DEFAULT NULL,
  `teacher_id` bigint UNSIGNED DEFAULT NULL,
  `supervisor_id` bigint UNSIGNED DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `class_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observation_date` date NOT NULL,
  `items` json NOT NULL,
  `dokumentasi_foto` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `school_settings`
--

CREATE TABLE `school_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `npsn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_settings`
--

INSERT INTO `school_settings` (`id`, `school_name`, `npsn`, `address`, `logo_path`, `created_at`, `updated_at`) VALUES
(1, 'SMK QUEEN AL-FALAH', '20574699', 'Jl. Raya Kebanan-Ploso, Desa Ploso, Kecamatan Mojo, Kabupaten Kediri, Jawa Timur', 'logos/1789054293_logo_smk_queen.png', '2026-09-10 15:28:09', '2026-09-10 15:31:33');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('RryGEzmwuwR8j688n2vqexmpVzym64artUTX98Ap', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiN2J5dklpVnRYMTlnUTUzTm5wUGNWS0hKUE5HRU1CRGs3Q1lRdmNMcyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE2OiJhY3RpdmVfcGVyaW9kX2lkIjtpOjE7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wYXNjYS1zdXBlcnZpc2kvcmVrYXAiO319', 1790171574);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`) VALUES
(1, 'school_name', 'SMK QUEEN AL-FALAH'),
(2, 'address', 'Jl. Raya Mojo, Ploso, Kec. Mojo, Kabupaten Kediri, Jawa Timur 64162'),
(3, 'npsn', '20574699'),
(4, 'tahun_ajaran', NULL),
(5, 'semester', NULL),
(6, 'logo', 'logos/ll4oLCTRdeWIKCePmlEKdSNIrWOQ1B7GcGmf2BIn.png'),
(7, 'instrument_default_rows', '[{\"tahap\":\"A. KEGIATAN AWAL\",\"dimensi\":\"Berkesadaran\",\"indikator\":\"Guru membangun suasana belajar yang aman, nyaman, menghargai murid, dan menyiapkan murid secara mental untuk belajar.\"},{\"tahap\":\"A. KEGIATAN AWAL\",\"dimensi\":\"Bermakna\",\"indikator\":\"Guru mengaitkan tujuan\\/materi dengan pengalaman, kebutuhan, lingkungan, budaya, dunia kerja, atau fenomena nyata.\"},{\"tahap\":\"A. KEGIATAN AWAL\",\"dimensi\":\"Menggembirakan\",\"indikator\":\"Guru membangun rasa ingin tahu melalui apersepsi, pertanyaan pemantik, demonstrasi, atau aktivitas yang menarik.\"},{\"tahap\":\"A. KEGIATAN AWAL\",\"dimensi\":\"Tujuan belajar\",\"indikator\":\"Guru menyampaikan tujuan dan kriteria keberhasilan yang dipahami murid.\"},{\"tahap\":\"A. KEGIATAN AWAL\",\"dimensi\":\"Literasi\",\"indikator\":\"Tujuan\\/aktivitas belajar memuat kegiatan menemukan informasi, mengintegrasi\\/menginterpretasi, atau mengevaluasi\\/merefleksi informasi.\"},{\"tahap\":\"A. KEGIATAN AWAL\",\"dimensi\":\"Numerasi\",\"indikator\":\"Tujuan\\/aktivitas belajar memuat aktivitas numerasi sesuai konteks: memahami, menerapkan, menalar\\/menyelesaikan masalah, atau mengambil keputusan berbasis data.\"},{\"tahap\":\"B. KEGIATAN INTI – MEMAHAMI\",\"dimensi\":\"Memahami\",\"indikator\":\"Guru memfasilitasi murid membangun pemahaman konsep secara aktif dari berbagai sumber.\"},{\"tahap\":\"B. KEGIATAN INTI – MEMAHAMI\",\"dimensi\":\"Berkesadaran\",\"indikator\":\"Murid terlibat aktif: berpikir, bertanya, berdiskusi, mendengarkan, dan memantau pemahamannya.\"},{\"tahap\":\"B. KEGIATAN INTI – MEMAHAMI\",\"dimensi\":\"Bermakna\",\"indikator\":\"Materi dikaitkan dengan pengalaman, lingkungan, budaya, kebutuhan, atau dunia kerja.\"},{\"tahap\":\"B. KEGIATAN INTI – MEMAHAMI\",\"dimensi\":\"Menggembirakan\",\"indikator\":\"Metode\\/media\\/sumber belajar mendorong antusiasme, rasa ingin tahu, dan partisipasi murid.\"},{\"tahap\":\"B. KEGIATAN INTI – MEMAHAMI\",\"dimensi\":\"Literasi\",\"indikator\":\"Murid membaca\\/menelaah berbagai sumber (teks, artikel, infografik, grafik, media digital) sebagai dasar belajar.\"},{\"tahap\":\"B. KEGIATAN INTI – MEMAHAMI\",\"dimensi\":\"Literasi\",\"indikator\":\"Guru memfasilitasi murid menemukan informasi penting dan membedakan informasi yang relevan\\/tidak relevan.\"},{\"tahap\":\"B. KEGIATAN INTI – MEMAHAMI\",\"dimensi\":\"Literasi\",\"indikator\":\"Murid mengintegrasi dan menginterpretasi informasi dari satu atau lebih sumber untuk membangun pemahaman.\"},{\"tahap\":\"B. KEGIATAN INTI – MEMAHAMI\",\"dimensi\":\"Numerasi\",\"indikator\":\"Guru menggunakan data\\/angka\\/tabel\\/diagram\\/bagan\\/grafik atau representasi lain yang relevan dengan materi.\"},{\"tahap\":\"B. KEGIATAN INTI – MEMAHAMI\",\"dimensi\":\"Numerasi\",\"indikator\":\"Murid memahami makna angka, besaran, pola, satuan, data, atau representasi matematika dalam konteks pembelajaran.\"},{\"tahap\":\"B. KEGIATAN INTI – MEMAHAMI\",\"dimensi\":\"Numerasi\",\"indikator\":\"Murid membandingkan, mengelompokkan, memperkirakan, atau menafsirkan data\\/informasi kuantitatif.\"},{\"tahap\":\"C. KEGIATAN INTI – MENGAPLIKASI\",\"dimensi\":\"Mengaplikasi\",\"indikator\":\"Murid menerapkan konsep pada penyelesaian masalah nyata, kontekstual, atau terkait dunia kerja.\"},{\"tahap\":\"C. KEGIATAN INTI – MENGAPLIKASI\",\"dimensi\":\"Berkesadaran\",\"indikator\":\"Murid bekerja mandiri\\/kolaboratif dengan tanggung jawab, mengelola strategi, waktu, dan sumber belajar.\"},{\"tahap\":\"C. KEGIATAN INTI – MENGAPLIKASI\",\"dimensi\":\"Bermakna\",\"indikator\":\"Aktivitas menghasilkan solusi, produk, layanan, keputusan, atau karya yang bermanfaat.\"},{\"tahap\":\"C. KEGIATAN INTI – MENGAPLIKASI\",\"dimensi\":\"Menggembirakan\",\"indikator\":\"Guru memberikan tantangan\\/interaksi yang memotivasi murid mencoba, mengeksplorasi, dan memperbaiki solusi.\"},{\"tahap\":\"C. KEGIATAN INTI – MENGAPLIKASI\",\"dimensi\":\"Literasi\",\"indikator\":\"Murid menggunakan informasi dari teks\\/sumber untuk menentukan strategi penyelesaian masalah.\"},{\"tahap\":\"C. KEGIATAN INTI – MENGAPLIKASI\",\"dimensi\":\"Literasi\",\"indikator\":\"Murid menganalisis, mengevaluasi kredibilitas\\/relevansi informasi, dan menghubungkan informasi dengan konteks masalah.\"},{\"tahap\":\"C. KEGIATAN INTI – MENGAPLIKASI\",\"dimensi\":\"Numerasi\",\"indikator\":\"Terdapat pemecahan masalah kontekstual yang melibatkan menghitung, mengukur, membandingkan, memperkirakan, menafsirkan, atau mengambil keputusan.\"},{\"tahap\":\"C. KEGIATAN INTI – MENGAPLIKASI\",\"dimensi\":\"Numerasi\",\"indikator\":\"Murid menggunakan data, tabel, grafik, diagram, rumus, alat ukur, atau representasi matematika yang sesuai.\"},{\"tahap\":\"C. KEGIATAN INTI – MENGAPLIKASI\",\"dimensi\":\"Numerasi\",\"indikator\":\"Murid menjelaskan strategi\\/langkah penyelesaian dan alasan pemilihan strategi.\"},{\"tahap\":\"C. KEGIATAN INTI – MENGAPLIKASI\",\"dimensi\":\"Numerasi\",\"indikator\":\"Murid menggunakan alat bantu digital\\/non-digital secara tepat untuk mengolah informasi numerik.\"},{\"tahap\":\"D. KEGIATAN INTI – MEREFLEKSI\",\"dimensi\":\"Merefleksi\",\"indikator\":\"Guru memfasilitasi murid merefleksi proses dan hasil belajar.\"},{\"tahap\":\"D. KEGIATAN INTI – MEREFLEKSI\",\"dimensi\":\"Berkesadaran\",\"indikator\":\"Murid mengenali kekuatan, kesulitan, kesalahan, dan strategi perbaikan belajarnya.\"},{\"tahap\":\"D. KEGIATAN INTI – MEREFLEKSI\",\"dimensi\":\"Bermakna\",\"indikator\":\"Murid menjelaskan manfaat hasil belajar bagi diri, masyarakat, lingkungan, atau dunia kerja.\"},{\"tahap\":\"D. KEGIATAN INTI – MEREFLEKSI\",\"dimensi\":\"Menggembirakan\",\"indikator\":\"Umpan balik guru membangun motivasi, rasa percaya diri, dan kemauan untuk memperbaiki diri.\"},{\"tahap\":\"D. KEGIATAN INTI – MEREFLEKSI\",\"dimensi\":\"Literasi\",\"indikator\":\"Murid menuliskan\\/mengomunikasikan kembali informasi atau hasil belajar dalam bentuk rangkuman, laporan, jurnal, peta konsep, atau refleksi.\"},{\"tahap\":\"D. KEGIATAN INTI – MEREFLEKSI\",\"dimensi\":\"Literasi\",\"indikator\":\"Murid mengevaluasi dan merefleksi informasi\\/argumen serta menyampaikan alasan atau bukti pendukung.\"},{\"tahap\":\"D. KEGIATAN INTI – MEREFLEKSI\",\"dimensi\":\"Numerasi\",\"indikator\":\"Murid memeriksa kewajaran hasil, menafsirkan makna hasil, dan merefleksi strategi numerasi yang digunakan.\"},{\"tahap\":\"D. KEGIATAN INTI – MEREFLEKSI\",\"dimensi\":\"Numerasi\",\"indikator\":\"Murid mengomunikasikan hasil analisis data\\/perhitungan secara lisan, tulisan, visual, atau digital.\"},{\"tahap\":\"E. KEGIATAN PENUTUP\",\"dimensi\":\"Penguatan\",\"indikator\":\"Guru dan murid menyimpulkan pembelajaran berdasarkan bukti hasil belajar.\"},{\"tahap\":\"E. KEGIATAN PENUTUP\",\"dimensi\":\"Umpan balik\",\"indikator\":\"Guru memberikan penguatan terhadap pencapaian murid dan umpan balik yang spesifik.\"},{\"tahap\":\"E. KEGIATAN PENUTUP\",\"dimensi\":\"Tindak lanjut\",\"indikator\":\"Guru memberikan tindak lanjut kontekstual untuk memperdalam atau memperbaiki pembelajaran.\"},{\"tahap\":\"E. KEGIATAN PENUTUP\",\"dimensi\":\"Suasana positif\",\"indikator\":\"Guru menutup pembelajaran secara positif, apresiatif, dan memotivasi.\"},{\"tahap\":\"E. KEGIATAN PENUTUP\",\"dimensi\":\"Literasi & Numerasi\",\"indikator\":\"Asesmen\\/tindak lanjut memuat kesempatan memperkuat literasi dan\\/atau numerasi sesuai kebutuhan murid.\"},{\"tahap\":\"F. ASESMEN TERINTEGRASI\",\"dimensi\":\"Literasi\",\"indikator\":\"Guru melakukan asesmen yang mengukur menemukan informasi; mengintegrasi\\/menginterpretasi; serta mengevaluasi\\/merefleksi.\"},{\"tahap\":\"F. ASESMEN TERINTEGRASI\",\"dimensi\":\"Numerasi\",\"indikator\":\"Guru melakukan asesmen yang mengukur memahami; menerapkan; menalar\\/memecahkan masalah; serta merefleksi.\"},{\"tahap\":\"F. ASESMEN TERINTEGRASI\",\"dimensi\":\"Autentik\",\"indikator\":\"Asesmen menggunakan teks, data, grafik, tabel, situasi nyata, produk, kinerja, atau masalah kontekstual yang relevan.\"},{\"tahap\":\"F. ASESMEN TERINTEGRASI\",\"dimensi\":\"Umpan balik\",\"indikator\":\"Guru memberikan umpan balik berdasarkan hasil asesmen dan memanfaatkannya untuk memperbaiki strategi penguatan literasi\\/numerasi.\"},{\"tahap\":\"F. ASESMEN TERINTEGRASI\",\"dimensi\":\"Tindak lanjut\",\"indikator\":\"Guru merancang tindak lanjut\\/remedial\\/pengayaan berdasarkan kebutuhan literasi dan numerasi murid.\"}]'),
(9, 'pre_observation_default_rows', '[{\"komponen\":\"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\",\"indikator\":\"Identitas pembelajaran, fase\\/kelas, alokasi waktu, dan konteks pembelajaran ditulis jelas dan konsisten.\",\"keterkaitan\":\"Kesiapan pembelajaran dan konteks bermakna\"},{\"komponen\":\"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\",\"indikator\":\"CP\\/kompetensi yang menjadi acuan pembelajaran tercantum dan relevan dengan materi.\",\"keterkaitan\":\"Keselarasan CP–TP\"},{\"komponen\":\"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\",\"indikator\":\"Tujuan pembelajaran dirumuskan spesifik, terukur, relevan, dan berorientasi pada kemampuan murid.\",\"keterkaitan\":\"Tujuan sebagai arah pengalaman belajar mendalam\"},{\"komponen\":\"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\",\"indikator\":\"Tujuan pembelajaran memuat tuntutan literasi yang relevan: menemukan informasi, mengintegrasi\\/menginterpretasi, mengevaluasi\\/merefleksi.\",\"keterkaitan\":\"Literasi\"},{\"komponen\":\"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\",\"indikator\":\"Tujuan pembelajaran memuat tuntutan numerasi yang relevan: memahami, menerapkan, menalar\\/memecahkan masalah, atau mengambil keputusan.\",\"keterkaitan\":\"Numerasi\"},{\"komponen\":\"A. IDENTITAS, CP, DAN TUJUAN PEMBELAJARAN\",\"indikator\":\"8 Dimensi Profil lulusan\",\"keterkaitan\":\"8 Dimensi Profil lulusan\"},{\"komponen\":\"B. PERANCANGAN PEMBELAJARAN MENDALAM\",\"indikator\":\"RPP\\/RPM menunjukkan prinsip Berkesadaran: tujuan, kriteria keberhasilan, kesiapan belajar, pilihan\\/agensi murid, dan kesadaran terhadap proses belajar.\",\"keterkaitan\":\"BBM – Berkesadaran\"},{\"komponen\":\"B. PERANCANGAN PEMBELAJARAN MENDALAM\",\"indikator\":\"RPP\\/RPM menunjukkan prinsip Bermakna: materi\\/aktivitas dikaitkan dengan pengalaman, kehidupan nyata, lingkungan, budaya, atau dunia kerja.\",\"keterkaitan\":\"BBM – Bermakna\"},{\"komponen\":\"B. PERANCANGAN PEMBELAJARAN MENDALAM\",\"indikator\":\"RPP\\/RPM menunjukkan prinsip Menggembirakan: aktivitas menantang, interaktif, aman, positif, dan memotivasi murid.\",\"keterkaitan\":\"BBM – Menggembirakan\"},{\"komponen\":\"B. PERANCANGAN PEMBELAJARAN MENDALAM\",\"indikator\":\"Alur kegiatan dirancang melalui pengalaman belajar Memahami → Mengaplikasi → Merefleksi.\",\"keterkaitan\":\"3M\"},{\"komponen\":\"B. PERANCANGAN PEMBELAJARAN MENDALAM\",\"indikator\":\"Aktivitas murid lebih dominan daripada aktivitas guru dan memberi ruang berpikir, bertanya, berdiskusi, mencoba, serta berkarya.\",\"keterkaitan\":\"Pembelajaran berpusat pada murid\"},{\"komponen\":\"C. TAHAP MEMAHAMI\",\"indikator\":\"Kegiatan awal\\/inti memberi kesempatan murid mengakses berbagai sumber informasi dan membangun pemahaman konsep.\",\"keterkaitan\":\"3M – Memahami + Literasi\"},{\"komponen\":\"C. TAHAP MEMAHAMI\",\"indikator\":\"Tersedia aktivitas membaca\\/menelaah teks, gambar, infografik, grafik, tabel, data, video, atau sumber digital yang relevan.\",\"keterkaitan\":\"Literasi\"},{\"komponen\":\"C. TAHAP MEMAHAMI\",\"indikator\":\"Pertanyaan\\/tugas mendorong murid menemukan informasi penting, menghubungkan informasi, dan menafsirkan makna.\",\"keterkaitan\":\"Literasi kritis\"},{\"komponen\":\"C. TAHAP MEMAHAMI\",\"indikator\":\"Tersedia data\\/angka\\/tabel\\/diagram\\/pola atau konteks kuantitatif yang relevan dengan materi.\",\"keterkaitan\":\"Numerasi\"},{\"komponen\":\"C. TAHAP MEMAHAMI\",\"indikator\":\"Aktivitas mendorong murid memahami informasi kuantitatif sebelum melakukan perhitungan\\/pemecahan masalah.\",\"keterkaitan\":\"Numerasi – Memahami\"},{\"komponen\":\"D. TAHAP MENGAPLIKASI\",\"indikator\":\"Murid menerapkan konsep pada masalah nyata, kontekstual, autentik, atau situasi dunia kerja.\",\"keterkaitan\":\"3M – Mengaplikasi + Bermakna\"},{\"komponen\":\"D. TAHAP MENGAPLIKASI\",\"indikator\":\"Murid menggunakan informasi dari berbagai sumber untuk menyelesaikan tugas\\/masalah.\",\"keterkaitan\":\"Literasi\"},{\"komponen\":\"D. TAHAP MENGAPLIKASI\",\"indikator\":\"Murid mengolah, membandingkan, menafsirkan, atau mengevaluasi informasi sebelum mengambil kesimpulan\\/keputusan.\",\"keterkaitan\":\"Literasi\"},{\"komponen\":\"D. TAHAP MENGAPLIKASI\",\"indikator\":\"Murid menggunakan perhitungan, estimasi, pengukuran, data, tabel, grafik, diagram, pola, atau representasi matematis sesuai kebutuhan.\",\"keterkaitan\":\"Numerasi\"},{\"komponen\":\"D. TAHAP MENGAPLIKASI\",\"indikator\":\"Murid menjelaskan strategi penyelesaian masalah dan alasan atas jawaban\\/keputusan yang dibuat.\",\"keterkaitan\":\"Numerasi – Menalar\"},{\"komponen\":\"D. TAHAP MENGAPLIKASI\",\"indikator\":\"RPP\\/RPM menyediakan aktivitas mandiri dan\\/atau kolaboratif dengan pembagian peran dan tanggung jawab yang jelas.\",\"keterkaitan\":\"Berkesadaran + Kolaborasi\"},{\"komponen\":\"E. TAHAP MEREFLEKSI\",\"indikator\":\"Murid merefleksikan proses, strategi, hasil, kesulitan, dan rencana perbaikan belajar.\",\"keterkaitan\":\"3M – Merefleksi\"},{\"komponen\":\"E. TAHAP MEREFLEKSI\",\"indikator\":\"Refleksi mengajak murid menilai kualitas informasi\\/sumber yang digunakan dan manfaat hasil belajar.\",\"keterkaitan\":\"Literasi – Evaluasi\\/Refleksi\"},{\"komponen\":\"E. TAHAP MEREFLEKSI\",\"indikator\":\"Refleksi dapat meminta murid menafsirkan hasil perhitungan\\/data dan menilai kewajaran hasil atau keputusan.\",\"keterkaitan\":\"Numerasi – Refleksi\"},{\"komponen\":\"E. TAHAP MEREFLEKSI\",\"indikator\":\"Tersedia pertanyaan reflektif yang menghubungkan pembelajaran dengan kehidupan nyata\\/dunia kerja.\",\"keterkaitan\":\"Bermakna\"},{\"komponen\":\"F. STRATEGI PENGUATAN LITERASI\",\"indikator\":\"Sumber belajar mencakup teks\\/media yang beragam, relevan, kredibel, dan sesuai tingkat kemampuan murid.\",\"keterkaitan\":\"Literasi\"},{\"komponen\":\"F. STRATEGI PENGUATAN LITERASI\",\"indikator\":\"Ada kegiatan membaca\\/menelaah sebelum diskusi atau pemecahan masalah.\",\"keterkaitan\":\"Literasi\"},{\"komponen\":\"F. STRATEGI PENGUATAN LITERASI\",\"indikator\":\"Murid mengolah informasi dalam bentuk catatan, rangkuman, peta konsep, laporan, presentasi, atau produk komunikasi.\",\"keterkaitan\":\"Literasi\"},{\"komponen\":\"F. STRATEGI PENGUATAN LITERASI\",\"indikator\":\"Murid mengomunikasikan pemahaman\\/hasil analisis secara lisan, tulisan, visual, atau digital.\",\"keterkaitan\":\"Literasi\"},{\"komponen\":\"G. STRATEGI PENGUATAN NUMERASI\",\"indikator\":\"Sumber belajar memuat data atau informasi kuantitatif yang autentik sesuai konteks pembelajaran.\",\"keterkaitan\":\"Numerasi\"},{\"komponen\":\"G. STRATEGI PENGUATAN NUMERASI\",\"indikator\":\"Ada masalah kontekstual yang menuntut murid menggunakan konsep numerasi untuk memecahkan masalah.\",\"keterkaitan\":\"Numerasi\"},{\"komponen\":\"G. STRATEGI PENGUATAN NUMERASI\",\"indikator\":\"Murid menggunakan alat bantu digital\\/non-digital yang sesuai untuk mengolah atau menyajikan data\\/informasi.\",\"keterkaitan\":\"Numerasi\"},{\"komponen\":\"G. STRATEGI PENGUATAN NUMERASI\",\"indikator\":\"Murid menyajikan atau mengomunikasikan hasil pengolahan data\\/perhitungan dengan alasan yang logis.\",\"keterkaitan\":\"Numerasi\"},{\"komponen\":\"H. ASESMEN\",\"indikator\":\"Asesmen selaras dengan CP\\/TP dan mengukur kompetensi yang benar-benar ditargetkan.\",\"keterkaitan\":\"Asesmen selaras\"},{\"komponen\":\"H. ASESMEN\",\"indikator\":\"Asesmen formatif dirancang untuk memperoleh informasi proses belajar dan memberi umpan balik.\",\"keterkaitan\":\"Asesmen untuk pembelajaran\"},{\"komponen\":\"H. ASESMEN\",\"indikator\":\"Asesmen memuat bukti kemampuan literasi yang relevan dengan tujuan pembelajaran.\",\"keterkaitan\":\"Asesmen Literasi\"},{\"komponen\":\"H. ASESMEN\",\"indikator\":\"Asesmen memuat bukti kemampuan numerasi yang relevan dengan tujuan pembelajaran.\",\"keterkaitan\":\"Asesmen Numerasi\"},{\"komponen\":\"H. ASESMEN\",\"indikator\":\"Kriteria\\/rubrik keberhasilan jelas dan memungkinkan penilaian autentik terhadap proses dan produk\\/kinerja.\",\"keterkaitan\":\"Asesmen autentik\"},{\"komponen\":\"H. ASESMEN\",\"indikator\":\"Hasil asesmen dirancang untuk menjadi dasar tindak lanjut, remedial, pengayaan, atau perbaikan strategi pembelajaran.\",\"keterkaitan\":\"Tindak lanjut\"},{\"komponen\":\"I. DIFERENSIASI, MEDIA, DAN LINGKUNGAN BELAJAR\",\"indikator\":\"RPP\\/RPM mempertimbangkan kesiapan, kebutuhan, minat, atau karakteristik murid.\",\"keterkaitan\":\"Pembelajaran adaptif\"},{\"komponen\":\"I. DIFERENSIASI, MEDIA, DAN LINGKUNGAN BELAJAR\",\"indikator\":\"Media\\/LKM\\/LKPD mendukung murid memahami, mengolah, dan mengomunikasikan informasi serta data.\",\"keterkaitan\":\"Literasi-Numerasi\"},{\"komponen\":\"I. DIFERENSIASI, MEDIA, DAN LINGKUNGAN BELAJAR\",\"indikator\":\"Lingkungan belajar dirancang aman, inklusif, kolaboratif, dan memberi ruang bagi murid untuk aktif.\",\"keterkaitan\":\"Berkesadaran + Menggembirakan\"},{\"komponen\":\"J. KESINAMBUNGAN DAN KESIAPAN OBSERVASI\",\"indikator\":\"Alokasi waktu realistis untuk menjalankan Memahami, Mengaplikasi, dan Merefleksi.\",\"keterkaitan\":\"Keterlaksanaan 3M\"},{\"komponen\":\"J. KESINAMBUNGAN DAN KESIAPAN OBSERVASI\",\"indikator\":\"Langkah pembelajaran, asesmen, media, dan sumber belajar konsisten satu sama lain.\",\"keterkaitan\":\"Koherensi perencanaan\"},{\"komponen\":\"J. KESINAMBUNGAN DAN KESIAPAN OBSERVASI\",\"indikator\":\"Terdapat bukti bahwa pembelajaran dirancang untuk menghasilkan pengalaman belajar yang mendalam, bukan sekadar penyampaian materi.\",\"keterkaitan\":\"Hakikat Pembelajaran Mendalam\"}]'),
(11, 'observasi_3m_default_rows', '[{\"pengalaman_belajar\":\"Memahami\",\"indikator\":\"Murid mengetahui tujuan pembelajaran\"},{\"pengalaman_belajar\":\"Memahami\",\"indikator\":\"Murid menghubungkan pengetahuan awal dengan materi\"},{\"pengalaman_belajar\":\"Memahami\",\"indikator\":\"Murid memperoleh kesempatan mengeksplorasi konsep\"},{\"pengalaman_belajar\":\"Memahami\",\"indikator\":\"Murid melakukan pengecekan pemahaman\"},{\"pengalaman_belajar\":\"Mengaplikasi\",\"indikator\":\"Murid menerapkan pengetahuan\\/keterampilan\"},{\"pengalaman_belajar\":\"Mengaplikasi\",\"indikator\":\"Murid menyelesaikan masalah\\/kasus\"},{\"pengalaman_belajar\":\"Mengaplikasi\",\"indikator\":\"Murid melakukan praktik\\/unjuk kerja\"},{\"pengalaman_belajar\":\"Mengaplikasi\",\"indikator\":\"Murid menghasilkan karya\\/produk\\/solusi\"},{\"pengalaman_belajar\":\"Merefleksi\",\"indikator\":\"Murid merefleksikan proses belajarnya\"},{\"pengalaman_belajar\":\"Merefleksi\",\"indikator\":\"Murid mengidentifikasi kesulitan yang dialami\"},{\"pengalaman_belajar\":\"Merefleksi\",\"indikator\":\"Murid menentukan hal yang perlu diperbaiki\"},{\"pengalaman_belajar\":\"Merefleksi\",\"indikator\":\"Guru menggunakan hasil refleksi untuk tindak lanjut\"}]'),
(13, 'observasi_bbm_default_rows', '[{\"prinsip_bbm\":\"Berkesadaran\",\"indikator\":\"Murid mengetahui tujuan pembelajaran\"},{\"prinsip_bbm\":\"Berkesadaran\",\"indikator\":\"Murid memahami kriteria keberhasilan\"},{\"prinsip_bbm\":\"Berkesadaran\",\"indikator\":\"Murid mengetahui perkembangan belajarnya\"},{\"prinsip_bbm\":\"Berkesadaran\",\"indikator\":\"Murid melakukan refleksi\"},{\"prinsip_bbm\":\"Bermakna\",\"indikator\":\"Materi dikaitkan dengan kehidupan nyata\"},{\"prinsip_bbm\":\"Bermakna\",\"indikator\":\"Materi dikaitkan dengan pengalaman murid\"},{\"prinsip_bbm\":\"Bermakna\",\"indikator\":\"Materi dikaitkan dengan dunia kerja\"},{\"prinsip_bbm\":\"Bermakna\",\"indikator\":\"Murid memecahkan masalah nyata\"},{\"prinsip_bbm\":\"Menggembirakan\",\"indikator\":\"Suasana pembelajaran aman dan nyaman\"},{\"prinsip_bbm\":\"Menggembirakan\",\"indikator\":\"Murid aktif dan antusias\"},{\"prinsip_bbm\":\"Menggembirakan\",\"indikator\":\"Guru memberikan apresiasi\"},{\"prinsip_bbm\":\"Menggembirakan\",\"indikator\":\"Interaksi guru-murid positif\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `code`, `created_at`, `updated_at`) VALUES
(11, 'matematika 1', 'mtk', '2026-09-14 12:54:47', '2026-09-14 12:54:47'),
(12, 'Produk kewirausahaan', 'pkk', '2026-09-14 12:54:47', '2026-09-14 12:54:47'),
(13, 'bahasa indonesia', 'bi', '2026-09-14 12:54:47', '2026-09-14 12:54:47');

-- --------------------------------------------------------

--
-- Table structure for table `supervisions`
--

CREATE TABLE `supervisions` (
  `id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED DEFAULT NULL,
  `teacher_id` bigint UNSIGNED NOT NULL,
  `supervisor_id` bigint UNSIGNED NOT NULL,
  `subject_id` bigint UNSIGNED NOT NULL,
  `class_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule_date` date NOT NULL,
  `status` enum('Scheduled','Completed','Cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Scheduled',
  `approval_status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supervisions`
--

INSERT INTO `supervisions` (`id`, `period_id`, `teacher_id`, `supervisor_id`, `subject_id`, `class_name`, `schedule_date`, `status`, `approval_status`, `notes`, `created_at`, `updated_at`, `approved_by`, `approved_at`) VALUES
(3, NULL, 23, 22, 12, 'xii tjkt 2', '2026-09-16', 'Scheduled', 'pending', NULL, '2026-09-14 12:58:27', '2026-09-14 12:58:27', NULL, NULL),
(5, NULL, 25, 21, 11, 'sasas', '2026-09-17', 'Completed', 'pending', NULL, '2026-09-14 13:05:32', '2026-09-14 13:06:05', NULL, NULL),
(7, NULL, 26, 21, 11, 'xi tjkt 5', '2026-09-17', 'Completed', 'pending', NULL, '2026-09-15 13:28:17', '2026-09-15 13:29:49', NULL, NULL),
(13, 1, 27, 28, 12, 'xii tjk 4', '2026-09-23', 'Scheduled', 'pending', NULL, '2026-09-23 13:33:42', '2026-09-23 13:33:42', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teaching_documents`
--

CREATE TABLE `teaching_documents` (
  `id` bigint UNSIGNED NOT NULL,
  `period_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `document_package_id` bigint UNSIGNED DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `document_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'RPP',
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','reviewed','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `review_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reviewed_by` bigint UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teaching_documents`
--

INSERT INTO `teaching_documents` (`id`, `period_id`, `user_id`, `document_package_id`, `subject_id`, `title`, `description`, `document_type`, `file_path`, `file_name`, `file_size`, `status`, `review_notes`, `reviewed_by`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(5, 1, 21, NULL, 12, 'rpm', 'ok', 'RPM / Modul Ajar Deep Learning', 'teaching-documents/2swJgDDoXq8jKdZDzI156nT7jgaTT1fersD4TvFy.zip', 'Dokumen Kelengkapan KC 2026-20260505T060422Z-3-001.zip', '1.74 MB', 'approved', 'bisa dilaksanakan observasi di kelas', 27, '2026-09-23 02:17:02', '2026-09-15 14:14:36', '2026-09-23 02:17:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'guru',
  `nip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mata_pelajaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `nip`, `mata_pelajaran`, `avatar`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Baru', 'admin@gmail.com', '$2y$12$4iYpa9lqvPJH6oQ6K53XsuNG.nLxrNNBiHH/e4jn4AMkLS9MCrx4y', 'admin', NULL, NULL, NULL, NULL, NULL, '2026-09-07 11:51:12', '2026-09-14 12:35:34'),
(21, 'agus', 'agus@gmail.com', '$2y$12$aiwgbRssQ.WisrTf8tMfE.jZj6ZIqzKablPuSCKFtOMxi7PTSxvVG', 'supervisor', '-', 'MM', NULL, NULL, NULL, '2026-09-14 12:54:54', '2026-09-15 11:55:01'),
(22, 'Irkham Hendi, S.Pd', 'kepala@gmail.com', '$2y$12$uZO89azSnICg9NsKB/3ScuRwLQAdp99Sq9zIxUbwtWJ0lDyKKrK/a', 'kepala_sekolah', '-', NULL, NULL, NULL, NULL, '2026-09-14 12:54:55', '2026-09-14 13:13:01'),
(23, 'agus tris', 'agussutrisno1994@gmail.com', '$2y$12$/I4HxbaKS5lg.kF11I0AQe5sMYL5OfsbWdvPDTYmHpMnVZ1ZUS3ZS', 'guru', '-', 'PAI', NULL, NULL, NULL, '2026-09-14 12:54:55', '2026-09-22 04:19:52'),
(24, 'Lukman Hakim, S.HI', 'pengawas@gmail.com', '$2y$12$8.t9D7Rs5kXzt2b.rVeGaekq.jiXxLSLIlvItK3wFgtqnxjX13IzW', 'pengawas', '-', NULL, NULL, NULL, NULL, '2026-09-14 12:54:55', '2026-09-14 12:54:55'),
(25, 'cobaa', 'haddi@gmail.com', '$2y$12$bDI14he5P4nZyp6AgjGEyemZ9FXbF1wz.5xQ99NXvq2Fofslb5nyK', 'guru', '', NULL, NULL, NULL, NULL, '2026-09-14 12:54:56', '2026-09-14 12:54:56'),
(26, 'oden', 'oden@gmail.com', '$2y$12$OIro3UU055JP3M106aeIeOXdrCcaAIHJKLi5Xgph1MSArs5nqVS4.', 'guru', '', 'BING', NULL, NULL, NULL, '2026-09-14 12:54:56', '2026-09-14 12:54:56'),
(27, 'IDA ROSYIDAH', 'idarosyidah32@guru.smk.belajar.id', '$2y$12$ZkinWClXl.7MvFmyinm5K.xbCJbx1CSyjiTULo6Khc/.u5/vMpCC.', 'supervisor', '-', NULL, NULL, NULL, NULL, '2026-09-23 02:13:49', '2026-09-23 02:13:49'),
(28, 'Harlinvia Maulitha Indahsari', 'harlinvia.maulitha7@guru.smk.belajar.id', '$2y$12$PVzbamffGX9.DnzsR.OKaeuXBGBebUXclf74gbQODt9CWg76qDRoy', 'supervisor', NULL, NULL, NULL, NULL, NULL, '2026-09-23 02:34:10', '2026-09-23 02:35:24');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role`, `created_at`, `updated_at`) VALUES
(42, 1, 'admin', '2026-09-14 12:35:34', '2026-09-14 12:35:34'),
(46, 24, 'pengawas', '2026-09-14 12:54:55', '2026-09-14 12:54:55'),
(47, 25, 'guru', '2026-09-14 12:54:56', '2026-09-14 12:54:56'),
(48, 26, 'guru', '2026-09-14 12:54:56', '2026-09-14 12:54:56'),
(50, 22, 'kepala_sekolah', '2026-09-14 13:13:01', '2026-09-14 13:13:01'),
(51, 21, 'supervisor', '2026-09-15 11:55:01', '2026-09-15 11:55:01'),
(52, 21, 'guru', '2026-09-15 11:55:01', '2026-09-15 11:55:01'),
(53, 23, 'guru', '2026-09-22 04:19:52', '2026-09-22 04:19:52'),
(54, 27, 'supervisor', '2026-09-23 02:13:49', '2026-09-23 02:13:49'),
(55, 27, 'guru', '2026-09-23 02:13:49', '2026-09-23 02:13:49'),
(57, 28, 'supervisor', '2026-09-23 02:35:24', '2026-09-23 02:35:24'),
(58, 28, 'guru', '2026-09-23 02:35:24', '2026-09-23 02:35:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `document_packages`
--
ALTER TABLE `document_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_packages_user_id_foreign` (`user_id`),
  ADD KEY `document_packages_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `instruments`
--
ALTER TABLE `instruments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `instruments_period_id_foreign` (`period_id`),
  ADD KEY `instruments_teacher_id_foreign` (`teacher_id`),
  ADD KEY `instruments_supervisor_id_foreign` (`supervisor_id`),
  ADD KEY `instruments_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `observations`
--
ALTER TABLE `observations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `observations_supervision_id_foreign` (`supervision_id`),
  ADD KEY `observations_teacher_id_foreign` (`teacher_id`),
  ADD KEY `observations_supervisor_id_foreign` (`supervisor_id`),
  ADD KEY `observations_subject_id_foreign` (`subject_id`),
  ADD KEY `observations_approved_by_foreign` (`approved_by`),
  ADD KEY `observations_period_id_foreign` (`period_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `periods`
--
ALTER TABLE `periods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `periods_tahun_ajaran_semester_unique` (`tahun_ajaran`,`semester`);

--
-- Indexes for table `post_supervisions`
--
ALTER TABLE `post_supervisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_supervisions_period_id_foreign` (`period_id`),
  ADD KEY `post_supervisions_teacher_id_foreign` (`teacher_id`),
  ADD KEY `post_supervisions_supervisor_id_foreign` (`supervisor_id`),
  ADD KEY `post_supervisions_subject_id_foreign` (`subject_id`),
  ADD KEY `post_supervisions_bagian_period_id_index` (`bagian`,`period_id`);

--
-- Indexes for table `pre_observations`
--
ALTER TABLE `pre_observations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pre_observations_period_id_foreign` (`period_id`),
  ADD KEY `pre_observations_teacher_id_foreign` (`teacher_id`),
  ADD KEY `pre_observations_supervisor_id_foreign` (`supervisor_id`),
  ADD KEY `pre_observations_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `pre_observation_konferensis`
--
ALTER TABLE `pre_observation_konferensis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pre_observation_konferensis_period_id_foreign` (`period_id`),
  ADD KEY `pre_observation_konferensis_teacher_id_foreign` (`teacher_id`),
  ADD KEY `pre_observation_konferensis_supervisor_id_foreign` (`supervisor_id`),
  ADD KEY `pre_observation_konferensis_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `school_settings`
--
ALTER TABLE `school_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supervisions`
--
ALTER TABLE `supervisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supervisions_teacher_id_foreign` (`teacher_id`),
  ADD KEY `supervisions_supervisor_id_foreign` (`supervisor_id`),
  ADD KEY `supervisions_subject_id_foreign` (`subject_id`),
  ADD KEY `supervisions_approved_by_foreign` (`approved_by`),
  ADD KEY `supervisions_period_id_foreign` (`period_id`);

--
-- Indexes for table `teaching_documents`
--
ALTER TABLE `teaching_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teaching_documents_user_id_foreign` (`user_id`),
  ADD KEY `teaching_documents_subject_id_foreign` (`subject_id`),
  ADD KEY `teaching_documents_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `teaching_documents_document_package_id_foreign` (`document_package_id`),
  ADD KEY `teaching_documents_period_id_foreign` (`period_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_roles_user_id_role_unique` (`user_id`,`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `document_packages`
--
ALTER TABLE `document_packages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instruments`
--
ALTER TABLE `instruments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `observations`
--
ALTER TABLE `observations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `periods`
--
ALTER TABLE `periods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `post_supervisions`
--
ALTER TABLE `post_supervisions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pre_observations`
--
ALTER TABLE `pre_observations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pre_observation_konferensis`
--
ALTER TABLE `pre_observation_konferensis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_settings`
--
ALTER TABLE `school_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `supervisions`
--
ALTER TABLE `supervisions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `teaching_documents`
--
ALTER TABLE `teaching_documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `document_packages`
--
ALTER TABLE `document_packages`
  ADD CONSTRAINT `document_packages_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `document_packages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `instruments`
--
ALTER TABLE `instruments`
  ADD CONSTRAINT `instruments_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `instruments_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `instruments_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `instruments_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `observations`
--
ALTER TABLE `observations`
  ADD CONSTRAINT `observations_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `observations_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `observations_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `observations_supervision_id_foreign` FOREIGN KEY (`supervision_id`) REFERENCES `supervisions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `observations_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `observations_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_supervisions`
--
ALTER TABLE `post_supervisions`
  ADD CONSTRAINT `post_supervisions_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_supervisions_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `post_supervisions_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `post_supervisions_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pre_observations`
--
ALTER TABLE `pre_observations`
  ADD CONSTRAINT `pre_observations_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pre_observations_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pre_observations_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pre_observations_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pre_observation_konferensis`
--
ALTER TABLE `pre_observation_konferensis`
  ADD CONSTRAINT `pre_observation_konferensis_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pre_observation_konferensis_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pre_observation_konferensis_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pre_observation_konferensis_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supervisions`
--
ALTER TABLE `supervisions`
  ADD CONSTRAINT `supervisions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `supervisions_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `supervisions_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supervisions_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supervisions_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teaching_documents`
--
ALTER TABLE `teaching_documents`
  ADD CONSTRAINT `teaching_documents_document_package_id_foreign` FOREIGN KEY (`document_package_id`) REFERENCES `document_packages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `teaching_documents_period_id_foreign` FOREIGN KEY (`period_id`) REFERENCES `periods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `teaching_documents_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `teaching_documents_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `teaching_documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
