DELIMITER $$

CREATE PROCEDURE `sp_kardex_promedio` ()  BEGIN
DECLARE p_producto_id INT;
DECLARE p_producto_id_anterior INT;
DECLARE p_fecha DATE;
DECLARE p_compra_venta SMALLINT(6);
DECLARE p_documento_id INT;
DECLARE p_tipo_documento_id INT;
DECLARE p_documento_relacionado_id INT;
DECLARE p_tipo_movimiento INT;
DECLARE p_serie VARCHAR(20);
DECLARE p_numero VARCHAR(12);
DECLARE p_costo_unitario DECIMAL(10,4);
DECLARE p_cantidad DECIMAL(10,2);
DECLARE anterior_cantidad DECIMAL(10,2) DEFAULT 0;
DECLARE anterior_costo DECIMAL(10,4) DEFAULT 0;
DECLARE anterior_final DECIMAL(10,4) DEFAULT 0;
DECLARE valor_nota_credito DECIMAL(10,4) DEFAULT 0;
DECLARE no_hay_mas_registros INT DEFAULT 0;
DECLARE elCursor CURSOR FOR
SELECT producto_id, fecha, compra_venta, documento_id, tipo_documento_id, documento_relacionado_id, tipo_movimiento, serie, numero, costo_unitario, cantidad FROM kardex_temporal ORDER BY producto_id, fecha, compra_venta, tipo_documento_id;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET no_hay_mas_registros = 1;
UPDATE productos SET `precio_costo` = 0 WHERE `precio_costo` IS NULL;
TRUNCATE TABLE kardex_promedio;
OPEN elCursor;
bucle: LOOP
 FETCH elCursor INTO p_producto_id, p_fecha, p_compra_venta, p_documento_id, p_tipo_documento_id, p_documento_relacionado_id, p_tipo_movimiento, p_serie, p_numero, p_costo_unitario, p_cantidad;
 IF (no_hay_mas_registros = 1) THEN
	LEAVE bucle;
 END IF;
 
 IF(p_producto_id_anterior != p_producto_id)THEN
	UPDATE productos SET stock_actual = anterior_cantidad WHERE id = p_producto_id_anterior; 
 
	SET anterior_costo = 0;
	SET anterior_cantidad = 0;
	SET anterior_final = 0;
 END IF;
 
 IF(p_compra_venta = 0) THEN
	INSERT kardex_promedio(producto_id, fecha, compra_venta, documento_id, tipo_documento_id, serie, numero, entrada_cantidad, entrada_costo,      final_cantidad,      final_costo, final_total) VALUES 
	(p_producto_id, p_fecha, p_compra_venta, p_documento_id, p_tipo_documento_id, p_serie, p_numero,         p_cantidad,    p_costo_unitario,          p_cantidad, p_costo_unitario, p_cantidad*p_costo_unitario);
	SET anterior_cantidad = p_cantidad;
	SET anterior_costo = p_costo_unitario;
	SET anterior_final = p_cantidad*p_costo_unitario;
 END IF;
 
IF(p_compra_venta = 1) THEN
	IF(p_tipo_documento_id = 7) THEN
		SELECT entrada_costo INTO valor_nota_credito FROM kardex_promedio WHERE producto_id = p_producto_id AND compra_venta = 1 AND documento_id = p_documento_relacionado_id;
		
		INSERT kardex_promedio(producto_id,   fecha,   compra_venta,   documento_id,   tipo_documento_id,  serie,    numero,     entrada_cantidad,       entrada_costo,                 final_cantidad,                                                                        final_costo,                                  final_total) VALUES 
		(                    p_producto_id, p_fecha, p_compra_venta, p_documento_id, p_tipo_documento_id, p_serie, p_numero,          -p_cantidad, -valor_nota_credito, anterior_cantidad - p_cantidad, ( anterior_final - p_cantidad*p_costo_unitario) / (anterior_cantidad - p_cantidad), anterior_final - p_cantidad*p_costo_unitario);
		SET anterior_costo = (anterior_final - p_cantidad*p_costo_unitario) / (anterior_cantidad - p_cantidad);	
		SET anterior_cantidad = anterior_cantidad - p_cantidad;	
		SET anterior_final = anterior_final - p_cantidad*p_costo_unitario;
	
	ELSE
		INSERT kardex_promedio(producto_id,   fecha,   compra_venta,   documento_id,   tipo_documento_id,  serie,    numero, entrada_cantidad,    entrada_costo,                final_cantidad,                                                                        final_costo,                                  final_total) VALUES 
		(                    p_producto_id, p_fecha, p_compra_venta, p_documento_id, p_tipo_documento_id, p_serie, p_numero,       p_cantidad, p_costo_unitario, p_cantidad + anterior_cantidad, (p_cantidad*p_costo_unitario + anterior_final) / (p_cantidad + anterior_cantidad), anterior_final + p_cantidad*p_costo_unitario);
		SET anterior_costo = (p_cantidad*p_costo_unitario + anterior_final) / (p_cantidad + anterior_cantidad);	
		SET anterior_cantidad = p_cantidad + anterior_cantidad;	
		SET anterior_final = p_cantidad*p_costo_unitario + anterior_final;
	END IF;
 END IF;
 
 IF(p_compra_venta = 2) THEN
	IF(p_tipo_documento_id = 7) THEN
		SELECT salida_costo INTO valor_nota_credito FROM kardex_promedio WHERE producto_id = p_producto_id AND compra_venta = 2 AND documento_id = p_documento_relacionado_id LIMIT 1;
		
		INSERT kardex_promedio(producto_id,   fecha,   compra_venta,   documento_id,   tipo_documento_id,   serie,  numero,                 salida_cantidad,       salida_costo,                 final_cantidad,                                                                      final_costo,                               final_total) VALUES 
		(                    p_producto_id, p_fecha, p_compra_venta, p_documento_id, p_tipo_documento_id, p_serie, p_numero,                    -p_cantidad, -valor_nota_credito, anterior_cantidad + p_cantidad, (anterior_final + p_cantidad*anterior_costo) / (anterior_cantidad + p_cantidad), anterior_final + p_cantidad*anterior_costo);
		
		SET anterior_costo = (anterior_final + p_cantidad*anterior_costo) / (anterior_cantidad + p_cantidad);	
		SET anterior_cantidad = anterior_cantidad + p_cantidad;	
		SET anterior_final = anterior_final + p_cantidad*anterior_costo;		
	ELSE
		INSERT kardex_promedio(producto_id,   fecha,   compra_venta,   documento_id,   tipo_documento_id,   serie,  numero,  salida_cantidad,  salida_costo,                 final_cantidad,                                                                      final_costo,                               final_total) VALUES 
		(                    p_producto_id, p_fecha, p_compra_venta, p_documento_id, p_tipo_documento_id, p_serie, p_numero,      p_cantidad, anterior_costo, anterior_cantidad - p_cantidad, (anterior_final - p_cantidad*anterior_costo) / (anterior_cantidad - p_cantidad), anterior_final - p_cantidad*anterior_costo);
		
		SET anterior_costo = (anterior_final - p_cantidad*anterior_costo) / (anterior_cantidad - p_cantidad);	
		SET anterior_cantidad = anterior_cantidad - p_cantidad;	
		SET anterior_final = anterior_final - p_cantidad*anterior_costo;
	END IF; 	
 END IF;
 
 IF(p_compra_venta = 3) THEN
	IF(p_tipo_movimiento = 1) THEN	
		INSERT kardex_promedio(producto_id,   fecha,   compra_venta,   documento_id, tipo_movimiento, entrada_cantidad,  entrada_costo,                 final_cantidad,                                                                     final_costo,                         final_total) VALUES 
		(                    p_producto_id, p_fecha, p_compra_venta, p_documento_id,               1,       p_cantidad, anterior_costo, p_cantidad + anterior_cantidad, (p_cantidad*anterior_costo + anterior_final) / (p_cantidad + anterior_cantidad), anterior_final + p_cantidad*anterior_costo);
		SET anterior_costo = (p_cantidad*anterior_costo + anterior_final) / (p_cantidad + anterior_cantidad);	
		SET anterior_cantidad = p_cantidad + anterior_cantidad;	
		SET anterior_final = p_cantidad*anterior_costo + anterior_final;
	END IF;
	
	IF(p_tipo_movimiento = 2) THEN	
		INSERT kardex_promedio(producto_id,   fecha,   compra_venta,   documento_id, tipo_movimiento, salida_cantidad,   salida_costo,                 final_cantidad,                                                                     final_costo,                         final_total) VALUES 
		(                    p_producto_id, p_fecha, p_compra_venta, p_documento_id,               2,      p_cantidad, anterior_costo, anterior_cantidad - p_cantidad, (anterior_final - p_cantidad*anterior_costo) / (anterior_cantidad - p_cantidad), anterior_final - p_cantidad*anterior_costo);
		SET anterior_costo = (anterior_final - p_cantidad*anterior_costo) / (anterior_cantidad - p_cantidad);	
		SET anterior_cantidad = anterior_cantidad - p_cantidad;	
		SET anterior_final = anterior_final - p_cantidad*anterior_costo;
	END IF;
 END IF;
 
 SET p_producto_id_anterior = p_producto_id;
 
END LOOP bucle;
UPDATE productos SET stock_actual = anterior_cantidad WHERE id = p_producto_id_anterior; 	  
	 
CLOSE elCursor;
END$$


CREATE PROCEDURE `sp_kardex_temporal` ()  BEGIN 
INSERT INTO `kardex_temporal` (producto_id, fecha,      compra_venta, costo_unitario, cantidad) 
SELECT                                `id`, DATE(fecha_insert),    0,   precio_costo, stock_inicial 
FROM productos;
INSERT INTO `kardex_temporal` (producto_id,               fecha, compra_venta, documento_id,     tipo_documento_id, documento_relacionado_id,       serie,       numero,    costo_unitario, cantidad) 
SELECT                   cde.`producto_id`, com.`fecha_emision`,            1,       com.id, com.tipo_documento_id, com.compra_relacionado_id, com.`serie`, com.`numero`, cde.`precio_base`, cde.`cantidad` 
FROM compras com JOIN compra_detalles cde ON cde.`compra_id` = com.`id` WHERE com.operacion = 1;
INSERT INTO `kardex_temporal` (producto_id,                fecha, compra_venta, documento_id,     tipo_documento_id, documento_relacionado_id,        serie,       numero, cantidad) 
SELECT                   vde.`producto_id`,  ven.`fecha_emision`,            2,       ven.id, ven.tipo_documento_id, ven.venta_relacionado_id,  ven.`serie`, ven.`numero`, vde.`cantidad` 
FROM ventas ven JOIN venta_detalles vde ON vde.`venta_id` = ven.`id` WHERE ven.operacion = 1 AND ((estado_anulacion IS NULL) || (estado_anulacion != 1));
INSERT INTO `kardex_temporal` (producto_id,                fecha, compra_venta, documento_id,  tipo_movimiento, cantidad) 
SELECT                         producto_id,   DATE(fecha_insert),            3,           id,        movimiento, cantidad
FROM `producto_movimientos`;
END$$


DELIMITER ;