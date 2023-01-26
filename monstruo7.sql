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
/*Table structure for table `accesos` */

CREATE TABLE `accesos` (
  `id` int(10) unsigned NOT NULL,
  `acceso` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `accesos` */

LOCK TABLES `accesos` WRITE;

insert  into `accesos`(`id`,`acceso`) values (0,'sin acceso'),(1,'con acceso');

UNLOCK TABLES;

/*Table structure for table `activos` */

CREATE TABLE `activos` (
  `id` int(10) unsigned NOT NULL,
  `activo` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `activos` */

LOCK TABLES `activos` WRITE;

insert  into `activos`(`id`,`activo`) values (0,'inactivo'),(1,'activo');

UNLOCK TABLES;

/*Table structure for table `almacenes` */

CREATE TABLE `almacenes` (
  `alm_id` int(11) NOT NULL AUTO_INCREMENT,
  `alm_nombre` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alm_direccion` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alm_encargado` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alm_telefono` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alm_principal` tinyint(4) DEFAULT NULL,
  `alm_estado` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`alm_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `almacenes` */

LOCK TABLES `almacenes` WRITE;

insert  into `almacenes`(`alm_id`,`alm_nombre`,`alm_direccion`,`alm_encargado`,`alm_telefono`,`alm_principal`,`alm_estado`) values (1,'ALMACEN PRINCIPAL','LIMA2','USUARIO','',1,2);

UNLOCK TABLES;

/*Table structure for table `anulaciones` */

CREATE TABLE `anulaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `numero` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `ticket` varchar(50) DEFAULT NULL,
  `respuesta` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `anulaciones` */

LOCK TABLES `anulaciones` WRITE;

UNLOCK TABLES;

/*Table structure for table `bancos` */

CREATE TABLE `bancos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `banco` varchar(200) DEFAULT NULL,
  `abreviado` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Data for the table `bancos` */

LOCK TABLES `bancos` WRITE;

insert  into `bancos`(`id`,`banco`,`abreviado`) values (1,'Crédito','BCP'),(2,'Continental','BBVA'),(3,'Nación','BN'),(4,'Interbank','INTERB'),(5,'Scotiabank','SCOTIAB');

UNLOCK TABLES;

/*Table structure for table `categorias` */

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` varchar(150) COLLATE utf32_unicode_ci NOT NULL,
  `codigo` varchar(10) COLLATE utf32_unicode_ci DEFAULT NULL,
  `imagen` varchar(200) COLLATE utf32_unicode_ci DEFAULT NULL,
  `eliminado` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 no eliminado, 1 eliminado',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

/*Data for the table `categorias` */

LOCK TABLES `categorias` WRITE;

insert  into `categorias`(`id`,`categoria`,`codigo`,`imagen`,`eliminado`) values (1,'VARIOS',NULL,NULL,0);

UNLOCK TABLES;

/*Table structure for table `ci_sessions` */

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `ci_sessions` */

LOCK TABLES `ci_sessions` WRITE;

insert  into `ci_sessions`(`session_id`,`ip_address`,`user_agent`,`last_activity`,`user_data`) values ('82babb8feee06f7ec285769edc228b2b','190.81.111.215','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36',1417185576,'a:9:{s:9:\"user_data\";s:0:\"\";s:11:\"empleado_id\";s:1:\"2\";s:7:\"usuario\";s:12:\"Héctor ivan\";s:3:\"dni\";s:6:\"112233\";s:16:\"apellido_paterno\";s:10:\"De La Cruz\";s:16:\"apellido_materno\";s:10:\"Del Carpio\";s:16:\"tipo_empleado_id\";s:1:\"1\";s:13:\"tipo_empleado\";s:13:\"administrador\";s:20:\"categoria_abogado_id\";s:1:\"2\";}'),('c96217a12d7f219c52921a8fb68bfab0','190.81.111.215','Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko',1417191437,'a:10:{s:9:\"user_data\";s:0:\"\";s:11:\"empleado_id\";s:3:\"124\";s:7:\"usuario\";s:5:\"ERIKA\";s:3:\"dni\";s:8:\"46131422\";s:16:\"apellido_paterno\";s:4:\"ABAD\";s:16:\"apellido_materno\";s:6:\"REALPE\";s:16:\"tipo_empleado_id\";s:1:\"4\";s:13:\"tipo_empleado\";s:7:\"abogado\";s:20:\"categoria_abogado_id\";s:1:\"2\";s:17:\"flash:old:mensaje\";s:33:\"Actividad: Ingresada exitosamente\";}'),('97a650b319fedf8c882c431b12ca8656','190.81.111.215','Mozilla/5.0 (Windows NT 6.1; WOW64; rv:33.0) Gecko/20100101 Firefox/33.0',1417185360,'a:10:{s:9:\"user_data\";s:0:\"\";s:11:\"empleado_id\";s:1:\"2\";s:7:\"usuario\";s:12:\"Héctor ivan\";s:3:\"dni\";s:6:\"112233\";s:16:\"apellido_paterno\";s:10:\"De La Cruz\";s:16:\"apellido_materno\";s:10:\"Del Carpio\";s:16:\"tipo_empleado_id\";s:1:\"1\";s:13:\"tipo_empleado\";s:13:\"administrador\";s:20:\"categoria_abogado_id\";s:1:\"2\";s:17:\"flash:old:mensaje\";s:15:\"Datos Correctos\";}');

UNLOCK TABLES;

/*Table structure for table `cobros` */

CREATE TABLE `cobros` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `venta_id` int(11) NOT NULL,
  `modo_pago_id` int(11) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `fecha_pago` date DEFAULT NULL,
  `archivo_adjunto` varchar(150) DEFAULT NULL,
  `nota` text,
  `empleado_insert` int(11) DEFAULT NULL,
  `fecha_insert` datetime DEFAULT NULL,
  `empleado_update` int(11) DEFAULT NULL,
  `fecha_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Data for the table `cobros` */

LOCK TABLES `cobros` WRITE;

insert  into `cobros`(`id`,`venta_id`,`modo_pago_id`,`monto`,`fecha_pago`,`archivo_adjunto`,`nota`,`empleado_insert`,`fecha_insert`,`empleado_update`,`fecha_update`) values (1,1,1,23.60,'0000-00-00',NULL,'',1,'2022-12-23 14:00:39',NULL,NULL),(2,2,1,1185.90,'0000-00-00',NULL,'',1,'2022-12-30 10:22:09',NULL,NULL),(3,3,1,59.00,'0000-00-00',NULL,'',1,'2022-12-30 10:23:56',NULL,NULL),(4,4,1,23.60,'0000-00-00',NULL,'',1,'2022-12-30 10:26:36',NULL,NULL),(5,5,1,0.00,'0000-00-00',NULL,'',1,'2022-12-30 10:41:17',NULL,NULL),(6,6,1,0.00,'0000-00-00',NULL,'',1,'2023-01-01 12:01:22',NULL,NULL),(7,7,1,0.00,'0000-00-00',NULL,'',1,'2023-01-07 11:38:18',NULL,NULL),(8,8,1,23.60,'0000-00-00',NULL,'',1,'2023-01-07 11:44:24',NULL,NULL),(9,9,1,29.50,'0000-00-00',NULL,'',1,'2023-01-07 11:52:12',NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `codigo_de_leyendas` */

CREATE TABLE `codigo_de_leyendas` (
  `id` int(10) DEFAULT NULL,
  `codigo_de_leyenda` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `codigo_de_leyendas` */

LOCK TABLES `codigo_de_leyendas` WRITE;

insert  into `codigo_de_leyendas`(`id`,`codigo_de_leyenda`) values (1000,'Monto en Letras'),(1002,'Leyenda \"TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE\"'),(2000,'Leyenda \"COMPROBANTE DE PERCEPCIÓN\"'),(2001,'Leyenda \"BIENES TRANSFERIDOS EN LA AMAZONÍA REGIÓN SELVA PARA SER CONSUMIDOS EN LA MISMA\"'),(2002,'Leyenda \"SERVICIOS PRESTADOS EN LA AMAZONÍA  REGIÓN SELVA PARA SER CONSUMIDOS EN LA MISMA\"'),(2003,'Leyenda \"CONTRATOS DE CONSTRUCCIÓN EJECUTADOS  EN LA AMAZONÍA REGIÓN SELVA\"'),(2004,'Leyenda \"Agencia de Viaje - Paquete turístico\" '),(2005,'Leyenda \"Venta realizada por emiso itinerante\"'),(2006,'Leyenda \"Operación sujeta a detracción\"'),(2007,'Leyenda \"Operación sujeta al IVAP\"'),(2008,'Leyenda: \"VENTA EXONERADA DEL IGV-ISC-IPM. PROHIBIDA LA VENTA FUERA DE LA ZONA COMERCIAL DE TACNA\"'),(2009,'Leyenda: \"PRIMERA VENTA DE MERCANCÍA IDENTIFICABLE ENTRE USUARIOS DE LA ZONA COMERCIAL\"'),(2010,'Restitucion Simplificado de Derechos Arancelarios'),(2011,'Leyenda \"EXPORTACION DE SERVICIOS - DECRETO LEGISLATIVO Nº 919\"');

UNLOCK TABLES;

/*Table structure for table `codigo_tipo_tributos` */

CREATE TABLE `codigo_tipo_tributos` (
  `codigo` int(10) NOT NULL,
  `descripcion` varchar(150) DEFAULT NULL,
  `codigo_internacional` varchar(20) DEFAULT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `codigo_tipo_tributos` */

LOCK TABLES `codigo_tipo_tributos` WRITE;

insert  into `codigo_tipo_tributos`(`codigo`,`descripcion`,`codigo_internacional`,`nombre`) values (1000,'IGV Impuesto General a las Ventas\r\n','VAT','IGV'),(1016,'Impuesto a la Venta Arroz Pilado\r\n','VAT','IVAP'),(2000,'ISC Impuesto Selectivo al Consumo\r\n','EXC','ISC'),(3000,'Impuesto a la Renta\r\n','TOX','IR'),(7152,'Impuesto a la bolsa plastica\r\n','OTH','ICBPER'),(9995,'Exportación\r\n','FRE','EXP'),(9996,'Gratuito\r\n','FRE','GRA'),(9997,'Exonerado\r\n','VAT','EXO'),(9998,'Inafecto\r\n','FRE','INA'),(9999,'Otros tributos\r\n','OTH','OTROS');

UNLOCK TABLES;

/*Table structure for table `compra_detalles` */

CREATE TABLE `compra_detalles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `compra_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `producto` text NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precio_base` decimal(11,6) NOT NULL COMMENT 'precio del producto/servicio sin igv',
  `tipo_igv_id` int(10) NOT NULL,
  `descuento` decimal(11,2) DEFAULT NULL,
  `impuesto_bolsa` decimal(6,2) DEFAULT NULL COMMENT 'si el item lleva bolsa, tons... precio unitario de la bolsa (del anio)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `compra_detalles` */

LOCK TABLES `compra_detalles` WRITE;

UNLOCK TABLES;

/*Table structure for table `compras` */

CREATE TABLE `compras` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entidad_id` int(11) NOT NULL,
  `direccion` text,
  `tipo_documento_id` int(4) DEFAULT NULL,
  `tipo_ncredito_id` int(11) DEFAULT NULL,
  `tipo_ndebito_id` int(11) DEFAULT NULL,
  `compra_relacionado_id` int(11) DEFAULT NULL COMMENT 'factura o boleta relacionad a Nota de credito o debito',
  `operacion` int(11) NOT NULL COMMENT '1: factura, boleta o Notas, 2: Nota de Venta, 3: Cotizacion',
  `operacion_id` int(11) DEFAULT NULL COMMENT 'id de la factura o boleta relacionada (Este campo se usara cuando se llene la: nota de venta o cotizacion)',
  `serie` char(4) DEFAULT NULL,
  `numero` char(8) DEFAULT NULL,
  `fecha_emision` date NOT NULL,
  `hora_emision` time DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `moneda_id` int(3) NOT NULL,
  `tipo_de_cambio` decimal(10,3) DEFAULT NULL,
  `total_gravada` float(10,2) DEFAULT NULL,
  `porcentaje_igv` decimal(5,2) DEFAULT NULL COMMENT 'porcentaje igv generalmente sera 0.18',
  `total_igv` float(10,2) DEFAULT NULL,
  `total_gratuita` decimal(10,2) DEFAULT NULL,
  `total_exportacion` decimal(10,2) DEFAULT NULL,
  `total_exonerada` decimal(10,2) DEFAULT NULL,
  `total_inafecta` decimal(10,2) DEFAULT NULL,
  `bolsa_monto_unitario` decimal(5,2) DEFAULT NULL COMMENT 'monto unitario bolsa para el 2020 sera 0.20 centimos',
  `total_bolsa` decimal(10,2) DEFAULT NULL,
  `total_otros_cargos` decimal(10,2) DEFAULT NULL,
  `total_descuentos` decimal(10,2) DEFAULT NULL,
  `PrepaidAmount` decimal(10,2) DEFAULT NULL COMMENT 'total pago anticipos',
  `total_a_pagar` decimal(10,2) DEFAULT NULL,
  `notas` text,
  `forma_pago_id` smallint(5) unsigned DEFAULT NULL,
  `compra_pagada` smallint(6) DEFAULT '0' COMMENT 'para forma de pago al credito, sera 1 cuando se paguen todas las cuotas',
  `empleado_insert` int(11) NOT NULL,
  `fecha_insert` datetime DEFAULT NULL,
  `empleado_update` int(11) DEFAULT NULL,
  `fecha_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comprobante_serie_numero` (`entidad_id`,`serie`,`numero`,`tipo_documento_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `compras` */

LOCK TABLES `compras` WRITE;

UNLOCK TABLES;

/*Table structure for table `contactos` */

CREATE TABLE `contactos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entidad_id` int(11) NOT NULL,
  `apellido_paterno` varchar(200) DEFAULT NULL,
  `apellido_materno` varchar(200) DEFAULT NULL,
  `nombres` varchar(200) DEFAULT NULL,
  `celular` varchar(50) DEFAULT NULL,
  `correo` varchar(120) DEFAULT NULL,
  `comentario` text,
  `empleado_insert` int(11) NOT NULL,
  `fecha_insert` datetime NOT NULL,
  `empleado_update` int(11) DEFAULT NULL,
  `fecha_update` datetime DEFAULT NULL,
  `empleado_delete` int(11) DEFAULT NULL,
  `fecha_delete` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `contactos` */

LOCK TABLES `contactos` WRITE;

UNLOCK TABLES;

/*Table structure for table `correos` */

CREATE TABLE `correos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `user` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pass` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `correo_cifrado` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `notas` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `correos` */

LOCK TABLES `correos` WRITE;

insert  into `correos`(`id`,`host`,`port`,`user`,`pass`,`correo_cifrado`,`notas`) values (1,'mail.grupofact.com',465,'prueba@grupofact.com','8pvx77k4wShcZZM','ssl','notas monstruo 6.0');

UNLOCK TABLES;

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

LOCK TABLES `cuenta_entidades` WRITE;

insert  into `cuenta_entidades`(`id`,`entidad_id`,`banco_id`,`tipo_cuenta_id`,`moneda_id`,`numero_cuenta`,`titular`,`codigo_interbancario`,`comentario`,`empleado_insert`,`fecha_insert`,`empleado_update`,`fecha_update`,`empleado_delete`,`fecha_delete`) values (2,13,1,1,1,'444','666','555',NULL,1,'2020-11-11 13:49:19',NULL,NULL,1,'2020-11-12 00:06:49'),(3,13,1,1,1,'111','333','222',NULL,1,'2020-11-11 16:24:41',NULL,NULL,1,'2020-11-12 00:06:53'),(4,13,1,1,1,'11','333','222',NULL,1,'2020-11-11 16:26:34',NULL,NULL,1,'2020-11-12 00:06:57'),(5,13,1,1,1,'11','333','222',NULL,1,'2020-11-11 16:27:22',NULL,NULL,1,'2020-11-12 00:37:46'),(6,13,1,1,1,'a','c','b',NULL,1,'2020-11-11 16:45:41',NULL,NULL,1,'2020-11-12 00:11:19'),(7,13,1,1,1,'12','14','13',NULL,1,'2020-11-11 16:45:54',NULL,NULL,1,'2020-11-12 00:07:25'),(8,13,1,1,1,'123','Hector','CCI',NULL,1,'2020-11-11 16:51:50',NULL,NULL,1,'2020-11-12 00:07:19'),(9,13,1,1,1,'0011-0057-0212580989','Héctor Iván De La Cruz Del Carpio.','0011-0057-0212580989-1245',NULL,1,'2020-11-11 16:59:53',NULL,NULL,1,'2020-11-12 00:07:17'),(10,13,1,1,1,'55','555','444',NULL,1,'2020-11-11 17:03:31',NULL,NULL,1,'2020-11-12 00:06:45'),(11,13,2,2,2,'6465-56545465-55465','Fabián De La Cruz Lopez','',NULL,1,'2020-11-11 18:11:48',NULL,NULL,NULL,NULL),(12,13,1,1,1,'1212','4545','3213',NULL,1,'2020-11-13 02:13:21',NULL,NULL,NULL,NULL),(13,13,2,2,3,'13','15','14',NULL,1,'2020-11-13 11:41:06',NULL,NULL,NULL,NULL),(14,26,1,1,1,'564654','elmer','',NULL,1,'2020-11-13 11:52:51',NULL,NULL,NULL,NULL),(15,26,1,1,1,'4656-54654-5454','Elmer feo','545-65-5654-55456',NULL,1,'2020-11-13 12:39:12',NULL,NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `cuotas` */

CREATE TABLE `cuotas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `venta_id` int(11) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `fecha_cuota` date DEFAULT NULL,
  `estado` smallint(6) DEFAULT '0' COMMENT '0 no pagado, 1 pagado',
  `empleado_insert` int(11) DEFAULT NULL,
  `fecha_insert` datetime DEFAULT NULL,
  `empleado_update` int(11) DEFAULT NULL,
  `fecha_update` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `cuotas` */

LOCK TABLES `cuotas` WRITE;

UNLOCK TABLES;

/*Table structure for table `ejemplar` */

CREATE TABLE `ejemplar` (
  `ejm_id` int(11) NOT NULL AUTO_INCREMENT,
  `ejm_producto_id` int(11) DEFAULT NULL,
  `ejm_ingreso_id` int(11) DEFAULT NULL,
  `ejm_fecha_ingreso` date DEFAULT NULL,
  `ejm_almacen_id` int(11) DEFAULT NULL,
  `ejm_compra_id` int(11) NOT NULL,
  `ejm_estado` tinyint(4) NOT NULL,
  PRIMARY KEY (`ejm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `ejemplar` */

LOCK TABLES `ejemplar` WRITE;

UNLOCK TABLES;

/*Table structure for table `empleados` */

CREATE TABLE `empleados` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `apellido_paterno` varchar(80) DEFAULT NULL,
  `apellido_materno` varchar(80) DEFAULT NULL,
  `nombres` varchar(80) DEFAULT NULL,
  `contrasena` varchar(80) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `dni` varchar(30) DEFAULT NULL,
  `domicilio` text,
  `telefono_fijo` varchar(60) DEFAULT NULL,
  `telefono_movil` varchar(60) DEFAULT NULL,
  `email_1` varchar(200) DEFAULT NULL,
  `email_2` varchar(60) DEFAULT NULL,
  `foto` varchar(120) DEFAULT NULL,
  `tipo_empleado_id` int(11) NOT NULL,
  `fecha_insert` datetime NOT NULL,
  `empleado_insert` int(11) NOT NULL,
  `fecha_update` datetime DEFAULT NULL,
  `empleado_update` int(11) DEFAULT NULL,
  `fecha_delete` datetime DEFAULT NULL,
  `empleado_delete` int(11) DEFAULT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=424 DEFAULT CHARSET=utf8;

/*Data for the table `empleados` */

LOCK TABLES `empleados` WRITE;

insert  into `empleados`(`id`,`apellido_paterno`,`apellido_materno`,`nombres`,`contrasena`,`fecha_nacimiento`,`dni`,`domicilio`,`telefono_fijo`,`telefono_movil`,`email_1`,`email_2`,`foto`,`tipo_empleado_id`,`fecha_insert`,`empleado_insert`,`fecha_update`,`empleado_update`,`fecha_delete`,`empleado_delete`) values (1,'HECTOR','POCOHUANCA','JOSE LUIS','12','2017-06-13','45830932','Callao','44','55','12',NULL,'2.jpg',1,'1969-12-31 19:00:00',1,'1969-12-31 19:00:00',1,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `empresas` */

CREATE TABLE `empresas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `empresa` varchar(180) DEFAULT NULL,
  `nombre_comercial` varchar(250) DEFAULT NULL,
  `ruc` varchar(20) NOT NULL,
  `domicilio_fiscal` varchar(200) DEFAULT NULL,
  `telefono_fijo` varchar(30) DEFAULT NULL,
  `telefono_fijo2` varchar(30) DEFAULT NULL,
  `telefono_movil` varchar(30) DEFAULT NULL,
  `telefono_movil2` varchar(30) DEFAULT NULL,
  `foto` varchar(250) DEFAULT NULL,
  `correo` varchar(150) DEFAULT NULL,
  `ubigeo` char(20) DEFAULT NULL,
  `codigo_sucursal_sunat` varchar(10) DEFAULT NULL,
  `regimen_id` int(11) DEFAULT NULL,
  `urbanizacion` varchar(100) DEFAULT NULL,
  `usu_secundario_prueba_user` varchar(150) DEFAULT NULL,
  `usu_secundario_prueba_passoword` varchar(150) DEFAULT NULL,
  `usu_secundario_produccion_user` varchar(150) DEFAULT NULL,
  `usu_secundario_produccion_password` varchar(150) DEFAULT NULL,
  `certi_prueba_nombre` varchar(120) DEFAULT NULL,
  `certi_prueba_password` varchar(120) DEFAULT NULL,
  `certi_produccion_nombre` varchar(120) DEFAULT NULL,
  `certi_produccion_password` varchar(120) DEFAULT NULL,
  `guias_client_id` varchar(60) DEFAULT NULL,
  `guias_client_secret` varchar(50) DEFAULT NULL,
  `modo` tinyint(4) DEFAULT '1' COMMENT '0 beta, 1 produccion',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `empresas` */

LOCK TABLES `empresas` WRITE;

insert  into `empresas`(`id`,`empresa`,`nombre_comercial`,`ruc`,`domicilio_fiscal`,`telefono_fijo`,`telefono_fijo2`,`telefono_movil`,`telefono_movil2`,`foto`,`correo`,`ubigeo`,`codigo_sucursal_sunat`,`regimen_id`,`urbanizacion`,`usu_secundario_prueba_user`,`usu_secundario_prueba_passoword`,`usu_secundario_produccion_user`,`usu_secundario_produccion_password`,`certi_prueba_nombre`,`certi_prueba_password`,`certi_produccion_nombre`,`certi_produccion_password`,`guias_client_id`,`guias_client_secret`,`modo`) values (1,'SANCHEZ MUÑOZ ANGEL JESUS','SANCHEZ','10481211641','APV. JARDINES DE CHILLON, Mz. LL2, Lt.03, Puente Piedra','','','','','WhatsApp Image 2022-05-29 at 7.01.21 PM.jpeg','','150125','0000',3,'-','MODDATOS','moddatos','','','impresoar.jpg','112233','WIN_20190704_14_05_24_Pro.jpg','','','',0);

UNLOCK TABLES;

/*Table structure for table `entidades` */

CREATE TABLE `entidades` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_entidad_id` smallint(6) DEFAULT NULL,
  `entidad` varchar(220) DEFAULT NULL COMMENT 'razon social, nombres completos, otros',
  `nombre_comercial` varchar(220) DEFAULT NULL COMMENT 'si tuviera',
  `numero_documento` varchar(20) DEFAULT NULL COMMENT 'numero DNI o RUC o Carnet de extranjeria u otro',
  `direccion` varchar(350) DEFAULT NULL,
  `email_1` varchar(150) DEFAULT NULL,
  `email_2` varchar(150) DEFAULT NULL,
  `telefono_fijo_1` varchar(70) DEFAULT NULL,
  `telefono_fijo_2` varchar(70) DEFAULT NULL,
  `telefono_movil_1` varchar(70) DEFAULT NULL,
  `telefono_movil_2` varchar(70) DEFAULT NULL,
  `pagina_web` varchar(180) DEFAULT NULL,
  `facebook` varchar(250) DEFAULT NULL,
  `twitter` varchar(250) DEFAULT NULL,
  `fecha_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `empleado_insert` int(11) NOT NULL,
  `fecha_update` timestamp NULL DEFAULT NULL,
  `empleado_update` int(11) DEFAULT NULL,
  `fecha_delete` timestamp NULL DEFAULT NULL,
  `empleado_delete` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1295 DEFAULT CHARSET=utf8;

/*Data for the table `entidades` */

LOCK TABLES `entidades` WRITE;

insert  into `entidades`(`id`,`tipo_entidad_id`,`entidad`,`nombre_comercial`,`numero_documento`,`direccion`,`email_1`,`email_2`,`telefono_fijo_1`,`telefono_fijo_2`,`telefono_movil_1`,`telefono_movil_2`,`pagina_web`,`facebook`,`twitter`,`fecha_insert`,`empleado_insert`,`fecha_update`,`empleado_update`,`fecha_delete`,`empleado_delete`) values (1,1,'CLIENTE VARIOS ',NULL,'00000000','LIMA','','','','0','','0','',NULL,NULL,'2019-07-24 10:41:19',1,'2019-07-20 04:26:02',1,NULL,NULL),(1291,2,'DE LA CRUZ DEL CARPIO HECTOR IVAN',NULL,'10407086274','-',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2022-12-23 13:18:32',0,NULL,NULL,NULL,NULL),(1292,2,'FACTURACION ELECTRONICA MONSTRUO E.I.R.L.',NULL,'20604051984','AV. VICENTE MORALES DUAREZ MZA. L LOTE 14 A.H. SANTA ROSA ZONA 1 ',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2023-01-01 12:03:22',0,NULL,NULL,NULL,NULL),(1293,2,'JARA CHUIZ REMIGIO ARTEMIO',NULL,'10060679661','Lima, Callao 1285',NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-01-03 02:24:05',1,NULL,NULL,NULL,NULL),(1294,2,'TRANSPORTES WILLIAM WRA S.A.C.',NULL,'20606875208','AV. 28 DE JULIO NRO. 2195 LIMA - LIMA - LA VICTORIA',NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'2023-01-03 12:14:26',1,NULL,NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `forma_pagos` */

CREATE TABLE `forma_pagos` (
  `id` smallint(6) NOT NULL,
  `forma_pago` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `forma_pagos` */

LOCK TABLES `forma_pagos` WRITE;

insert  into `forma_pagos`(`id`,`forma_pago`) values (1,'contado'),(2,'crédito');

UNLOCK TABLES;

/*Table structure for table `guia_detalles` */

CREATE TABLE `guia_detalles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `guia_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `guia_detalles` */

LOCK TABLES `guia_detalles` WRITE;

UNLOCK TABLES;

/*Table structure for table `guia_modalidad_traslados` */

CREATE TABLE `guia_modalidad_traslados` (
  `id` int(11) NOT NULL,
  `guia_modalidad_traslado` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `guia_modalidad_traslados` */

LOCK TABLES `guia_modalidad_traslados` WRITE;

insert  into `guia_modalidad_traslados`(`id`,`guia_modalidad_traslado`) values (1,'Transporte público'),(2,'Transporte privado');

UNLOCK TABLES;

/*Table structure for table `guia_motivo_traslados` */

CREATE TABLE `guia_motivo_traslados` (
  `id` int(11) NOT NULL,
  `guia_motivo_traslado` varchar(200) DEFAULT NULL,
  `codigo` char(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `guia_motivo_traslados` */

LOCK TABLES `guia_motivo_traslados` WRITE;

insert  into `guia_motivo_traslados`(`id`,`guia_motivo_traslado`,`codigo`) values (1,'Venta','01'),(2,'Venta sujeta a confirmación del comprador','14'),(3,'Compra','02'),(4,'Traslado entre establecimiento de la misma empresa','04'),(5,'Traslado por emisor itinerante de comprobantes de pago','18'),(6,'Traslado zona primaria','19'),(7,'Importación','08'),(8,'Exportación','09'),(9,'Otras NO incluida en los puntos anteriores','13');

UNLOCK TABLES;

/*Table structure for table `guia_transportista_adjuntos` */

CREATE TABLE `guia_transportista_adjuntos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guia_transportista_id` int(11) DEFAULT NULL,
  `tipo_documento_id` int(11) DEFAULT NULL,
  `serie` varchar(10) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `guia_transportista_adjuntos` */

LOCK TABLES `guia_transportista_adjuntos` WRITE;

UNLOCK TABLES;

/*Table structure for table `guia_transportista_detalles` */

CREATE TABLE `guia_transportista_detalles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guia_transportista_id` int(11) DEFAULT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `guia_transportista_detalles` */

LOCK TABLES `guia_transportista_detalles` WRITE;

UNLOCK TABLES;

/*Table structure for table `guia_transportistas` */

CREATE TABLE `guia_transportistas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serie` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `numero` char(11) COLLATE utf8_unicode_ci NOT NULL,
  `numero_mtc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_emision` date NOT NULL,
  `hora_emision` time NOT NULL,
  `fecha_traslado` date DEFAULT NULL,
  `partida` varchar(350) COLLATE utf8_unicode_ci DEFAULT NULL,
  `partida_ubigeo` char(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `llegada` varchar(350) COLLATE utf8_unicode_ci DEFAULT NULL,
  `llegada_ubigeo` char(6) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remitente_id` int(11) NOT NULL,
  `destinatario_id` int(11) NOT NULL,
  `vehiculo_placa` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vehiculo_mtc` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `conductor_dni` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `conductor_nombres` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `conductor_apellidos` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `conductor_licencia` varchar(80) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sub_contratista_id` int(11) DEFAULT NULL,
  `pagador_flete_id` int(11) DEFAULT NULL,
  `peso_total` decimal(10,2) DEFAULT NULL,
  `observaciones` text COLLATE utf8_unicode_ci,
  `estado_operacion` tinyint(4) DEFAULT '0' COMMENT '0 creado, 1 aceptado, 2 rechazado',
  `respuesta_sunat_codigo` tinyint(4) DEFAULT NULL,
  `respuesta_sunat_descripcion` text COLLATE utf8_unicode_ci,
  `ticket_guia` varchar(70) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_update` datetime DEFAULT NULL,
  `fecha_insert` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `guia_transportistas` */

LOCK TABLES `guia_transportistas` WRITE;

UNLOCK TABLES;

/*Table structure for table `guias` */

CREATE TABLE `guias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `serie` varchar(4) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `fecha_emision` date DEFAULT NULL,
  `fecha_traslado` date DEFAULT NULL,
  `guia_motivo_traslado_id` int(11) NOT NULL,
  `guia_modalidad_traslado_id` char(2) NOT NULL COMMENT '01 transporte publico, 02 transporte privado',
  `entidad_id_transporte` int(11) DEFAULT NULL COMMENT 'en caso, modalidad sea: 01 publico',
  `numero_mtc_transporte` varchar(50) DEFAULT NULL COMMENT 'en caso, modalidad sea: 01 publico',
  `conductor_dni` varchar(20) DEFAULT NULL,
  `conductor_nombres` varchar(50) DEFAULT NULL,
  `conductor_apellidos` varchar(50) DEFAULT NULL,
  `conductor_licencia` varchar(50) DEFAULT NULL,
  `vehiculo_placa` varchar(20) DEFAULT NULL,
  `destinatario_id` int(11) DEFAULT NULL,
  `partida_ubigeo` char(6) DEFAULT NULL,
  `partida_direccion` varchar(200) DEFAULT NULL,
  `llegada_ubigeo` char(6) DEFAULT NULL,
  `llegada_direccion` varchar(200) DEFAULT NULL,
  `peso_total` decimal(10,2) DEFAULT NULL,
  `numero_bultos` int(11) DEFAULT NULL,
  `notas` text,
  `envio_sunat` tinyint(4) DEFAULT NULL,
  `estado_operacion` tinyint(4) DEFAULT '0' COMMENT '0 creado, 1 aceptado, 2 rechazado',
  `respuesta_sunat_codigo` tinyint(4) DEFAULT NULL,
  `respuesta_sunat_descripcion` text,
  `ticket_guia` varchar(70) DEFAULT NULL,
  `insert_fecha` datetime NOT NULL,
  `insert_empleado_id` int(11) NOT NULL,
  `update_fecha` datetime DEFAULT NULL,
  `update_empleado_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `guias` */

LOCK TABLES `guias` WRITE;

UNLOCK TABLES;

/*Table structure for table `igv` */

CREATE TABLE `igv` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `valor` decimal(10,3) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `activo` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `igv` */

LOCK TABLES `igv` WRITE;

insert  into `igv`(`id`,`valor`,`fecha`,`activo`) values (1,0.180,'2015-12-03','activo'),(2,0.190,'2015-11-05','inactivo');

UNLOCK TABLES;

/*Table structure for table `kardex_promedio` */

CREATE TABLE `kardex_promedio` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `compra_venta` tinyint(4) DEFAULT NULL COMMENT '1 compra, 2 venta',
  `documento_id` int(11) DEFAULT NULL COMMENT 'id del documento. Sea de compra, venta o producto',
  `tipo_documento_id` int(11) DEFAULT NULL COMMENT 'id tipo de documento (solo para compras y ventas)',
  `tipo_movimiento` tinyint(4) DEFAULT NULL COMMENT 'solo para movimiento de productos(compra_venta = 3) 1 ingreso, 2 salida',
  `serie` varchar(10) DEFAULT NULL,
  `numero` varchar(15) DEFAULT NULL COMMENT 'tipo varchar pq en compras se podria poner cualquier caracter.',
  `entrada_cantidad` decimal(10,2) DEFAULT NULL,
  `entrada_costo` decimal(10,4) DEFAULT NULL,
  `salida_cantidad` decimal(10,2) DEFAULT NULL,
  `salida_costo` decimal(10,4) DEFAULT NULL,
  `final_cantidad` decimal(10,2) DEFAULT NULL,
  `final_costo` decimal(10,6) DEFAULT NULL,
  `final_total` decimal(10,4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `kardex_promedio` */

LOCK TABLES `kardex_promedio` WRITE;

UNLOCK TABLES;

/*Table structure for table `kardex_temporal` */

CREATE TABLE `kardex_temporal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `compra_venta` tinyint(4) DEFAULT NULL COMMENT '0 stock inicial, 1 compra, 2 venta, 3 movimientos de productos',
  `documento_id` int(11) DEFAULT NULL COMMENT 'id del documento. Sea de compra, venta o producto',
  `tipo_documento_id` int(11) DEFAULT NULL COMMENT 'id tipo de documento (solo para compras y ventas)',
  `documento_relacionado_id` int(11) DEFAULT NULL COMMENT 'id de factura o boleta relacionada (Para Nota de crédito o débito)',
  `tipo_movimiento` tinyint(4) DEFAULT NULL COMMENT 'solo para movimiento de productos(compra_venta = 3) 1 ingreso, 2 salida',
  `serie` varchar(10) DEFAULT NULL,
  `numero` varchar(15) DEFAULT NULL COMMENT 'tipo varchar pq en compras se podria poner cualquier caracter.',
  `costo_unitario` decimal(10,2) DEFAULT NULL,
  `cantidad` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `kardex_temporal` */

LOCK TABLES `kardex_temporal` WRITE;

UNLOCK TABLES;

/*Table structure for table `le_compras8_1` */

CREATE TABLE `le_compras8_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `anio` int(11) DEFAULT NULL,
  `mes` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `le_compras8_1` */

LOCK TABLES `le_compras8_1` WRITE;

UNLOCK TABLES;

/*Table structure for table `le_compras8_1_detalles` */

CREATE TABLE `le_compras8_1_detalles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `periodo` char(8) DEFAULT NULL,
  `codigo_unico` int(11) DEFAULT NULL,
  `numero_correlativo` varchar(10) DEFAULT NULL,
  `fecha_emision` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `tipo_documento` char(2) DEFAULT NULL,
  `serie` varchar(15) DEFAULT NULL,
  `anio_dua` varchar(20) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `numero_final` varchar(20) DEFAULT NULL,
  `tipo_documento_proveedor` char(1) DEFAULT NULL,
  `numero_documento` varchar(20) DEFAULT NULL,
  `razon_social` varchar(200) DEFAULT NULL,
  `base_imponible_tipo_1` decimal(10,2) DEFAULT NULL,
  `igv_tipo_1` decimal(10,2) DEFAULT NULL,
  `base_imponible_tipo_2` decimal(10,2) DEFAULT NULL,
  `igv_tipo_2` decimal(10,2) DEFAULT NULL,
  `base_imponible_tipo_3` decimal(10,2) DEFAULT NULL,
  `igv_tipo_3` decimal(10,2) DEFAULT NULL,
  `no_grabadas` decimal(10,2) DEFAULT NULL,
  `isc` decimal(10,2) DEFAULT NULL,
  `ICBPER` decimal(10,2) NOT NULL DEFAULT '0.00',
  `otros_conceptos` decimal(10,2) DEFAULT NULL,
  `importe_total` decimal(10,2) DEFAULT NULL,
  `codigo_moneda` char(3) DEFAULT NULL,
  `tipo_cambio` decimal(10,3) NOT NULL DEFAULT '1.000',
  `da_fecha_emision` date DEFAULT NULL,
  `da_tipo_documento` varchar(2) DEFAULT NULL,
  `da_serie` varchar(20) DEFAULT NULL,
  `da_dua` varchar(10) DEFAULT NULL,
  `da_numero` varchar(20) DEFAULT NULL,
  `fecha_emision_detraccion` date DEFAULT NULL,
  `numero_deposito_detraccion` varchar(20) DEFAULT NULL,
  `sujeto_retencion` char(1) DEFAULT NULL,
  `clasificacion_bienes` varchar(20) DEFAULT NULL,
  `identificacion_contrato` varchar(20) DEFAULT NULL,
  `error_tipo_1` varchar(200) DEFAULT NULL,
  `error_tipo_2` varchar(200) DEFAULT NULL,
  `error_tipo_3` varchar(200) DEFAULT NULL,
  `error_tipo_4` varchar(200) DEFAULT NULL,
  `medio_pago_cancelacion` char(1) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL,
  `compra_id` int(11) DEFAULT NULL,
  `insercion_automatica` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `le_compras8_1_detalles` */

LOCK TABLES `le_compras8_1_detalles` WRITE;

UNLOCK TABLES;

/*Table structure for table `le_compras8_2` */

CREATE TABLE `le_compras8_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `anio` int(11) DEFAULT NULL,
  `mes` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `le_compras8_2` */

LOCK TABLES `le_compras8_2` WRITE;

UNLOCK TABLES;

/*Table structure for table `le_compras8_2_detalles` */

CREATE TABLE `le_compras8_2_detalles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `periodo` char(8) DEFAULT NULL,
  `codigo_unico` int(11) DEFAULT NULL,
  `numero_correlativo` varchar(10) DEFAULT NULL,
  `fecha_emision` date DEFAULT NULL,
  `tipo_documento` char(2) DEFAULT NULL,
  `serie` varchar(15) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `adquisicion` decimal(10,2) DEFAULT NULL,
  `otros_conceptos` decimal(10,2) DEFAULT NULL,
  `importe_total` decimal(10,2) DEFAULT NULL,
  `tipo_comprobante_pago` char(2) DEFAULT NULL,
  `serie_pago` varchar(20) DEFAULT NULL,
  `anio_dua` varchar(4) DEFAULT NULL,
  `numero_pago` varchar(20) DEFAULT NULL,
  `retencion_igv` decimal(10,2) DEFAULT NULL,
  `codigo_moneda` varchar(3) DEFAULT NULL,
  `tipo_cambio` decimal(10,3) DEFAULT NULL,
  `pais_sujeto` varchar(4) DEFAULT NULL,
  `razon_sujeto` varchar(150) DEFAULT NULL,
  `domicilio_sujeto` varchar(200) DEFAULT NULL,
  `numero_documento_sujeto` varchar(15) DEFAULT NULL,
  `numero_documento_beneficiario` varchar(15) DEFAULT NULL,
  `razon_beneficiario` varchar(15) DEFAULT NULL,
  `pais_beneficiario` varchar(4) DEFAULT NULL,
  `vinculo` varchar(2) DEFAULT NULL,
  `renta_bruta` decimal(10,2) DEFAULT NULL,
  `deduccion` decimal(10,2) DEFAULT NULL,
  `renta_neta` decimal(10,2) DEFAULT NULL,
  `taza_retencion` decimal(10,2) DEFAULT NULL,
  `impuesto_retenido` decimal(10,2) DEFAULT NULL,
  `doble_disposicion` varchar(2) DEFAULT NULL,
  `exoneracion_aplicada` char(1) DEFAULT NULL,
  `tipo_renta` varchar(2) DEFAULT NULL,
  `modalidad` char(1) DEFAULT NULL,
  `aplica_ley` char(1) DEFAULT NULL,
  `estado` char(1) DEFAULT NULL,
  `compra_id` int(11) DEFAULT NULL,
  `insercion_automatica` tinyint(4) DEFAULT NULL COMMENT '1 para insercion automatica del sistema, 0 para insercion manual',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `le_compras8_2_detalles` */

LOCK TABLES `le_compras8_2_detalles` WRITE;

UNLOCK TABLES;

/*Table structure for table `le_ventas14_1` */

CREATE TABLE `le_ventas14_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `anio` int(11) DEFAULT NULL,
  `mes` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `le_ventas14_1` */

LOCK TABLES `le_ventas14_1` WRITE;

UNLOCK TABLES;

/*Table structure for table `le_ventas14_1_detalles` */

CREATE TABLE `le_ventas14_1_detalles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `periodo` char(8) DEFAULT NULL,
  `codigo_unico` int(11) DEFAULT NULL,
  `numero_correlativo` varchar(10) DEFAULT NULL,
  `fecha_emision` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `tipo_documento` char(2) DEFAULT NULL,
  `serie` varchar(15) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `numero_final` int(11) DEFAULT NULL,
  `tipo_cliente` tinyint(4) DEFAULT NULL,
  `numero_documento` varchar(15) DEFAULT NULL,
  `cliente` varchar(250) DEFAULT NULL,
  `exportacion` decimal(10,2) DEFAULT NULL,
  `base_imponible` decimal(10,2) DEFAULT NULL,
  `base_imponible_descuento` decimal(10,2) DEFAULT NULL,
  `igv` decimal(10,2) DEFAULT NULL,
  `igv_descuento` decimal(10,2) DEFAULT NULL,
  `exonerado` decimal(10,2) DEFAULT NULL,
  `inafecto` decimal(10,2) DEFAULT NULL,
  `isc` decimal(10,2) DEFAULT NULL,
  `arroz_pillado_base_disponible` decimal(10,2) DEFAULT NULL,
  `arroz_pillado_igv` decimal(10,2) DEFAULT NULL,
  `ICBPER` decimal(10,2) NOT NULL DEFAULT '0.00',
  `otros_conceptos` decimal(10,2) DEFAULT NULL,
  `importe_total` decimal(10,2) DEFAULT NULL,
  `codigo_moneda` char(3) DEFAULT NULL,
  `tipo_cambio` decimal(10,3) NOT NULL DEFAULT '1.000' COMMENT 'para soles,tipo de cambio será 1',
  `da_fecha_emision` date DEFAULT NULL COMMENT 'documento adjunto para nota de credito',
  `da_tipo_documento` char(2) DEFAULT NULL,
  `da_serie` char(15) DEFAULT NULL,
  `da_numero` int(11) DEFAULT NULL,
  `identificacion_contrato` varchar(200) DEFAULT NULL,
  `error_tipo_1` int(11) DEFAULT NULL,
  `medio_pago_cancelacion` int(11) DEFAULT NULL,
  `estado` int(11) DEFAULT NULL,
  `venta_id` int(11) DEFAULT NULL,
  `insercion_automatica` tinyint(4) DEFAULT NULL COMMENT '1 para insercion automatica del sistema, 0 para insercion manual',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `le_ventas14_1_detalles` */

LOCK TABLES `le_ventas14_1_detalles` WRITE;

UNLOCK TABLES;

/*Table structure for table `modo_pagos` */

CREATE TABLE `modo_pagos` (
  `id` int(11) NOT NULL,
  `modo_pago` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `modo_pagos` */

LOCK TABLES `modo_pagos` WRITE;

insert  into `modo_pagos`(`id`,`modo_pago`) values (1,'efectivo'),(2,'deposito'),(3,'tarjeta crédito'),(4,'tarjeta débito'),(5,'cheque'),(6,'giro'),(7,'otros');

UNLOCK TABLES;

/*Table structure for table `modulos` */

CREATE TABLE `modulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `direccion_icono` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `modulo` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `enlace` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `referencia` smallint(6) NOT NULL,
  `orden` int(11) NOT NULL,
  `padre` tinyint(4) NOT NULL,
  `estado` tinyint(4) NOT NULL COMMENT '0 inactivo, 1 activo',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=607 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `modulos` */

LOCK TABLES `modulos` WRITE;

insert  into `modulos`(`id`,`direccion_icono`,`modulo`,`enlace`,`referencia`,`orden`,`padre`,`estado`) values (100,'<i class=\"glyphicon glyphicon-credit-card\"></i>','Entidades','--',0,0,1,1),(101,'','Cliente/proveedor','entidades/index',100,1,0,1),(102,'','Empleados','empleados/index',100,2,0,1),(200,'','Compras','--',0,0,1,1),(201,'','Compras','compras/index/1',200,1,0,1),(202,'','Orden de compras','compras/index/2',200,2,0,1),(300,'<i class=\"glyphicon glyphicon-usd\"></i>','Ventas','--',0,0,1,1),(301,'','Comprobantes Electrónicos','ventas/index/1',300,1,0,1),(302,'','Pedidos','ventas/index/2',300,2,0,1),(303,'','Cotizaciones','ventas/index/3',300,3,0,1),(304,'','Guia Remitente','guias/index',300,4,0,1),(400,'<i class=\"glyphicon glyphicon-ruble\"></i>','Almacén','--',0,0,1,1),(401,'','Unidades','unidades/index',400,1,0,1),(402,'','Categorias','categorias/index',400,2,0,1),(403,'','Productos','productos/index',400,3,0,1),(404,'','Movimiento Almacen','producto_movimientos',400,4,0,1),(500,'','Contabilidad','--',0,0,1,1),(501,'','Libros Electrónicos - Ventas (14.1)','le_ventas14_1/index',500,1,0,1),(502,'','Libros Electrónicos - Compras (8.1)','le_compras8_1/index',500,2,0,1),(503,'','Libros Electrónicos - Compras - No domiciliados (8.2)','le_compras8_2/index',500,3,0,1),(504,'','Kardex(Promedio Ponderado)','productos/kardex_promedio_ponderado',500,4,0,1),(600,'<i class=\"glyphicon glyphicon-wrench\"></i>','Configuración','--',0,0,1,1),(601,'','Series','series',600,1,0,1),(602,'','Empresa','empresas',600,2,0,1),(603,'','Correo','correos',600,3,0,1),(604,'','Perfiles','tipo_empleados/index',600,4,0,1),(405,'','Pedido Almacen(Interno)','pedido_almacenes/index',400,5,0,1),(505,'','Kardex(Mensual)','productos/kardex_mensual',500,5,0,0),(305,'','Productos mas vendidos','ventas/mas_vendidos_cantidad',300,6,0,1),(605,'','Configuraciones','variables_diversas',600,5,0,1),(606,'','Manuales','manuales',600,6,0,1),(306,'','Guia Transportista','Guias_transportistas',300,5,0,1);

UNLOCK TABLES;

/*Table structure for table `monedas` */

CREATE TABLE `monedas` (
  `id` int(11) NOT NULL,
  `moneda` varchar(50) NOT NULL,
  `abreviado` varchar(10) NOT NULL,
  `abrstandar` varchar(10) NOT NULL,
  `simbolo` varchar(2) NOT NULL,
  `activo` char(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `monedas` */

LOCK TABLES `monedas` WRITE;

insert  into `monedas`(`id`,`moneda`,`abreviado`,`abrstandar`,`simbolo`,`activo`) values (1,'soles','sol','PEN','S/','1'),(2,'dólares','dol','USD','$','1'),(3,'euros','eur','EUR','E','1');

UNLOCK TABLES;

/*Table structure for table `pedido_almacen_detalles` */

CREATE TABLE `pedido_almacen_detalles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pedido_almacen_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `producto` varchar(250) DEFAULT NULL,
  `cantidad` decimal(10,0) DEFAULT NULL,
  `eliminado` datetime DEFAULT NULL COMMENT 'solo el administrador lo puede eliminar',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `pedido_almacen_detalles` */

LOCK TABLES `pedido_almacen_detalles` WRITE;

UNLOCK TABLES;

/*Table structure for table `pedido_almacenes` */

CREATE TABLE `pedido_almacenes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `numero` int(11) NOT NULL,
  `fecha_pedido` date DEFAULT NULL,
  `fecha_insert` datetime NOT NULL,
  `empleado_insert` int(11) NOT NULL,
  `fecha_aceptado` datetime DEFAULT NULL COMMENT 'cambia de null, cuando el administrador lo acepta',
  `empleado_aceptado` int(11) DEFAULT NULL,
  `notas` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `pedido_almacenes` */

LOCK TABLES `pedido_almacenes` WRITE;

UNLOCK TABLES;

/*Table structure for table `producto_movimientos` */

CREATE TABLE `producto_movimientos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) DEFAULT NULL,
  `movimiento` tinyint(4) DEFAULT NULL COMMENT '1 ingreso(de mercaderia), 2 salida (de mercaderia)',
  `cantidad` int(11) DEFAULT NULL,
  `motivo` text,
  `empleado_insert` int(11) DEFAULT NULL,
  `fecha_insert` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `producto_movimientos` */

LOCK TABLES `producto_movimientos` WRITE;

insert  into `producto_movimientos`(`id`,`producto_id`,`movimiento`,`cantidad`,`motivo`,`empleado_insert`,`fecha_insert`) values (1,10,2,20,'cuadre',1,'2022-01-05 11:27:43');

UNLOCK TABLES;

/*Table structure for table `productos` */

CREATE TABLE `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_sunat` char(50) DEFAULT NULL,
  `codigo` varchar(40) NOT NULL,
  `producto` varchar(250) NOT NULL,
  `descripcion` text,
  `precio_base_venta` decimal(15,6) DEFAULT NULL,
  `comision_venta` decimal(10,2) DEFAULT NULL COMMENT 'comision de ganancia para el vendedor',
  `stock_inicial` decimal(10,2) DEFAULT '0.00',
  `stock_actual` decimal(10,2) DEFAULT '0.00',
  `precio_costo` decimal(15,6) DEFAULT NULL,
  `imagen` varchar(350) DEFAULT NULL,
  `categoria_id` int(11) NOT NULL,
  `unidad_id` int(11) NOT NULL,
  `fecha_insert` datetime NOT NULL,
  `empleado_insert` int(11) NOT NULL,
  `fecha_update` datetime DEFAULT NULL,
  `empleado_update` int(11) DEFAULT NULL,
  `fecha_delete` datetime DEFAULT NULL,
  `empleado_delete` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `productos` */

LOCK TABLES `productos` WRITE;

insert  into `productos`(`id`,`codigo_sunat`,`codigo`,`producto`,`descripcion`,`precio_base_venta`,`comision_venta`,`stock_inicial`,`stock_actual`,`precio_costo`,`imagen`,`categoria_id`,`unidad_id`,`fecha_insert`,`empleado_insert`,`fecha_update`,`empleado_update`,`fecha_delete`,`empleado_delete`) values (1,'-','C00-1','Pollo a la brasa','',0.000000,NULL,0.00,-8.00,42.373000,NULL,1,58,'2022-12-23 13:19:50',1,NULL,NULL,NULL,NULL),(2,'-','C00-2','Lapicero','',5.000000,NULL,0.00,-12.00,0.000000,NULL,1,58,'2022-12-30 10:21:33',1,NULL,NULL,NULL,NULL),(3,'-','C00-3','Laptop','',1000.000000,NULL,0.00,-1.00,0.000000,NULL,1,58,'2022-12-30 10:21:44',1,NULL,NULL,NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `regimenes` */

CREATE TABLE `regimenes` (
  `id` int(11) DEFAULT NULL,
  `regimen` varchar(150) DEFAULT NULL,
  `abreviatura` varchar(20) DEFAULT NULL,
  `codigo` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `regimenes` */

LOCK TABLES `regimenes` WRITE;

insert  into `regimenes`(`id`,`regimen`,`abreviatura`,`codigo`) values (1,'Nuevo Régimen Único Simplificado','NRUS',NULL),(2,'Régimen Especial de Renta','RER',NULL),(3,'Régimen MYPE Tributario','RMT',NULL),(4,'Régimen General de Renta','RG',NULL);

UNLOCK TABLES;

/*Table structure for table `resumenes` */

CREATE TABLE `resumenes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `correlativo` int(11) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `estado` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `resumenes` */

LOCK TABLES `resumenes` WRITE;

insert  into `resumenes`(`id`,`numero`,`correlativo`,`fecha`,`estado`) values (3,'19-09-2020-1',1,'2020-09-19 19:09:16',5),(4,'19-09-2020-2',2,'2020-09-19 20:09:39',0);

UNLOCK TABLES;

/*Table structure for table `series` */

CREATE TABLE `series` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_documento_id` int(11) NOT NULL,
  `serie` varchar(4) NOT NULL,
  `fecha_insert` datetime NOT NULL,
  `fecha_update` datetime DEFAULT NULL,
  `fecha_delete` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `series` */

LOCK TABLES `series` WRITE;

insert  into `series`(`id`,`tipo_documento_id`,`serie`,`fecha_insert`,`fecha_update`,`fecha_delete`) values (2,3,'B001','2020-10-07 02:16:20',NULL,NULL),(4,7,'FC01','2020-10-07 02:16:41',NULL,NULL),(3,7,'BC01','2020-10-07 02:16:55',NULL,NULL),(6,8,'FD01','2020-10-07 02:17:19',NULL,NULL),(5,8,'BD11','2020-10-07 02:17:32',NULL,NULL),(1,1,'F001','2020-10-07 21:56:49',NULL,NULL),(7,9,'TV50','2021-01-24 13:23:23',NULL,NULL);

UNLOCK TABLES;

/*Table structure for table `test_borrar` */

CREATE TABLE `test_borrar` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `year` varchar(10) DEFAULT NULL,
  `month` varchar(50) DEFAULT NULL,
  `profit` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=93 DEFAULT CHARSET=utf8;

/*Data for the table `test_borrar` */

LOCK TABLES `test_borrar` WRITE;

insert  into `test_borrar`(`id`,`year`,`month`,`profit`) values (1,'2016','January','50000'),(2,'2016','Febreary','45000'),(3,'2016','March','60000'),(4,'2016','April','52000'),(5,'2016','May','67000'),(6,'2016','June','74000'),(7,'2016','July','71000'),(8,'2016','August','76000'),(9,'2016','September','80000'),(10,'2016','October','60000'),(11,'2016','November','76000'),(12,'2016','December','83000'),(21,'2017','January','60000'),(22,'2017','Febreary','95000'),(23,'2017','March','59000'),(24,'2017','April','56000'),(25,'2017','May','35000'),(26,'2017','June','29000'),(27,'2017','July','84000'),(28,'2017','August','35000'),(29,'2017','September','29000'),(30,'2017','October','93000'),(31,'2017','November','71000'),(32,'2017','December','19000'),(41,'2018','January','70000'),(42,'2018','Febreary','15000'),(43,'2018','March','69000'),(44,'2018','April','66000'),(45,'2018','May','45000'),(46,'2018','June','39000'),(47,'2018','July','94000'),(48,'2018','August','45000'),(49,'2018','September','39000'),(50,'2018','October','13000'),(51,'2018','November','81000'),(52,'2018','December','39000'),(61,'2019','January','80000'),(62,'2019','Febreary','15000'),(63,'2019','March','69000'),(64,'2019','April','66000'),(65,'2019','May','45000'),(66,'2019','June','39000'),(67,'2019','July','94000'),(68,'2019','August','45000'),(69,'2019','September','39000'),(70,'2019','October','13000'),(71,'2019','November','81000'),(72,'2019','December','39000'),(81,'2020','January','90000'),(82,'2020','Febreary','15000'),(83,'2020','March','69000'),(84,'2020','April','66000'),(85,'2020','May','45000'),(86,'2020','June','39000'),(87,'2020','July','94000'),(88,'2020','August','45000'),(89,'2020','September','39000'),(90,'2020','October','13000'),(91,'2020','November','81000'),(92,'2020','December','39000');

UNLOCK TABLES;

/*Table structure for table `tipo_accesos` */

CREATE TABLE `tipo_accesos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '1: regular (ingresa con dni), 2: acceso con clave mensual ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `tipo_accesos` */

LOCK TABLES `tipo_accesos` WRITE;

insert  into `tipo_accesos`(`id`) values (1);

UNLOCK TABLES;

/*Table structure for table `tipo_cambios` */

CREATE TABLE `tipo_cambios` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `moneda_id` int(10) NOT NULL,
  `fecha` date NOT NULL,
  `tipo_cambio` decimal(10,3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=372 DEFAULT CHARSET=utf8;

/*Data for the table `tipo_cambios` */

LOCK TABLES `tipo_cambios` WRITE;

insert  into `tipo_cambios`(`id`,`moneda_id`,`fecha`,`tipo_cambio`) values (369,2,'2018-11-13',3.151),(370,2,'2019-02-07',3.400),(371,2,'2020-10-08',3.510);

UNLOCK TABLES;

/*Table structure for table `tipo_cuentas` */

CREATE TABLE `tipo_cuentas` (
  `id` int(11) DEFAULT NULL,
  `tipo_cuenta` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `tipo_cuentas` */

LOCK TABLES `tipo_cuentas` WRITE;

insert  into `tipo_cuentas`(`id`,`tipo_cuenta`) values (1,'Corriente'),(2,'Ahorro'),(3,'Detracciones');

UNLOCK TABLES;

/*Table structure for table `tipo_documentos` */

CREATE TABLE `tipo_documentos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(4) NOT NULL,
  `tipo_documento` varchar(50) DEFAULT NULL,
  `abreviado` varchar(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

/*Data for the table `tipo_documentos` */

LOCK TABLES `tipo_documentos` WRITE;

insert  into `tipo_documentos`(`id`,`codigo`,`tipo_documento`,`abreviado`) values (1,'01','Factura','F'),(3,'03','Boleta','B'),(7,'07','Nota de Credito','NC'),(8,'08','Nota de Debito','ND'),(9,'09','Guía de Remisión Remitente','GR');

UNLOCK TABLES;

/*Table structure for table `tipo_empleado_modulos` */

CREATE TABLE `tipo_empleado_modulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_empleado_id` int(11) NOT NULL,
  `modulo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=994 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `tipo_empleado_modulos` */

LOCK TABLES `tipo_empleado_modulos` WRITE;

insert  into `tipo_empleado_modulos`(`id`,`tipo_empleado_id`,`modulo_id`) values (12,1,400),(13,1,401),(14,1,402),(15,1,403),(16,1,404),(17,1,500),(18,1,501),(19,1,502),(20,1,503),(21,1,504),(11,1,304),(22,1,600),(10,1,303),(23,1,601),(24,1,602),(9,1,302),(8,1,301),(7,1,300),(6,1,202),(5,1,201),(4,1,200),(3,1,102),(2,1,101),(25,1,603),(1,1,100),(26,1,604),(969,1,405),(27,1,505),(305,1,305),(29,1,606),(28,1,605),(306,1,306);

UNLOCK TABLES;

/*Table structure for table `tipo_empleados` */

CREATE TABLE `tipo_empleados` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_empleado` varchar(200) DEFAULT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

/*Data for the table `tipo_empleados` */

LOCK TABLES `tipo_empleados` WRITE;

insert  into `tipo_empleados`(`id`,`tipo_empleado`,`estado`) values (1,'ADMINISTRADOR',2),(56,'Almacen',1),(57,'Cajero',1),(58,'1111',1),(59,'333',1),(60,'333',1),(61,'Vendedora',1);

UNLOCK TABLES;

/*Table structure for table `tipo_entidades` */

CREATE TABLE `tipo_entidades` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `tipo_entidad` varchar(60) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `descripcion` varchar(60) NOT NULL,
  `abreviatura` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `tipo_entidades` */

LOCK TABLES `tipo_entidades` WRITE;

insert  into `tipo_entidades`(`id`,`tipo_entidad`,`codigo`,`descripcion`,`abreviatura`) values (1,'DNI','1','DOC.NACIONAL DE IDEN','DNI'),(2,'RUC','6','REG. UNICO DE CONTRI','RUC'),(3,'Empresas Del Extranjero - No Domiciliado','0','DOC.TRIB.NO.DOM.SIN.RUC','Emp. Ext'),(4,'Carnet de Extranjeria','4','CARNET DE EXTRANJERIA','Car. Ext'),(5,'Pasaporte','7','PASAPORTE','Pasaport');

UNLOCK TABLES;

/*Table structure for table `tipo_igvs` */

CREATE TABLE `tipo_igvs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) DEFAULT NULL,
  `tipo_igv` varchar(60) DEFAULT NULL,
  `codigo_de_tributo` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

/*Data for the table `tipo_igvs` */

LOCK TABLES `tipo_igvs` WRITE;

insert  into `tipo_igvs`(`id`,`codigo`,`tipo_igv`,`codigo_de_tributo`) values (1,'10','Gravado - Operación Onerosa',1000),(2,'11','Gravado - Retiro por premio',9996),(3,'12','Gravado - Retiro por donación',9996),(4,'13','Gravado - Retiro',9996),(5,'14','Gravado - Retiro por publicidad',9996),(6,'15','Gravado - Bonificaciones',9996),(7,'16','Gravado - Retiro por entrega a trabajadores',9996),(8,'17','Gravado - IVAP',9996),(9,'20','Exonerado - Operación Onerosa',9997),(10,'21','Exonerado - Transferencia gratuita',9996),(11,'30','Inafecto - Operación Onerosa',9998),(12,'31','Inafecto - Retiro por Bonificación',9996),(13,'32','Inafecto - Retiro',9996),(14,'33','Inafecto - Retiro por Muestras Médicas',9996),(15,'34','Inafecto - Retiro por Convenio Colectivo',9996),(16,'35','Inafecto - Retiro por premio',9996),(17,'36','Inafecto - Retiro por publicidad',9996),(18,'37','Inafecto - Transferencia gratuita',9996),(19,'40','Exportación de Bienes o Servicios',9995);

UNLOCK TABLES;

/*Table structure for table `tipo_ncreditos` */

CREATE TABLE `tipo_ncreditos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) DEFAULT NULL,
  `tipo_ncredito` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `tipo_ncreditos` */

LOCK TABLES `tipo_ncreditos` WRITE;

insert  into `tipo_ncreditos`(`id`,`codigo`,`tipo_ncredito`) values (1,'01','Anulación de la operacion'),(2,'02','Anulación por error en el RUC'),(3,'03','Corrección por error en la descripcion'),(4,'04','Descuento Global'),(5,'05','Descuento por ítem'),(6,'06','Devolución total'),(7,'07','Devolución por ítem'),(8,'08','Bonificación'),(9,'09','Disminición en el valor'),(10,'10','Otros conceptos'),(11,'11','Ajustes de operaciones de exportación'),(12,'12','Ajustes afectos al IVAP'),(13,'13','Corrección o modificación del monto neto pendiente de pago y');

UNLOCK TABLES;

/*Table structure for table `tipo_ndebitos` */

CREATE TABLE `tipo_ndebitos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) DEFAULT NULL,
  `tipo_ndebito` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `tipo_ndebitos` */

LOCK TABLES `tipo_ndebitos` WRITE;

insert  into `tipo_ndebitos`(`id`,`codigo`,`tipo_ndebito`) values (1,'01','Interes por mora'),(2,'02','Aumento en el valor'),(3,'03','Penalidades / Otros conceptos');

UNLOCK TABLES;

/*Table structure for table `ubigeo_departamentos` */

CREATE TABLE `ubigeo_departamentos` (
  `id` char(2) NOT NULL,
  `departamento` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `ubigeo_departamentos` */

LOCK TABLES `ubigeo_departamentos` WRITE;

insert  into `ubigeo_departamentos`(`id`,`departamento`) values ('01','Amazonas'),('02','Ancash'),('03','Apurimac'),('04','Arequipa'),('05','Ayacucho'),('06','Cajamarca'),('07','Callao'),('08','Cusco'),('09','Huancavelica'),('10','Huanuco'),('11','Ica'),('12','Junin'),('13','La Libertad'),('14','Lambayeque'),('15','Lima'),('16','Loreto'),('17','Madre de Dios'),('18','Moquegua'),('19','Pasco'),('20','Piura'),('21','Puno'),('22','San Martin'),('23','Tacna'),('24','Tumbes'),('25','Ucayali');

UNLOCK TABLES;

/*Table structure for table `ubigeo_distritos` */

CREATE TABLE `ubigeo_distritos` (
  `id` char(6) NOT NULL,
  `distrito` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `ubigeo_distritos` */

LOCK TABLES `ubigeo_distritos` WRITE;

insert  into `ubigeo_distritos`(`id`,`distrito`) values ('010101','Chachapoyas'),('010102','Asuncion'),('010103','Balsas'),('010104','Cheto'),('010105','Chiliquin'),('010106','Chuquibamba'),('010107','Granada'),('010108','Huancas'),('010109','La Jalca'),('010110','Leimebamba'),('010111','Levanto'),('010112','Magdalena'),('010113','Mariscal Castilla'),('010114','Molinopampa'),('010115','Montevideo'),('010116','Olleros'),('010117','Quinjalca'),('010118','San Francisco de Daguas'),('010119','San Isidro de Maino'),('010120','Soloco'),('010121','Sonche'),('010201','Bagua'),('010202','Aramango'),('010203','Copallin'),('010204','El Parco'),('010205','Imaza'),('010206','La Peca'),('010301','Jumbilla'),('010302','Chisquilla'),('010303','Churuja'),('010304','Corosha'),('010305','Cuispes'),('010306','Florida'),('010307','Jazan'),('010308','Recta'),('010309','San Carlos'),('010310','Shipasbamba'),('010311','Valera'),('010312','Yambrasbamba'),('010401','Nieva'),('010402','El Cenepa'),('010403','Rio Santiago'),('010501','Lamud'),('010502','Camporredondo'),('010503','Cocabamba'),('010504','Colcamar'),('010505','Conila'),('010506','Inguilpata'),('010507','Longuita'),('010508','Lonya Chico'),('010509','Luya'),('010510','Luya Viejo'),('010511','Maria'),('010512','Ocalli'),('010513','Ocumal'),('010514','Pisuquia'),('010515','Providencia'),('010516','San Cristobal'),('010517','San Francisco del Yeso'),('010518','San Jeronimo'),('010519','San Juan de Lopecancha'),('010520','Santa Catalina'),('010521','Santo Tomas'),('010522','Tingo'),('010523','Trita'),('010601','San Nicolas'),('010602','Chirimoto'),('010603','Cochamal'),('010604','Huambo'),('010605','Limabamba'),('010606','Longar'),('010607','Mariscal Benavides'),('010608','Milpuc'),('010609','Omia'),('010610','Santa Rosa'),('010611','Totora'),('010612','Vista Alegre'),('010701','Bagua Grande'),('010702','Cajaruro'),('010703','Cumba'),('010704','El Milagro'),('010705','Jamalca'),('010706','Lonya Grande'),('010707','Yamon'),('020101','Huaraz'),('020102','Cochabamba'),('020103','Colcabamba'),('020104','Huanchay'),('020105','Independencia'),('020106','Jangas'),('020107','La Libertad'),('020108','Olleros'),('020109','Pampas'),('020110','Pariacoto'),('020111','Pira'),('020112','Tarica'),('020201','Aija'),('020202','Coris'),('020203','Huacllan'),('020204','La Merced'),('020205','Succha'),('020301','Llamellin'),('020302','Aczo'),('020303','Chaccho'),('020304','Chingas'),('020305','Mirgas'),('020306','San Juan de Rontoy'),('020401','Chacas'),('020402','Acochaca'),('020501','Chiquian'),('020502','Abelardo Pardo Lezameta'),('020503','Antonio Raymondi'),('020504','Aquia'),('020505','Cajacay'),('020506','Canis'),('020507','Colquioc'),('020508','Huallanca'),('020509','Huasta'),('020510','Huayllacayan'),('020511','La Primavera'),('020512','Mangas'),('020513','Pacllon'),('020514','San Miguel de Corpanqui'),('020515','Ticllos'),('020601','Carhuaz'),('020602','Acopampa'),('020603','Amashca'),('020604','Anta'),('020605','Ataquero'),('020606','Marcara'),('020607','Pariahuanca'),('020608','San Miguel de Aco'),('020609','Shilla'),('020610','Tinco'),('020611','Yungar'),('020701','San Luis'),('020702','San Nicolas'),('020703','Yauya'),('020801','Casma'),('020802','Buena Vista Alta'),('020803','Comandante Noel'),('020804','Yautan'),('020901','Corongo'),('020902','Aco'),('020903','Bambas'),('020904','Cusca'),('020905','La Pampa'),('020906','Yanac'),('020907','Yupan'),('021001','Huari'),('021002','Anra'),('021003','Cajay'),('021004','Chavin de Huantar'),('021005','Huacachi'),('021006','Huacchis'),('021007','Huachis'),('021008','Huantar'),('021009','Masin'),('021010','Paucas'),('021011','Ponto'),('021012','Rahuapampa'),('021013','Rapayan'),('021014','San Marcos'),('021015','San Pedro de Chana'),('021016','Uco'),('021101','Huarmey'),('021102','Cochapeti'),('021103','Culebras'),('021104','Huayan'),('021105','Malvas'),('021201','Caraz'),('021202','Huallanca'),('021203','Huata'),('021204','Huaylas'),('021205','Mato'),('021206','Pamparomas'),('021207','Pueblo Libre'),('021208','Santa Cruz'),('021209','Santo Toribio'),('021210','Yuracmarca'),('021301','Piscobamba'),('021302','Casca'),('021303','Eleazar Guzman Barron'),('021304','Fidel Olivas Escudero'),('021305','Llama'),('021306','Llumpa'),('021307','Lucma'),('021308','Musga'),('021401','Ocros'),('021402','Acas'),('021403','Cajamarquilla'),('021404','Carhuapampa'),('021405','Cochas'),('021406','Congas'),('021407','Llipa'),('021408','San Cristobal de Rajan'),('021409','San Pedro'),('021410','Santiago de Chilcas'),('021501','Cabana'),('021502','Bolognesi'),('021503','Conchucos'),('021504','Huacaschuque'),('021505','Huandoval'),('021506','Lacabamba'),('021507','Llapo'),('021508','Pallasca'),('021509','Pampas'),('021510','Santa Rosa'),('021511','Tauca'),('021601','Pomabamba'),('021602','Huayllan'),('021603','Parobamba'),('021604','Quinuabamba'),('021701','Recuay'),('021702','Catac'),('021703','Cotaparaco'),('021704','Huayllapampa'),('021705','Llacllin'),('021706','Marca'),('021707','Pampas Chico'),('021708','Pararin'),('021709','Tapacocha'),('021710','Ticapampa'),('021801','Chimbote'),('021802','Caceres del Peru'),('021803','Coishco'),('021804','Macate'),('021805','Moro'),('021806','Nepeña'),('021807','Samanco'),('021808','Santa'),('021809','Nuevo Chimbote'),('021901','Sihuas'),('021902','Acobamba'),('021903','Alfonso Ugarte'),('021904','Cashapampa'),('021905','Chingalpo'),('021906','Huayllabamba'),('021907','Quiches'),('021908','Ragash'),('021909','San Juan'),('021910','Sicsibamba'),('022001','Yungay'),('022002','Cascapara'),('022003','Mancos'),('022004','Matacoto'),('022005','Quillo'),('022006','Ranrahirca'),('022007','Shupluy'),('022008','Yanama'),('030101','Abancay'),('030102','Chacoche'),('030103','Circa'),('030104','Curahuasi'),('030105','Huanipaca'),('030106','Lambrama'),('030107','Pichirhua'),('030108','San Pedro de Cachora'),('030109','Tamburco'),('030201','Andahuaylas'),('030202','Andarapa'),('030203','Chiara'),('030204','Huancarama'),('030205','Huancaray'),('030206','Huayana'),('030207','Kishuara'),('030208','Pacobamba'),('030209','Pacucha'),('030210','Pampachiri'),('030211','Pomacocha'),('030212','San Antonio de Cachi'),('030213','San Jeronimo'),('030214','San Miguel de Chaccrampa'),('030215','Santa Maria de Chicmo'),('030216','Talavera'),('030217','Tumay Huaraca'),('030218','Turpo'),('030219','Kaquiabamba'),('030220','José María Arguedas'),('030301','Antabamba'),('030302','El Oro'),('030303','Huaquirca'),('030304','Juan Espinoza Medrano'),('030305','Oropesa'),('030306','Pachaconas'),('030307','Sabaino'),('030401','Chalhuanca'),('030402','Capaya'),('030403','Caraybamba'),('030404','Chapimarca'),('030405','Colcabamba'),('030406','Cotaruse'),('030407','Huayllo'),('030408','Justo Apu Sahuaraura'),('030409','Lucre'),('030410','Pocohuanca'),('030411','San Juan de Chacña'),('030412','Sañayca'),('030413','Soraya'),('030414','Tapairihua'),('030415','Tintay'),('030416','Toraya'),('030417','Yanaca'),('030501','Tambobamba'),('030502','Cotabambas'),('030503','Coyllurqui'),('030504','Haquira'),('030505','Mara'),('030506','Challhuahuacho'),('030601','Chincheros'),('030602','Anco_Huallo'),('030603','Cocharcas'),('030604','Huaccana'),('030605','Ocobamba'),('030606','Ongoy'),('030607','Uranmarca'),('030608','Ranracancha'),('030609','Rocchacc'),('030610','El Porvenir'),('030611','Los Chankas'),('030701','Chuquibambilla'),('030702','Curpahuasi'),('030703','Gamarra'),('030704','Huayllati'),('030705','Mamara'),('030706','Micaela Bastidas'),('030707','Pataypampa'),('030708','Progreso'),('030709','San Antonio'),('030710','Santa Rosa'),('030711','Turpay'),('030712','Vilcabamba'),('030713','Virundo'),('030714','Curasco'),('040101','Arequipa'),('040102','Alto Selva Alegre'),('040103','Cayma'),('040104','Cerro Colorado'),('040105','Characato'),('040106','Chiguata'),('040107','Jacobo Hunter'),('040108','La Joya'),('040109','Mariano Melgar'),('040110','Miraflores'),('040111','Mollebaya'),('040112','Paucarpata'),('040113','Pocsi'),('040114','Polobaya'),('040115','Quequeña'),('040116','Sabandia'),('040117','Sachaca'),('040118','San Juan de Siguas'),('040119','San Juan de Tarucani'),('040120','Santa Isabel de Siguas'),('040121','Santa Rita de Siguas'),('040122','Socabaya'),('040123','Tiabaya'),('040124','Uchumayo'),('040125','Vitor'),('040126','Yanahuara'),('040127','Yarabamba'),('040128','Yura'),('040129','Jose Luis Bustamante y Rivero'),('040201','Camana'),('040202','Jose Maria Quimper'),('040203','Mariano Nicolas Valcarcel'),('040204','Mariscal Caceres'),('040205','Nicolas de Pierola'),('040206','Ocoña'),('040207','Quilca'),('040208','Samuel Pastor'),('040301','Caraveli'),('040302','Acari'),('040303','Atico'),('040304','Atiquipa'),('040305','Bella Union'),('040306','Cahuacho'),('040307','Chala'),('040308','Chaparra'),('040309','Huanuhuanu'),('040310','Jaqui'),('040311','Lomas'),('040312','Quicacha'),('040313','Yauca'),('040401','Aplao'),('040402','Andagua'),('040403','Ayo'),('040404','Chachas'),('040405','Chilcaymarca'),('040406','Choco'),('040407','Huancarqui'),('040408','Machaguay'),('040409','Orcopampa'),('040410','Pampacolca'),('040411','Tipan'),('040412','Uñon'),('040413','Uraca'),('040414','Viraco'),('040501','Chivay'),('040502','Achoma'),('040503','Cabanaconde'),('040504','Callalli'),('040505','Caylloma'),('040506','Coporaque'),('040507','Huambo'),('040508','Huanca'),('040509','Ichupampa'),('040510','Lari'),('040511','Lluta'),('040512','Maca'),('040513','Madrigal'),('040514','San Antonio de Chuca'),('040515','Sibayo'),('040516','Tapay'),('040517','Tisco'),('040518','Tuti'),('040519','Yanque'),('040520','Majes'),('040601','Chuquibamba'),('040602','Andaray'),('040603','Cayarani'),('040604','Chichas'),('040605','Iray'),('040606','Rio Grande'),('040607','Salamanca'),('040608','Yanaquihua'),('040701','Mollendo'),('040702','Cocachacra'),('040703','Dean Valdivia'),('040704','Islay'),('040705','Mejia'),('040706','Punta de Bombon'),('040801','Cotahuasi'),('040802','Alca'),('040803','Charcana'),('040804','Huaynacotas'),('040805','Pampamarca'),('040806','Puyca'),('040807','Quechualla'),('040808','Sayla'),('040809','Tauria'),('040810','Tomepampa'),('040811','Toro'),('050101','Ayacucho'),('050102','Acocro'),('050103','Acos Vinchos'),('050104','Carmen Alto'),('050105','Chiara'),('050106','Ocros'),('050107','Pacaycasa'),('050108','Quinua'),('050109','San Jose de Ticllas'),('050110','San Juan Bautista'),('050111','Santiago de Pischa'),('050112','Socos'),('050113','Tambillo'),('050114','Vinchos'),('050115','Jesus Nazareno'),('050116','Andrés Avelino Cáceres Dorregaray'),('050201','Cangallo'),('050202','Chuschi'),('050203','Los Morochucos'),('050204','Maria Parado de Bellido'),('050205','Paras'),('050206','Totos'),('050301','Sancos'),('050302','Carapo'),('050303','Sacsamarca'),('050304','Santiago de Lucanamarca'),('050401','Huanta'),('050402','Ayahuanco'),('050403','Huamanguilla'),('050404','Iguain'),('050405','Luricocha'),('050406','Santillana'),('050407','Sivia'),('050408','Llochegua'),('050409','Canayre'),('050410','Uchuraccay'),('050411','Pucacolpa'),('050412','Chaca'),('050501','San Miguel'),('050502','Anco'),('050503','Ayna'),('050504','Chilcas'),('050505','Chungui'),('050506','Luis Carranza'),('050507','Santa Rosa'),('050508','Tambo'),('050509','Samugari'),('050510','Anchihuay'),('050511','Oronccoy'),('050601','Puquio'),('050602','Aucara'),('050603','Cabana'),('050604','Carmen Salcedo'),('050605','Chaviña'),('050606','Chipao'),('050607','Huac-Huas'),('050608','Laramate'),('050609','Leoncio Prado'),('050610','Llauta'),('050611','Lucanas'),('050612','Ocaña'),('050613','Otoca'),('050614','Saisa'),('050615','San Cristobal'),('050616','San Juan'),('050617','San Pedro'),('050618','San Pedro de Palco'),('050619','Sancos'),('050620','Santa Ana de Huaycahuacho'),('050621','Santa Lucia'),('050701','Coracora'),('050702','Chumpi'),('050703','Coronel Castañeda'),('050704','Pacapausa'),('050705','Pullo'),('050706','Puyusca'),('050707','San Francisco de Ravacayco'),('050708','Upahuacho'),('050801','Pausa'),('050802','Colta'),('050803','Corculla'),('050804','Lampa'),('050805','Marcabamba'),('050806','Oyolo'),('050807','Pararca'),('050808','San Javier de Alpabamba'),('050809','San Jose de Ushua'),('050810','Sara Sara'),('050901','Querobamba'),('050902','Belen'),('050903','Chalcos'),('050904','Chilcayoc'),('050905','Huacaña'),('050906','Morcolla'),('050907','Paico'),('050908','San Pedro de Larcay'),('050909','San Salvador de Quije'),('050910','Santiago de Paucaray'),('050911','Soras'),('051001','Huancapi'),('051002','Alcamenca'),('051003','Apongo'),('051004','Asquipata'),('051005','Canaria'),('051006','Cayara'),('051007','Colca'),('051008','Huamanquiquia'),('051009','Huancaraylla'),('051010','Huaya'),('051011','Sarhua'),('051012','Vilcanchos'),('051101','Vilcas Huaman'),('051102','Accomarca'),('051103','Carhuanca'),('051104','Concepcion'),('051105','Huambalpa'),('051106','Independencia'),('051107','Saurama'),('051108','Vischongo'),('060101','Cajamarca'),('060102','Asuncion'),('060103','Chetilla'),('060104','Cospan'),('060105','Encañada'),('060106','Jesus'),('060107','Llacanora'),('060108','Los Baños del Inca'),('060109','Magdalena'),('060110','Matara'),('060111','Namora'),('060112','San Juan'),('060201','Cajabamba'),('060202','Cachachi'),('060203','Condebamba'),('060204','Sitacocha'),('060301','Celendin'),('060302','Chumuch'),('060303','Cortegana'),('060304','Huasmin'),('060305','Jorge Chavez'),('060306','Jose Galvez'),('060307','Miguel Iglesias'),('060308','Oxamarca'),('060309','Sorochuco'),('060310','Sucre'),('060311','Utco'),('060312','La Libertad de Pallan'),('060401','Chota'),('060402','Anguia'),('060403','Chadin'),('060404','Chiguirip'),('060405','Chimban'),('060406','Choropampa'),('060407','Cochabamba'),('060408','Conchan'),('060409','Huambos'),('060410','Lajas'),('060411','Llama'),('060412','Miracosta'),('060413','Paccha'),('060414','Pion'),('060415','Querocoto'),('060416','San Juan de Licupis'),('060417','Tacabamba'),('060418','Tocmoche'),('060419','Chalamarca'),('060501','Contumaza'),('060502','Chilete'),('060503','Cupisnique'),('060504','Guzmango'),('060505','San Benito'),('060506','Santa Cruz de Toled'),('060507','Tantarica'),('060508','Yonan'),('060601','Cutervo'),('060602','Callayuc'),('060603','Choros'),('060604','Cujillo'),('060605','La Ramada'),('060606','Pimpingos'),('060607','Querocotillo'),('060608','San Andres de Cutervo'),('060609','San Juan de Cutervo'),('060610','San Luis de Lucma'),('060611','Santa Cruz'),('060612','Santo Domingo de La Capilla'),('060613','Santo Tomas'),('060614','Socota'),('060615','Toribio Casanova'),('060701','Bambamarca'),('060702','Chugur'),('060703','Hualgayoc'),('060801','Jaen'),('060802','Bellavista'),('060803','Chontali'),('060804','Colasay'),('060805','Huabal'),('060806','Las Pirias'),('060807','Pomahuaca'),('060808','Pucara'),('060809','Sallique'),('060810','San Felipe'),('060811','San Jose del Alto'),('060812','Santa Rosa'),('060901','San Ignacio'),('060902','Chirinos'),('060903','Huarango'),('060904','La Coipa'),('060905','Namballe'),('060906','San Jose de Lourdes'),('060907','Tabaconas'),('061001','Pedro Galvez'),('061002','Chancay'),('061003','Eduardo Villanueva'),('061004','Gregorio Pita'),('061005','Ichocan'),('061006','Jose Manuel Quiroz'),('061007','Jose Sabogal'),('061101','San Miguel'),('061102','Bolivar'),('061103','Calquis'),('061104','Catilluc'),('061105','El Prado'),('061106','La Florida'),('061107','Llapa'),('061108','Nanchoc'),('061109','Niepos'),('061110','San Gregorio'),('061111','San Silvestre de Cochan'),('061112','Tongod'),('061113','Union Agua Blanca'),('061201','San Pablo'),('061202','San Bernardino'),('061203','San Luis'),('061204','Tumbaden'),('061301','Santa Cruz'),('061302','Andabamba'),('061303','Catache'),('061304','Chancaybaños'),('061305','La Esperanza'),('061306','Ninabamba'),('061307','Pulan'),('061308','Saucepampa'),('061309','Sexi'),('061310','Uticyacu'),('061311','Yauyucan'),('070101','Callao'),('070102','Bellavista'),('070103','Carmen de La Legua'),('070104','La Perla'),('070105','La Punta'),('070106','Ventanilla'),('070107','Mi Peru'),('080101','Cusco'),('080102','Ccorca'),('080103','Poroy'),('080104','San Jeronimo'),('080105','San Sebastian'),('080106','Santiago'),('080107','Saylla'),('080108','Wanchaq'),('080201','Acomayo'),('080202','Acopia'),('080203','Acos'),('080204','Mosoc Llacta'),('080205','Pomacanchi'),('080206','Rondocan'),('080207','Sangarara'),('080301','Anta'),('080302','Ancahuasi'),('080303','Cachimayo'),('080304','Chinchaypujio'),('080305','Huarocondo'),('080306','Limatambo'),('080307','Mollepata'),('080308','Pucyura'),('080309','Zurite'),('080401','Calca'),('080402','Coya'),('080403','Lamay'),('080404','Lares'),('080405','Pisac'),('080406','San Salvador'),('080407','Taray'),('080408','Yanatile'),('080501','Yanaoca'),('080502','Checca'),('080503','Kunturkanki'),('080504','Langui'),('080505','Layo'),('080506','Pampamarca'),('080507','Quehue'),('080508','Tupac Amaru'),('080601','Sicuani'),('080602','Checacupe'),('080603','Combapata'),('080604','Marangani'),('080605','Pitumarca'),('080606','San Pablo'),('080607','San Pedro'),('080608','Tinta'),('080701','Santo Tomas'),('080702','Capacmarca'),('080703','Chamaca'),('080704','Colquemarca'),('080705','Livitaca'),('080706','Llusco'),('080707','Quiñota'),('080708','Velille'),('080801','Espinar'),('080802','Condoroma'),('080803','Coporaque'),('080804','Ocoruro'),('080805','Pallpata'),('080806','Pichigua'),('080807','Suyckutambo'),('080808','Alto Pichigua'),('080901','Santa Ana'),('080902','Echarate'),('080903','Huayopata'),('080904','Maranura'),('080905','Ocobamba'),('080906','Quellouno'),('080907','Kimbiri'),('080908','Santa Teresa'),('080909','Vilcabamba'),('080910','Pichari'),('080911','Inkawasi'),('080912','Villa Virgen'),('080913','Villa Kintiarina'),('080914','Megantoni'),('081001','Paruro'),('081002','Accha'),('081003','Ccapi'),('081004','Colcha'),('081005','Huanoquite'),('081006','Omacha'),('081007','Paccaritambo'),('081008','Pillpinto'),('081009','Yaurisque'),('081101','Paucartambo'),('081102','Caicay'),('081103','Challabamba'),('081104','Colquepata'),('081105','Huancarani'),('081106','Kosñipata'),('081201','Urcos'),('081202','Andahuaylillas'),('081203','Camanti'),('081204','Ccarhuayo'),('081205','Ccatca'),('081206','Cusipata'),('081207','Huaro'),('081208','Lucre'),('081209','Marcapata'),('081210','Ocongate'),('081211','Oropesa'),('081212','Quiquijana'),('081301','Urubamba'),('081302','Chinchero'),('081303','Huayllabamba'),('081304','Machupicchu'),('081305','Maras'),('081306','Ollantaytambo'),('081307','Yucay'),('090101','Huancavelica'),('090102','Acobambilla'),('090103','Acoria'),('090104','Conayca'),('090105','Cuenca'),('090106','Huachocolpa'),('090107','Huayllahuara'),('090108','Izcuchaca'),('090109','Laria'),('090110','Manta'),('090111','Mariscal Caceres'),('090112','Moya'),('090113','Nuevo Occoro'),('090114','Palca'),('090115','Pilchaca'),('090116','Vilca'),('090117','Yauli'),('090118','Ascension'),('090119','Huando'),('090201','Acobamba'),('090202','Andabamba'),('090203','Anta'),('090204','Caja'),('090205','Marcas'),('090206','Paucara'),('090207','Pomacocha'),('090208','Rosario'),('090301','Lircay'),('090302','Anchonga'),('090303','Callanmarca'),('090304','Ccochaccasa'),('090305','Chincho'),('090306','Congalla'),('090307','Huanca-Huanca'),('090308','Huayllay Grande'),('090309','Julcamarca'),('090310','San Antonio de Antaparco'),('090311','Santo Tomas de Pata'),('090312','Secclla'),('090401','Castrovirreyna'),('090402','Arma'),('090403','Aurahua'),('090404','Capillas'),('090405','Chupamarca'),('090406','Cocas'),('090407','Huachos'),('090408','Huamatambo'),('090409','Mollepampa'),('090410','San Juan'),('090411','Santa Ana'),('090412','Tantara'),('090413','Ticrapo'),('090501','Churcampa'),('090502','Anco'),('090503','Chinchihuasi'),('090504','El Carmen'),('090505','La Merced'),('090506','Locroja'),('090507','Paucarbamba'),('090508','San Miguel de Mayocc'),('090509','San Pedro de Coris'),('090510','Pachamarca'),('090511','Cosme'),('090601','Huaytara'),('090602','Ayavi'),('090603','Cordova'),('090604','Huayacundo Arma'),('090605','Laramarca'),('090606','Ocoyo'),('090607','Pilpichaca'),('090608','Querco'),('090609','Quito-Arma'),('090610','San Antonio de Cusicancha'),('090611','San Francisco de Sangayaico'),('090612','San Isidro'),('090613','Santiago de Chocorvos'),('090614','Santiago de Quirahuara'),('090615','Santo Domingo de Capillas'),('090616','Tambo'),('090701','Pampas'),('090702','Acostambo'),('090703','Acraquia'),('090704','Ahuaycha'),('090705','Colcabamba'),('090706','Daniel Hernandez'),('090707','Huachocolpa'),('090709','Huaribamba'),('090710','Ñahuimpuquio'),('090711','Pazos'),('090713','Quishuar'),('090714','Salcabamba'),('090715','Salcahuasi'),('090716','San Marcos de Rocchac'),('090717','Surcubamba'),('090718','Tintay Puncu'),('090719','Quichuas'),('090720','Andaymarca'),('090721','Roble'),('090722','Pichos'),('090723','Santiago de Túcuma'),('100101','Huanuco'),('100102','Amarilis'),('100103','Chinchao'),('100104','Churubamba'),('100105','Margos'),('100106','Quisqui'),('100107','San Francisco de Cayran'),('100108','San Pedro de Chaulan'),('100109','Santa Maria del Valle'),('100110','Yarumayo'),('100111','Pillco Marca'),('100112','Yacus'),('100113','San Pablo de Pillao'),('100201','Ambo'),('100202','Cayna'),('100203','Colpas'),('100204','Conchamarca'),('100205','Huacar'),('100206','San Francisco'),('100207','San Rafael'),('100208','Tomay Kichwa'),('100301','La Union'),('100307','Chuquis'),('100311','Marias'),('100313','Pachas'),('100316','Quivilla'),('100317','Ripan'),('100321','Shunqui'),('100322','Sillapata'),('100323','Yanas'),('100401','Huacaybamba'),('100402','Canchabamba'),('100403','Cochabamba'),('100404','Pinra'),('100501','Llata'),('100502','Arancay'),('100503','Chavin de Pariarca'),('100504','Jacas Grande'),('100505','Jircan'),('100506','Miraflores'),('100507','Monzon'),('100508','Punchao'),('100509','Puños'),('100510','Singa'),('100511','Tantamayo'),('100601','Rupa-Rupa'),('100602','Daniel Alomias Robles'),('100603','Hermilio Valdizan'),('100604','Jose Crespo y Castillo'),('100605','Luyando'),('100606','Mariano Damaso Beraun'),('100607','Pucayacu'),('100608','Castillo Grande'),('100609','Pueblo Nuevo'),('100610','Santo Domingo de Anda'),('100701','Huacrachuco'),('100702','Cholon'),('100703','San Buenaventura'),('100704','La Morada'),('100705','Santa Rosa de Alto Yanajanca'),('100801','Panao'),('100802','Chaglla'),('100803','Molino'),('100804','Umari'),('100901','Puerto Inca'),('100902','Codo del Pozuzo'),('100903','Honoria'),('100904','Tournavista'),('100905','Yuyapichis'),('101001','Jesus'),('101002','Baños'),('101003','Jivia'),('101004','Queropalca'),('101005','Rondos'),('101006','San Francisco de Asis'),('101007','San Miguel de Cauri'),('101101','Chavinillo'),('101102','Cahuac'),('101103','Chacabamba'),('101104','Aparicio Pomares'),('101105','Jacas Chico'),('101106','Obas'),('101107','Pampamarca'),('101108','Choras'),('110101','Ica'),('110102','La Tinguiña'),('110103','Los Aquijes'),('110104','Ocucaje'),('110105','Pachacutec'),('110106','Parcona'),('110107','Pueblo Nuevo'),('110108','Salas'),('110109','San Jose de los Molinos'),('110110','San Juan Bautista'),('110111','Santiago'),('110112','Subtanjalla'),('110113','Tate'),('110114','Yauca del Rosario'),('110201','Chincha Alta'),('110202','Alto Laran'),('110203','Chavin'),('110204','Chincha Baja'),('110205','El Carmen'),('110206','Grocio Prado'),('110207','Pueblo Nuevo'),('110208','San Juan de Yanac'),('110209','San Pedro de Huacarpana'),('110210','Sunampe'),('110211','Tambo de Mora'),('110301','Nazca'),('110302','Changuillo'),('110303','El Ingenio'),('110304','Marcona'),('110305','Vista Alegre'),('110401','Palpa'),('110402','Llipata'),('110403','Rio Grande'),('110404','Santa Cruz'),('110405','Tibillo'),('110501','Pisco'),('110502','Huancano'),('110503','Humay'),('110504','Independencia'),('110505','Paracas'),('110506','San Andres'),('110507','San Clemente'),('110508','Tupac Amaru Inca'),('120101','Huancayo'),('120104','Carhuacallanga'),('120105','Chacapampa'),('120106','Chicche'),('120107','Chilca'),('120108','Chongos Alto'),('120111','Chupuro'),('120112','Colca'),('120113','Cullhuas'),('120114','El Tambo'),('120116','Huacrapuquio'),('120117','Hualhuas'),('120119','Huancan'),('120120','Huasicancha'),('120121','Huayucachi'),('120122','Ingenio'),('120124','Pariahuanca'),('120125','Pilcomayo'),('120126','Pucara'),('120127','Quichuay'),('120128','Quilcas'),('120129','San Agustin'),('120130','San Jeronimo de Tunan'),('120132','Saño'),('120133','Sapallanga'),('120134','Sicaya'),('120135','Santo Domingo de Acobamba'),('120136','Viques'),('120201','Concepcion'),('120202','Aco'),('120203','Andamarca'),('120204','Chambara'),('120205','Cochas'),('120206','Comas'),('120207','Heroinas Toledo'),('120208','Manzanares'),('120209','Mariscal Castilla'),('120210','Matahuasi'),('120211','Mito'),('120212','Nueve de Julio'),('120213','Orcotuna'),('120214','San Jose de Quero'),('120215','Santa Rosa de Ocopa'),('120301','Chanchamayo'),('120302','Perene'),('120303','Pichanaqui'),('120304','San Luis de Shuaro'),('120305','San Ramon'),('120306','Vitoc'),('120401','Jauja'),('120402','Acolla'),('120403','Apata'),('120404','Ataura'),('120405','Canchayllo'),('120406','Curicaca'),('120407','El Mantaro'),('120408','Huamali'),('120409','Huaripampa'),('120410','Huertas'),('120411','Janjaillo'),('120412','Julcan'),('120413','Leonor Ordoñez'),('120414','Llocllapampa'),('120415','Marco'),('120416','Masma'),('120417','Masma Chicche'),('120418','Molinos'),('120419','Monobamba'),('120420','Muqui'),('120421','Muquiyauyo'),('120422','Paca'),('120423','Paccha'),('120424','Pancan'),('120425','Parco'),('120426','Pomacancha'),('120427','Ricran'),('120428','San Lorenzo'),('120429','San Pedro de Chunan'),('120430','Sausa'),('120431','Sincos'),('120432','Tunan Marca'),('120433','Yauli'),('120434','Yauyos'),('120501','Junin'),('120502','Carhuamayo'),('120503','Ondores'),('120504','Ulcumayo'),('120601','Satipo'),('120602','Coviriali'),('120603','Llaylla'),('120604','Mazamari'),('120605','Pampa Hermosa'),('120606','Pangoa'),('120607','Rio Negro'),('120608','Rio Tambo'),('120609','Vizcatán del Ene'),('120701','Tarma'),('120702','Acobamba'),('120703','Huaricolca'),('120704','Huasahuasi'),('120705','La Union'),('120706','Palca'),('120707','Palcamayo'),('120708','San Pedro de Cajas'),('120709','Tapo'),('120801','La Oroya'),('120802','Chacapalpa'),('120803','Huay-Huay'),('120804','Marcapomacocha'),('120805','Morococha'),('120806','Paccha'),('120807','Santa Barbara de Carhuacayan'),('120808','Santa Rosa de Sacco'),('120809','Suitucancha'),('120810','Yauli'),('120901','Chupaca'),('120902','Ahuac'),('120903','Chongos Bajo'),('120904','Huachac'),('120905','Huamancaca Chico'),('120906','San Juan de Yscos'),('120907','San Juan de Jarpa'),('120908','Tres de Diciembre'),('120909','Yanacancha'),('130101','Trujillo'),('130102','El Porvenir'),('130103','Florencia de Mora'),('130104','Huanchaco'),('130105','La Esperanza'),('130106','Laredo'),('130107','Moche'),('130108','Poroto'),('130109','Salaverry'),('130110','Simbal'),('130111','Victor Larco Herrera'),('130201','Ascope'),('130202','Chicama'),('130203','Chocope'),('130204','Magdalena de Cao'),('130205','Paijan'),('130206','Razuri'),('130207','Santiago de Cao'),('130208','Casa Grande'),('130301','Bolivar'),('130302','Bambamarca'),('130303','Condormarca'),('130304','Longotea'),('130305','Uchumarca'),('130306','Ucuncha'),('130401','Chepen'),('130402','Pacanga'),('130403','Pueblo Nuevo'),('130501','Julcan'),('130502','Calamarca'),('130503','Carabamba'),('130504','Huaso'),('130601','Otuzco'),('130602','Agallpampa'),('130604','Charat'),('130605','Huaranchal'),('130606','La Cuesta'),('130608','Mache'),('130610','Paranday'),('130611','Salpo'),('130613','Sinsicap'),('130614','Usquil'),('130701','San Pedro de Lloc'),('130702','Guadalupe'),('130703','Jequetepeque'),('130704','Pacasmayo'),('130705','San Jose'),('130801','Tayabamba'),('130802','Buldibuyo'),('130803','Chillia'),('130804','Huancaspata'),('130805','Huaylillas'),('130806','Huayo'),('130807','Ongon'),('130808','Parcoy'),('130809','Pataz'),('130810','Pias'),('130811','Santiago de Challas'),('130812','Taurija'),('130813','Urpay'),('130901','Huamachuco'),('130902','Chugay'),('130903','Cochorco'),('130904','Curgos'),('130905','Marcabal'),('130906','Sanagoran'),('130907','Sarin'),('130908','Sartimbamba'),('131001','Santiago de Chuco'),('131002','Angasmarca'),('131003','Cachicadan'),('131004','Mollebamba'),('131005','Mollepata'),('131006','Quiruvilca'),('131007','Santa Cruz de Chuca'),('131008','Sitabamba'),('131101','Cascas'),('131102','Lucma'),('131103','Compin'),('131104','Sayapullo'),('131201','Viru'),('131202','Chao'),('131203','Guadalupito'),('140101','Chiclayo'),('140102','Chongoyape'),('140103','Eten'),('140104','Eten Puerto'),('140105','Jose Leonardo Ortiz'),('140106','La Victoria'),('140107','Lagunas'),('140108','Monsefu'),('140109','Nueva Arica'),('140110','Oyotun'),('140111','Picsi'),('140112','Pimentel'),('140113','Reque'),('140114','Santa Rosa'),('140115','Saña'),('140116','Cayalti'),('140117','Patapo'),('140118','Pomalca'),('140119','Pucala'),('140120','Tuman'),('140201','Ferreñafe'),('140202','Cañaris'),('140203','Incahuasi'),('140204','Manuel Antonio Mesones Muro'),('140205','Pitipo'),('140206','Pueblo Nuevo'),('140301','Lambayeque'),('140302','Chochope'),('140303','Illimo'),('140304','Jayanca'),('140305','Mochumi'),('140306','Morrope'),('140307','Motupe'),('140308','Olmos'),('140309','Pacora'),('140310','Salas'),('140311','San Jose'),('140312','Tucume'),('150101','Lima'),('150102','Ancon'),('150103','Ate'),('150104','Barranco'),('150105','Breña'),('150106','Carabayllo'),('150107','Chaclacayo'),('150108','Chorrillos'),('150109','Cieneguilla'),('150110','Comas'),('150111','El Agustino'),('150112','Independencia'),('150113','Jesus Maria'),('150114','La Molina'),('150115','La Victoria'),('150116','Lince'),('150117','Los Olivos'),('150118','Lurigancho'),('150119','Lurin'),('150120','Magdalena del Mar'),('150121','Pueblo Libre'),('150122','Miraflores'),('150123','Pachacamac'),('150124','Pucusana'),('150125','Puente Piedra'),('150126','Punta Hermosa'),('150127','Punta Negra'),('150128','Rimac'),('150129','San Bartolo'),('150130','San Borja'),('150131','San Isidro'),('150132','San Juan de Lurigancho'),('150133','San Juan de Miraflores'),('150134','San Luis'),('150135','San Martin de Porres'),('150136','San Miguel'),('150137','Santa Anita'),('150138','Santa Maria del Mar'),('150139','Santa Rosa'),('150140','Santiago de Surco'),('150141','Surquillo'),('150142','Villa El Salvador'),('150143','Villa Maria del Triunfo'),('150201','Barranca'),('150202','Paramonga'),('150203','Pativilca'),('150204','Supe'),('150205','Supe Puerto'),('150301','Cajatambo'),('150302','Copa'),('150303','Gorgor'),('150304','Huancapon'),('150305','Manas'),('150401','Canta'),('150402','Arahuay'),('150403','Huamantanga'),('150404','Huaros'),('150405','Lachaqui'),('150406','San Buenaventura'),('150407','Santa Rosa de Quives'),('150501','San Vicente de Cañete'),('150502','Asia'),('150503','Calango'),('150504','Cerro Azul'),('150505','Chilca'),('150506','Coayllo'),('150507','Imperial'),('150508','Lunahuana'),('150509','Mala'),('150510','Nuevo Imperial'),('150511','Pacaran'),('150512','Quilmana'),('150513','San Antonio'),('150514','San Luis'),('150515','Santa Cruz de Flores'),('150516','Zuñiga'),('150601','Huaral'),('150602','Atavillos Alto'),('150603','Atavillos Bajo'),('150604','Aucallama'),('150605','Chancay'),('150606','Ihuari'),('150607','Lampian'),('150608','Pacaraos'),('150609','San Miguel de Acos'),('150610','Santa Cruz de Andamarca'),('150611','Sumbilca'),('150612','Veintisiete de Noviembre'),('150701','Matucana'),('150702','Antioquia'),('150703','Callahuanca'),('150704','Carampoma'),('150705','Chicla'),('150706','Cuenca'),('150707','Huachupampa'),('150708','Huanza'),('150709','Huarochiri'),('150710','Lahuaytambo'),('150711','Langa'),('150712','Laraos'),('150713','Mariatana'),('150714','Ricardo Palma'),('150715','San Andres de Tupicocha'),('150716','San Antonio'),('150717','San Bartolome'),('150718','San Damian'),('150719','San Juan de Iris'),('150720','San Juan de Tantaranche'),('150721','San Lorenzo de Quinti'),('150722','San Mateo'),('150723','San Mateo de Otao'),('150724','San Pedro de Casta'),('150725','San Pedro de Huancayre'),('150726','Sangallaya'),('150727','Santa Cruz de Cocachacra'),('150728','Santa Eulalia'),('150729','Santiago de Anchucaya'),('150730','Santiago de Tuna'),('150731','Santo Domingo de los Olleros'),('150732','Surco'),('150801','Huacho'),('150802','Ambar'),('150803','Caleta de Carquin'),('150804','Checras'),('150805','Hualmay'),('150806','Huaura'),('150807','Leoncio Prado'),('150808','Paccho'),('150809','Santa Leonor'),('150810','Santa Maria'),('150811','Sayan'),('150812','Vegueta'),('150901','Oyon'),('150902','Andajes'),('150903','Caujul'),('150904','Cochamarca'),('150905','Navan'),('150906','Pachangara'),('151001','Yauyos'),('151002','Alis'),('151003','Ayauca'),('151004','Ayaviri'),('151005','Azangaro'),('151006','Cacra'),('151007','Carania'),('151008','Catahuasi'),('151009','Chocos'),('151010','Cochas'),('151011','Colonia'),('151012','Hongos'),('151013','Huampara'),('151014','Huancaya'),('151015','Huangascar'),('151016','Huantan'),('151017','Huañec'),('151018','Laraos'),('151019','Lincha'),('151020','Madean'),('151021','Miraflores'),('151022','Omas'),('151023','Putinza'),('151024','Quinches'),('151025','Quinocay'),('151026','San Joaquin'),('151027','San Pedro de Pilas'),('151028','Tanta'),('151029','Tauripampa'),('151030','Tomas'),('151031','Tupe'),('151032','Viñac'),('151033','Vitis'),('160101','Iquitos'),('160102','Alto Nanay'),('160103','Fernando Lores'),('160104','Indiana'),('160105','Las Amazonas'),('160106','Mazan'),('160107','Napo'),('160108','Punchana'),('160110','Torres Causana'),('160112','Belen'),('160113','San Juan Bautista'),('160201','Yurimaguas'),('160202','Balsapuerto'),('160205','Jeberos'),('160206','Lagunas'),('160210','Santa Cruz'),('160211','Teniente Cesar Lopez Rojas'),('160301','Nauta'),('160302','Parinari'),('160303','Tigre'),('160304','Trompeteros'),('160305','Urarinas'),('160401','Ramon Castilla'),('160402','Pebas'),('160403','Yavari'),('160404','San Pablo'),('160501','Requena'),('160502','Alto Tapiche'),('160503','Capelo'),('160504','Emilio San Martin'),('160505','Maquia'),('160506','Puinahua'),('160507','Saquena'),('160508','Soplin'),('160509','Tapiche'),('160510','Jenaro Herrera'),('160511','Yaquerana'),('160601','Contamana'),('160602','Inahuaya'),('160603','Padre Marquez'),('160604','Pampa Hermosa'),('160605','Sarayacu'),('160606','Vargas Guerra'),('160701','Barranca'),('160702','Cahuapanas'),('160703','Manseriche'),('160704','Morona'),('160705','Pastaza'),('160706','Andoas'),('160801','Putumayo'),('160802','Rosa Panduro'),('160803','Teniente Manuel Clavero'),('160804','Yaguas'),('170101','Tambopata'),('170102','Inambari'),('170103','Las Piedras'),('170104','Laberinto'),('170201','Manu'),('170202','Fitzcarrald'),('170203','Madre de Dios'),('170204','Huepetuhe'),('170301','Iñapari'),('170302','Iberia'),('170303','Tahuamanu'),('180101','Moquegua'),('180102','Carumas'),('180103','Cuchumbaya'),('180104','Samegua'),('180105','San Cristobal'),('180106','Torata'),('180201','Omate'),('180202','Chojata'),('180203','Coalaque'),('180204','Ichuña'),('180205','La Capilla'),('180206','Lloque'),('180207','Matalaque'),('180208','Puquina'),('180209','Quinistaquillas'),('180210','Ubinas'),('180211','Yunga'),('180301','Ilo'),('180302','El Algarrobal'),('180303','Pacocha'),('190101','Chaupimarca'),('190102','Huachon'),('190103','Huariaca'),('190104','Huayllay'),('190105','Ninacaca'),('190106','Pallanchacra'),('190107','Paucartambo'),('190108','San Francisco de Asis de Yarusyacan'),('190109','Simon Bolivar'),('190110','Ticlacayan'),('190111','Tinyahuarco'),('190112','Vicco'),('190113','Yanacancha'),('190201','Yanahuanca'),('190202','Chacayan'),('190203','Goyllarisquizga'),('190204','Paucar'),('190205','San Pedro de Pillao'),('190206','Santa Ana de Tusi'),('190207','Tapuc'),('190208','Vilcabamba'),('190301','Oxapampa'),('190302','Chontabamba'),('190303','Huancabamba'),('190304','Palcazu'),('190305','Pozuzo'),('190306','Puerto Bermudez'),('190307','Villa Rica'),('190308','Constitución'),('200101','Piura'),('200104','Castilla'),('200105','Catacaos'),('200107','Cura Mori'),('200108','El Tallan'),('200109','La Arena'),('200110','La Union'),('200111','Las Lomas'),('200114','Tambo Grande'),('200115','26 de octubre'),('200201','Ayabaca'),('200202','Frias'),('200203','Jilili'),('200204','Lagunas'),('200205','Montero'),('200206','Pacaipampa'),('200207','Paimas'),('200208','Sapillica'),('200209','Sicchez'),('200210','Suyo'),('200301','Huancabamba'),('200302','Canchaque'),('200303','El Carmen de La Frontera'),('200304','Huarmaca'),('200305','Lalaquiz'),('200306','San Miguel de El Faique'),('200307','Sondor'),('200308','Sondorillo'),('200401','Chulucanas'),('200402','Buenos Aires'),('200403','Chalaco'),('200404','La Matanza'),('200405','Morropon'),('200406','Salitral'),('200407','San Juan de Bigote'),('200408','Santa Catalina de Mossa'),('200409','Santo Domingo'),('200410','Yamango'),('200501','Paita'),('200502','Amotape'),('200503','Arenal'),('200504','Colan'),('200505','La Huaca'),('200506','Tamarindo'),('200507','Vichayal'),('200601','Sullana'),('200602','Bellavista'),('200603','Ignacio Escudero'),('200604','Lancones'),('200605','Marcavelica'),('200606','Miguel Checa'),('200607','Querecotillo'),('200608','Salitral'),('200701','Pariñas'),('200702','El Alto'),('200703','La Brea'),('200704','Lobitos'),('200705','Los Organos'),('200706','Mancora'),('200801','Sechura'),('200802','Bellavista de La Union'),('200803','Bernal'),('200804','Cristo Nos Valga'),('200805','Vice'),('200806','Rinconada Llicuar'),('210101','Puno'),('210102','Acora'),('210103','Amantani'),('210104','Atuncolla'),('210105','Capachica'),('210106','Chucuito'),('210107','Coata'),('210108','Huata'),('210109','Mañazo'),('210110','Paucarcolla'),('210111','Pichacani'),('210112','Plateria'),('210113','San Antonio'),('210114','Tiquillaca'),('210115','Vilque'),('210201','Azangaro'),('210202','Achaya'),('210203','Arapa'),('210204','Asillo'),('210205','Caminaca'),('210206','Chupa'),('210207','Jose Domingo Choquehuanca'),('210208','Muñani'),('210209','Potoni'),('210210','Saman'),('210211','San Anton'),('210212','San Jose'),('210213','San Juan de Salinas'),('210214','Santiago de Pupuja'),('210215','Tirapata'),('210301','Macusani'),('210302','Ajoyani'),('210303','Ayapata'),('210304','Coasa'),('210305','Corani'),('210306','Crucero'),('210307','Ituata'),('210308','Ollachea'),('210309','San Gaban'),('210310','Usicayos'),('210401','Juli'),('210402','Desaguadero'),('210403','Huacullani'),('210404','Kelluyo'),('210405','Pisacoma'),('210406','Pomata'),('210407','Zepita'),('210501','Ilave'),('210502','Capazo'),('210503','Pilcuyo'),('210504','Santa Rosa'),('210505','Conduriri'),('210601','Huancane'),('210602','Cojata'),('210603','Huatasani'),('210604','Inchupalla'),('210605','Pusi'),('210606','Rosaspata'),('210607','Taraco'),('210608','Vilque Chico'),('210701','Lampa'),('210702','Cabanilla'),('210703','Calapuja'),('210704','Nicasio'),('210705','Ocuviri'),('210706','Palca'),('210707','Paratia'),('210708','Pucara'),('210709','Santa Lucia'),('210710','Vilavila'),('210801','Ayaviri'),('210802','Antauta'),('210803','Cupi'),('210804','Llalli'),('210805','Macari'),('210806','Nuñoa'),('210807','Orurillo'),('210808','Santa Rosa'),('210809','Umachiri'),('210901','Moho'),('210902','Conima'),('210903','Huayrapata'),('210904','Tilali'),('211001','Putina'),('211002','Ananea'),('211003','Pedro Vilca Apaza'),('211004','Quilcapuncu'),('211005','Sina'),('211101','Juliaca'),('211102','Cabana'),('211103','Cabanillas'),('211104','Caracoto'),('211105','San Miguel'),('211201','Sandia'),('211202','Cuyocuyo'),('211203','Limbani'),('211204','Patambuco'),('211205','Phara'),('211206','Quiaca'),('211207','San Juan del Oro'),('211208','Yanahuaya'),('211209','Alto Inambari'),('211210','San Pedro de Putina Punco'),('211301','Yunguyo'),('211302','Anapia'),('211303','Copani'),('211304','Cuturapi'),('211305','Ollaraya'),('211306','Tinicachi'),('211307','Unicachi'),('220101','Moyobamba'),('220102','Calzada'),('220103','Habana'),('220104','Jepelacio'),('220105','Soritor'),('220106','Yantalo'),('220201','Bellavista'),('220202','Alto Biavo'),('220203','Bajo Biavo'),('220204','Huallaga'),('220205','San Pablo'),('220206','San Rafael'),('220301','San Jose de Sisa'),('220302','Agua Blanca'),('220303','San Martin'),('220304','Santa Rosa'),('220305','Shatoja'),('220401','Saposoa'),('220402','Alto Saposoa'),('220403','El Eslabon'),('220404','Piscoyacu'),('220405','Sacanche'),('220406','Tingo de Saposoa'),('220501','Lamas'),('220502','Alonso de Alvarado'),('220503','Barranquita'),('220504','Caynarachi'),('220505','Cuñumbuqui'),('220506','Pinto Recodo'),('220507','Rumisapa'),('220508','San Roque de Cumbaza'),('220509','Shanao'),('220510','Tabalosos'),('220511','Zapatero'),('220601','Juanjui'),('220602','Campanilla'),('220603','Huicungo'),('220604','Pachiza'),('220605','Pajarillo'),('220701','Picota'),('220702','Buenos Aires'),('220703','Caspisapa'),('220704','Pilluana'),('220705','Pucacaca'),('220706','San Cristobal'),('220707','San Hilarion'),('220708','Shamboyacu'),('220709','Tingo de Ponasa'),('220710','Tres Unidos'),('220801','Rioja'),('220802','Awajun'),('220803','Elias Soplin Vargas'),('220804','Nueva Cajamarca'),('220805','Pardo Miguel'),('220806','Posic'),('220807','San Fernando'),('220808','Yorongos'),('220809','Yuracyacu'),('220901','Tarapoto'),('220902','Alberto Leveau'),('220903','Cacatachi'),('220904','Chazuta'),('220905','Chipurana'),('220906','El Porvenir'),('220907','Huimbayoc'),('220908','Juan Guerra'),('220909','La Banda de Shilcayo'),('220910','Morales'),('220911','Papaplaya'),('220912','San Antonio'),('220913','Sauce'),('220914','Shapaja'),('221001','Tocache'),('221002','Nuevo Progreso'),('221003','Polvora'),('221004','Shunte'),('221005','Uchiza'),('230101','Tacna'),('230102','Alto de La Alianza'),('230103','Calana'),('230104','Ciudad Nueva'),('230105','Inclan'),('230106','Pachia'),('230107','Palca'),('230108','Pocollay'),('230109','Sama'),('230110','Coronel Gregorio Albarracin Lanchipa'),('230111','La Yarada-Los Palos'),('230201','Candarave'),('230202','Cairani'),('230203','Camilaca'),('230204','Curibaya'),('230205','Huanuara'),('230206','Quilahuani'),('230301','Locumba'),('230302','Ilabaya'),('230303','Ite'),('230401','Tarata'),('230402','Heroes Albarracin'),('230403','Estique'),('230404','Estique-Pampa'),('230405','Sitajara'),('230406','Susapaya'),('230407','Tarucachi'),('230408','Ticaco'),('240101','Tumbes'),('240102','Corrales'),('240103','La Cruz'),('240104','Pampas de Hospital'),('240105','San Jacinto'),('240106','San Juan de La Virgen'),('240201','Zorritos'),('240202','Casitas'),('240203','Canoas de Punta Sal'),('240301','Zarumilla'),('240302','Aguas Verdes'),('240303','Matapalo'),('240304','Papayal'),('250101','Calleria'),('250102','Campoverde'),('250103','Iparia'),('250104','Masisea'),('250105','Yarinacocha'),('250106','Nueva Requena'),('250107','Manantay'),('250201','Raymondi'),('250202','Sepahua'),('250203','Tahuania'),('250204','Yurua'),('250301','Padre Abad'),('250302','Irazola'),('250303','Curimana'),('250304','Neshuya'),('250305','Alexander von Humboldt'),('250401','Purus');

UNLOCK TABLES;

/*Table structure for table `ubigeo_provincias` */

CREATE TABLE `ubigeo_provincias` (
  `id` char(4) NOT NULL,
  `provincia` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `ubigeo_provincias` */

LOCK TABLES `ubigeo_provincias` WRITE;

insert  into `ubigeo_provincias`(`id`,`provincia`) values ('0101','Chachapoyas'),('0102','Bagua'),('0103','Bongara'),('0104','Condorcanqui'),('0105','Luya'),('0106','Rodriguez de Mendoza'),('0107','Utcubamba'),('0201','Huaraz'),('0202','Aija'),('0203','Antonio Raymondi'),('0204','Asuncion'),('0205','Bolognesi'),('0206','Carhuaz'),('0207','Carlos Fermin Fitzca'),('0208','Casma'),('0209','Corongo'),('0210','Huari'),('0211','Huarmey'),('0212','Huaylas'),('0213','Mariscal Luzuriaga'),('0214','Ocros'),('0215','Pallasca'),('0216','Pomabamba'),('0217','Recuay'),('0218','Santa'),('0219','Sihuas'),('0220','Yungay'),('0301','Abancay'),('0302','Andahuaylas'),('0303','Antabamba'),('0304','Aymaraes'),('0305','Cotabambas'),('0306','Chincheros'),('0307','Grau'),('0401','Arequipa'),('0402','Camana'),('0403','Caraveli'),('0404','Castilla'),('0405','Caylloma'),('0406','Condesuyos'),('0407','Islay'),('0408','La Union'),('0501','Huamanga'),('0502','Cangallo'),('0503','Huanca Sancos'),('0504','Huanta'),('0505','La Mar'),('0506','Lucanas'),('0507','Parinacochas'),('0508','Paucar del Sara Sara'),('0509','Sucre'),('0510','Victor Fajardo'),('0511','Vilcas Huaman'),('0601','Cajamarca'),('0602','Cajabamba'),('0603','Celendin'),('0604','Chota'),('0605','Contumaza'),('0606','Cutervo'),('0607','Hualgayoc'),('0608','Jaen'),('0609','San Ignacio'),('0610','San Marcos'),('0611','San Miguel'),('0612','San Pablo'),('0613','Santa Cruz'),('0701','Callao'),('0801','Cusco'),('0802','Acomayo'),('0803','Anta'),('0804','Calca'),('0805','Canas'),('0806','Canchis'),('0807','Chumbivilcas'),('0808','Espinar'),('0809','La Convencion'),('0810','Paruro'),('0811','Paucartambo'),('0812','Quispicanchi'),('0813','Urubamba'),('0901','Huancavelica'),('0902','Acobamba'),('0903','Angaraes'),('0904','Castrovirreyna'),('0905','Churcampa'),('0906','Huaytara'),('0907','Tayacaja'),('1001','Huanuco'),('1002','Ambo'),('1003','Dos de Mayo'),('1004','Huacaybamba'),('1005','Huamalies'),('1006','Leoncio Prado'),('1007','Marañon'),('1008','Pachitea'),('1009','Puerto Inca'),('1010','Lauricocha'),('1011','Yarowilca'),('1101','Ica'),('1102','Chincha'),('1103','Nazca'),('1104','Palpa'),('1105','Pisco'),('1201','Huancayo'),('1202','Concepcion'),('1203','Chanchamayo'),('1204','Jauja'),('1205','Junin'),('1206','Satipo'),('1207','Tarma'),('1208','Yauli'),('1209','Chupaca'),('1301','Trujillo'),('1302','Ascope'),('1303','Bolivar'),('1304','Chepen'),('1305','Julcan'),('1306','Otuzco'),('1307','Pacasmayo'),('1308','Pataz'),('1309','Sanchez Carrion'),('1310','Santiago de Chuco'),('1311','Gran Chimu'),('1312','Viru'),('1401','Chiclayo'),('1402','Ferreñafe'),('1403','Lambayeque'),('1501','Lima'),('1502','Barranca'),('1503','Cajatambo'),('1504','Canta'),('1505','Cañete'),('1506','Huaral'),('1507','Huarochiri'),('1508','Huaura'),('1509','Oyon'),('1510','Yauyos'),('1601','Maynas'),('1602','Alto Amazonas'),('1603','Loreto'),('1604','Mariscal Ramon Castilla'),('1605','Requena'),('1606','Ucayali'),('1607','Datem del Marañon'),('1608','Putumayo'),('1701','Tambopata'),('1702','Manu'),('1703','Tahuamanu'),('1801','Mariscal Nieto'),('1802','General Sanchez Cerr'),('1803','Ilo'),('1901','Pasco'),('1902','Daniel Alcides Carri'),('1903','Oxapampa'),('2001','Piura'),('2002','Ayabaca'),('2003','Huancabamba'),('2004','Morropon'),('2005','Paita'),('2006','Sullana'),('2007','Talara'),('2008','Sechura'),('2101','Puno'),('2102','Azangaro'),('2103','Carabaya'),('2104','Chucuito'),('2105','El Collao'),('2106','Huancane'),('2107','Lampa'),('2108','Melgar'),('2109','Moho'),('2110','San Antonio de Putin'),('2111','San Roman'),('2112','Sandia'),('2113','Yunguyo'),('2201','Moyobamba'),('2202','Bellavista'),('2203','El Dorado'),('2204','Huallaga'),('2205','Lamas'),('2206','Mariscal Caceres'),('2207','Picota'),('2208','Rioja'),('2209','San Martin'),('2210','Tocache'),('2301','Tacna'),('2302','Candarave'),('2303','Jorge Basadre'),('2304','Tarata'),('2401','Tumbes'),('2402','Contralmirante Villa'),('2403','Zarumilla'),('2501','Coronel Portillo'),('2502','Atalaya'),('2503','Padre Abad'),('2504','Purus');

UNLOCK TABLES;

/*Table structure for table `unidades` */

CREATE TABLE `unidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(100) COLLATE utf32_unicode_ci NOT NULL,
  `unidad` varchar(100) COLLATE utf32_unicode_ci DEFAULT NULL,
  `activo` tinyint(11) NOT NULL COMMENT 'para mostrar al vender solo las activas',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_ci;

/*Data for the table `unidades` */

LOCK TABLES `unidades` WRITE;

insert  into `unidades`(`id`,`codigo`,`unidad`,`activo`) values (1,'4A','BOBINAS',0),(2,'BJ','BALDE',0),(3,'BLL','BARRILES',0),(4,'BG','BOLSA',0),(5,'BO','BOTELLAS',0),(6,'BX','CAJA',1),(7,'CT','CARTONES',0),(8,'CMK','CENTIMETRO CUADRADO',0),(9,'CMQ','CENTIMETRO CUBICO',0),(10,'CMT','CENTIMETRO LINEAL',0),(11,'CEN','CIENTO DE UNIDADES',0),(12,'CY','CILINDRO',1),(13,'CJ','CONOS',0),(14,'DZN','DOCENA',0),(15,'DZP','DOCENA POR 10**6',0),(16,'BE','FARDO',0),(17,'GLI','GALON INGLES (4,545956L)',0),(18,'GRM','GRAMO',0),(19,'GRO','GRUESA',0),(20,'HLT','HECTOLITRO',0),(21,'LEF','HOJA',0),(22,'SET','JUEGO',0),(23,'KGM','KILOGRAMO',1),(24,'KTM','KILOMETRO',0),(25,'KWH','KILOVATIO HORA',0),(26,'KT','KIT',0),(27,'CA','LATAS',0),(28,'LBR','LIBRAS',0),(29,'LTR','LITRO',1),(30,'MWH','MEGAWATT HORA',0),(31,'MTR','METRO',1),(32,'MTK','METRO CUADRADO',0),(33,'MTQ','METRO CUBICO',0),(34,'MGM','MILIGRAMOS',0),(35,'MLT','MILILITRO',0),(36,'MMT','MILIMETRO',0),(37,'MMK','MILIMETRO CUADRADO',0),(38,'MMQ','MILIMETRO CUBICO',0),(39,'MLL','MILLARES',0),(40,'UM','MILLON DE UNIDADES',0),(41,'ONZ','ONZAS',0),(42,'PF','PALETAS',0),(43,'PK','PAQUETE',0),(44,'PR','PAR',0),(45,'FOT','PIES',0),(46,'FTK','PIES CUADRADOS',0),(47,'FTQ','PIES CUBICOS',0),(48,'C62','PIEZAS',0),(49,'PG','PLACAS',0),(50,'ST','PLIEGO',0),(51,'INH','PULGADAS',0),(52,'RM','RESMA',0),(53,'DR','TAMBOR',0),(54,'STN','TONELADA CORTA',0),(55,'LTN','TONELADA LARGA',0),(56,'TNE','TONELADAS',0),(57,'TU','TUBOS',0),(58,'NIU','UNIDAD (BIENES)',1),(59,'ZZ','UNIDAD (SERVICIOS)',1),(60,'GLL','US GALON (3,7843 L)',0),(61,'YRD','YARDA',0),(62,'YDK','YARDA CUADRADA',0),(63,'VA','VARIOS',0);

UNLOCK TABLES;

/*Table structure for table `variables_diversas` */

CREATE TABLE `variables_diversas` (
  `id` int(11) NOT NULL,
  `precio_con_igv` tinyint(4) DEFAULT '0' COMMENT 'precio con o sin IGV, al momento de generar la venta',
  `tipo_igv_defecto` int(1) DEFAULT NULL COMMENT 'el tipo de igv q sale por defecto al vender, se usa para la selva',
  `productos_automaticos` tinyint(4) DEFAULT '0' COMMENT '1 puede crear productos automaticamente(al momento de vender), sin necesidad de crearlo en almacen',
  `ultima_actualizacion_kardex` datetime DEFAULT NULL COMMENT 'fecha de actualizacion para ver kardex',
  `detracciones` tinyint(4) DEFAULT '0' COMMENT '0 sin detraccion - 1 con detraccion',
  `retenciones` tinyint(4) DEFAULT '0' COMMENT '0 sin retencion - 1 con retencion',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `variables_diversas` */

LOCK TABLES `variables_diversas` WRITE;

insert  into `variables_diversas`(`id`,`precio_con_igv`,`tipo_igv_defecto`,`productos_automaticos`,`ultima_actualizacion_kardex`,`detracciones`,`retenciones`) values (1,0,1,0,'2022-08-10 16:16:29',0,0);

UNLOCK TABLES;

/*Table structure for table `venta_anticipos` */

CREATE TABLE `venta_anticipos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `venta_id` int(11) NOT NULL,
  `anticipo_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `venta_anticipos` */

LOCK TABLES `venta_anticipos` WRITE;

UNLOCK TABLES;

/*Table structure for table `venta_detalles` */

CREATE TABLE `venta_detalles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `venta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `producto` text NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precio_base` decimal(11,6) NOT NULL COMMENT 'precio del producto/servicio sin igv',
  `tipo_igv_id` int(10) NOT NULL,
  `descuento` decimal(11,2) DEFAULT NULL,
  `impuesto_bolsa` decimal(6,2) DEFAULT NULL COMMENT 'si el item lleva bolsa, tons... precio unitario de la bolsa (del anio)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `venta_detalles` */

LOCK TABLES `venta_detalles` WRITE;

UNLOCK TABLES;

/*Table structure for table `venta_guias` */

CREATE TABLE `venta_guias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `venta_id` int(11) NOT NULL,
  `guia_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `venta_guias` */

LOCK TABLES `venta_guias` WRITE;

insert  into `venta_guias`(`id`,`venta_id`,`guia_id`) values (1,5,6),(2,6,7);

UNLOCK TABLES;

/*Table structure for table `ventas` */

CREATE TABLE `ventas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entidad_id` int(11) NOT NULL,
  `direccion_cliente` text,
  `tipo_documento_id` int(4) DEFAULT NULL,
  `tipo_ncredito_id` int(11) DEFAULT NULL,
  `tipo_ndebito_id` int(11) DEFAULT NULL,
  `venta_relacionado_id` int(11) DEFAULT NULL COMMENT 'factura o boleta relacionad a Nota de credito o debito',
  `operacion` int(11) NOT NULL COMMENT '1: factura, boleta o Notas, 2: Nota de Venta, 3: Cotizacion',
  `operacion_id` int(11) DEFAULT NULL COMMENT 'id de la factura o boleta relacionada (Este campo se usara cuando se llene la: nota de venta o cotizacion)',
  `serie` char(4) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `fecha_emision` date NOT NULL,
  `hora_emision` time DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `moneda_id` int(3) NOT NULL,
  `tipo_de_cambio` decimal(10,3) DEFAULT NULL,
  `total_gravada` float(10,2) DEFAULT NULL,
  `porcentaje_igv` decimal(5,2) DEFAULT NULL COMMENT 'porcentaje igv generalmente sera 0.18',
  `total_igv` float(10,2) DEFAULT NULL,
  `total_gratuita` decimal(10,2) DEFAULT NULL,
  `total_exportacion` decimal(10,2) DEFAULT NULL,
  `total_exonerada` decimal(10,2) DEFAULT NULL,
  `total_inafecta` decimal(10,2) DEFAULT NULL,
  `bolsa_monto_unitario` decimal(5,2) DEFAULT NULL COMMENT 'monto unitario bolsa para el 2020 sera 0.20 centimos',
  `total_bolsa` decimal(10,2) DEFAULT NULL,
  `total_otros_cargos` decimal(10,2) DEFAULT NULL,
  `total_descuentos` decimal(10,2) DEFAULT NULL,
  `PrepaidAmount` decimal(10,2) DEFAULT NULL COMMENT 'total pago anticipos',
  `total_a_pagar` decimal(10,2) DEFAULT NULL,
  `estado_operacion` tinyint(4) DEFAULT '0' COMMENT '0 creado, 1 aceptado, 2 rechazado',
  `estado_anulacion` int(4) DEFAULT NULL COMMENT 'null -> anulacion no enviada, 0 anulación enviada (con recepción de ticket), 1 anulacion aceptada, 2 anulacion rechazada',
  `respuesta_sunat_codigo` varchar(5) DEFAULT NULL,
  `respuesta_sunat_descripcion` text,
  `respuesta_anulacion_codigo` tinyint(4) DEFAULT NULL,
  `respuesta_anulacion_descripcion` text,
  `orden_compra` varchar(50) DEFAULT NULL,
  `notas` text,
  `tipo_operacion` char(10) DEFAULT NULL COMMENT 'para ventas 0101, para exportacion...',
  `forma_pago_id` smallint(5) unsigned DEFAULT NULL,
  `modo_pago_id` smallint(5) unsigned DEFAULT NULL COMMENT 'solo serviría para forma de pago contado',
  `venta_pagada` smallint(6) DEFAULT '0' COMMENT 'para forma de pago al credito, sera 1 cuando se paguen todas las cuotas',
  `UBLVersionID` varchar(10) DEFAULT NULL,
  `CustomizationID` varchar(10) DEFAULT NULL,
  `firma_sunat` varchar(500) DEFAULT NULL,
  `salida_almacen` tinyint(4) DEFAULT NULL COMMENT 'Para notas o facturas(no cotizaciones). Una nota de venta puede tener salida de Almacén, en dicho caso cuando llegue a Factura o boleta este documento, No tedrá salida de Almacen',
  `numero_guia` varchar(200) DEFAULT NULL,
  `condicion_venta` varchar(200) DEFAULT NULL,
  `nota_venta` varchar(200) DEFAULT NULL,
  `numero_pedido` varchar(200) DEFAULT NULL,
  `empleado_insert` int(11) NOT NULL,
  `fecha_insert` datetime DEFAULT NULL,
  `empleado_update` int(11) DEFAULT NULL,
  `fecha_update` datetime DEFAULT NULL,
  `detraccion_codigo` varchar(5) DEFAULT NULL,
  `detraccion_porcentaje` int(5) DEFAULT NULL,
  `retencion_porcentaje` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comprobante_serie_numero` (`serie`,`numero`,`tipo_documento_id`,`operacion`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `ventas` */

LOCK TABLES `ventas` WRITE;

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
