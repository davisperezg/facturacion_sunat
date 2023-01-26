<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class pedido_almacenes_model extends CI_Model{

    public $tabla = 'pedido_almacenes';

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

    public function insertar($data, $mensaje = '') {
        $this->db->insert($this->tabla, $data);   
    }           

    public function modificar($id, $data) {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);
    }
    
    public function ultimoNumeroDeSerie() {        
        $sql = "SELECT MAX(numero) numero FROM $this->tabla ORDER BY id DESC LIMIT 1";       
        $query = $this->db->query($sql);        
                
        $row = $query->row_array();        
        $resultado = ($row['numero'] == null) ? 0 : $row['numero'];
            
        return $resultado;
    }
    
    public function select_max_id(){
        $sql = "SELECT MAX(id) pedido_almacen_id FROM $this->tabla";
        $query = $this->db->query($sql);
        $resultado = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['pedido_almacen_id'];
        }
        return $resultado;
    }
    
    //se utilizara generalmente en el index    
    function query_standar_1(){
        $sql = " FROM pedido_almacenes ped
        JOIN empleados epl ON ped.`empleado_insert` = epl.`id`";
        return $sql;
    }
    
    public function ws_select($pagina, $filas_por_pagina, $modo, $select = array(), $condicion = array(), $order = '') {
        if ($select == '')
            $select = array();
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            $where .= " AND $key $value ";
        }
        
        $limit = '';
        if(($pagina != '') && ($filas_por_pagina != '')){
            $inicio = ($pagina - 1)*$filas_por_pagina;
            $limit = "LIMIT $inicio, $filas_por_pagina";
        }
        
        $query_1 = $this->query_standar_1();
        $campos = ($select == array()) ? '*' : implode(", ", $select);
        $sql = "SELECT " . $campos . " $query_1 WHERE 1 = 1 " . $where . " " . $order . " " . $limit;
        //echo $sql."<br>";
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
        $query_1 = $this->query_standar_1();
        $sql = "select count(ped.id) total_filas $query_1 WHERE 1 = 1 " . $where;
        $query = $this->db->query($sql);

        $resultado = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['total_filas'];
        }
        return $resultado;
    }
        
}