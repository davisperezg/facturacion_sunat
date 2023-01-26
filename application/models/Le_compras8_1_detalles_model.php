<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Le_compras8_1_detalles_model extends CI_Model{

    public $tabla = 'le_compras8_1_detalles';

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
        $sql = "SELECT " . $campos . " , DATE_FORMAT(fecha_emision, '%d-%m-%Y') fecha_emision_cf, DATE_FORMAT(fecha_emision, '%d/%m/%Y') fecha_emision_cf_raya, DATE_FORMAT(fecha_vencimiento, '%d-%m-%Y') fecha_vencimiento_cf, DATE_FORMAT(fecha_vencimiento, '%d/%m/%Y') fecha_vencimiento_cf_raya, DATE_FORMAT(da_fecha_emision, '%d-%m-%Y') da_fecha_emision_cf, DATE_FORMAT(da_fecha_emision, '%d/%m/%Y') da_fecha_emision_cf_raya, DATE_FORMAT(fecha_emision_detraccion, '%d-%m-%Y') fecha_emision_detraccion_cf, DATE_FORMAT(fecha_emision_detraccion, '%d/%m/%Y') fecha_emision_detraccion_cf_raya FROM $this->tabla WHERE 1 = 1 " . $where . " " . $order;
        //echo $sql;exit;
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

    public function insertar($data) {
        $this->db->insert($this->tabla, $data);
    }           

    public function modificar($id, $data) {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);
    }    
    
    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->tabla);
    }
}