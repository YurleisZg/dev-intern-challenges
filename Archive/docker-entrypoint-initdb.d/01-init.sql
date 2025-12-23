/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Crear base de datos phpaccountant
CREATE DATABASE IF NOT EXISTS phpaccountant DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE phpaccountant;

-- Estructura de tabla para la tabla `users`
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `email` varchar(100) NOT NULL UNIQUE,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `salary_records`
CREATE TABLE IF NOT EXISTS `salary_records` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `gross_salary_input` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'Draft',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`record_id`),
  KEY `fk_user_record` (`user_id`),
  CONSTRAINT `fk_user_record` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estructura de tabla para la tabla `salary_records_details`
CREATE TABLE IF NOT EXISTS `salary_records_details` (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `record_id` int(11) NOT NULL,
  `shift_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  PRIMARY KEY (`detail_id`),
  KEY `fk_record_details` (`record_id`),
  CONSTRAINT `fk_record_details` FOREIGN KEY (`record_id`) REFERENCES `salary_records` (`record_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertar datos de ejemplo en users
INSERT IGNORE INTO `users` (`user_id`, `username`, `email`, `password_hash`, `created_at`) VALUES
(1, 'elkin', 'elkin@gmail.com', '$2y$10$P/8rKMiIRAZ1AsC4Iyy4b..kB1q.CSOKtXFLgdfi58LsKXwXYQaBK', '2025-12-12 21:29:35'),
(2, 'david', 'david@gmail.com', '$2y$10$5LcTqm5B.6r7Rn4nGYCJQu/RXeYCYefmCfxvBMf515zv9WBg5/B8e', '2025-12-13 15:38:09');

-- Insertar datos de ejemplo en salary_records
INSERT IGNORE INTO `salary_records` (`record_id`, `user_id`, `gross_salary_input`, `status`, `created_at`, `updated_at`) VALUES
(5, 1, 2000000.00, 'Draft', '2025-12-12 23:02:30', '2025-12-12 23:02:30'),
(6, 2, 30000000.00, 'Draft', '2025-12-16 17:18:59', '2025-12-16 17:19:07');

-- Insertar datos de ejemplo en salary_records_details
INSERT IGNORE INTO `salary_records_details` (`detail_id`, `record_id`, `shift_date`, `start_time`, `end_time`) VALUES
(2, 5, '2025-12-13', '18:02:00', '19:02:00');

COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
