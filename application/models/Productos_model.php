<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Productos_model extends CI_Model{

    public $tabla = 'productos';

    public function __construct() {
        parent::__construct();            
    }

    public function select($modo, $select = array(), $condicion = array(), $order = '') {

        if ($select == '')
            $select = array();
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            if ($value == 'IS NULL') {
                $where .= " AND $key " . $value;
            } else {
                $where .= " AND $key = '" . $value . "' ";
            }
        }

        $campos = ($select == array()) ? '*' : implode(", ", $select);
        $sql = "SELECT " . $campos . " FROM $this->tabla WHERE 1 = 1 " . $where . " " . $order;
        $query = $this->db->query($sql);

        switch ($modo) {
            case '1':
                $resultado = '';
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                    $resultado = $row[$campos];
                }
                return $resultado;

            case '2':
                $row = array();
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                }
                return $row;

            case '3':
                $rows = array();
                foreach ($query->result_array() as $row) {
                    $rows[] = $row;
                }
                return $rows;
        }
    }
    
    public function select2($modo, $select = array(), $condicion = array(), $order = '') {

        if ($select == '')
            $select = array();
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            $where .= " AND $key $value ";
        }

        $campos = ($select == array()) ? '*' : implode(", ", $select);
        $sql = "SELECT " . $campos . " FROM $this->tabla WHERE 1 = 1 " . $where . " " . $order;
        $query = $this->db->query($sql);

        switch ($modo) {
            case '1':
                $resultado = '';
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                    $resultado = $row[$campos];
                }
                return $resultado;

            case '2':
                $row = array();
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                }
                return $row;

            case '3':
                $rows = array();
                foreach ($query->result_array() as $row) {
                    $rows[] = $row;
                }
                return $rows;
        }
    }        

    public function insertar($data, $mensaje = '') {
        $this->db->insert($this->tabla, $data);
    }           

    public function modificar($id, $data) {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);
    }
    
    public function select_productos($pagina, $filas_por_pagina, $condicion = array(), $order = '') {
        if ($condicion == '')
        $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {            
            $where .= " AND $key $value ";
        }
        
        $inicio = ($pagina - 1)*$filas_por_pagina;
        $sql = "SELECT pro.`id` producto_id, pro.precio_costo, pro.imagen, pro.`codigo_sunat`, pro.`codigo`, pro.`producto`, pro.`precio_base_venta`, pro.`comision_venta`, pro.stock_actual, pro.`stock_inicial`, cat.`categoria`, und.`unidad` 
        FROM productos pro
        JOIN categorias cat ON pro.`categoria_id` = cat.`id`
        JOIN unidades und ON und.`id` = pro.`unidad_id`
        WHERE pro.`fecha_delete` IS NULL AND cat.`eliminado` = 0 " . $where . " " . $order . "
        LIMIT $inicio, $filas_por_pagina";
        //echo $sql;
        $query = $this->db->query($sql);
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    public function select_productos_all($condicion = array(), $order = '') {
        if ($condicion == '')
        $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {            
            $where .= " AND $key $value ";
        }
        
        $inicio = ($pagina - 1)*$filas_por_pagina;
        $sql = "SELECT pro.`id` producto_id, pro.precio_costo, pro.imagen, pro.`codigo_sunat`, pro.`codigo`, pro.`producto`, pro.`precio_base_venta`, pro.`comision_venta`, pro.stock_actual, pro.`stock_inicial`, cat.`categoria`, und.`unidad` 
        FROM productos pro
        JOIN categorias cat ON pro.`categoria_id` = cat.`id`
        JOIN unidades und ON und.`id` = pro.`unidad_id`
        WHERE pro.`fecha_delete` IS NULL AND cat.`eliminado` = 0 " . $where . " " . $order;
        //echo $sql;
        $query = $this->db->query($sql);
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    public function select_buscador_completo($buscar) {
        $sql = "SELECT pro.`id` producto_id, pro.producto, pro.codigo, CONCAT(producto,' - ', pro.codigo, ' Stock:',pro.stock_actual) value, stock_inicial, stock_actual, precio_base_venta precio, precio_costo, pro.imagen imagen, categoria, unidad, und.id unidad_id FROM $this->tabla pro
        JOIN categorias cat ON cat.`id` = pro.`categoria_id`
        JOIN unidades und ON und.`id` = pro.`unidad_id` WHERE (producto LIKE '%$buscar%' OR pro.codigo LIKE '%$buscar%') AND `fecha_delete` IS NULL  ORDER BY producto";                
        //echo $sql;
        $query = $this->db->query($sql);

        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }    

    public function total_filas($condicion) {
        if ($condicion == '')
        $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {            
            $where .= " AND $key $value ";
        }        
        $sql = "select count(pro.id) total_filas from $this->tabla pro
        JOIN categorias cat ON pro.`categoria_id` = cat.`id`
        JOIN unidades und ON und.`id` = pro.`unidad_id`
        WHERE pro.`fecha_delete` IS NULL AND cat.`eliminado` = 0 " . $where;
        $query = $this->db->query($sql);

        $resultado = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['total_filas'];
        }
        return $resultado;
    }    

    public function ws_select($pagina, $filas_por_pagina, $condicion = array(), $order = ''){
        $data = $this->select_productos($pagina, $filas_por_pagina, $condicion, $order);
        $total_filas = $this->total_filas($condicion);

        $datos = array();
        foreach ($data as $value){
            $datos[] = array(
                'producto_id' => (int)$value['producto_id'],
                'codigo_sunat'=>$value['codigo_sunat'],
                'codigo'=>$value['codigo'],
                'producto'=>$value['producto'],
                'precio_base_venta'=>$value['precio_base_venta'],
                'comision_venta'=>$value['comision_venta'],
                'stock_inicial'=>$value['stock_inicial'],
                'stock_actual'=>$value['stock_actual'],
                'precio_costo'=>$value['precio_costo'],
                'categoria'=>$value['categoria'],
                'unidad'=>$value['unidad'],
                'imagen'=>$value['imagen']
            );
        }

        $salida = array(
            'ws_select_productos' => $datos,
            'total_filas' => $total_filas
        );
        return $salida;
    }
    
    public function select_buscador($buscar) {
        $sql = "SELECT id producto_id, codigo, producto FROM $this->tabla WHERE (producto LIKE '%$buscar%' OR codigo LIKE '%$buscar%') AND `fecha_delete` IS NULL  ORDER BY producto";
        //echo $sql;
        $query = $this->db->query($sql);

        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }    
    
    public function ws_buscador($buscar){
        $data = $this->select_buscador($buscar);

        $datos = array();
        foreach ($data as $value){
            $datos[] = array(
                'id' => (int)$value['producto_id'],
                'value'=>$value['codigo'].' - '.$value['producto']
            );
        }        
        return $datos;
    }    
    
    public function ws_item($producto_id){
        $data = $this->select(2, '', array('id' => $producto_id));
        $datos[] = array(
            'codigo_sunat'      =>$data['codigo_sunat'],
            'codigo'            =>$data['codigo'],
            'producto'          =>$data['producto'],
            'descripcion'       =>$data['descripcion'],
            'precio_base_venta' =>$data['precio_base_venta'],
            'comision_venta'    =>$data['comision_venta'],
            'stock_inicial'     =>$data['stock_inicial'],
            'precio_costo'      =>$data['precio_costo'],
            'categoria_id'      =>$data['categoria_id'],
            'unidad_id'         =>$data['unidad_id']
        );      
        return $datos;        
    }
    
    public function select_item($producto_id) {
        $sql = "select pro.id producto_id, pro.descripcion descripcion, pro.imagen, codigo_sunat, pro.codigo codigo, producto, precio_costo, precio_base_venta, comision_venta, stock_inicial, stock_actual, categoria_id, unidad_id, categoria, unidad 
        from productos pro
        join unidades uni on pro.`unidad_id` = uni.`id`
        join categorias cat on pro.`categoria_id` = cat.`id`
        where pro.`id` = $producto_id";
        $query = $this->db->query($sql);

        $row = array();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        }
        return $row;
    }
    
    public function ws_select_item($producto_id){
        $data = $this->select_item($producto_id);
        $datos[] = array(
            'codigo_sunat'      =>$data['codigo_sunat'],
            'codigo'            =>$data['codigo'],
            'producto'          =>$data['producto'],
            'descripcion'       =>$data['descripcion'],
            'producto_id'       =>$data['producto_id'],
            'precio_base_venta' =>$data['precio_base_venta'],
            'precio_costo'      =>$data['precio_costo'],
            'comision_venta'    =>$data['comision_venta'],
            'stock_inicial'     =>$data['stock_inicial'],
            'stock_actual'      =>$data['stock_actual'],
            'categoria_id'      =>$data['categoria_id'],
            'unidad_id'         =>$data['unidad_id'],
            'categoria'         =>$data['categoria'],
            'unidad'            =>$data['unidad'],            
            'imagen'            =>$data['imagen']            
        );      
        return $datos;        
    }
    
    public function max_producto_id(){        
        $sql = "SELECT MAX(id) producto_id FROM $this->tabla";
        $query = $this->db->query($sql);
        
        $row = $query->row_array();
        $resultado = $row['producto_id'];            
        if($resultado == null) $resultado = 0;        
        return $resultado;
    }
    
    public function variar_stock($producto_id, $cantidad_a_restar){
        $sql = "UPDATE productos SET stock_actual = stock_actual + $cantidad_a_restar WHERE id = $producto_id";
        //echo $sql;
        $this->db->query($sql);
    }

}