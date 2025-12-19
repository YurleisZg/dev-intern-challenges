/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Crear base de datos thirdchallenge
CREATE DATABASE IF NOT EXISTS thirdchallenge DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE thirdchallenge;

-- Estructura de tabla para la tabla `pattern_scores`
CREATE TABLE IF NOT EXISTS `pattern_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `score` int(11) NOT NULL,
  `time_seconds` float NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertar datos de ejemplo
INSERT IGNORE INTO `pattern_scores` (`id`, `username`, `score`, `time_seconds`, `created_at`) VALUES
(3, 'Elkin', 9750, 5, '2025-12-13 13:22:19'),
(4, 'David', 9650, 7, '2025-12-13 17:56:07');

COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
