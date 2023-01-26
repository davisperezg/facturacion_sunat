<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_guia_detalles extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('guia_detalles_model');        
    }
    
    public function ws_select(){
        $guia_id = $this->uri->segment(3);    
        $data = $this->guia_detalles_model->query_standar($guia_id);
        //var_dump($data['guias']);exit;
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    

    
}