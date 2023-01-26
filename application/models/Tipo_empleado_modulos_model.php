<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Tipo_empleado_modulos_model extends CI_Model{

    public $tabla = 'tipo_empleado_modulos';

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

    public function insertar($data) {
        $this->db->insert($this->tabla, $data);
    }           

    public function modificar($id, $data) {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);
    }    
    
    public function delete_tipo_empleado_id($tipo_empleado_id) {        
        $this->db->where('tipo_empleado_id', $tipo_empleado_id);
        $this->db->delete($this->tabla);
    }

}