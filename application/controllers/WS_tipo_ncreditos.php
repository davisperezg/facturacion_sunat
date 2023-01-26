<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_tipo_ncreditos extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('tipo_ncreditos_model');
    }
    
    public function select_all(){
        $data = $this->tipo_ncreditos_model->select(3, '', '', ' ORDER BY id ASC');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } 

    
}