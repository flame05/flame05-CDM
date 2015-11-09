-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2015 alle 18:35
-- Versione del server: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `flame-cdm`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `articolo`
--

CREATE TABLE IF NOT EXISTS `articolo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_modello` int(11) NOT NULL,
  `id_parte` int(11) NOT NULL,
  `posizione` int(3) NOT NULL,
  `pos_madre` int(3) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `colore` varchar(12) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tempo` varchar(15) NOT NULL,
  `finito` int(1) NOT NULL DEFAULT '0',
  `confermato` int(1) NOT NULL DEFAULT '0',
  `zen` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dump dei dati per la tabella `articolo`
--

INSERT INTO `articolo` (`id`, `id_modello`, `id_parte`, `posizione`, `pos_madre`, `nome`, `colore`, `id_user`, `tempo`, `finito`, `confermato`, `zen`) VALUES
(1, 1, 1, 4, 2, 'FRA 115', 'btn-primary', 1, '1447085628 ', 0, 0, 1),
(2, 1, 1, 1, 1, 'FRA 200', 'btn-primary', 1, '1447090495', 0, 0, 1),
(3, 1, 3, 2, 1, 'FRA 300', 'btn-primary', 1, '1447090503', 0, 0, 1),
(4, 1, 3, 3, 2, 'FRA 117', 'btn-danger', 1, '1447090513', 0, 0, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `lavori`
--

CREATE TABLE IF NOT EXISTS `lavori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `colore` varchar(10) NOT NULL,
  `id_mamma` int(11) NOT NULL,
  `ordine` int(3) NOT NULL,
  `zen` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dump dei dati per la tabella `lavori`
--

INSERT INTO `lavori` (`id`, `nome`, `colore`, `id_mamma`, `ordine`, `zen`) VALUES
(1, 'PRESSE', '#EF9A9A', 1, 1, 1),
(2, 'VERNICIATURA', '#A5D6A7', 1, 2, 1),
(3, 'IMBALLO', '#81D4FA', 1, 3, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `lavoro`
--

CREATE TABLE IF NOT EXISTS `lavoro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_mamma` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `ordine` int(3) NOT NULL,
  `zen` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dump dei dati per la tabella `lavoro`
--

INSERT INTO `lavoro` (`id`, `id_mamma`, `nome`, `ordine`, `zen`) VALUES
(1, 1, 'PRESSAGGIO', 1, 1),
(2, 1, 'FRESATURA', 2, 1),
(3, 1, 'CARTEGGIO', 3, 1),
(4, 2, 'SBIANCATURA', 1, 1),
(5, 2, 'TINTA', 2, 1),
(6, 2, 'FONDO/ANNERIMENTO', 3, 1),
(7, 2, 'CART.FONDO', 4, 1),
(8, 2, 'OPACO', 5, 1),
(9, 2, 'ASCIUGATURA', 6, 1),
(10, 3, 'IMBALLO', 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `logp`
--

CREATE TABLE IF NOT EXISTS `logp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_articolo` int(11) NOT NULL,
  `id_lavoro` int(11) NOT NULL,
  `tempo` varchar(15) NOT NULL,
  `id_user` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `modello`
--

CREATE TABLE IF NOT EXISTS `modello` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pro` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `zen` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `modello`
--

INSERT INTO `modello` (`id`, `id_pro`, `nome`, `zen`) VALUES
(1, 1, 'OPACO', 1),
(2, 1, 'LUCIDO', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `parte`
--

CREATE TABLE IF NOT EXISTS `parte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_modello` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `ordine` int(3) NOT NULL,
  `zen` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dump dei dati per la tabella `parte`
--

INSERT INTO `parte` (`id`, `id_modello`, `nome`, `ordine`, `zen`) VALUES
(1, 1, 'CONS.SX', 1, 1),
(2, 1, 'CONS.DX', 2, 1),
(3, 1, 'PASS.SX', 3, 1),
(4, 1, 'PASS.DX', 4, 1),
(5, 1, 'LISTELLI', 5, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `processo`
--

CREATE TABLE IF NOT EXISTS `processo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `zen` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `processo`
--

INSERT INTO `processo` (`id`, `nome`, `zen`) VALUES
(1, 'LEXUS', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE IF NOT EXISTS `utenti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(64) NOT NULL,
  `livello` int(1) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `ultimi_login` text NOT NULL,
  `zen` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `username`, `password`, `livello`, `nome`, `ultimi_login`, `zen`) VALUES
(1, 'admin', 'admin123', 9, 'Admin', '', 1),
(2, 'operatore', 'operatore', 1, 'Franco Cavallo', '', 1),
(3, 'capo_operatore', 'capo_operatore', 2, 'John Struzzo', '', 1),
(4, 'test', 'testiamo', 1, 'da eliminare', '', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
