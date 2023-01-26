<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Guia_detalles_model extends CI_Model{

    public $tabla = 'guia_detalles';

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
    
    public function query_standar($guia_id){
        $sql = "SELECT 
        gde.cantidad, pro.producto, pro.id producto_id, pro.codigo producto_codigo, und.unidad, und.codigo codigo_unidad, und.id unidad_id
        FROM guia_detalles gde
        JOIN productos pro ON gde.producto_id = pro.id
        JOIN unidades und ON und.id = pro.unidad_id
        WHERE gde.`guia_id` = " . $guia_id;
        $query = $this->db->query($sql);
        
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    public function query_api($guia_id){
        $sql = "SELECT 
        gde.cantidad, pro.producto producto, pro.codigo producto_codigo, und.codigo codigo_unidad
        FROM guia_detalles gde
        JOIN productos pro ON gde.producto_id = pro.id
        JOIN unidades und ON pro.unidad_id = und.id
        WHERE gde.`guia_id` = " . $guia_id;
        $query = $this->db->query($sql);
        
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    public function delete_guia_id($guia_id){
        $this->db->where('guia_id', $guia_id);
        $this->db->delete($this->tabla);
    }

    public function insertar($data) {
        $this->db->insert($this->tabla, $data);
    }    

    public function modificar($id, $data, $mensaje='') {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);
    }

}