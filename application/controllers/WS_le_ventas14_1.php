<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_le_ventas14_1 extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('le_ventas14_1_model');
    }
    
    public function ws_select(){
        $data = $this->le_ventas14_1_model->select(3,'','', ' ORDER BY anio DESC, mes DESC');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


}