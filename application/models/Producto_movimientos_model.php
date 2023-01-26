<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Producto_movimientos_model extends CI_Model {
    
    public $tabla = 'producto_movimientos';

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
    
    public function standar($modo, $select = array(), $pagina, $filas_por_pagina, $condicion = array(), $order = '') {
        if ($select == '') $select = array();        
        $campos_standar = array('pro.producto', 'mov.id producto_movimiento_id', 'mov.fecha_insert', 'mov.movimiento ', 'mov.cantidad', 'mov.motivo ');
        $select = (count($select) == 0) ? $campos_standar : $select;
        $select = ($select == array()) ? '*' : implode(", ", $select);
        
        if ($condicion == '')
        $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            $where .= " AND $key $value ";
        }
        
        $limit = '';
        if(($pagina != '') || ($filas_por_pagina != '')){
            $inicio = ($pagina - 1)*$filas_por_pagina;
            $limit = "LIMIT $inicio, $filas_por_pagina";
        }

        $sql = "SELECT $select FROM producto_movimientos mov
        JOIN productos pro ON pro.id = mov.producto_id WHERE 1 = 1 " . $where . " " . $order . " " . $limit;
        
        //echo $sql;
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
    
    public function total_filas($condicion) {
        if ($condicion == '')
        $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {            
            $where .= " AND $key $value ";
        }
        $sql = "SELECT COUNT(mov.id) total_filas FROM producto_movimientos mov
        JOIN productos pro ON pro.id = mov.producto_id WHERE 1 = 1 " . $where;                
        $query = $this->db->query($sql);

        $resultado = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['total_filas'];
        }
        return $resultado;
    }

    public function insertar($data) {
        $this->db->insert($this->tabla, $data);
    }

    public function modificar($id, $data) {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);      
    }
    
    public function movimientos($param_id = ''){
        $datos = array(
            array('id'  =>  1,  'movimiento'    =>  'ingreso'),
            array('id'  =>  2,  'movimiento'    =>  'salida')
        );
        
//        if($param_id != ''){
//            $datos  =   array($param_id     =>  $datos[$param_id]);
//        }
        
        return $datos;
    }

}