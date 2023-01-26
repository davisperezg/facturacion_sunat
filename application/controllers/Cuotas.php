<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cuotas extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');        
        $this->load->model('cuotas_model');
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
        $this->load->view('cuotas/index');
        $this->load->view('templates/footer');
    }
    
    public function nuevo(){
        $this->accesos_model->menuGeneral();
        $this->load->view('cuotas/nuevo');
        $this->load->view('templates/footer');
    }
    
    public function operaciones(){
        
        $venta_id = $_GET['venta_id'];
        for($i = 0; $i < count($_GET['monto']); $i++){            
            $data = array(
                'venta_id'      =>  $venta_id,
                'monto'         =>  $_GET['monto'][$i],
                'fecha_cuota'   =>  $_GET['fecha_cuota'][$i],
            );

            $data_insert = array(
                'fecha_insert'      =>  date("Y-m-d H:i:s"),
                'empleado_insert'   =>  $this->session->userdata('empleado_id')
            ); 
                       
            $data = array_merge($data, $data_insert);
            $this->cuotas_model->insertar($data);
        }                              
        
                
        $jsondata = array(
            'success'       =>  true,
            'message'       =>  'Operación correcta'
        );
        echo json_encode($jsondata);
    }
    
    public function select_venta_id(){
        $venta_id = $this->uri->segment(3);
        $data = $this->cuotas_model->select(3, '', array('venta_id' => $venta_id));
        return $data;
    }
}