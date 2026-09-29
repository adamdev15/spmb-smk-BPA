-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2026 at 11:43 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spmb`
--

-- --------------------------------------------------------

--
-- Table structure for table `biayas`
--

CREATE TABLE `biayas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_biaya` varchar(255) NOT NULL,
  `jenis_biaya` varchar(255) NOT NULL,
  `nominal` bigint(20) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `biayas`
--

INSERT INTO `biayas` (`id`, `nama_biaya`, `jenis_biaya`, `nominal`, `created_at`, `updated_at`) VALUES
(2, 'Iuran Dana Pendidikan (SPP) per bulan', 'SPP', 140000, '2026-08-18 07:45:05', '2026-08-18 07:45:05'),
(3, 'Iuran Dana Pendidikan (SPP) per bulan', 'SPP', 120000, '2026-08-18 07:45:05', '2026-08-18 07:45:05'),
(4, 'Iuran Kegiatan Osis', 'Daftar Ulang', 275000, '2026-08-18 07:45:05', '2026-08-18 07:45:05'),
(5, 'Proses Peningkatan Mutu', 'Daftar Ulang', 220000, '2026-08-18 07:45:05', '2026-08-18 07:45:05'),
(6, 'Pengadaan Atribut (Osis & Pramuka)', 'Daftar Ulang', 115000, '2026-08-18 07:45:05', '2026-08-18 07:45:05'),
(7, 'Asuransi', 'Daftar Ulang', 50000, '2026-08-18 07:45:05', '2026-08-18 07:45:05'),
(8, 'Seragam Kaos Olahraga', 'Seragam', 135000, '2026-08-18 07:45:05', '2026-08-18 07:45:05'),
(9, 'Bahan Kejuruan', 'Seragam', 150000, '2026-08-18 07:45:05', '2026-08-18 07:45:05'),
(10, 'Bahan Kejuruan', 'Seragam', 146000, '2026-08-18 07:45:05', '2026-08-18 07:45:05'),
(11, 'Baju Werpak', 'Seragam', 190000, '2026-08-18 07:45:05', '2026-08-18 07:45:05'),
(12, 'Baju Werpak', 'Seragam', 160000, '2026-08-18 07:45:05', '2026-08-18 07:45:05');

-- --------------------------------------------------------

--
-- Table structure for table `biaya_jurusan`
--

CREATE TABLE `biaya_jurusan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `biaya_id` bigint(20) UNSIGNED NOT NULL,
  `jurusan_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `biaya_jurusan`
--

INSERT INTO `biaya_jurusan` (`id`, `biaya_id`, `jurusan_id`, `created_at`, `updated_at`) VALUES
(4, 2, 1, NULL, NULL),
(5, 2, 2, NULL, NULL),
(6, 2, 4, NULL, NULL),
(7, 3, 3, NULL, NULL),
(8, 4, 1, NULL, NULL),
(9, 4, 2, NULL, NULL),
(10, 4, 3, NULL, NULL),
(11, 4, 4, NULL, NULL),
(12, 5, 1, NULL, NULL),
(13, 5, 2, NULL, NULL),
(14, 5, 3, NULL, NULL),
(15, 5, 4, NULL, NULL),
(16, 6, 1, NULL, NULL),
(17, 6, 2, NULL, NULL),
(18, 6, 3, NULL, NULL),
(19, 6, 4, NULL, NULL),
(20, 7, 1, NULL, NULL),
(21, 7, 2, NULL, NULL),
(22, 7, 3, NULL, NULL),
(23, 7, 4, NULL, NULL),
(24, 8, 1, NULL, NULL),
(25, 8, 2, NULL, NULL),
(26, 8, 3, NULL, NULL),
(27, 8, 4, NULL, NULL),
(28, 9, 1, NULL, NULL),
(29, 9, 2, NULL, NULL),
(30, 9, 4, NULL, NULL),
(31, 10, 3, NULL, NULL),
(32, 11, 1, NULL, NULL),
(33, 11, 4, NULL, NULL),
(34, 12, 2, NULL, NULL),
(35, 12, 3, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `casis`
--

CREATE TABLE `casis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `spmb_period_id` bigint(20) UNSIGNED DEFAULT NULL,
  `no_pendaftaran` varchar(255) NOT NULL,
  `sumber_pendaftaran` enum('online','offline') NOT NULL DEFAULT 'online',
  `password` varchar(255) NOT NULL,
  `nisn` varchar(255) NOT NULL,
  `nik` varchar(255) DEFAULT NULL,
  `no_kk` varchar(255) DEFAULT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `jk` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `anak_ke` int(11) DEFAULT NULL,
  `jml_saudara` int(11) DEFAULT NULL,
  `agama` varchar(255) NOT NULL,
  `status_keluarga` varchar(255) DEFAULT NULL,
  `alamat_siswa` text NOT NULL,
  `rt` varchar(10) DEFAULT NULL,
  `rw` varchar(10) DEFAULT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `kab_kota` varchar(255) DEFAULT NULL,
  `no_hp_siswa` varchar(255) NOT NULL,
  `jurusan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `program_keunggulan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ketrampilan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hobi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cita_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nik_ayah` varchar(255) DEFAULT NULL,
  `nama_ayah` varchar(255) DEFAULT NULL,
  `tgl_lahir_ayah` date DEFAULT NULL,
  `alamat_ayah` text DEFAULT NULL,
  `pendidikan_ayah_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pekerjaan_ayah_id` bigint(20) UNSIGNED DEFAULT NULL,
  `penghasilan_ayah_id` bigint(20) UNSIGNED DEFAULT NULL,
  `no_hp_ayah` varchar(255) DEFAULT NULL,
  `status_ayah` varchar(255) DEFAULT NULL,
  `nik_ibu` varchar(255) DEFAULT NULL,
  `nama_ibu` varchar(255) DEFAULT NULL,
  `tgl_lahir_ibu` date DEFAULT NULL,
  `alamat_ibu` text DEFAULT NULL,
  `pendidikan_ibu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pekerjaan_ibu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `penghasilan_ibu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `no_hp_ibu` varchar(255) DEFAULT NULL,
  `status_ibu` varchar(255) DEFAULT NULL,
  `nik_wali` varchar(255) DEFAULT NULL,
  `nama_wali` varchar(255) DEFAULT NULL,
  `tgl_lahir_wali` date DEFAULT NULL,
  `alamat_wali` text DEFAULT NULL,
  `pendidikan_wali_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pekerjaan_wali_id` bigint(20) UNSIGNED DEFAULT NULL,
  `penghasilan_wali_id` bigint(20) UNSIGNED DEFAULT NULL,
  `no_hp_wali` varchar(255) DEFAULT NULL,
  `status_wali` varchar(255) DEFAULT NULL,
  `orientasi_ortu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `jenis_sekolah` enum('SMP','MTS') DEFAULT NULL,
  `nama_sekolah` varchar(255) DEFAULT NULL,
  `status_sekolah` enum('NEGERI','SWASTA') DEFAULT NULL,
  `alamat_sekolah` text DEFAULT NULL,
  `akreditasi_sekolah` enum('A','B','C','TIDAK TERAKREDITASI') DEFAULT NULL,
  `status_verifikasi` varchar(255) NOT NULL DEFAULT 'Belum Diverifikasi',
  `status_pendaftaran` varchar(255) NOT NULL DEFAULT 'Draft',
  `nilai_tpa` decimal(5,2) DEFAULT NULL,
  `nilai_wawancara` decimal(5,2) DEFAULT NULL,
  `status_kelulusan` enum('Proses','Lulus','Tidak Lulus','Cadangan') NOT NULL DEFAULT 'Proses',
  `hasil_psikotes` enum('Belum Tes','Lulus','Tidak Lulus') NOT NULL DEFAULT 'Belum Tes',
  `catatan_psikotes` text DEFAULT NULL,
  `tes_tindik` enum('Belum Periksa','Memenuhi','Tidak Memenuhi') NOT NULL DEFAULT 'Belum Periksa',
  `tes_tato` enum('Belum Periksa','Memenuhi','Tidak Memenuhi') NOT NULL DEFAULT 'Belum Periksa',
  `tes_buta_warna` enum('Belum Periksa','Normal','Parsial','Total') NOT NULL DEFAULT 'Belum Periksa',
  `status_daftar_ulang` enum('Belum','Sudah') NOT NULL DEFAULT 'Belum',
  `tgl_daftar_ulang` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `casis`
--

INSERT INTO `casis` (`id`, `spmb_period_id`, `no_pendaftaran`, `sumber_pendaftaran`, `password`, `nisn`, `nik`, `no_kk`, `nama_lengkap`, `jk`, `tempat_lahir`, `tgl_lahir`, `anak_ke`, `jml_saudara`, `agama`, `status_keluarga`, `alamat_siswa`, `rt`, `rw`, `kecamatan`, `kab_kota`, `no_hp_siswa`, `jurusan_id`, `program_keunggulan_id`, `ketrampilan_id`, `hobi_id`, `cita_id`, `nik_ayah`, `nama_ayah`, `tgl_lahir_ayah`, `alamat_ayah`, `pendidikan_ayah_id`, `pekerjaan_ayah_id`, `penghasilan_ayah_id`, `no_hp_ayah`, `status_ayah`, `nik_ibu`, `nama_ibu`, `tgl_lahir_ibu`, `alamat_ibu`, `pendidikan_ibu_id`, `pekerjaan_ibu_id`, `penghasilan_ibu_id`, `no_hp_ibu`, `status_ibu`, `nik_wali`, `nama_wali`, `tgl_lahir_wali`, `alamat_wali`, `pendidikan_wali_id`, `pekerjaan_wali_id`, `penghasilan_wali_id`, `no_hp_wali`, `status_wali`, `orientasi_ortu_id`, `jenis_sekolah`, `nama_sekolah`, `status_sekolah`, `alamat_sekolah`, `akreditasi_sekolah`, `status_verifikasi`, `status_pendaftaran`, `nilai_tpa`, `nilai_wawancara`, `status_kelulusan`, `hasil_psikotes`, `catatan_psikotes`, `tes_tindik`, `tes_tato`, `tes_buta_warna`, `status_daftar_ulang`, `tgl_daftar_ulang`, `created_at`, `updated_at`) VALUES
(2, 1, 'BPA-2026-0001', 'online', '$2y$12$Fms11Qts45KyQNWOJt5RyOoHeMRfkUIvlkiKQ9YXZRV1hr4pZygjO', '11212121212122', '3323232323232323', NULL, 'ADAM ADAM', 'P', 'KABUPATEN TEGAL', '2004-10-10', NULL, NULL, 'ISLAM', NULL, 'BULAKWARU RT08/RW01', '23', '12', '12', 'KABUPATEN TEGAL', '085640600585', 2, 3, NULL, NULL, NULL, NULL, 'AYAH', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'IBU', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SMP', 'ALAMAK', 'NEGERI', 'TEGALANDONG', 'A', 'Diverifikasi', 'Submitted', NULL, NULL, 'Lulus', 'Lulus', NULL, 'Memenuhi', 'Memenuhi', 'Normal', 'Sudah', '2026-08-17 02:19:00', '2026-08-12 03:06:27', '2026-08-17 02:20:16'),
(3, 1, 'BPA-2026-0002', 'online', '$2y$12$G/U2kHnL3fTeHrmkPZp7k.1nMsbXYYml9274xRRZeNblvnQfkLP6K', '22222222', '3323232323231616', NULL, 'JAKA', 'L', 'KABUPATEN TEGAL', '2004-10-10', NULL, NULL, 'KRISTEN', NULL, 'TEGALANDONG', 'BULAKWARU', '2', '1', 'KABUPATEN TEGAL', '085640600585', 2, 4, NULL, NULL, NULL, NULL, 'AYA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'QSQSQ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SMP', 'SMK HASRY', 'NEGERI', NULL, 'A', 'Diverifikasi', 'Submitted', NULL, NULL, 'Proses', 'Belum Tes', NULL, 'Belum Periksa', 'Belum Periksa', 'Belum Periksa', 'Belum', NULL, '2026-08-12 06:18:19', '2026-08-17 06:51:15'),
(4, NULL, 'BPA-2026-0003', 'offline', '$2y$12$6lxjU8dH6QKWvUx2EHIujOL4kou7Tl.2EUIDQF4DXBGs7nSuljuuq', '1111111111', '3328141008040072', NULL, 'Adam Adam', 'P', 'Jakarta', '2009-10-10', NULL, NULL, 'Islam', NULL, 'Kabupaten Tegal, Jawa Tengah', NULL, NULL, NULL, NULL, '085640600585', 3, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SMP TAHFIDZUL QUR\'AN NURIDIN IDRIS KEDAWON', NULL, 'TEGALANDONG', NULL, 'Belum Diverifikasi', 'Draft', NULL, NULL, 'Proses', 'Belum Tes', NULL, 'Belum Periksa', 'Belum Periksa', 'Belum Periksa', 'Belum', NULL, '2026-08-19 03:16:15', '2026-08-19 03:16:15'),
(5, 1, 'BPA-2026-0004', 'offline', '$2y$12$TDqde2hcV6vAHOw/iUktfujJRF0G9tDPBN9dcFkwnfE3Xbo0elpra', '1111111112', '3328141008040072', NULL, 'rasho degeng', 'P', 'Jakarta', '2009-10-10', NULL, NULL, 'Islam', NULL, 'Kabupaten Tegal, Jawa Tengah', NULL, NULL, NULL, NULL, '085640600585', 3, 5, NULL, NULL, NULL, NULL, 'jaya', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'rengas', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SMP TAHFIDZUL QUR\'AN NURIDIN IDRIS KEDAWON', NULL, 'TEGALANDONG', NULL, 'Diverifikasi', 'Draft', NULL, NULL, 'Proses', 'Belum Tes', NULL, 'Belum Periksa', 'Belum Periksa', 'Belum Periksa', 'Sudah', '2026-08-19 04:31:50', '2026-08-19 03:26:34', '2026-08-19 04:31:50'),
(6, 1, 'BPA-2026-0005', 'offline', '$2y$12$voT4Yh8FfuQ2yn6biLZ2L.fQIgLQkzHJi7WsAwabi5phyj3jgRGjS', '1111111123', '3328141008040072', NULL, 'rizki muyang', 'P', 'Jakarta', '2002-10-10', NULL, NULL, 'Kristen', NULL, 'Kabupaten Tegal, Jawa Tengah', NULL, NULL, NULL, NULL, '085640600585', 4, 4, NULL, NULL, NULL, NULL, 'Pesta Aditya P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sahro', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SMK HASYIM ASY\'ARI TARUB', NULL, 'TEGALANDONG', NULL, 'Diverifikasi', 'Draft', NULL, NULL, 'Proses', 'Belum Tes', NULL, 'Belum Periksa', 'Belum Periksa', 'Belum Periksa', 'Belum', NULL, '2026-08-19 03:39:32', '2026-08-19 05:03:15');

-- --------------------------------------------------------

--
-- Table structure for table `casis_berkas`
--

CREATE TABLE `casis_berkas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `casis_id` bigint(20) UNSIGNED NOT NULL,
  `nama_berkas` varchar(255) NOT NULL,
  `path` text NOT NULL,
  `extension` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `casis_berkas`
--

INSERT INTO `casis_berkas` (`id`, `casis_id`, `nama_berkas`, `path`, `extension`, `size`, `created_at`, `updated_at`) VALUES
(1, 2, 'Pas Foto 3x4', 'berkas/foto/vt90w5rpfnbjHbu40kVUMZYT4XWlMRZdpUUsu0Bg.jpg', 'jpg', 155219, '2026-08-12 03:06:27', '2026-08-12 03:06:27'),
(2, 3, 'Pas Foto 3x4', 'berkas/foto/pUPJzB4PG5GFMkmkirj3bS9UfL5hGNb9axftfHpA.png', 'png', 11172, '2026-08-12 06:18:19', '2026-08-12 06:18:19'),
(3, 3, 'FC Akta Kelahiran', 'berkas/Iv3rO6ASctX1aIu5K1TMtFKivUPNhigOlaL8qVCc.png', 'png', 91577, '2026-08-12 09:04:32', '2026-08-12 09:04:32'),
(4, 6, 'Pas Foto 3x4', 'berkas/foto/MrgdGdhTMSM3XlQ530uQx0UNkFKNvwFU7ALt0U6a.jpg', 'jpeg', 54738, '2026-08-19 03:39:32', '2026-08-19 03:39:32'),
(5, 5, 'Pas Foto 3x4', 'berkas/kzjUdE9eSMH7ljNEKahK3yiUume5SCrf226dbHnw.jpg', 'jpeg', 54738, '2026-08-19 04:23:50', '2026-08-19 04:23:50');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_cita`
--

CREATE TABLE `master_cita` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_cita`
--

INSERT INTO `master_cita` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Guru', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(2, 'Dokter', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(3, 'Insinyur', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(4, 'TNI/Polri', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(5, 'Pengusaha', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(6, 'Lainnya', '2026-08-11 15:42:15', '2026-08-11 15:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `master_hobi`
--

CREATE TABLE `master_hobi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_hobi`
--

INSERT INTO `master_hobi` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Membaca', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(2, 'Menulis', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(3, 'Olahraga', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(4, 'Seni', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(5, 'Traveling', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(6, 'Gaming', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(7, 'Lainnya', '2026-08-11 15:42:15', '2026-08-11 15:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `master_jurusan`
--

CREATE TABLE `master_jurusan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `kuota` int(11) NOT NULL DEFAULT 36,
  `biaya_daftar_ulang` decimal(12,2) NOT NULL DEFAULT 0.00,
  `link_wa_group` varchar(255) DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_jurusan`
--

INSERT INTO `master_jurusan` (`id`, `kode`, `nama`, `logo`, `deskripsi`, `kuota`, `biaya_daftar_ulang`, `link_wa_group`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'TKRO', 'Teknik Kendaraan Ringan Otomotif', 'jurusan/pgmkbbVpAJcgVtx0b3ZLYaTxEN4haHAqmas7UWom.jpg', 'Program keahlian yang menyiapkan tenaga terampil di bidang mekanik otomotif kendaraan ringan, servis berkala, diagnosis mesin, dan teknologi otomotif terkini.', 36, 1500000.00, 'https://chat.whatsapp.com/sample-tkro-smkbpa', 1, '2026-08-11 15:42:15', '2026-08-12 02:09:51'),
(2, 'TJKT', 'Teknik Jaringan Komputer dan Telekomunikasi', 'jurusan/QIGBzDNZk4oUImo1rEJdvP2KO6Bjk4mgSzyfZ1cC.jpg', 'Menguasai infrastruktur jaringan komputer, instalasi jaringan fiber optic, administrasi server, cyber security, dan pengoperasian perangkat telekomunikasi modern.', 36, 1500000.00, 'https://chat.whatsapp.com/sample-tjkt-smkbpa', 1, '2026-08-11 15:42:15', '2026-08-12 02:18:54'),
(3, 'AKL', 'Akuntansi dan Keuangan Lembaga', 'jurusan/zMuGwW7dTz70x0xExB7qDsBil9l8UlXBNeatIkpe.jpg', 'Membentuk tenaga ahli di bidang pembukuan keuangan, perpajakan, sistem akuntansi komputerisasi, perbankan syariah, dan manajemen keuangan perusahaaan.', 36, 1500000.00, 'https://chat.whatsapp.com/sample-akl-smkbpa', 1, '2026-08-11 15:42:15', '2026-08-12 02:19:07'),
(4, 'TBSM', 'Teknik dan Bisnis Sepeda Motor', 'jurusan/AjMONtFZdmJbiJywrKGH7wGYWgTFQqi8hgiPoQFN.jpg', 'Keahlian teknis dan wirausaha di bidang servis tune-up sepeda motor, sistem injeksi fuel, kelistrikan sepeda motor, dan manajemen bengkel resmi.', 36, 1500000.00, 'https://chat.whatsapp.com/sample-tbsm-smkbpa', 1, '2026-08-11 15:42:15', '2026-08-12 02:19:26');

-- --------------------------------------------------------

--
-- Table structure for table `master_ketrampilan`
--

CREATE TABLE `master_ketrampilan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jk` char(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_ketrampilan`
--

INSERT INTO `master_ketrampilan` (`id`, `nama`, `jk`, `created_at`, `updated_at`) VALUES
(1, 'TBSM', 'L', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(2, 'TITL', 'L', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(3, 'REGULER', 'L', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(4, 'TATABOGA', 'P', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(5, 'TATABUSANA', 'P', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(6, 'TKJ', 'P', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(7, 'RESET', 'P', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(8, 'REGULER', 'P', '2026-08-11 15:42:15', '2026-08-11 15:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `master_orientasi_ortu`
--

CREATE TABLE `master_orientasi_ortu` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_orientasi_ortu`
--

INSERT INTO `master_orientasi_ortu` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Perguruan Tinggi', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(2, 'Kerja', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(3, 'mondok', '2026-08-11 15:42:15', '2026-08-11 15:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `master_pekerjaan`
--

CREATE TABLE `master_pekerjaan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `target` enum('ayah','ibu','wali','semua') NOT NULL DEFAULT 'semua',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_pekerjaan`
--

INSERT INTO `master_pekerjaan` (`id`, `nama`, `target`, `created_at`, `updated_at`) VALUES
(1, 'PNS', 'semua', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(2, 'TNI/Polri', 'semua', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(3, 'Wiraswasta', 'semua', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(4, 'Petani', 'semua', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(5, 'Buruh', 'semua', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(6, 'Ibu Rumah Tangga', 'ibu', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(7, 'Pensiunan', 'semua', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(8, 'Tidak Bekerja', 'semua', '2026-08-11 15:42:15', '2026-08-11 15:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `master_pendidikan`
--

CREATE TABLE `master_pendidikan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_pendidikan`
--

INSERT INTO `master_pendidikan` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'SD', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(2, 'SMP', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(3, 'SMA', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(4, 'S1', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(5, 'S2', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(6, 'S3', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(7, 'Tidak Sekolah', '2026-08-11 15:42:15', '2026-08-11 15:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `master_penghasilan`
--

CREATE TABLE `master_penghasilan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_penghasilan`
--

INSERT INTO `master_penghasilan` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, '< 1 Juta', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(2, '1 - 3 Juta', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(3, '3 - 5 Juta', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(4, '> 5 Juta', '2026-08-11 15:42:15', '2026-08-11 15:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_04_155540_create_master_data_tables', 1),
(5, '2026_02_04_155545_create_casis_table', 1),
(6, '2026_02_04_155550_create_nilai_rapor_table', 1),
(7, '2026_02_04_164513_create_settings_table', 1),
(8, '2026_02_05_105000_add_jk_to_master_ketrampilan_table', 1),
(9, '2026_02_07_090835_create_casis_berkas_table', 1),
(10, '2026_02_07_142428_add_role_to_users_table', 1),
(11, '2026_02_07_142428_add_selection_fields_to_casis_table', 1),
(12, '2026_02_08_043520_add_file_type_to_settings_table', 1),
(13, '2026_02_12_000001_create_master_jurusan_table', 1),
(14, '2026_02_12_000002_create_program_keunggulan_table', 1),
(15, '2026_02_12_000003_create_jadwal_spmb_table', 1),
(16, '2026_02_12_000004_update_casis_table_for_smk_bpa', 1),
(17, '2026_02_12_000005_create_pembayaran_table', 1),
(18, '2026_08_12_002553_update_casis_table_alamat_npsn', 2),
(19, '2026_08_12_123952_create_tahun_ajarans_table', 3),
(20, '2026_08_12_123953_create_spmb_periods_table', 3),
(21, '2026_08_12_123954_add_spmb_period_id_to_casis_table', 3),
(22, '2026_08_12_123954_drop_jadwal_spmb_table', 3),
(23, '2026_08_12_162124_create_notifications_table', 4),
(24, '2026_08_14_000001_update_pembayaran_table_complete_fields', 5),
(25, '2026_08_17_150514_create_biayas_table', 6),
(26, '2026_08_17_150515_create_biaya_jurusan_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `nilai_rapor`
--

CREATE TABLE `nilai_rapor` (
  `id_nilai` bigint(20) UNSIGNED NOT NULL,
  `casis_id` bigint(20) UNSIGNED NOT NULL,
  `semester` enum('3','4','5') NOT NULL,
  `ipa` decimal(5,2) NOT NULL DEFAULT 0.00,
  `ips` decimal(5,2) NOT NULL DEFAULT 0.00,
  `matematika` decimal(5,2) NOT NULL DEFAULT 0.00,
  `bind` decimal(5,2) NOT NULL DEFAULT 0.00,
  `bing` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('7807d580-e871-4a5d-a694-bedafaabce95', 'App\\Notifications\\PaymentSuccessNotification', 'App\\Models\\User', 1, '{\"title\":\"Pembayaran Daftar Ulang Lunas\",\"message\":\"Siswa rasho degeng (No Pendaftaran: BPA-2026-0004) telah melunasi biaya daftar ulang.\",\"url\":\"http:\\/\\/6e16-103-38-104-22.ngrok-free.app\\/admin\\/casis\\/5\"}', NULL, '2026-08-19 04:31:51', '2026-08-19 04:31:51'),
('fb0214fe-d5f0-4e82-a96a-3d65caf943e3', 'App\\Notifications\\PaymentSuccessNotification', 'App\\Models\\User', 2, '{\"title\":\"Pembayaran Daftar Ulang Lunas\",\"message\":\"Siswa rasho degeng (No Pendaftaran: BPA-2026-0004) telah melunasi biaya daftar ulang.\",\"url\":\"http:\\/\\/6e16-103-38-104-22.ngrok-free.app\\/admin\\/casis\\/5\"}', NULL, '2026-08-19 04:31:51', '2026-08-19 04:31:51');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `casis_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` varchar(255) NOT NULL,
  `jenis_pembayaran` varchar(255) NOT NULL DEFAULT 'daftar_ulang',
  `tipe_pembayaran` enum('online','offline') NOT NULL DEFAULT 'online',
  `nominal` decimal(12,2) NOT NULL,
  `snap_token` varchar(255) DEFAULT NULL,
  `transaction_status` enum('pending','settlement','expire','cancel','failed') NOT NULL DEFAULT 'pending',
  `payment_type` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_gateway` varchar(255) DEFAULT 'midtrans',
  `settlement_time` timestamp NULL DEFAULT NULL,
  `tgl_jatuh_tempo` date DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL,
  `raw_response` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `casis_id`, `user_id`, `order_id`, `jenis_pembayaran`, `tipe_pembayaran`, `nominal`, `snap_token`, `transaction_status`, `payment_type`, `transaction_id`, `payment_gateway`, `settlement_time`, `tgl_jatuh_tempo`, `catatan_admin`, `raw_response`, `created_at`, `updated_at`) VALUES
(5, 2, 1, 'PAY-BPA-2-1786933155', 'daftar_ulang', 'offline', 1500000.00, NULL, 'settlement', 'Tunai', NULL, 'midtrans', '2026-08-17 02:19:00', '2026-04-09', 'Pembayaran Kasir Sekolah (Lunas)', NULL, '2026-08-14 13:55:28', '2026-08-17 02:20:16'),
(6, 3, NULL, 'PAY-BPA-3-1787031345', 'daftar_ulang', 'online', 800000.00, 'bcaa32b9-ad7c-4230-a471-0f74b5f6d8ec', 'pending', NULL, NULL, 'midtrans', NULL, '2026-04-09', NULL, NULL, '2026-08-17 06:51:15', '2026-08-18 08:03:10'),
(7, 5, NULL, 'PAY-BPA-5-1787113901', 'daftar_ulang', 'online', 780000.00, '7f4a3c92-6a38-4aa7-8dbb-95cc67f4aac1', 'settlement', 'credit_card', '7fd35df4-991b-403a-8af9-74c51b49ac32', 'midtrans', '2026-08-19 04:31:50', '2026-04-09', NULL, '{\"transaction_time\":\"2026-08-19 11:31:47\",\"transaction_status\":\"capture\",\"transaction_id\":\"7fd35df4-991b-403a-8af9-74c51b49ac32\",\"status_message\":\"midtrans payment notification\",\"status_code\":\"200\",\"signature_key\":\"25f8872ed908a75702a3956cf164550f1a081442d42d4e37130b40bfcb33822de9308c8fe9541d9d2e58ebf4e7697ee19fb11b92e053e8dac63ce3adc7c00956\",\"payment_type\":\"credit_card\",\"order_id\":\"PAY-BPA-5-1787113901\",\"metadata\":[],\"merchant_id\":\"M160342461\",\"masked_card\":\"48111111-1114\",\"gross_amount\":\"780000.00\",\"fraud_status\":\"accept\",\"expiry_time\":\"2026-08-27 11:31:47\",\"customer_details\":{\"phone\":\"+6285640600585\",\"full_name\":\"rasho degeng\",\"email\":\"1111111112@spmb.sch.id\"},\"currency\":\"IDR\",\"channel_response_message\":\"Approved\",\"channel_response_code\":\"00\",\"card_type\":\"credit\",\"bank\":\"bni\",\"approval_code\":\"1787113908489\"}', '2026-08-19 04:20:22', '2026-08-19 04:31:50'),
(8, 6, NULL, 'PAY-BPA-6-1787113222', 'daftar_ulang', 'online', 800000.00, 'bfb65d33-8bac-4f1f-92ee-5798e2569307', 'pending', NULL, NULL, 'midtrans', NULL, '2026-04-09', NULL, NULL, '2026-08-19 04:20:22', '2026-08-19 04:22:07');

-- --------------------------------------------------------

--
-- Table structure for table `program_keunggulan`
--

CREATE TABLE `program_keunggulan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `jurusan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `program_keunggulan`
--

INSERT INTO `program_keunggulan` (`id`, `nama`, `logo`, `deskripsi`, `jurusan_id`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'SMK Binaan Isuzu', 'program_keunggulan/ZinUTr8A9cjKthbQUhEPpNgUEV4SQYWjbbN7ykHb.png', 'Program pendidikan dan pelatihan kurikulum standar industri otomotif Isuzu Indonesia dengan jaminan magang dan kesempatan kerja.', 1, 1, '2026-08-11 15:42:15', '2026-08-12 02:29:33'),
(2, 'Kelas Binaan Daihatsu', 'program_keunggulan/yMQSzErtIAQ1InvO5CsmxptofxizBPWmupLwo5IG.webp', 'Pintar Bersama Daihatsu (PBD) memberikan sertifikasi keahlian standar pabrik Astra Daihatsu Motor.', 1, 1, '2026-08-11 15:42:15', '2026-08-12 02:29:44'),
(3, 'Axioo Class Program', 'program_keunggulan/IHsGulyCRF2sX5dHDpiy5ZuTE2JJS1ZqXm77IDvv.webp', 'Program industri IT berskala nasional bersama Axioo Indonesia mencakup perakitan laptop, jaringan, dan sertifikasi IT internasional.', 2, 1, '2026-08-11 15:42:15', '2026-08-12 02:29:56'),
(4, 'Astra Motor', 'program_keunggulan/wYhXkG0PvoBTx1r8FcLpYksyokRDXP5R0nCl9kLE.webp', 'Kerja sama kurikulum dan kelas industri teknik sepeda motor Honda standar Astra Motor.', 4, 1, '2026-08-11 15:42:15', '2026-08-12 02:30:09'),
(5, 'Bank Jateng Syariah', 'program_keunggulan/NLTlOnDZU2nyTWmzuVrr0akg15tu4x3MLgxR8oo0.webp', 'Kemitraan transaksi keuangan, tempat pkl/magang, dan pembinaan kompetensi perbankan syariah.', 3, 1, '2026-08-11 15:42:15', '2026-08-12 02:30:21'),
(6, 'Bahasa Jepang', 'program_keunggulan/8d1xmq41b1zu91z5r2bVhejy8u00If50GscNmLSk.webp', 'Program unggulan penguasaan Bahasa Jepang & persiapan penempatan kerja (Tokutei Ginou / Internships) ke Jepang.', NULL, 1, '2026-08-11 15:42:15', '2026-08-12 02:32:16');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('HhksGgt7zstHw3StPw9jM9DWEsuJu7OXHAYrg2Gh', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZUljbFp3NGRIU3hkTllVcU84bXpyWTE2cVVua0o0WEtENGlXYklvTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly82ZTE2LTEwMy0zOC0xMDQtMjIubmdyb2stZnJlZS5hcHAvYWRtaW4vY2FzaXMiO3M6NToicm91dGUiO3M6MTc6ImFkbWluLmNhc2lzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1787112671),
('UhbA2IbbfAhifRWxwtVm5847KJtzU9USYSwVSV7d', NULL, '127.0.0.1', 'Veritrans', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoidGF0UTZ0Ykt3N1JLUnA3UmZ0bnd3MHRvRWdUaFIwaGJsVmhmaVN5SyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1787113911),
('xKVrcGVVuhMRIH09qjpMxxEgXzOII7aUzdMZZqE7', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicm9kNUVONUl0YWRpQ28yRlRKelg4QUlScEZIUUVOcGFzekRhZ2JJUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zZXR0aW5ncyI7czo1OiJyb3V0ZSI7czoxNDoiYWRtaW4uc2V0dGluZ3MiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1787116023);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('text','longtext','date','datetime','number','file') DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `name`, `type`, `created_at`, `updated_at`) VALUES
(1, 'nama_sekolah', 'SMK Bhakti Praja Adiwerna', 'Nama Sekolah', 'text', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(2, 'singkatan_sekolah', 'SMK BP Adiwerna', 'Singkatan Sekolah', 'text', '2026-08-11 15:42:15', '2026-08-18 07:26:59'),
(3, 'tagline_sekolah', 'Mewujudkan Lulusan Berkarakter, Kompeten, dan Siap Kerja', 'Tagline Sekolah', 'text', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(4, 'logo', 'settings/logo_1787037712.png', 'Logo Sekolah', 'file', '2026-08-11 15:42:15', '2026-08-18 07:21:52'),
(5, 'landing_hero', '[\"settings\\/landing_hero_1787037767_0.png\"]', 'Gambar Hero Landing Page', 'file', '2026-08-11 15:42:15', '2026-08-18 07:22:47'),
(6, 'deskripsi_hero', 'Selamat datang di SPMB SMK Bhakti Praja Adiwerna Tahun Ajaran 2026/2027. Sekolah Kejuruan Unggulan yang Didukung Program Binaan Industri Terkemuka.', 'Deskripsi Hero', 'longtext', '2026-08-11 15:42:15', '2026-08-12 14:18:16'),
(7, 'tahun_ajaran', '2026/2027', 'Tahun Ajaran', 'text', '2026-08-11 15:42:15', '2026-08-13 02:16:21'),
(8, 'brosur', NULL, 'Brosur SPMB (PDF/Image)', 'file', '2026-08-11 15:42:15', '2026-08-11 17:27:41'),
(9, 'alamat_sekolah', 'Jl. Singkil No. 24, Adiwerna, Kab. Tegal, Jawa Tengah', 'Alamat Sekolah', 'longtext', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(10, 'telepon_sekolah', '(0283) 443210', 'Telepon Sekolah', 'text', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(11, 'email_sekolah', 'spmb@smkbhaktiprajaadiwerna.sch.id', 'Email Sekolah', 'text', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(12, 'wa_center', '085640600585', 'Nomor WA Center / Panitia', 'text', '2026-08-11 15:42:15', '2026-08-19 03:13:57'),
(14, 'prefix_no_pendaftaran', 'BPA-2026-', 'Prefix Nomor Pendaftaran', 'text', '2026-08-11 15:42:15', '2026-08-11 15:42:15'),
(15, 'fonnte_token', 'DRUqNr6TjX5EChNMsCR4', 'Fonnte Token (WhatsApp API)', 'text', '2026-08-11 15:42:15', '2026-08-19 04:16:53'),
(16, 'template_pesan_pendaftaran', 'Pendaftaran SPMB SMK Bhakti Praja Adiwerna Berhasil!\r\n\r\nNomor Pendaftaran: [NOMOR_DAFTAR]\r\nNama: [NAMA]\r\nJurusan: [JURUSAN]\r\nProgram Keunggulan: [PROGRAM_KEUNGGULAN]\r\nPassword Login: [PASSWORD]\r\n\r\nSilakan cetak Kartu Bukti Pendaftaran dan ikuti tes seleksi sesuai jadwal.\r\nLink Grup WhatsApp Jurusan: [LINK_WA_GROUP]\r\n\r\nWebsite: http://localhost', 'Template WA Pendaftaran Berhasil', 'longtext', '2026-08-11 15:42:15', '2026-08-18 07:05:18'),
(17, 'ketentuan_spmb', '<ul><li>Calon siswa mengisi data pendaftaran online atau offline secara akurat.</li><li>Pas foto 3x4 berwarna wajib diunggah pada sistem online.</li><li>Mengikuti rangkaian tes seleksi (Psikotes & Seleksi Fisik: tindik, tato, buta warna).</li><li>Daftar ulang dilakukan setelah dinyatakan lulus seleksi.</li></ul>', 'Ketentuan & Persyaratan SPMB', 'longtext', '2026-08-11 15:42:15', '2026-08-18 06:16:23'),
(18, 'jadwal_pendaftaran_mulai', '2026-08-11', 'Jadwal Pendaftaran Mulai', 'text', '2026-08-11 16:06:43', '2026-08-11 16:26:21'),
(19, 'jadwal_pendaftaran_selesai', '2026-09-11', 'Jadwal Pendaftaran Mulai', 'text', '2026-08-11 16:06:43', '2026-08-11 16:26:21'),
(20, 'jadwal_daftar_ulang', '2026-04-09', 'Jadwal Daftar Ulang', 'date', '2026-08-11 16:11:57', '2026-08-11 16:11:57'),
(21, 'kontak_nama_1', 'Wahyu Cahyo Nugroho', 'Kontak - Nama 1', 'text', '2026-08-11 16:11:57', '2026-08-11 16:11:57'),
(22, 'kontak_nomor_1', '6289512846071', 'Kontak - Nomor 1', 'text', '2026-08-11 16:11:57', '2026-08-11 16:11:57'),
(23, 'kontak_nama_2', 'Titik Wijayanti', 'Kontak - Nama 2', 'text', '2026-08-11 16:11:57', '2026-08-11 16:11:57'),
(24, 'kontak_nomor_2', '6285328817676', 'Kontak - Nomor 2', 'text', '2026-08-11 16:11:57', '2026-08-11 16:11:57'),
(26, 'sosmed_facebook', 'https://facebook.com/', 'Link Facebook', 'text', '2026-08-11 17:27:41', '2026-08-11 17:27:41'),
(27, 'sosmed_instagram', 'https://instagram.com/', 'Link Instagram', 'text', '2026-08-11 17:27:41', '2026-08-11 17:27:41'),
(28, 'sosmed_youtube', 'https://youtube.com/', 'Link YouTube', 'text', '2026-08-11 17:27:41', '2026-08-11 17:27:41'),
(29, 'sosmed_tiktok', 'https://tiktok.com/', 'Link TikTok', 'text', '2026-08-11 17:27:41', '2026-08-11 17:27:41'),
(30, 'jadwal_pengumuman', '2026-09-11', 'Jadwal Pengumuman', 'text', NULL, NULL),
(31, 'contact_person', 'Wahyu Cahyo Nugroho, ST (08123456789)\r\nTitik Wijayanti, S.Pd (08987654321)', 'Contact Person (Bisa diisi banyak pisahkan baris baru)', 'longtext', '2026-08-12 04:03:51', '2026-08-18 07:05:18'),
(32, 'wa_pesan_ingatkan', 'Halo [NAMA],\r\nKami dari Panitia SPMB SMK Bhakti Praja Adiwerna mengingatkan untuk segera melengkapi berkas pendaftaran Anda dan melakukan verifikasi data.', 'Template WA Pengingat Berkas/Verifikasi', 'longtext', '2026-08-12 13:18:09', '2026-08-18 07:05:18'),
(33, 'wa_pesan_daftar_ulang', 'Halo [NAMA],\r\nKami dari Panitia SPMB SMK Bhakti Praja Adiwerna mengingatkan untuk segera melakukan pembayaran Daftar Ulang karena Anda telah Dinyatakan Lulus Seleksi/Terverifikasi. Silakan hubungi panitia untuk informasi lebih lanjut.', 'Template WA Pengingat Daftar Ulang', 'longtext', '2026-08-12 13:18:09', '2026-08-18 07:05:18'),
(34, 'midtrans_server_key', 'Mid-server-rFigScUcQI5ZnQJAv6AB6Bi8', 'Midtrans Server Key', 'text', '2026-08-12 14:06:23', '2026-08-18 07:54:39'),
(35, 'midtrans_client_key', 'Mid-client-SD-Eu4SgjFCQrlht', 'Midtrans Client Key', 'text', '2026-08-12 14:06:23', '2026-08-18 07:54:39'),
(36, 'midtrans_is_production', '0', 'Gunakan Midtrans Production? (1 = Ya, 0 = Tidak)', 'text', '2026-08-12 14:06:52', '2026-08-17 02:18:44'),
(38, 'midtrans_merchant_id', 'M160342461', 'Midtrans Merchant ID', 'text', '2026-08-12 14:18:16', '2026-08-18 07:54:39'),
(39, 'alur_spmb_gambar', 'settings/alur_spmb_gambar_1787037674.png', 'Gambar Alur SPMB (Upload Gambar)', 'file', '2026-08-13 02:16:21', '2026-08-18 07:21:14'),
(40, 'alur_spmb_konten', '<ol class=\"list-decimal pl-4 space-y-2\"><li>Calon peserta didik mengisi formulir pendaftaran online di website SPMB</li><li>Calon peserta didik login menggunakan NISN dan kata sandi/password yang telah dibuat sebelumnya</li><li>Calon peserta didik melengkapi biodata dan mencetak kartu pendaftaran</li><li>Calon peserta didik mengikuti tes seleksi sesuai jadwal yang ditentukan</li></ol>', 'Konten Teks Alur SPMB', 'longtext', '2026-08-13 02:16:21', '2026-08-18 06:16:23'),
(41, 'fonnte_status', '1', 'Status Fonnte (Aktif/Tidak)', 'text', '2026-08-14 10:09:31', '2026-08-14 10:09:31'),
(42, 'wa_pesan_tagihan_daftar_ulang', 'Selamat [NAMA]!\r\n\r\nPendaftaran Anda dengan No. Pendaftaran: [NOMOR_DAFTAR] telah Dinyatakan DITERIMA / LULUS VERIFIKASI di SMK Bhakti Praja Adiwerna.\r\n\r\nRincian Tagihan Daftar Ulang:\r\n- Jenis: Daftar Ulang Siswa Baru\r\n- Jurusan: [JURUSAN]\r\n- Program: [PROGRAM_KEUNGGULAN]\r\n- Nominal: Rp [NOMINAL]\r\n- Jatuh Tempo: [JATUH_TEMPO]\r\n\r\nSilakan login ke dashboard siswa untuk melakukan pembayaran online (QRIS/VA/E-Wallet) atau datang langsung ke loket pendaftaran sekolah:\r\n[LINK_DASHBOARD]\r\n\r\nTerima kasih.', 'Template Pesan WA Tagihan Daftar Ulang', 'longtext', '2026-08-14 13:43:56', '2026-08-17 02:18:44'),
(43, 'wa_pesan_pembayaran_sukses', 'Halo [NAMA],\r\n\r\nPembayaran Daftar Ulang Anda telah BERHASIL kami terima (LUNAS).\r\n\r\nRincian Pembayaran:\r\n- No. Pembayaran: [NOMOR_PEMBAYARAN]\r\n- Nominal: Rp [NOMINAL]\r\n- Metode: [METODE]\r\n- Status: LUNAS\r\n- Tanggal: [TANGGAL_BAYAR]\r\n\r\nStatus Pendaftaran Anda saat ini: SUDAH DAFTAR ULANG.\r\nSilakan simpan pesan ini sebagai bukti pembayaran yang sah.\r\n\r\nTerima kasih,\r\nPanitia SPMB SMK Bhakti Praja Adiwerna', 'Template Pesan WA Pembayaran Berhasil (Lunas)', 'longtext', '2026-08-14 13:43:56', '2026-08-17 02:18:44'),
(44, 'jadwal_daftar_ulang_jatuh_tempo_hari', NULL, 'Batas Jatuh Tempo Tagihan (Jumlah Hari)', 'text', '2026-08-14 13:43:56', '2026-08-17 02:18:44'),
(45, 'kwitansi_nama_panitia', 'Wahyu Cahyo', 'Nama Panitia SPMB', 'text', '2026-08-17 07:37:26', '2026-08-17 07:37:26'),
(46, 'kwitansi_ttd_panitia', 'settings/kwitansi_ttd_panitia_1787037513.png', 'Tanda Tangan Panitia', 'file', '2026-08-17 07:37:27', '2026-08-18 07:18:33'),
(47, 'kwitansi_stempel_panitia', 'settings/kwitansi_stempel_panitia_1787037083.png', 'Stempel Sekolah / Panitia', 'file', '2026-08-17 07:37:27', '2026-08-18 08:30:17'),
(48, 'pengumuman_nomor_surat', '700.a/SMK.BP/VI/2026', 'Nomor Surat Pengumuman', 'text', '2026-08-17 07:43:22', '2026-08-17 07:43:22'),
(49, 'pengumuman_tgl_mpls', '2028-08-18', 'Tanggal Pembekalan MPLS', 'text', '2026-08-17 07:43:22', '2026-08-18 08:31:18'),
(50, 'pengumuman_tgl_masuk', '2027-09-18', 'Tanggal Awal Masuk Sekolah', 'text', '2026-08-17 07:43:22', '2026-08-18 08:31:18'),
(51, 'pengumuman_nama_kepsek', 'Drs. H. Erfan, M.Pd.', 'Nama Kepala Sekolah', 'text', '2026-08-17 07:43:22', '2026-08-17 07:43:22'),
(52, 'pengumuman_nip_kepsek', '19700101 199512 1 001', 'NIP Kepala Sekolah', 'text', '2026-08-17 07:43:22', '2026-08-17 07:43:22'),
(53, 'pengumuman_ttd_kepsek', 'settings/pengumuman_ttd_kepsek_1787037083.png', 'Tanda Tangan Kepala Sekolah', 'file', '2026-08-17 07:43:22', '2026-08-18 07:11:23'),
(54, 'pengumuman_nama_ketua', 'Fulanah, S.Pd.', 'Nama Ketua SPMB', 'text', '2026-08-17 07:43:22', '2026-08-17 07:43:22'),
(55, 'pengumuman_ttd_ketua', 'settings/pengumuman_ttd_ketua_1787037036.png', 'Tanda Tangan Ketua SPMB', 'file', '2026-08-17 07:43:22', '2026-08-18 07:10:36');

-- --------------------------------------------------------

--
-- Table structure for table `spmb_periods`
--

CREATE TABLE `spmb_periods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tahun_ajaran_id` bigint(20) UNSIGNED NOT NULL,
  `gelombang` varchar(255) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spmb_periods`
--

INSERT INTO `spmb_periods` (`id`, `tahun_ajaran_id`, `gelombang`, `tanggal_mulai`, `tanggal_selesai`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Gelombang 1', '2026-01-01', '2026-12-31', 'aktif', '2026-08-12 05:42:36', '2026-08-12 05:42:36');

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajarans`
--

CREATE TABLE `tahun_ajarans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tahun_ajarans`
--

INSERT INTO `tahun_ajarans` (`id`, `nama`, `status`, `created_at`, `updated_at`) VALUES
(1, '2026/2027', 'aktif', '2026-08-12 05:42:36', '2026-08-12 05:42:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas') NOT NULL DEFAULT 'admin',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin SPMB', 'admin@gmail.com', NULL, '$2y$12$DV83ak8yusMQq2QLHzT7Oe3AwUmPgMEwda6xormKn5Z7yEgUlBAz.', 'admin', 'bYrKwfNEissRz98TZdIQkVkkg17fpDdWCyfRwjayeY85HAaIjUX97EFMyIGW', '2026-08-11 15:51:34', '2026-08-14 10:17:16'),
(2, 'Administrator', 'admin@admin.com', '2026-08-11 15:51:35', '$2y$12$g5Ht0jEnZI/jjHAgHbNrKOsE3NgcA3ER19yR9ydVcIsZGzSWXJpYy', 'petugas', NULL, '2026-08-11 15:51:35', '2026-08-11 17:50:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `biayas`
--
ALTER TABLE `biayas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `biaya_jurusan`
--
ALTER TABLE `biaya_jurusan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `biaya_jurusan_biaya_id_foreign` (`biaya_id`),
  ADD KEY `biaya_jurusan_jurusan_id_foreign` (`jurusan_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `casis`
--
ALTER TABLE `casis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `casis_no_pendaftaran_unique` (`no_pendaftaran`),
  ADD UNIQUE KEY `casis_nisn_unique` (`nisn`),
  ADD KEY `casis_ketrampilan_id_foreign` (`ketrampilan_id`),
  ADD KEY `casis_hobi_id_foreign` (`hobi_id`),
  ADD KEY `casis_cita_id_foreign` (`cita_id`),
  ADD KEY `casis_pendidikan_ayah_id_foreign` (`pendidikan_ayah_id`),
  ADD KEY `casis_pekerjaan_ayah_id_foreign` (`pekerjaan_ayah_id`),
  ADD KEY `casis_penghasilan_ayah_id_foreign` (`penghasilan_ayah_id`),
  ADD KEY `casis_pendidikan_ibu_id_foreign` (`pendidikan_ibu_id`),
  ADD KEY `casis_pekerjaan_ibu_id_foreign` (`pekerjaan_ibu_id`),
  ADD KEY `casis_penghasilan_ibu_id_foreign` (`penghasilan_ibu_id`),
  ADD KEY `casis_pendidikan_wali_id_foreign` (`pendidikan_wali_id`),
  ADD KEY `casis_pekerjaan_wali_id_foreign` (`pekerjaan_wali_id`),
  ADD KEY `casis_penghasilan_wali_id_foreign` (`penghasilan_wali_id`),
  ADD KEY `casis_orientasi_ortu_id_foreign` (`orientasi_ortu_id`),
  ADD KEY `casis_jurusan_id_foreign` (`jurusan_id`),
  ADD KEY `casis_program_keunggulan_id_foreign` (`program_keunggulan_id`),
  ADD KEY `casis_spmb_period_id_foreign` (`spmb_period_id`);

--
-- Indexes for table `casis_berkas`
--
ALTER TABLE `casis_berkas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `casis_berkas_casis_id_foreign` (`casis_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `master_cita`
--
ALTER TABLE `master_cita`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_hobi`
--
ALTER TABLE `master_hobi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_jurusan`
--
ALTER TABLE `master_jurusan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `master_jurusan_kode_unique` (`kode`);

--
-- Indexes for table `master_ketrampilan`
--
ALTER TABLE `master_ketrampilan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_orientasi_ortu`
--
ALTER TABLE `master_orientasi_ortu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_pekerjaan`
--
ALTER TABLE `master_pekerjaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_pendidikan`
--
ALTER TABLE `master_pendidikan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_penghasilan`
--
ALTER TABLE `master_penghasilan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nilai_rapor`
--
ALTER TABLE `nilai_rapor`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `nilai_rapor_casis_id_foreign` (`casis_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pembayaran_order_id_unique` (`order_id`),
  ADD KEY `pembayaran_casis_id_foreign` (`casis_id`),
  ADD KEY `pembayaran_user_id_foreign` (`user_id`);

--
-- Indexes for table `program_keunggulan`
--
ALTER TABLE `program_keunggulan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_keunggulan_jurusan_id_foreign` (`jurusan_id`);

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
-- Indexes for table `spmb_periods`
--
ALTER TABLE `spmb_periods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spmb_periods_tahun_ajaran_id_foreign` (`tahun_ajaran_id`);

--
-- Indexes for table `tahun_ajarans`
--
ALTER TABLE `tahun_ajarans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `biayas`
--
ALTER TABLE `biayas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `biaya_jurusan`
--
ALTER TABLE `biaya_jurusan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `casis`
--
ALTER TABLE `casis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `casis_berkas`
--
ALTER TABLE `casis_berkas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_cita`
--
ALTER TABLE `master_cita`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `master_hobi`
--
ALTER TABLE `master_hobi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `master_jurusan`
--
ALTER TABLE `master_jurusan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `master_ketrampilan`
--
ALTER TABLE `master_ketrampilan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `master_orientasi_ortu`
--
ALTER TABLE `master_orientasi_ortu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `master_pekerjaan`
--
ALTER TABLE `master_pekerjaan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `master_pendidikan`
--
ALTER TABLE `master_pendidikan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `master_penghasilan`
--
ALTER TABLE `master_penghasilan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `nilai_rapor`
--
ALTER TABLE `nilai_rapor`
  MODIFY `id_nilai` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `program_keunggulan`
--
ALTER TABLE `program_keunggulan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `spmb_periods`
--
ALTER TABLE `spmb_periods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tahun_ajarans`
--
ALTER TABLE `tahun_ajarans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `biaya_jurusan`
--
ALTER TABLE `biaya_jurusan`
  ADD CONSTRAINT `biaya_jurusan_biaya_id_foreign` FOREIGN KEY (`biaya_id`) REFERENCES `biayas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `biaya_jurusan_jurusan_id_foreign` FOREIGN KEY (`jurusan_id`) REFERENCES `master_jurusan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `casis`
--
ALTER TABLE `casis`
  ADD CONSTRAINT `casis_cita_id_foreign` FOREIGN KEY (`cita_id`) REFERENCES `master_cita` (`id`),
  ADD CONSTRAINT `casis_hobi_id_foreign` FOREIGN KEY (`hobi_id`) REFERENCES `master_hobi` (`id`),
  ADD CONSTRAINT `casis_jurusan_id_foreign` FOREIGN KEY (`jurusan_id`) REFERENCES `master_jurusan` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `casis_ketrampilan_id_foreign` FOREIGN KEY (`ketrampilan_id`) REFERENCES `master_ketrampilan` (`id`),
  ADD CONSTRAINT `casis_orientasi_ortu_id_foreign` FOREIGN KEY (`orientasi_ortu_id`) REFERENCES `master_orientasi_ortu` (`id`),
  ADD CONSTRAINT `casis_pekerjaan_ayah_id_foreign` FOREIGN KEY (`pekerjaan_ayah_id`) REFERENCES `master_pekerjaan` (`id`),
  ADD CONSTRAINT `casis_pekerjaan_ibu_id_foreign` FOREIGN KEY (`pekerjaan_ibu_id`) REFERENCES `master_pekerjaan` (`id`),
  ADD CONSTRAINT `casis_pekerjaan_wali_id_foreign` FOREIGN KEY (`pekerjaan_wali_id`) REFERENCES `master_pekerjaan` (`id`),
  ADD CONSTRAINT `casis_pendidikan_ayah_id_foreign` FOREIGN KEY (`pendidikan_ayah_id`) REFERENCES `master_pendidikan` (`id`),
  ADD CONSTRAINT `casis_pendidikan_ibu_id_foreign` FOREIGN KEY (`pendidikan_ibu_id`) REFERENCES `master_pendidikan` (`id`),
  ADD CONSTRAINT `casis_pendidikan_wali_id_foreign` FOREIGN KEY (`pendidikan_wali_id`) REFERENCES `master_pendidikan` (`id`),
  ADD CONSTRAINT `casis_penghasilan_ayah_id_foreign` FOREIGN KEY (`penghasilan_ayah_id`) REFERENCES `master_penghasilan` (`id`),
  ADD CONSTRAINT `casis_penghasilan_ibu_id_foreign` FOREIGN KEY (`penghasilan_ibu_id`) REFERENCES `master_penghasilan` (`id`),
  ADD CONSTRAINT `casis_penghasilan_wali_id_foreign` FOREIGN KEY (`penghasilan_wali_id`) REFERENCES `master_penghasilan` (`id`),
  ADD CONSTRAINT `casis_program_keunggulan_id_foreign` FOREIGN KEY (`program_keunggulan_id`) REFERENCES `program_keunggulan` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `casis_spmb_period_id_foreign` FOREIGN KEY (`spmb_period_id`) REFERENCES `spmb_periods` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `casis_berkas`
--
ALTER TABLE `casis_berkas`
  ADD CONSTRAINT `casis_berkas_casis_id_foreign` FOREIGN KEY (`casis_id`) REFERENCES `casis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nilai_rapor`
--
ALTER TABLE `nilai_rapor`
  ADD CONSTRAINT `nilai_rapor_casis_id_foreign` FOREIGN KEY (`casis_id`) REFERENCES `casis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_casis_id_foreign` FOREIGN KEY (`casis_id`) REFERENCES `casis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayaran_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `program_keunggulan`
--
ALTER TABLE `program_keunggulan`
  ADD CONSTRAINT `program_keunggulan_jurusan_id_foreign` FOREIGN KEY (`jurusan_id`) REFERENCES `master_jurusan` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `spmb_periods`
--
ALTER TABLE `spmb_periods`
  ADD CONSTRAINT `spmb_periods_tahun_ajaran_id_foreign` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajarans` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
