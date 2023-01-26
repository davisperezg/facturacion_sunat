<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tipo_empleados extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');
        $this->load->model('modulos_model');
        $this->load->helper('ayuda');

        $empleado_id = $this->session->userdata('empleado_id');
        if (empty($empleado_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }

    public function index(){
        $this->accesos_model->menuGeneral();
        $this->load->view('perfiles/index');
        $this->load->view('templates/footer');
    }
    
    public function modal_operacion(){
        $this->load->view('perfiles/modal_operacion');
    }

}