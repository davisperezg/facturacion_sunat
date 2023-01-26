<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Modulos_model extends CI_Model{

    public $tabla = 'modulos';

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
        $sql = "SELECT " . $campos . " FROM modulos mdu
        JOIN tipo_empleado_modulos tip_mdu ON tip_mdu.modulo_id = mdu.id WHERE 1 = 1 " . $where . " " . $order;
        //echo $sql;exit;
        //echo $this->db->last_query();exit;
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
    
    public function format_modulos($tipo_empleado_id){
        $condicion_padres = array(
            'tipo_empleado_id'  =>  " = " . $tipo_empleado_id,
            'referencia'        =>  ' = ' . 0,
            'estado'            =>  ' = ' . 1
        );
        $_SESSION['padres'] = $this->select2(3, array('mdu.id modulo_id', 'direccion_icono', 'modulo', 'enlace'), $condicion_padres, ' ORDER BY mdu.id ASC');

        $condicion_hijos = array(
            'tipo_empleado_id'  =>  " = " . $tipo_empleado_id,
            'referencia'        =>  ' != ' . 0,
            'estado'            =>  ' = ' . 1
        );
        $datos = $this->select2(3, '', $condicion_hijos, ' ORDER BY referencia ASC, orden ASC');

        $datos_array = array();
        foreach ($datos as $value){
            $datos_array[$value['referencia']][$value['id']]['id']                = $value['id'];
            $datos_array[$value['referencia']][$value['id']]['direccion_icono']   = $value['direccion_icono'];
            $datos_array[$value['referencia']][$value['id']]['modulo']            = $value['modulo'];
            $datos_array[$value['referencia']][$value['id']]['enlace']            = $value['enlace'];
            $datos_array[$value['referencia']][$value['id']]['referencia']        = $value['referencia'];
            $datos_array[$value['referencia']][$value['id']]['orden']             = $value['orden'];
            $datos_array[$value['referencia']][$value['id']]['padre']             = $value['padre'];
            $datos_array[$value['referencia']][$value['id']]['estado']            = $value['estado'];                
        }
        $_SESSION['hijos'] = $datos_array;        
    }
    
    public function modulos_all(){        
        $sql = "SELECT tabla1.modulo papa, tabla2.`modulo` hijo, tabla2.`id` hijo_id FROM modulos tabla1
        JOIN modulos tabla2 ON tabla1.`id` = tabla2.`referencia`  AND tabla1.`padre` = 1 AND tabla2.`padre` = 0
        WHERE tabla1.`estado` = 1 AND tabla2.`estado` = 1
        ORDER BY tabla1.id, tabla2.orden";        
        $query = $this->db->query($sql);
        
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    public function modulos_usados($tipo_empleado_id){        
        $sql = "SELECT tabla1.modulo papa, tabla2.`modulo` hijo, tabla2.`id` hijo_id, tem.`modulo_id` modulo_id_usado FROM modulos tabla1
        JOIN modulos tabla2 ON tabla1.`id` = tabla2.`referencia` AND tabla1.`padre` = 1 AND tabla2.`padre` = 0
        LEFT JOIN `tipo_empleado_modulos` tem ON tem.`modulo_id` = tabla2.id AND tem.`tipo_empleado_id` = $tipo_empleado_id
        WHERE tabla1.`estado` = 1 AND tabla2.`estado` = 1
        ORDER BY tabla1.id, tabla2.orden";        
        $query = $this->db->query($sql);
        
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    public function formato_modulos($datos){
        $datos_array = array();
        foreach ($datos as $value){
            $datos_array[$value['papa']][$value['hijo_id']]['hijo']     = $value['hijo'];
            $datos_array[$value['papa']][$value['hijo_id']]['hijo_id']  = $value['hijo_id'];            
        }
        return $datos_array;
    }
    
    public function modulos_tipo_empleado($tipo_empleado_id){
        $sql = "SELECT tabla1.modulo papa, tabla2.`modulo` hijo, tabla2.`id` hijo_id FROM modulos tabla1
        JOIN modulos tabla2 ON tabla1.`id` = tabla2.`referencia`  AND tabla1.`padre` = 1 AND tabla2.`padre` = 0
        JOIN `tipo_empleado_modulos` tem ON tem.`modulo_id` = tabla2.`id`
        WHERE tabla1.`estado` = 1 AND tabla2.`estado` = 1 AND tem.`tipo_empleado_id` = $tipo_empleado_id
        ORDER BY tabla1.id, tabla2.orden";        
        $query = $this->db->query($sql);
        
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }

}