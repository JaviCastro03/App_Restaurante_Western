-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-05-2026 a las 09:12:03
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
-- Base de datos: `restaurante_western`
--
CREATE DATABASE IF NOT EXISTS `restaurante_western` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `restaurante_western`;

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `sp_cerrar_servicio`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_cerrar_servicio` (IN `p_servicio_id` INT, IN `p_ticket_path` VARCHAR(500))   BEGIN
    DECLARE v_total DECIMAL(10,2);
    
    SELECT SUM(subtotal) INTO v_total
    FROM pedidos 
    WHERE servicio_id = p_servicio_id AND estado != 'cancelado';
    
    UPDATE servicios 
    SET estado = 'cerrado', 
        total = COALESCE(v_total, 0),
        ticket_pdf = p_ticket_path,
        closed_at = NOW()
    WHERE id = p_servicio_id;
    
    UPDATE mesas SET estado = 'libre' 
    WHERE id = (SELECT mesa_id FROM servicios WHERE id = p_servicio_id);
    
    SELECT COALESCE(v_total, 0) AS total_cobrado;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_menu`
--

DROP TABLE IF EXISTS `categorias_menu`;
CREATE TABLE `categorias_menu` (
  `id` int(11) NOT NULL,
  `nombre_es` varchar(100) NOT NULL,
  `nombre_en` varchar(100) NOT NULL,
  `tipo` enum('comida','bebida','pan','postre') DEFAULT 'comida',
  `icono` varchar(50) DEFAULT '?️',
  `orden` int(11) DEFAULT 0,
  `activa` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias_menu`
--

INSERT INTO `categorias_menu` (`id`, `nombre_es`, `nombre_en`, `tipo`, `icono`, `orden`, `activa`) VALUES
(1, 'Entrantes', 'Starters', 'comida', '🥗', 1, 1),
(2, 'Carnes', 'Meats', 'comida', '🥩', 2, 1),
(3, 'Pescados', 'Fish', 'comida', '🐟', 3, 1),
(4, 'Guarniciones', 'Sides', 'comida', '🥔', 4, 1),
(5, 'Postres', 'Desserts', 'postre', '🍮', 5, 1),
(6, 'Vinos', 'Wines', 'bebida', '🍷', 6, 1),
(7, 'Cervezas', 'Beers', 'bebida', '🍺', 7, 1),
(8, 'Refrescos', 'Soft Drinks', 'bebida', '🥤', 8, 1),
(9, 'Agua', 'Water', 'bebida', '💧', 9, 1),
(10, 'Pan y Extras', 'Bread & Extras', 'pan', '🥖', 10, 1),
(11, 'Hamburguesas', 'Burgers', 'comida', '🍔', 11, 1),
(12, 'Sandwiches', 'Sandwiches', 'comida', '🥪', 12, 1),
(13, 'Pastas', 'Pasta', 'comida', '🍝', 13, 1),
(14, 'Bocadillos', 'Rolls', 'pan', '🥖', 14, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

DROP TABLE IF EXISTS `configuracion`;
CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL DEFAULT 1 COMMENT 'Solo existe 1 fila - Singleton',
  `nombre_restaurante` varchar(200) DEFAULT 'El Último Corral',
  `slogan_es` varchar(500) DEFAULT 'Donde el sabor no tiene fronteras',
  `slogan_en` varchar(500) DEFAULT 'Where flavor knows no boundaries',
  `descripcion_es` text DEFAULT NULL,
  `descripcion_en` text DEFAULT NULL,
  `direccion` varchar(500) DEFAULT 'Calle Rodríguez Acosta, 23680 Alcalá la Real, Jaén',
  `telefono` varchar(20) DEFAULT '953 000 000',
  `email_contacto` varchar(255) DEFAULT 'info@elultimocorral.es',
  `google_maps_key` varchar(255) DEFAULT '',
  `google_client_id` varchar(255) DEFAULT '',
  `logo` varchar(500) DEFAULT NULL,
  `horario_es` text DEFAULT NULL,
  `horario_en` text DEFAULT NULL,
  `facebook` varchar(500) DEFAULT NULL,
  `instagram` varchar(500) DEFAULT NULL,
  `twitter` varchar(500) DEFAULT NULL,
  `iva` decimal(5,2) DEFAULT 10.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `nombre_restaurante`, `slogan_es`, `slogan_en`, `descripcion_es`, `descripcion_en`, `direccion`, `telefono`, `email_contacto`, `google_maps_key`, `google_client_id`, `logo`, `horario_es`, `horario_en`, `facebook`, `instagram`, `twitter`, `iva`) VALUES
(1, 'El Último Corral', 'Donde el sabor no tiene fronteras', 'Where flavor knows no boundaries', 'Bienvenido a El Último Corral, donde la tradición culinaria andaluza se funde con el espíritu del lejano oeste. Nuestras recetas, transmitidas de generación en generación, son preparadas con los mejores ingredientes de la tierra de Jaén.', 'Welcome to El Último Corral, where Andalusian culinary tradition merges with the spirit of the Wild West. Our recipes, passed down through generations, are prepared with the finest ingredients from the land of Jaén.', 'Calle Rodríguez Acosta, 23680 Alcalá la Real, Jaén', '953 000 000', 'info@elultimocorral.es', '', '', NULL, 'Lunes a Sábado: 13:00 - 00:30 | Domingos: Cerrado', 'Monday to Saturday: 13:00 - 00:30 | Sundays: Closed', NULL, NULL, NULL, 10.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dias_apertura`
--

DROP TABLE IF EXISTS `dias_apertura`;
CREATE TABLE `dias_apertura` (
  `id` int(11) NOT NULL,
  `dia_semana` tinyint(4) NOT NULL COMMENT '0=Lunes ... 6=Domingo',
  `abierto` tinyint(4) DEFAULT 1,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `dias_apertura`
--

INSERT INTO `dias_apertura` (`id`, `dia_semana`, `abierto`, `updated_at`) VALUES
(1, 0, 1, '2026-04-25 16:45:35'),
(2, 1, 1, '2026-04-25 16:45:35'),
(3, 2, 1, '2026-04-25 16:45:35'),
(4, 3, 1, '2026-04-25 16:45:35'),
(5, 4, 1, '2026-04-25 16:45:35'),
(6, 5, 1, '2026-04-25 16:45:35'),
(7, 6, 0, '2026-04-25 16:45:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dias_descanso`
--

DROP TABLE IF EXISTS `dias_descanso`;
CREATE TABLE `dias_descanso` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadisticas_diarias`
--

DROP TABLE IF EXISTS `estadisticas_diarias`;
CREATE TABLE `estadisticas_diarias` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `total_servicios` int(11) DEFAULT 0,
  `total_comensales` int(11) DEFAULT 0,
  `total_ingresos` decimal(12,2) DEFAULT 0.00,
  `total_reservas` int(11) DEFAULT 0,
  `plato_mas_vendido` int(11) DEFAULT NULL,
  `bebida_mas_vendida` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `nombre_es` varchar(200) NOT NULL,
  `nombre_en` varchar(200) NOT NULL,
  `descripcion_es` text DEFAULT NULL,
  `descripcion_en` text DEFAULT NULL,
  `precio` decimal(8,2) NOT NULL,
  `imagen` varchar(500) DEFAULT NULL,
  `alergenos` varchar(500) DEFAULT NULL,
  `disponible` tinyint(4) DEFAULT 1,
  `destacado` tinyint(4) DEFAULT 0,
  `orden` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `menu_items`
--

INSERT INTO `menu_items` (`id`, `categoria_id`, `nombre_es`, `nombre_en`, `descripcion_es`, `descripcion_en`, `precio`, `imagen`, `alergenos`, `disponible`, `destacado`, `orden`, `created_at`) VALUES
(1, 1, 'Jamón Ibérico de Bellota', 'Acorn-fed Iberian Ham', 'Lonchas de jamón ibérico de bellota con pan tostado', 'Slices of acorn-fed Iberian ham with toasted bread', 18.50, NULL, 'Gluten', 1, 1, 0, '2026-04-25 16:45:36'),
(2, 1, 'Croquetas de la Abuela', 'Grandma\'s Croquettes', 'Croquetas caseras de jamón y bechamel', 'Homemade ham and béchamel croquettes', 9.50, NULL, 'Gluten, Lactosa, Huevo', 1, 1, 0, '2026-04-25 16:45:36'),
(3, 1, 'Salmorejo Cordobés', 'Córdoba Salmorejo', 'Crema fría de tomate con virutas de jamón y huevo duro', 'Cold tomato cream with ham shavings and hard-boiled egg', 8.00, NULL, 'Gluten, Huevo', 1, 0, 0, '2026-04-25 16:45:36'),
(4, 2, 'Solomillo a la Brasa', 'Grilled Sirloin', 'Solomillo de ternera a la brasa con patatas panaderas', 'Grilled beef sirloin with baker potatoes', 24.00, NULL, '', 1, 1, 0, '2026-04-25 16:45:36'),
(5, 2, 'Cordero al Horno', 'Roasted Lamb', 'Pierna de cordero lechal al horno con verduras', 'Roasted milk-fed lamb leg with vegetables', 22.00, NULL, '', 1, 0, 0, '2026-04-25 16:45:36'),
(6, 2, 'Rabo de Toro Estofado', 'Braised Oxtail', 'Estofado tradicional de rabo de toro con patatas fritas', 'Traditional braised oxtail stew with fried potatoes', 19.50, NULL, '', 1, 1, 0, '2026-04-25 16:45:36'),
(7, 3, 'Bacalao al Pil-Pil', 'Cod in Pil-Pil Sauce', 'Bacalao confitado en su propia gelatina con alioli', 'Confit cod in its own gelatin with aioli', 21.00, NULL, 'Pescado', 1, 0, 0, '2026-04-25 16:45:36'),
(8, 3, 'Dorada a la Sal', 'Sea Bream in Salt Crust', 'Dorada fresca cocinada en costra de sal', 'Fresh sea bream cooked in salt crust', 20.00, NULL, 'Pescado', 1, 0, 0, '2026-04-25 16:45:36'),
(9, 4, 'Patatas Bravas', 'Spicy Potatoes', 'Patatas fritas con salsa brava y alioli', 'Fried potatoes with spicy sauce and aioli', 6.50, NULL, 'Huevo', 1, 0, 0, '2026-04-25 16:45:36'),
(10, 4, 'Pimientos del Padrón', 'Padrón Peppers', 'Pimientos del padrón fritos con sal marina', 'Fried Padrón peppers with sea salt', 7.00, NULL, '', 1, 0, 0, '2026-04-25 16:45:36'),
(11, 5, 'Tarta de Queso', 'Cheesecake', 'Tarta de queso artesanal con coulis de frutos rojos', 'Artisan cheesecake with red berry coulis', 7.50, NULL, 'Lactosa, Gluten, Huevo', 1, 1, 0, '2026-04-25 16:45:36'),
(12, 5, 'Torrijas al Vino', 'Wine French Toast', 'Torrijas tradicionales bañadas en vino tinto', 'Traditional French toast soaked in red wine', 6.00, NULL, 'Gluten, Lactosa, Huevo', 1, 0, 0, '2026-04-25 16:45:36'),
(13, 6, 'Vino de la Casa (Copa)', 'House Wine (Glass)', 'Vino tinto/blanco de la casa', 'House red/white wine', 3.50, NULL, 'Sulfitos', 1, 0, 0, '2026-04-25 16:45:36'),
(14, 6, 'Botella Ribera del Duero', 'Ribera del Duero Bottle', 'Botella de vino tinto Ribera del Duero crianza', 'Bottle of Ribera del Duero aged red wine', 22.00, NULL, 'Sulfitos', 1, 0, 0, '2026-04-25 16:45:36'),
(15, 7, 'Cerveza Nacional', 'National Beer', 'Cerveza española en caña o botellín', 'Spanish beer draft or bottle', 2.50, NULL, 'Gluten', 1, 0, 0, '2026-04-25 16:45:36'),
(16, 7, 'Cerveza Artesanal', 'Craft Beer', 'Cerveza artesanal local', 'Local craft beer', 4.50, NULL, 'Gluten', 1, 0, 0, '2026-04-25 16:45:36'),
(17, 8, 'Refresco', 'Soft Drink', 'Coca-Cola, Fanta, Nestea, Sprite', 'Coca-Cola, Fanta, Nestea, Sprite', 2.50, NULL, '', 1, 0, 0, '2026-04-25 16:45:36'),
(18, 9, 'Agua Mineral (0.5L)', 'Mineral Water (0.5L)', 'Agua mineral natural o con gas', 'Natural or sparkling mineral water', 1.80, NULL, '', 1, 0, 0, '2026-04-25 16:45:36'),
(19, 9, 'Agua Mineral (1L)', 'Mineral Water (1L)', 'Agua mineral natural o con gas', 'Natural or sparkling mineral water', 2.80, NULL, '', 1, 0, 0, '2026-04-25 16:45:36'),
(20, 10, 'Pan de la Casa', 'House Bread', 'Pan artesano con aceite de oliva virgen extra', 'Artisan bread with extra virgin olive oil', 2.00, NULL, 'Gluten', 1, 0, 0, '2026-04-25 16:45:36'),
(21, 10, 'Pan de Ajo', 'Garlic Bread', 'Pan tostado con mantequilla de ajo y perejil', 'Toasted bread with garlic and parsley butter', 3.50, NULL, 'Gluten, Lactosa', 1, 0, 0, '2026-04-25 16:45:36'),
(22, 11, 'Hamburguesa Clásica', 'Classic Burger', 'Hamburguesa de ternera con lechuga, tomate y cebolla', 'Beef burger with lettuce, tomato and onion', 12.50, NULL, 'Gluten, Lactosa, Huevo', 1, 0, 10, '2026-05-26 21:55:45'),
(23, 11, 'Hamburguesa BBQ Western', 'BBQ Western Burger', 'Hamburguesa de ternera con bacon, cheddar y salsa BBQ', 'Beef burger with bacon, cheddar and BBQ sauce', 14.50, NULL, 'Gluten, Lactosa, Huevo', 1, 1, 11, '2026-05-26 21:55:45'),
(24, 11, 'Hamburguesa de Pollo Crujiente', 'Crispy Chicken Burger', 'Pechuga de pollo empanada con mayonesa y pepinillos', 'Breaded chicken breast with mayo and pickles', 12.00, NULL, 'Gluten, Huevo', 1, 0, 12, '2026-05-26 21:55:45'),
(25, 11, 'Hamburguesa Doble Vaquera', 'Double Cowboy Burger', 'Doble carne de ternera, doble cheddar y salsa especial', 'Double beef patty, double cheddar and special sauce', 16.50, NULL, 'Gluten, Lactosa, Huevo', 1, 1, 13, '2026-05-26 21:55:45'),
(26, 2, 'Presa Ibérica a la Brasa', 'Grilled Iberian Presa', 'Presa ibérica a la brasa con chimichurri y patatas', 'Grilled Iberian presa with chimichurri and potatoes', 21.00, NULL, '', 1, 1, 14, '2026-05-26 21:55:45'),
(27, 2, 'Secreto Ibérico al Carbón', 'Charcoal Iberian Secreto', 'Secreto ibérico al carbón con pimientos asados', 'Charcoal Iberian secreto with roasted peppers', 19.50, NULL, '', 1, 1, 15, '2026-05-26 21:55:45'),
(28, 2, 'Ternera a la Plancha', 'Grilled Veal', 'Filete de ternera a la plancha con guarnición', 'Grilled veal fillet with side dish', 17.00, NULL, '', 1, 0, 16, '2026-05-26 21:55:45'),
(29, 2, 'Entrecot de Ternera', 'Veal Entrecote', 'Entrecot de ternera madurada con patatas y ensalada', 'Aged veal entrecote with potatoes and salad', 23.00, NULL, '', 1, 1, 17, '2026-05-26 21:55:45'),
(30, 3, 'Merluza a la Romana', 'Battered Hake', 'Merluza rebozada con patatas fritas y limón', 'Battered hake with fried potatoes and lemon', 16.50, NULL, 'Pescado, Gluten, Huevo', 1, 0, 10, '2026-05-26 21:55:45'),
(31, 3, 'Gambas al Ajillo', 'Garlic Prawns', 'Gambas salteadas con ajo, guindilla y aceite de oliva', 'Prawns sautéed with garlic, chili and olive oil', 14.00, NULL, 'Crustáceos', 1, 1, 11, '2026-05-26 21:55:45'),
(32, 5, 'Tarta de Santiago', 'Santiago Almond Cake', 'Tarta tradicional de almendra con azúcar glass', 'Traditional almond cake with icing sugar', 6.50, NULL, 'Frutos secos, Huevo', 1, 0, 10, '2026-05-26 21:55:45'),
(33, 5, 'Tarta de Chocolate', 'Chocolate Cake', 'Tarta de chocolate negro con crema de avellanas', 'Dark chocolate cake with hazelnut cream', 7.00, NULL, 'Gluten, Lactosa, Huevo, Frutos secos', 1, 1, 11, '2026-05-26 21:55:45'),
(34, 5, 'Tarta de Manzana', 'Apple Tart', 'Tarta de manzana casera con canela y helado de vainilla', 'Homemade apple tart with cinnamon and vanilla ice cream', 6.50, NULL, 'Gluten, Lactosa, Huevo', 1, 0, 12, '2026-05-26 21:55:45'),
(35, 4, 'Ensalada César', 'Caesar Salad', 'Lechuga romana, pollo, crutones, parmesano y salsa César', 'Romaine lettuce, chicken, croutons, parmesan and Caesar dressing', 10.50, NULL, 'Gluten, Lactosa, Huevo, Pescado', 1, 0, 10, '2026-05-26 21:55:45'),
(36, 4, 'Ensalada de la Casa', 'House Salad', 'Lechuga, tomate, cebolla, aceitunas, atún y huevo duro', 'Lettuce, tomato, onion, olives, tuna and hard-boiled egg', 9.00, NULL, 'Pescado, Huevo', 1, 0, 11, '2026-05-26 21:55:45'),
(37, 12, 'Sandwich Mixto', 'Mixed Sandwich', 'Sandwich de jamón york y queso con pan de molde tostado', 'Ham and cheese sandwich with toasted bread', 5.50, NULL, 'Gluten, Lactosa', 1, 0, 10, '2026-05-26 21:55:45'),
(38, 12, 'Sandwich Club', 'Club Sandwich', 'Sandwich triple con pollo, bacon, lechuga, tomate y mayonesa', 'Triple sandwich with chicken, bacon, lettuce, tomato and mayo', 8.50, NULL, 'Gluten, Lactosa, Huevo', 1, 0, 11, '2026-05-26 21:55:45'),
(39, 14, 'Bocadillo de Jamón Serrano', 'Serrano Ham Roll', 'Bocadillo de jamón serrano en pan de barra artesano', 'Serrano ham in artisan baguette', 6.50, NULL, 'Gluten', 1, 0, 12, '2026-05-26 21:55:45'),
(40, 14, 'Bocadillo de Lomo', 'Pork Loin Roll', 'Bocadillo de lomo a la plancha con pimientos y tomate', 'Grilled pork loin roll with peppers and tomato', 7.00, NULL, 'Gluten', 1, 0, 13, '2026-05-26 21:55:45'),
(41, 14, 'Bocadillo de Tortilla', 'Omelette Roll', 'Bocadillo de tortilla española en pan de barra', 'Spanish omelette in baguette', 5.50, NULL, 'Gluten, Huevo', 1, 0, 14, '2026-05-26 21:55:45'),
(42, 8, 'Refresco Zero', 'Zero Soft Drink', 'Coca-Cola Zero, Fanta Zero, sin azúcar', 'Coca-Cola Zero, Fanta Zero, sugar-free', 2.50, NULL, '', 1, 0, 10, '2026-05-26 21:55:45'),
(43, 7, 'Cerveza Sin Alcohol', 'Non-Alcoholic Beer', 'Cerveza nacional sin alcohol 0,0', 'National non-alcoholic beer 0.0', 2.50, NULL, 'Gluten', 1, 0, 10, '2026-05-26 21:55:45'),
(44, 6, 'Botella Albariño', 'Albariño Bottle', 'Botella de vino blanco Albariño Rías Baixas', 'Bottle of Albariño white wine from Rías Baixas', 19.00, NULL, 'Sulfitos', 1, 0, 10, '2026-05-26 21:55:45'),
(45, 6, 'Copa Vino Blanco', 'White Wine Glass', 'Copa de vino blanco de la casa', 'House white wine glass', 3.50, NULL, 'Sulfitos', 1, 0, 11, '2026-05-26 21:55:45'),
(46, 13, 'Pasta Carbonara', 'Carbonara Pasta', 'Espaguetis con salsa carbonara, bacon y parmesano', 'Spaghetti with carbonara sauce, bacon and parmesan', 13.00, NULL, 'Gluten, Lactosa, Huevo', 1, 0, 20, '2026-05-26 21:55:45'),
(47, 13, 'Pasta Boloñesa', 'Bolognese Pasta', 'Espaguetis con ragú de carne y tomate', 'Spaghetti with meat and tomato ragù', 12.50, NULL, 'Gluten, Lactosa', 1, 0, 21, '2026-05-26 21:55:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

DROP TABLE IF EXISTS `mesas`;
CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `capacidad` int(11) NOT NULL DEFAULT 4,
  `zona_id` int(11) NOT NULL,
  `estado` enum('libre','ocupada','reservada','mantenimiento') DEFAULT 'libre',
  `descripcion` text DEFAULT NULL,
  `activa` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `numero`, `capacidad`, `zona_id`, `estado`, `descripcion`, `activa`, `created_at`) VALUES
(1, 'M1', 2, 1, 'libre', NULL, 1, '2026-04-25 16:45:35'),
(2, 'M2', 4, 1, 'libre', NULL, 1, '2026-04-25 16:45:35'),
(3, 'M3', 4, 1, 'libre', NULL, 1, '2026-04-25 16:45:35'),
(4, 'M4', 6, 1, 'libre', NULL, 1, '2026-04-25 16:45:35'),
(5, 'M5', 8, 1, 'libre', NULL, 1, '2026-04-25 16:45:35'),
(6, 'T1', 2, 2, 'libre', NULL, 1, '2026-04-25 16:45:35'),
(7, 'T2', 4, 2, 'libre', NULL, 1, '2026-04-25 16:45:35'),
(8, 'T3', 4, 2, 'libre', NULL, 1, '2026-04-25 16:45:35'),
(9, 'T4', 6, 2, 'libre', NULL, 1, '2026-04-25 16:45:35'),
(10, 'P1', 10, 3, 'ocupada', NULL, 1, '2026-04-25 16:45:35'),
(11, 'P2', 12, 3, 'libre', NULL, 1, '2026-04-25 16:45:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(8,2) NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (`cantidad` * `precio_unitario`) STORED,
  `notas` text DEFAULT NULL,
  `estado` enum('pendiente','preparando','servido','cancelado') DEFAULT 'pendiente',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `servicio_id`, `item_id`, `cantidad`, `precio_unitario`, `notas`, `estado`, `created_at`) VALUES
(1, 1, 2, 1, 9.50, '', 'pendiente', '2026-05-26 16:50:03'),
(2, 1, 6, 1, 19.50, '', 'pendiente', '2026-05-26 16:50:08'),
(3, 1, 7, 1, 21.00, '', 'pendiente', '2026-05-26 16:50:12'),
(4, 1, 17, 2, 2.50, '', 'pendiente', '2026-05-26 16:50:16'),
(5, 1, 15, 1, 2.50, '', 'pendiente', '2026-05-26 16:50:22'),
(6, 1, 20, 1, 2.00, '', 'pendiente', '2026-05-26 16:50:25'),
(7, 1, 11, 2, 7.50, '', 'pendiente', '2026-05-26 16:50:30'),
(8, 3, 1, 1, 18.50, '', 'pendiente', '2026-05-26 16:51:42'),
(9, 3, 19, 1, 2.80, '', 'pendiente', '2026-05-26 16:51:45'),
(10, 6, 17, 2, 2.50, '', 'pendiente', '2026-05-26 21:44:57'),
(11, 6, 15, 1, 2.50, '', 'pendiente', '2026-05-26 21:45:02'),
(12, 6, 2, 1, 9.50, '', 'pendiente', '2026-05-26 21:45:06'),
(13, 5, 3, 1, 8.00, '', 'pendiente', '2026-05-26 21:49:49'),
(14, 5, 20, 1, 2.00, '', 'pendiente', '2026-05-26 21:49:52'),
(15, 7, 1, 1, 18.50, '', 'pendiente', '2026-05-27 11:21:20'),
(16, 8, 19, 1, 2.80, '', 'pendiente', '2026-05-27 11:25:14'),
(17, 8, 46, 1, 13.00, '', 'pendiente', '2026-05-27 11:25:16'),
(18, 9, 40, 1, 7.00, '', 'pendiente', '2026-05-27 12:24:53'),
(19, 9, 12, 1, 6.00, '', 'pendiente', '2026-05-27 12:24:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

DROP TABLE IF EXISTS `reservas`;
CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `mesa_id` int(11) NOT NULL,
  `turno_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `num_comensales` int(11) NOT NULL DEFAULT 1,
  `estado` enum('pendiente','confirmada','cancelada','completada') DEFAULT 'pendiente',
  `observaciones` text DEFAULT NULL,
  `token_reserva` varchar(64) DEFAULT NULL,
  `email_enviado` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `mesa_id`, `turno_id`, `fecha`, `num_comensales`, `estado`, `observaciones`, `token_reserva`, `email_enviado`, `created_at`, `updated_at`) VALUES
(1, 2, 8, 6, '2026-05-30', 1, 'pendiente', 'Intolerante a la lactosa', 'da259066b8bee7f4694b271f4d1039c99a11ed7c0eb2fe33263c02f50e43ade3', 0, '2026-05-26 16:34:38', '2026-05-26 16:34:38'),
(2, 5, 11, 3, '2026-06-04', 12, 'pendiente', 'Trona niño', '953c192465c59959c88cde3406420fee8ede51ab08cda0053fe084baa46ba9e2', 0, '2026-05-26 21:43:13', '2026-05-26 21:43:13'),
(3, 7, 2, 3, '2026-05-30', 3, 'pendiente', '', '2db10f3bc05b7382528cfbd2403475d5fd0681c1b4133ee598406fd967fd1081', 0, '2026-05-27 12:31:02', '2026-05-27 12:31:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

DROP TABLE IF EXISTS `servicios`;
CREATE TABLE `servicios` (
  `id` int(11) NOT NULL,
  `mesa_id` int(11) NOT NULL,
  `turno_id` int(11) NOT NULL,
  `camarero_id` int(11) NOT NULL,
  `reserva_id` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `num_comensales` int(11) DEFAULT 1,
  `estado` enum('abierto','cerrado','cancelado') DEFAULT 'abierto',
  `total` decimal(10,2) DEFAULT 0.00,
  `ticket_pdf` varchar(500) DEFAULT NULL,
  `factura_enviada` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `closed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id`, `mesa_id`, `turno_id`, `camarero_id`, `reserva_id`, `fecha`, `num_comensales`, `estado`, `total`, `ticket_pdf`, `factura_enviada`, `created_at`, `closed_at`) VALUES
(1, 7, 6, 2, NULL, '2026-05-26', 2, 'cerrado', 74.50, '', 0, '2026-05-26 16:49:35', '2026-05-26 16:50:48'),
(2, 10, 5, 2, NULL, '2026-05-26', 8, 'cerrado', 0.00, '', 0, '2026-05-26 16:51:07', '2026-05-26 16:57:12'),
(3, 10, 5, 2, NULL, '2026-05-26', 8, 'cerrado', 21.30, '', 0, '2026-05-26 16:51:20', '2026-05-26 16:58:42'),
(4, 10, 5, 2, NULL, '2026-05-26', 9, 'abierto', 0.00, NULL, 0, '2026-05-26 16:59:10', NULL),
(5, 7, 5, 2, NULL, '2026-05-26', 2, 'cerrado', 10.00, 'uploads/tickets/ticket_5_20260527_112058.pdf', 0, '2026-05-26 16:59:41', '2026-05-27 11:20:58'),
(6, 8, 3, 2, NULL, '2026-05-26', 3, 'cerrado', 17.00, '', 0, '2026-05-26 21:44:49', '2026-05-26 21:45:21'),
(7, 1, 5, 2, NULL, '2026-05-27', 4, 'cerrado', 18.50, 'uploads/tickets/ticket_7_20260527_112124.pdf', 0, '2026-05-27 11:21:16', '2026-05-27 11:21:24'),
(8, 2, 5, 2, NULL, '2026-05-27', 2, 'cerrado', 15.80, 'uploads/tickets/ticket_8_20260527_112519.pdf', 0, '2026-05-27 11:25:11', '2026-05-27 11:25:19'),
(9, 2, 6, 2, NULL, '2026-05-27', 2, 'cerrado', 13.00, 'uploads/tickets/ticket_9_20260527_122509.pdf', 0, '2026-05-27 12:24:46', '2026-05-27 12:25:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

DROP TABLE IF EXISTS `turnos`;
CREATE TABLE `turnos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `tipo` enum('comida','cena','desayuno','merienda') DEFAULT 'comida',
  `activo` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id`, `nombre`, `hora_inicio`, `hora_fin`, `tipo`, `activo`, `created_at`) VALUES
(1, 'Comida 1er Turno', '13:00:00', '15:00:00', 'comida', 1, '2026-04-25 16:45:35'),
(2, 'Comida 2º Turno', '15:00:00', '17:00:00', 'comida', 1, '2026-04-25 16:45:35'),
(3, 'Cena 1er Turno', '20:30:00', '22:30:00', 'cena', 1, '2026-04-25 16:45:35'),
(4, 'Cena 2º Turno', '22:30:00', '00:30:00', 'cena', 1, '2026-04-25 16:45:35'),
(5, 'Desayuno 1er Turno', '08:30:00', '10:30:00', 'desayuno', 1, '2026-04-25 16:45:35'),
(6, 'Desayuno 2º Turno', '10:30:00', '12:30:00', 'desayuno', 1, '2026-04-25 16:45:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` tinyint(4) NOT NULL DEFAULT 3 COMMENT '0=Admin, 1=Camarero, 2=Sin_verificar, 3=Cliente',
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(500) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `via` varchar(255) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `piso` varchar(10) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `activo` tinyint(4) DEFAULT 1,
  `token_verificacion` varchar(64) DEFAULT NULL,
  `idioma` varchar(5) DEFAULT 'es'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `dni`, `nombre`, `apellidos`, `email`, `password`, `rol`, `google_id`, `avatar`, `telefono`, `provincia`, `municipio`, `via`, `numero`, `piso`, `fecha_registro`, `activo`, `token_verificacion`, `idioma`) VALUES
(1, '00000000A', 'Administrador', 'Principal', 'admin@elultimocorral.es', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-25 16:45:36', 1, NULL, 'es'),
(2, '78276539H', 'Javi', 'Castro', 'javiliyo333@gmail.com', '$2y$10$wx2/62aa60xD4UVkv5IaheB/WZbdXdRfE3mUPqbLgvjyr4bSIpdUe', 0, NULL, NULL, '63256223', 'CADIZ', 'CADIZ', 'CL - ALCALA DE GUADAIRA', NULL, NULL, '2026-05-25 16:07:45', 1, NULL, 'es'),
(5, 'GOOGLE', 'Javi', 'Castro', 'javiercastromuriana2006@gmail.com', '', 3, '105437631450748058579', 'https://lh3.googleusercontent.com/a/ACg8ocJvXSrpwvc2KUseZHsSdnjU9vYn6bI6Dn2ySgrsDvikkZKmFw=s96-c', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-26 21:42:23', 0, NULL, 'es'),
(6, 'GOOGLE', 'Javier', 'Castro Muriana', 'jcastro2gs@gmail.com', '', 3, '115914857701267354352', 'https://lh3.googleusercontent.com/a/ACg8ocIRRvNO8JfTXRYEA7kjlUu1NxWLy2RtvCjJ7QwruzPN8ByiUA=s96-c', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-26 22:04:46', 1, NULL, 'es'),
(7, 'GOOGLE', 'Holo', 'Yolo', 'yoloholo472@gmail.com', '', 3, '109203183405192169158', 'https://lh3.googleusercontent.com/a/ACg8ocJYI__npuKwOHtDewAFRg8IFhhwTPmZGLG7JYopj1DcZDyY0Q=s96-c', NULL, NULL, NULL, NULL, NULL, NULL, '2026-05-27 12:22:56', 1, NULL, 'es');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_ingresos_por_dia`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `v_ingresos_por_dia`;
CREATE TABLE `v_ingresos_por_dia` (
`fecha` date
,`num_servicios` bigint(21)
,`total_comensales` decimal(32,0)
,`total_ingresos` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_reservas_hoy`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `v_reservas_hoy`;
CREATE TABLE `v_reservas_hoy` (
`id` int(11)
,`fecha` date
,`num_comensales` int(11)
,`estado` enum('pendiente','confirmada','cancelada','completada')
,`observaciones` text
,`cliente` varchar(251)
,`cliente_email` varchar(255)
,`cliente_telefono` varchar(20)
,`mesa` varchar(10)
,`zona` varchar(100)
,`turno` varchar(100)
,`hora_inicio` time
,`hora_fin` time
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_servicios_activos`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `v_servicios_activos`;
CREATE TABLE `v_servicios_activos` (
`id` int(11)
,`fecha` date
,`num_comensales` int(11)
,`estado` enum('abierto','cerrado','cancelado')
,`total` decimal(10,2)
,`mesa_numero` varchar(10)
,`zona` varchar(100)
,`turno` varchar(100)
,`hora_inicio` time
,`hora_fin` time
,`camarero` varchar(251)
,`created_at` datetime
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zonas`
--

DROP TABLE IF EXISTS `zonas`;
CREATE TABLE `zonas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activa` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `zonas`
--

INSERT INTO `zonas` (`id`, `nombre`, `descripcion`, `activa`, `created_at`) VALUES
(1, 'Salón Principal', 'Zona interior climatizada', 1, '2026-04-25 16:45:35'),
(2, 'Terraza', 'Zona exterior con vistas al jardín', 1, '2026-04-25 16:45:35'),
(3, 'Sala Privada', 'Sala para eventos y reuniones privadas', 1, '2026-04-25 16:45:35');

-- --------------------------------------------------------

--
-- Estructura para la vista `v_ingresos_por_dia`
--
DROP TABLE IF EXISTS `v_ingresos_por_dia`;

DROP VIEW IF EXISTS `v_ingresos_por_dia`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_ingresos_por_dia`  AS SELECT cast(`servicios`.`closed_at` as date) AS `fecha`, count(0) AS `num_servicios`, sum(`servicios`.`num_comensales`) AS `total_comensales`, sum(`servicios`.`total`) AS `total_ingresos` FROM `servicios` WHERE `servicios`.`estado` = 'cerrado' GROUP BY cast(`servicios`.`closed_at` as date) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_reservas_hoy`
--
DROP TABLE IF EXISTS `v_reservas_hoy`;

DROP VIEW IF EXISTS `v_reservas_hoy`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_reservas_hoy`  AS SELECT `r`.`id` AS `id`, `r`.`fecha` AS `fecha`, `r`.`num_comensales` AS `num_comensales`, `r`.`estado` AS `estado`, `r`.`observaciones` AS `observaciones`, concat(`u`.`nombre`,' ',`u`.`apellidos`) AS `cliente`, `u`.`email` AS `cliente_email`, `u`.`telefono` AS `cliente_telefono`, `m`.`numero` AS `mesa`, `z`.`nombre` AS `zona`, `t`.`nombre` AS `turno`, `t`.`hora_inicio` AS `hora_inicio`, `t`.`hora_fin` AS `hora_fin` FROM ((((`reservas` `r` join `usuarios` `u` on(`r`.`usuario_id` = `u`.`id`)) join `mesas` `m` on(`r`.`mesa_id` = `m`.`id`)) join `zonas` `z` on(`m`.`zona_id` = `z`.`id`)) join `turnos` `t` on(`r`.`turno_id` = `t`.`id`)) WHERE `r`.`fecha` = curdate() ;

-- --------------------------------------------------------

--
-- Estructura para la vista `v_servicios_activos`
--
DROP TABLE IF EXISTS `v_servicios_activos`;

DROP VIEW IF EXISTS `v_servicios_activos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_servicios_activos`  AS SELECT `s`.`id` AS `id`, `s`.`fecha` AS `fecha`, `s`.`num_comensales` AS `num_comensales`, `s`.`estado` AS `estado`, `s`.`total` AS `total`, `m`.`numero` AS `mesa_numero`, `z`.`nombre` AS `zona`, `t`.`nombre` AS `turno`, `t`.`hora_inicio` AS `hora_inicio`, `t`.`hora_fin` AS `hora_fin`, concat(`u`.`nombre`,' ',`u`.`apellidos`) AS `camarero`, `s`.`created_at` AS `created_at` FROM ((((`servicios` `s` join `mesas` `m` on(`s`.`mesa_id` = `m`.`id`)) join `zonas` `z` on(`m`.`zona_id` = `z`.`id`)) join `turnos` `t` on(`s`.`turno_id` = `t`.`id`)) join `usuarios` `u` on(`s`.`camarero_id` = `u`.`id`)) WHERE `s`.`estado` = 'abierto' ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias_menu`
--
ALTER TABLE `categorias_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `dias_apertura`
--
ALTER TABLE `dias_apertura`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_dia` (`dia_semana`);

--
-- Indices de la tabla `dias_descanso`
--
ALTER TABLE `dias_descanso`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fecha` (`fecha`);

--
-- Indices de la tabla `estadisticas_diarias`
--
ALTER TABLE `estadisticas_diarias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fecha` (`fecha`),
  ADD KEY `plato_mas_vendido` (`plato_mas_vendido`),
  ADD KEY `bebida_mas_vendida` (`bebida_mas_vendida`);

--
-- Indices de la tabla `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_categoria` (`categoria_id`),
  ADD KEY `idx_disponible` (`disponible`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_zona` (`zona_id`),
  ADD KEY `idx_estado` (`estado`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `idx_servicio` (`servicio_id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mesa_id` (`mesa_id`),
  ADD KEY `turno_id` (`turno_id`),
  ADD KEY `idx_fecha` (`fecha`),
  ADD KEY `idx_usuario` (`usuario_id`),
  ADD KEY `idx_estado` (`estado`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mesa_id` (`mesa_id`),
  ADD KEY `turno_id` (`turno_id`),
  ADD KEY `reserva_id` (`reserva_id`),
  ADD KEY `idx_fecha_servicio` (`fecha`),
  ADD KEY `idx_camarero` (`camarero_id`),
  ADD KEY `idx_estado_servicio` (`estado`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tipo` (`tipo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_rol` (`rol`);

--
-- Indices de la tabla `zonas`
--
ALTER TABLE `zonas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias_menu`
--
ALTER TABLE `categorias_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `dias_apertura`
--
ALTER TABLE `dias_apertura`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `dias_descanso`
--
ALTER TABLE `dias_descanso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estadisticas_diarias`
--
ALTER TABLE `estadisticas_diarias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `zonas`
--
ALTER TABLE `zonas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `estadisticas_diarias`
--
ALTER TABLE `estadisticas_diarias`
  ADD CONSTRAINT `estadisticas_diarias_ibfk_1` FOREIGN KEY (`plato_mas_vendido`) REFERENCES `menu_items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `estadisticas_diarias_ibfk_2` FOREIGN KEY (`bebida_mas_vendida`) REFERENCES `menu_items` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_menu` (`id`);

--
-- Filtros para la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD CONSTRAINT `mesas_ibfk_1` FOREIGN KEY (`zona_id`) REFERENCES `zonas` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`id`);

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`),
  ADD CONSTRAINT `reservas_ibfk_3` FOREIGN KEY (`turno_id`) REFERENCES `turnos` (`id`);

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `servicios_ibfk_1` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`),
  ADD CONSTRAINT `servicios_ibfk_2` FOREIGN KEY (`turno_id`) REFERENCES `turnos` (`id`),
  ADD CONSTRAINT `servicios_ibfk_3` FOREIGN KEY (`camarero_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `servicios_ibfk_4` FOREIGN KEY (`reserva_id`) REFERENCES `reservas` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
