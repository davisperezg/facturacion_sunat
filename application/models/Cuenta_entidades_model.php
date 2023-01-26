<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Cuenta_entidades_model extends CI_Model{

    public $tabla = 'cuenta_entidades';

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

        $respuesta = ($mensaje == '') ? 'Operación realizada con éxito' : $mensaje;
        $this->session->set_flashdata('mensaje',$respuesta);
    }           

    public function modificar($id, $data, $mensaje='') {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);

        $respuesta = ($mensaje == '') ? 'Operación realizada con éxito' : $mensaje;
        $this->session->set_flashdata('mensaje',$respuesta);
    }
    
    public function select_cuentas($entidad_id){
        $sql = "SELECT cue.`id` cuenta_entidad_id, cue.entidad_id entidad_id, numero_cuenta, titular, codigo_interbancario, banco, tipo_cuenta, moneda FROM cuenta_entidades cue
        JOIN bancos ban ON cue.`banco_id` = ban.`id`
        JOIN tipo_cuentas tcu ON cue.`tipo_cuenta_id` = tcu.`id`
        JOIN monedas mon ON mon.`id` = cue.`moneda_id`
        WHERE cue.`fecha_delete`IS NULL AND cue.`entidad_id` = $entidad_id";
        $query = $this->db->query($sql);
        
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function ws_select($entidad_id){
        $data = $this->select_cuentas($entidad_id);

        $datos = array();
        foreach ($data as $value){
            $datos[] = array(
                'entidad_id' => (int)$value['entidad_id'],
                'cuenta_entidad_id' => (int)$value['cuenta_entidad_id'],
                'banco'=>$value['banco'],
                'numero_cuenta'=>$value['numero_cuenta'],
                'titular'=>$value['titular'],
                'codigo_interbancario'=>$value['codigo_interbancario'],
                'banco'=>$value['banco'],
                'tipo_cuenta'=>$value['tipo_cuenta'],
                'moneda'=>$value['moneda']
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