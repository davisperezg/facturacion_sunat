<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_test_borrar extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('test_borrar_model');
    }
    
    public function select(){       
        $data = $this->test_borrar_model->select(3, array('month', 'profit'), array('year' => $this->uri->segment(3)));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } 
    
    public function anios(){       
        $data = $this->test_borrar_model->anios(3);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } 

    
}