<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Series extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');        
        $this->load->model('series_model');
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
        $this->load->view('series/index');
        $this->load->view('templates/footer');
    }    
    
    public function modal_operacion(){
        $this->load->view('series/modal_operacion');
    }
    
    public function modal_detalle(){
        $this->load->view('series/modal_detalle');
    }
    
    public function operaciones(){
        
        $data = array(
            'tipo_documento_id'  =>  $_GET['tipo_documento_id'],
            'serie'           =>  $_GET['serie']        
        );        
        
        if(isset($_GET['serie_id']) && ($_GET['serie_id'] != '')){
            $data_update = array(
                'fecha_update'      =>  date("Y-m-d H:i:s")
            );
            $data = array_merge($data, $data_update);            
            $this->series_model->modificar($_GET['serie_id'], $data);
        }else{
            $data_update = array(
                'fecha_insert'      =>  date("Y-m-d H:i:s")
            );
            $data = array_merge($data, $data_update);
            $this->series_model->insertar($data);    
        }
                
        $jsondata = array(
            'success'   =>  true,
            'message'   =>  'Operación correcta'
        );
        echo json_encode($jsondata);
    }


}