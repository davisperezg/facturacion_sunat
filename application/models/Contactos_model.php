<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Contactos_model extends CI_Model{

    public $tabla = 'contactos';

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

    public function insertar($data) {
        $this->db->insert($this->tabla, $data);
    }           

    public function modificar($id, $data) {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);
    }    

    public function ws_select($entidad_id){
        $data = $this->select(3, '', array('entidad_id' => $entidad_id, 'fecha_delete' => 'IS NULL'));

        $datos = array();
        foreach ($data as $value){
            $datos[] = array(
                'entidad_id' => (int)$value['entidad_id'],
                'contacto_id' => (int)$value['id'],
                'apellido_paterno'=>$value['apellido_paterno'],
                'apellido_materno'=>$value['apellido_materno'],
                'nombres'=>$value['nombres'],
                'celular'=>$value['celular'],
                'correo'=>$value['correo'],
                'comentario'=>$value['comentario']
            );
        }
        return $datos;
    }
    
    public function max_id(){
        $sql = "SELECT MAX(id) maximo_id FROM $this->tabla";
        $query = $this->db->query($sql);
        $resultado = '';
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['maximo_id'];
        }
        return $resultado;
    }

}