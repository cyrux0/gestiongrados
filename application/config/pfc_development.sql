-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 12-04-2011 a las 10:03:00
-- Versión del servidor: 5.1.41
-- Versión de PHP: 5.3.2-1ubuntu4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `pfc_development`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

CREATE TABLE IF NOT EXISTS `asignaturas` (
  `id_asignatura` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(3) CHARACTER SET utf8 NOT NULL,
  `nombre` varchar(200) CHARACTER SET utf8 NOT NULL,
  `creditos` int(11) NOT NULL,
  `materia` varchar(100) CHARACTER SET utf8 NOT NULL,
  `departamento` varchar(200) CHARACTER SET utf8 NOT NULL,
  `horas_presen` int(11) NOT NULL,
  `horas_no_presen` int(11) NOT NULL,
  PRIMARY KEY (`id_asignatura`),
  UNIQUE KEY `codigo` (`codigo`,`nombre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `asignaturas`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titulaciones`
--

CREATE TABLE IF NOT EXISTS `titulaciones` (
  `id_titulacion` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(4) CHARACTER SET utf8 NOT NULL,
  `nombre` varchar(200) CHARACTER SET utf8 NOT NULL,
  `creditos` int(11) NOT NULL,
  PRIMARY KEY (`id_titulacion`),
  UNIQUE KEY `codigo` (`codigo`,`nombre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `titulaciones`
--

