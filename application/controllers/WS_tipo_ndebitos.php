<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_tipo_ndebitos extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('tipo_ndebitos_model');
    }
    
    public function select_all(){
        $data = $this->tipo_ndebitos_model->select(3);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } 

    
}