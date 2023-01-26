/*
SQLyog Ultimate v10.42 
MySQL - 5.5.5-10.1.37-MariaDB : Database - monstruo7.0
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `cuenta_entidades` */

CREATE TABLE `cuenta_entidades` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entidad_id` int(11) NOT NULL,
  `banco_id` int(11) NOT NULL,
  `tipo_cuenta_id` int(11) NOT NULL,
  `moneda_id` int(11) NOT NULL,
  `numero_cuenta` varchar(200) DEFAULT NULL,
  `titular` varchar(200) DEFAULT NULL,
  `codigo_interbancario` varchar(200) DEFAULT NULL,
  `comentario` text,
  `empleado_insert` int(11) DEFAULT NULL,
  `fecha_insert` datetime DEFAULT NULL,
  `empleado_update` int(11) DEFAULT NULL,
  `fecha_update` datetime DEFAULT NULL,
  `empleado_delete` int(11) DEFAULT NULL,
  `fecha_delete` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

/*Data for the table `cuenta_entidades` */

insert  into `cuenta_entidades`(`id`,`entidad_id`,`banco_id`,`tipo_cuenta_id`,`moneda_id`,`numero_cuenta`,`titular`,`codigo_interbancario`,`comentario`,`empleado_insert`,`fecha_insert`,`empleado_update`,`fecha_update`,`empleado_delete`,`fecha_delete`) values (2,13,1,1,1,'444','666','555',NULL,1,'2020-11-11 13:49:19',NULL,NULL,1,'2020-11-12 00:06:49'),(3,13,1,1,1,'111','333','222',NULL,1,'2020-11-11 16:24:41',NULL,NULL,1,'2020-11-12 00:06:53'),(4,13,1,1,1,'11','333','222',NULL,1,'2020-11-11 16:26:34',NULL,NULL,1,'2020-11-12 00:06:57'),(5,13,1,1,1,'11','333','222',NULL,1,'2020-11-11 16:27:22',NULL,NULL,1,'2020-11-12 00:37:46'),(6,13,1,1,1,'a','c','b',NULL,1,'2020-11-11 16:45:41',NULL,NULL,1,'2020-11-12 00:11:19'),(7,13,1,1,1,'12','14','13',NULL,1,'2020-11-11 16:45:54',NULL,NULL,1,'2020-11-12 00:07:25'),(8,13,1,1,1,'123','Hector','CCI',NULL,1,'2020-11-11 16:51:50',NULL,NULL,1,'2020-11-12 00:07:19'),(9,13,1,1,1,'0011-0057-0212580989','Héctor Iván De La Cruz Del Carpio.','0011-0057-0212580989-1245',NULL,1,'2020-11-11 16:59:53',NULL,NULL,1,'2020-11-12 00:07:17'),(10,13,1,1,1,'55','555','444',NULL,1,'2020-11-11 17:03:31',NULL,NULL,1,'2020-11-12 00:06:45'),(11,13,2,2,2,'6465-56545465-55465','Fabián De La Cruz Lopez','',NULL,1,'2020-11-11 18:11:48',NULL,NULL,NULL,NULL),(12,13,1,1,1,'1212','4545','3213',NULL,1,'2020-11-13 02:13:21',NULL,NULL,NULL,NULL),(13,13,2,2,3,'13','15','14',NULL,1,'2020-11-13 11:41:06',NULL,NULL,NULL,NULL),(14,26,1,1,1,'564654','elmer','',NULL,1,'2020-11-13 11:52:51',NULL,NULL,NULL,NULL),(15,26,1,1,1,'4656-54654-5454','Elmer feo','545-65-5654-55456',NULL,1,'2020-11-13 12:39:12',NULL,NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
