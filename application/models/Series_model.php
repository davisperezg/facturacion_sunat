<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Series_model extends CI_Model{

    public $tabla = 'series';

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
    
    //este query debe usarse tanto para la table, el detalle y el upate.
    public function query_standar($modo, $select = array(), $condicion = array(), $order = '') {
        if ($select == '')
            $select = array();
        $campos_standar = array('ser.id serie_id', 'tipo_documento_id', 'tipo_documento', 'serie', 'codigo', 'abreviado');
        $select = (count($select) == 0) ? $campos_standar : $select;
        $select = implode(", ", $select);

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
        if($order == '')
            $order = 'ORDER BY tipo_documento_id, ser.`id`';
        $sql = " SELECT " . $select . " FROM series ser
        JOIN tipo_documentos tpd ON tpd.`id` = ser.`tipo_documento_id`
        WHERE fecha_delete IS null " . $where . " " . $order;
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
                foreach ($query->result_array() as $key => $row) {
                    foreach ($row as $key => $value){
                        $supuesto_id = substr($key, -3);
                        if( ($supuesto_id == '_id') and is_numeric($value)){
                            $row[$key] = (int)$value;
                        }else{
                            $row[$key] = $value;
                        }
                    }
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

}