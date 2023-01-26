<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manuales extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('accesos_model');
    }

    function index() {
        //$this->session->sess_destroy();   
        $this->accesos_model->menuGeneral();
        $this->load->view('manuales/index');
        $this->load->view('templates/footer');
    }
    
}
