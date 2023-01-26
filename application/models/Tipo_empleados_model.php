<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Tipo_empleados_model extends CI_Model{

    public $tabla = 'tipo_empleados';

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
    
    function insert_last_id($data){
        $this->db->insert($this->tabla, $data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }
    
    public function modificar($id, $data) {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);
    }

}