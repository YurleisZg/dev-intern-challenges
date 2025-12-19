-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-12-2025 a las 16:02:47
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `phpaccountant`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salary_records`
--

CREATE TABLE `salary_records` (
  `record_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gross_salary_input` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'Draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salary_records`
--

INSERT INTO `salary_records` (`record_id`, `user_id`, `gross_salary_input`, `status`, `created_at`, `updated_at`) VALUES
(5, 1, 2000000.00, 'Draft', '2025-12-12 23:02:30', '2025-12-12 23:02:30'),
(6, 2, 30000000.00, 'Draft', '2025-12-16 17:18:59', '2025-12-16 17:19:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salary_records_details`
--

CREATE TABLE `salary_records_details` (
  `detail_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `shift_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salary_records_details`
--

INSERT INTO `salary_records_details` (`detail_id`, `record_id`, `shift_date`, `start_time`, `end_time`) VALUES
(2, 5, '2025-12-13', '18:02:00', '19:02:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `created_at`) VALUES
(1, 'elkin', 'elkin@gmail.com', '$2y$10$P/8rKMiIRAZ1AsC4Iyy4b..kB1q.CSOKtXFLgdfi58LsKXwXYQaBK', '2025-12-12 21:29:35'),
(2, 'david', 'david@gmail.com', '$2y$10$5LcTqm5B.6r7Rn4nGYCJQu/RXeYCYefmCfxvBMf515zv9WBg5/B8e', '2025-12-13 15:38:09');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `salary_records`
--
ALTER TABLE `salary_records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `fk_user_record` (`user_id`);

--
-- Indices de la tabla `salary_records_details`
--
ALTER TABLE `salary_records_details`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `fk_record_details` (`record_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `salary_records`
--
ALTER TABLE `salary_records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `salary_records_details`
--
ALTER TABLE `salary_records_details`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `salary_records`
--
ALTER TABLE `salary_records`
  ADD CONSTRAINT `fk_user_record` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `salary_records_details`
--
ALTER TABLE `salary_records_details`
  ADD CONSTRAINT `fk_record_details` FOREIGN KEY (`record_id`) REFERENCES `salary_records` (`record_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
