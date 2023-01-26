<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_monedas extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('monedas_model');
    }
    
    public function monedas(){        
        $data['monedas'] = $this->monedas_model->ws_select();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } 

    
}