<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Variables_diversas extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');
        $this->load->model('variables_diversas_model');
        $this->load->helper('ayuda');

        $empleado_id = $this->session->userdata('empleado_id');
        if (empty($empleado_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }  
    
    public function operaciones(){
        $this->variables_diversas_model->modificar(1, array('precio_con_igv' => $this->uri->segment(3)));

        $jsondata = array(
            'success'   =>  true,
            'message'   =>  'Operación correcta'
        );
        echo json_encode($jsondata);
    }
    
    public function index(){
        $this->accesos_model->menuGeneral();
        $this->load->view('variables_diversas/index.html');
        $this->load->view('templates/footer');
    }
    

}