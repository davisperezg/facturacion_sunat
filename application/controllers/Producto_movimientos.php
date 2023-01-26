<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Producto_movimientos extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');
        $this->load->model('producto_movimientos_model');
        $this->load->model('productos_model');
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
        $this->load->view('producto_movimientos/index');
        $this->load->view('templates/footer');
    }

    public function modal_detalle(){
        $this->load->view('producto_movimientos/modal_detalle');
    }
    
    public function modal_operacion(){
        $this->load->view('producto_movimientos/modal_operacion');
    }

    public function operaciones(){
        $data = array(
            'producto_id'       =>  $_GET['producto_id'],
            'movimiento'        =>  $_GET['movimiento'],
            'cantidad'          =>  $_GET['cantidad'],
            'motivo'            =>  $_GET['motivo'],
            'fecha_insert'      =>  date("Y-m-d H:i:s"),
            'empleado_insert'   =>  $this->session->userdata('empleado_id')
        );
        $this->producto_movimientos_model->insertar($data);
        
        $multiplicador = ($_GET['movimiento'] == 1) ? 1 : -1;
        $this->productos_model->variar_stock($_GET['producto_id'], $multiplicador * $_GET['cantidad']);
        
        $jsondata = array(
            'success'       =>  true,
            'message'       =>  'Operación correcta'
        );
        echo json_encode($jsondata);
    }

}