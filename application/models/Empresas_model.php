<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Empresas_model extends CI_Model{

    public $tabla = 'empresas';

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
            if ($value == 'IS NULL') {
                $where .= " AND $key " . $value;
            } else {
                $where .= " AND $key = '" . $value . "' ";
            }
        }

        $campos = ($select == array()) ? '*' : implode(", ", $select);
        $sql = "SELECT " . $campos . " FROM empresas epr JOIN regimenes reg on epr.regimen_id = reg.id  WHERE 1 = 1 " . $where . " " . $order;
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
    
    public function query_standar() {
        $sql = "SELECT emp.id empresa_id, empresa, nombre_comercial, ruc, domicilio_fiscal, telefono_fijo, telefono_fijo2, telefono_movil, telefono_movil2, foto, correo,
        ubigeo, urbanizacion, usu_secundario_prueba_user, usu_secundario_prueba_passoword, usu_secundario_produccion_user, usu_secundario_produccion_password, 
        certi_prueba_nombre, certi_prueba_password, certi_produccion_nombre, certi_produccion_password, modo, distrito, provincia, departamento  
        FROM empresas emp
        JOIN `ubigeo_distritos` udi ON emp.`ubigeo` = udi.`id`
        JOIN `ubigeo_provincias` upr ON upr.`id` = SUBSTRING(udi.id, 1, 4)
        JOIN `ubigeo_departamentos` ude ON ude.`id` = SUBSTRING(upr.`id`, 1, 2)";
        //echo $sql;
        
        $query = $this->db->query($sql);
        $row = array();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        }
        return $row;
    }    
    
    //query para el API
    public function query_api() {
        $sql = "SELECT guias_client_id, guias_client_secret, empresa razon_social, nombre_comercial, ruc, domicilio_fiscal,
        ubigeo, urbanizacion, usu_secundario_produccion_user, usu_secundario_produccion_password, modo, distrito, provincia, departamento
        FROM empresas emp
        JOIN `ubigeo_distritos` udi ON emp.`ubigeo` = udi.`id`
        JOIN `ubigeo_provincias` upr ON upr.`id` = SUBSTRING(udi.id, 1, 4)
        JOIN `ubigeo_departamentos` ude ON ude.`id` = SUBSTRING(upr.`id`, 1, 2)";
        //echo $sql;
        
        $query = $this->db->query($sql);
        $row = array();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        }
        return $row;
    }

    public function modificar($id, $data) {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);
    }    

}