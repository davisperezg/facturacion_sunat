<?PHP

class Accesos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();        
        $this->load->model('empresas_model');
    }

    public function select($where = FALSE) {
        if ($where === FALSE) {
            $query = $this->db->get('accesos');
            return $query->result_array();
        }
        $query = $this->db->get_where('accesos', array('id' => $where));
        return $query->row_array();        
    }
    
    public function menuGeneral(){  
        $data['empresa'] = $this->empresas_model->select(2); 
        $this->load->view('templates/header_administrador', $data);
    }

}