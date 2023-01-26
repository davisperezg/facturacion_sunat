<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Bancos_model extends CI_Model{

    public $tabla = 'bancos';

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

    public function modificar($id, $data, $mensaje='') {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);
    }

    public function ws_select(){
        $data = $this->select(3, array('id', 'banco'));

        $datos = array();
        foreach ($data as $value){
            $datos[] = array(
                'id' => (int)$value['id'],
                'banco'=>$value['banco']
            );
        }
        return $datos;
    }

}