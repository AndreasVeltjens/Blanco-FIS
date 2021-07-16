-- phpMyAdmin SQL Dump
-- version 2.6.4-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 15. Mai 2007 um 15:38
-- Server Version: 5.0.33
-- PHP-Version: 5.0.5
-- 
-- Datenbank: `qsdb`
-- 

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `versandlabel`
-- 

CREATE TABLE `versandlabel` (
  `id_ett` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `nummer` varchar(15) collate utf8_unicode_ci NOT NULL,
  `link` varchar(255) collate utf8_unicode_ci NOT NULL,
  `lastmod` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `formatett` varchar(250) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id_ett`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;
