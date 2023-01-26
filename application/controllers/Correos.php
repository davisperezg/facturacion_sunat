<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Correos extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');        
        $this->load->model('correos_model');
        $this->load->model('variables_diversas_model');
        $this->load->library('pdf');        
        $this->load->helper('ayuda');

        $empleado_id = $this->session->userdata('empleado_id');
        if (empty($empleado_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    
    public function index(){
        $this->accesos_model->menuGeneral();
        $this->load->view('correos/index');
        $this->load->view('templates/footer');
    }
    
    public function modal_operacion(){
        $this->load->view('correos/operacion');
    }
    
    public function operaciones(){
        $data = array(
            'user'              =>  $_REQUEST['user'],
            'pass'              =>  $_REQUEST['pass'],
            'host'              =>  $_REQUEST['host'],
            'port'              =>  $_REQUEST['port'],
            'correo_cifrado'    =>  $_REQUEST['correo_cifrado'],
            'notas'             =>  $_REQUEST['notas']
        );        
        //echo 'cobro_id:'.$_GET['cobro_id']."--";
        if($_GET['id'] != ''){
            $this->correos_model->modificar($_GET['id'], $data);
        }else{
            $this->correos_model->insertar($data);
        }

        $jsondata = array(
            'success'       =>  true,
            'message'       =>  'Operación correcta'
        );
        echo json_encode($jsondata);
    }    

}