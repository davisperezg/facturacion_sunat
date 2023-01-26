<?PHP
    if(!defined('BASEPATH'))
        exit ('No direct script access allowed');    
    
    class Tipo_documentos_model extends CI_Model {
     
        public function __construct() {
            parent::__construct();
            $this->load->database();
        }
                
        public function select($id = '' , $tipo_documento = '',$documentosId= '') {            
            if($id != ''){
                $sql = "SELECT *FROM tipo_documentos
                        WHERE id = ". $id;                
                $query = mysql_query($sql);
                return mysql_fetch_assoc($query);
            }
            
            $where = '';
            $where.= ($tipo_documento != '') ? " AND tipo_documento LIKE  '%".$tipo_documento."%'" : '';
            $where.= ($documentosId != '') ? "AND id < ".$documentosId : '';
            
            
            $sql = "SELECT *FROM tipo_documentos WHERE 1=1 ".$where;
            
            $query = $this->db->query($sql);
            $rows  = array(); 
            
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
            $sql = "SELECT " . $campos . " FROM tipo_documentos WHERE 1 = 1 " . $where . " " . $order;
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
        
        public function tipo_documentos() {
            $sql = "SELECT *FROM tipo_documentos WHERE id NOT IN (9) ORDER BY id";
            $query = $this->db->query($sql);

            $rows = array();
            foreach ($query->result_array() as $row) {
                $rows[] = $row;
            }
            return $rows;
        }
        
        public function documentos_menos_guia() {
            $sql = "SELECT *FROM tipo_documentos WHERE id IN (1, 3, 7, 8) ORDER BY id";
            $query = $this->db->query($sql);

            $rows = array();
            foreach ($query->result_array() as $row) {
                $rows[] = $row;
            }
            return $rows;
        }        
        
        public function select_formato_json(){
            $data = $this->tipo_documentos(3,array('id','tipo_documento'));
            
            $datos = array();
            foreach ($data as $value){
                $datos[] = array(
                    'id' => (int)$value['id'],
                    'tipo_documento'=>$value['tipo_documento']
                );
            }
            return $datos;
        }
        
    }