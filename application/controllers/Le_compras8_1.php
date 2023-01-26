<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Endroid\QrCode\QrCode;

class le_compras8_1 extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('le_compras8_1_model');
        $this->load->model('le_compras8_1_detalles_model');
        $this->load->model('ventas_model');
        $this->load->model('accesos_model');
        $this->load->model('variables_diversas_model');
        $this->load->library('pdf');        
        $this->load->helper('ayuda');
        
        require_once (APPPATH .'libraries/efactura.php');

        $empleado_id = $this->session->userdata('empleado_id');
        if (empty($empleado_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    
    public function index(){
        $this->accesos_model->menuGeneral();
        $this->load->view('le_compras8_1/index');
        $this->load->view('templates/footer');
    }
    
    public function modal_nuevo(){
        $this->load->view('le_compras8_1/modal_nuevo');
    }
    
    public function operaciones(){
        $data = array(
            'anio'  =>  $_REQUEST['anio'],
            'mes'   =>  $_REQUEST['mes']
        );        
        
        $this->le_compras8_1_model->insertar($data);

        $jsondata = array(
            'success'       =>  true,
            'message'       =>  'Operación correcta'
        );
        echo json_encode($jsondata);
    }
    
    public function generar(){        
        $this->accesos_model->menuGeneral();
        $this->load->view('le_compras8_1/generar');
        $this->load->view('templates/footer');
    }
        
}
?>