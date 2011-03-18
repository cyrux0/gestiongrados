-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tiempo de generación: 18-03-2011 a las 13:25:29
-- Versión del servidor: 5.0.51
-- Versión de PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Base de datos: `pfc_development`
-- 

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `titulaciones`
-- 

CREATE TABLE `titulaciones` (
  `id_titulacion` int(11) NOT NULL auto_increment,
  `codigo` char(4) collate utf8_spanish_ci NOT NULL,
  `nombre` varchar(50) collate utf8_spanish_ci NOT NULL,
  `creditos` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`id_titulacion`),
  UNIQUE KEY `codigo` (`codigo`,`nombre`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=4 ;

-- 
-- Volcar la base de datos para la tabla `titulaciones`
-- 

INSERT INTO `titulaciones` VALUES (1, '1711', 'Ingeniería Técnica en Informática de Sistemas', 200);
INSERT INTO `titulaciones` VALUES (2, '1712', 'Ingeniería Técnica en Informática de Gestión', 200);
INSERT INTO `titulaciones` VALUES (3, '1713', 'Ingeniería en Informática', 100);
