<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monedas_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }    
    
    public function select($id = FALSE) {
        if ($id != FALSE) {
            $sql = "SELECT *FROM monedas
                    WHERE id = " . $id;
            $query = mysql_query($sql);
            return mysql_fetch_assoc($query);
        }

        $sql = "SELECT *FROM monedas WHERE 1 = 1 ";
        $query = $this->db->query($sql);
        $rows = array();
        foreach($query->result_array() as $row){
            $rows[] = $row;
        }

        return $rows;
    }
    
    public function select2($modo, $select = array(), $condicion = array(), $order = '') {

        if ($select == '')
            $select = array();
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            switch ($value) {
                case 'IS NULL':
                    $where .= " AND $key " . $value;
                    break;

                case 'IS NULL':
                    $where .= " AND $key " . $value;
                    break;

                default:
                    $where .= " AND $key = '" . $value . "' ";
            }

        }

        $campos = ($select == array()) ? '*' : implode(", ", $select);
        $sql = "SELECT " . $campos . " FROM monedas WHERE 1 = 1 " . $where . " " . $order;
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
    
    public function ws_select(){
        $data = $this->select2(3,array('id','moneda'));

        $datos = array();
        foreach ($data as $value){
            $datos[] = array(
                'id' => (int)$value['id'],
                'moneda'=>$value['moneda']
            );
        }
        return $datos;
    }

}