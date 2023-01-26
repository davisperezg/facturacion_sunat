<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Categorias extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');                
        $this->load->model('categorias_model');
        
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
        $this->load->view('categorias/index');
        $this->load->view('templates/footer');
    }
    
    public function nuevo(){
        $this->accesos_model->menuGeneral();
        $this->load->view('categorias/nuevo');
        $this->load->view('templates/footer');
    }
    
    public function modal_operacion(){
        $this->load->view('categorias/modal_operacion');
    }
    
    public function modal_imagen(){
        $this->load->view('categorias/modal_imagen');
    }
    
    public function operaciones(){        
        $data = array(
            'categoria'     =>  $_GET['categoria'],
            'codigo'        =>  $_GET['codigo'],
        );        
        
        if(isset($_GET['categoria_id']) && ($_GET['categoria_id'] != '')){            
            $this->categorias_model->modificar($_GET['categoria_id'], $data);
        }else{
            $this->categorias_model->insertar($data);
        }

        $jsondata = array(
            'success'   =>  true,
            'message'   =>  'Operación correcta'
        );
        echo json_encode($jsondata);
    }
    
    public function guardar_imagen(){
        $carpeta = "images/categorias/";
        opendir($carpeta);
        $destino = $carpeta.$_FILES['imagen']['name'];
        if(copy($_FILES['imagen']['tmp_name'], $destino)){ 
            $data = array(
                'mostrar_imagen' => base_url()."images/categorias/".$_FILES['imagen']['name']
            );
            echo json_encode($data);
        }else{
            echo "problema al cargar";
        }
    }

}