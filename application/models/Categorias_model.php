<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Categorias_model extends CI_Model{

    public $tabla = 'categorias';

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
    
    public function ws_buscador($buscar) {
        $sql = "SELECT id categoria_id, categoria "
                . "FROM $this->tabla "
                . "WHERE (categoria LIKE '%$buscar%') "
                . "ORDER BY categoria";

        $query = $this->db->query($sql);

        $rows = array();        
        foreach ($query->result_array() as $tsArray){
            $rows[] = array(
                "value" => $tsArray['categoria'],
                "id" => $tsArray['categoria_id']
            );
        }
        return $rows;
    }
    
    public function select_categorias($pagina, $filas_por_pagina, $condicion = array(), $order = '') {
        if ($condicion == '')
        $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {            
            $where .= " AND $key $value ";
        }
        if($order == '')
        $order = ' ORDER BY id desc ';
        $inicio = ($pagina - 1)*$filas_por_pagina;
        $sql = "SELECT 
        id categoria_id, categoria, codigo
        FROM $this->tabla ent        
        WHERE 1 = 1 AND eliminado = 0 " . $where . " " . $order . "
        LIMIT $inicio, $filas_por_pagina";

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
        $sql = "select count(id) total_filas from $this->tabla
        WHERE 1 = 1 AND eliminado = 0 " . $where;
        $query = $this->db->query($sql);

        $resultado = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['total_filas'];
        }
        return $resultado;
    }    

    public function ws_select($pagina, $filas_por_pagina, $condicion = array(), $order = ''){
        $data = $this->select_categorias($pagina, $filas_por_pagina, $condicion, $order);
        $total_filas = $this->total_filas($condicion);

        $datos = array();
        foreach ($data as $value){
            $datos[] = array(
                'categoria_id'  =>  (int)$value['categoria_id'],
                'categoria'     =>  $value['categoria'],
                'codigo'        =>  $value['codigo'],
            );
        }

        $salida = array(
            'ws_select_categorias'  => $datos,
            'total_filas'           => $total_filas
        );
        return $salida;
    }
    
    public function ws_item($categoria_id){
        $data = $this->select(2, '', array('id' => $categoria_id));
        $datos[] = array(
            'categoria_id'      =>  $data['id'],
            'categoria'         =>  $data['categoria']
        );
        return $datos;        
    }
    
    public function ws_select_all(){
        $data = $this->select(3, array('id', 'categoria'), array('eliminado' => '0'));

        $datos = array();
        foreach ($data as $value){
            $datos[] = array(
                'id' => (int)$value['id'],
                'categoria'=>$value['categoria'],
            );
        }
        return $datos;
    }
    
    public function format($data){
        $datos = array();
        foreach ($data as $value){
            $datos[$value['codigo']] = $value['id'];
        }
        return $datos;
    }

}