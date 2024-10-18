-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Okt 2024 pada 15.07
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
-- Database: `data_pengguna_zapin`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `class_description` text NOT NULL,
  `pengajar_id` int(11) NOT NULL,
  `class_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `classes`
--

INSERT INTO `classes` (`id`, `class_name`, `class_description`, `pengajar_id`, `class_code`) VALUES
(6, 'tugas belajar', '7.30', 41, NULL),
(7, 'belajar siang', '08.00', 41, NULL),
(8, 'belajar sore', '15.00', 41, '7113b7e2'),
(9, 'b.inggris', '12.00', 42, '6eb27892'),
(10, 'pelatihan word', '12.00', 47, '5c83efc0'),
(11, 'Diklat', '08.00', 49, 'de4f6230');

-- --------------------------------------------------------

--
-- Struktur dari tabel `class_members`
--

CREATE TABLE `class_members` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `pegawai_id` int(11) DEFAULT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `class_members`
--

INSERT INTO `class_members` (`id`, `class_id`, `pegawai_id`, `joined_at`, `role`) VALUES
(1, 8, 39, '2024-08-29 15:38:19', ''),
(2, 9, 39, '2024-08-30 06:30:00', ''),
(3, 8, 40, '2024-09-01 11:41:55', ''),
(4, 8, 43, '2024-09-01 14:31:23', ''),
(39, 10, NULL, '2024-09-02 03:15:22', 'pegawai'),
(41, 8, NULL, '2024-09-01 19:36:59', 'pengajar'),
(42, 8, NULL, '2024-09-01 19:39:26', 'pengajar'),
(48, 10, NULL, '2024-09-02 03:13:48', 'pegawai'),
(49, 10, NULL, '2024-09-02 03:17:15', 'pegawai'),
(50, 10, NULL, '2024-09-02 03:21:25', 'pegawai'),
(51, 11, NULL, '2024-09-02 03:53:02', 'pegawai'),
(52, 11, NULL, '2024-09-02 03:53:31', 'pegawai'),
(53, 11, NULL, '2024-09-02 03:55:57', 'pegawai');

-- --------------------------------------------------------

--
-- Struktur dari tabel `class_tasks`
--

CREATE TABLE `class_tasks` (
  `id` int(11) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_description` text NOT NULL,
  `class_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `attachment_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `class_tasks`
--

INSERT INTO `class_tasks` (`id`, `task_name`, `task_description`, `class_id`, `created_at`, `attachment_path`) VALUES
(1, 'tugas belajar', 'kerjakan cepat woy', 8, '2024-08-30 04:25:13', 'uploads/Participation-Certificate-Template.docx'),
(2, 'makalah', 'cepat woy', 9, '2024-08-30 06:28:36', 'uploads/tabel_data.docx'),
(3, 'tugas belajar 2', 'upload dokumen kelengkapan anda dengan jujur', 10, '2024-09-02 03:11:10', 'uploads/Participation-Certificate-Template.docx'),
(4, 'tugas belajar', 'upload dokumen kelengkapan anda', 11, '2024-09-02 03:48:13', 'uploads/Bachelor Degree Certificate Template.docx');

-- --------------------------------------------------------

--
-- Struktur dari tabel `employee_classes`
--

CREATE TABLE `employee_classes` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `submissions`
--

CREATE TABLE `submissions` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `submission_path` varchar(255) NOT NULL,
  `submitted_at` datetime NOT NULL,
  `grade` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `submissions`
--

INSERT INTO `submissions` (`id`, `task_id`, `employee_id`, `submission_path`, `submitted_at`, `grade`) VALUES
(1, 2, 39, 'submissions/ZAPIN (1).png', '2024-09-01 16:47:52', NULL),
(2, 1, 39, 'submissions/ilham 1.jpeg.jpg.png', '2024-09-01 17:12:40', '80'),
(3, 1, 39, 'submissions/IMG-20240813-WA0004.jpg (2).png', '2024-09-01 18:38:07', '100'),
(4, 1, 40, 'submissions/Bachelor Degree Certificate Template.docx', '2024-09-01 18:43:43', '50'),
(5, 1, 43, 'submissions/Simple-Adoption-Certificate-Template.docx', '2024-09-01 21:31:52', '80');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `security_question` varchar(255) DEFAULT NULL,
  `security_answer` varchar(255) DEFAULT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `reset_token`, `token_expiry`, `security_question`, `security_answer`, `role`) VALUES
(39, 'safitra', 'safitra123@gmail.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-08-27 10:08:30', NULL, NULL, 'Apa makanan favorit Anda?', '4146d8015f24e1632c90d1f2b438508afe6c753c131eda0379a316c04fdca0a2', 'pegawai'),
(40, 'ratihsialan', 'ratih123@gmail.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-08-27 21:34:50', NULL, NULL, 'Apa makanan favorit Anda?', '9d65fa7a250a39295f559263803dfe78091dd78cf700b53fe37f7d091e769ef7', 'pegawai'),
(41, 'ilham', 'ilhamkur143@gmail.com', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '2024-08-28 14:19:40', NULL, NULL, 'Apa makanan favorit Anda?', 'c9d7ca87906c4a39d0a9ad1c435fcabefb2cf6d1c9671800d7fec8edf0331175', 'pengajar'),
(42, 'raisya', 'raisya123@gmail.com', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '2024-08-30 13:26:51', NULL, NULL, 'Apa makanan favorit Anda?', '9d65fa7a250a39295f559263803dfe78091dd78cf700b53fe37f7d091e769ef7', 'pengajar'),
(43, 'hani', 'hani123@gmail.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-09-01 21:30:42', NULL, NULL, 'Apa makanan favorit Anda?', '9d65fa7a250a39295f559263803dfe78091dd78cf700b53fe37f7d091e769ef7', 'pegawai'),
(47, 'wawa', 'wawa123@gmail.com', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '2024-09-02 10:07:17', NULL, NULL, 'Di mana kota kelahiran ibu Anda?', 'e4a637d6ffead84f333fc496ca5b9ae5fb1cb4b38a30e31ef53a2213fdfe9980', 'pengajar'),
(48, 'yuli', 'yuli123@gmail.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-09-02 10:13:15', NULL, NULL, 'Di mana kota kelahiran ibu Anda?', '68de3ce04b9d2b0b5b6866cc65a60303174b3c451264e90e74771c49bb42922c', 'pegawai'),
(49, 'bu rosa', 'rosa123@gmail.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-09-02 10:36:55', NULL, NULL, 'Di mana kota kelahiran ibu Anda?', 'd17f46018e7e4f3f7c893bbb10ef70db7ec3904b8f95c4e8ba2a68b029b60b8a', 'pengajar'),
(50, 'kak wawa', 'wawa1234@gmail.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-09-02 10:51:47', NULL, NULL, 'Di mana kota kelahiran ibu Anda?', '68de3ce04b9d2b0b5b6866cc65a60303174b3c451264e90e74771c49bb42922c', 'pegawai');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `class_code` (`class_code`),
  ADD KEY `pengajar_id` (`pengajar_id`);

--
-- Indeks untuk tabel `class_members`
--
ALTER TABLE `class_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `pegawai_id` (`pegawai_id`);

--
-- Indeks untuk tabel `class_tasks`
--
ALTER TABLE `class_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indeks untuk tabel `employee_classes`
--
ALTER TABLE `employee_classes`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `submissions`
--
ALTER TABLE `submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `class_members`
--
ALTER TABLE `class_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT untuk tabel `class_tasks`
--
ALTER TABLE `class_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `employee_classes`
--
ALTER TABLE `employee_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `submissions`
--
ALTER TABLE `submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`pengajar_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `class_members`
--
ALTER TABLE `class_members`
  ADD CONSTRAINT `class_members_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  ADD CONSTRAINT `class_members_ibfk_2` FOREIGN KEY (`pegawai_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `class_tasks`
--
ALTER TABLE `class_tasks`
  ADD CONSTRAINT `class_tasks_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `submissions`
--
ALTER TABLE `submissions`
  ADD CONSTRAINT `submissions_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `class_tasks` (`id`),
  ADD CONSTRAINT `submissions_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
