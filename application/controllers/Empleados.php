<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Empleados extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');        
        $this->load->model('empleados_model');
        $this->load->model('variables_diversas_model');
        $this->load->library('pdf');        
        $this->load->helper('ayuda');
    }
    
    public function index(){
        $this->accesos_model->menuGeneral();
        $this->load->view('empleados/index');
        $this->load->view('templates/footer');
    }    
    
    public function modal_operacion(){
        $this->load->view('empleados/modal_operacion');
    }
    
    public function modal_detalle(){
        $this->load->view('empleados/modal_detalle');
    }
    
    public function modal_foto(){
        $this->load->view('empleados/modal_foto');
    }
    
    public function guardar_foto(){
        $carpeta = "images/empleados/";
        opendir($carpeta);
        $destino = $carpeta.$_FILES['imagen']['name'];

        if(copy($_FILES['imagen']['tmp_name'], $destino)){
            $data_update = array('foto' => $_POST['foto']);            
            $this->empleados_model->modificar($_POST['empleado_id'], $data_update);
            
            $data = array(
                'mostrar_imagen' => base_url()."images/empleados/".$_FILES['imagen']['name']
            );
            echo json_encode($data);
        }else{
            echo "problema al cargar";
        }
    }
    
    public function operaciones(){
        
        $data = array(
            'tipo_empleado_id'  =>  $_GET['tipo_empleado_id'],
            'apellido_paterno'  =>  $_GET['apellido_paterno'],
            'apellido_materno'  =>  $_GET['apellido_materno'],
            'nombres'           =>  $_GET['nombres'],
            'contrasena'        =>  $_GET['contrasena'],
            'fecha_nacimiento'  =>  format_fecha_0000_00_00($_GET['fecha_nacimiento']),
            'dni'               =>  $_GET['dni'],
            'domicilio'         =>  $_GET['domicilio'],
            'telefono_fijo'     =>  $_GET['telefono_fijo'],
            'telefono_movil'    =>  $_GET['telefono_movil'],
            'email_1'           =>  $_GET['email_1'],
            'email_2'           =>  $_GET['email_2']       
        );        
        
        if(isset($_GET['empleado_id']) && ($_GET['empleado_id'] != '')){
            $data_update = array(
                'fecha_update'      =>  date("Y-m-d H:i:s"),
                'empleado_update'   =>  $this->session->userdata('empleado_id')
            );
            $data = array_merge($data, $data_update);            
            $this->empleados_model->modificar($_GET['empleado_id'], $data);
        }else{
            $data_update = array(
                'fecha_insert'      =>  date("Y-m-d H:i:s"),
                'empleado_insert'   =>  $this->session->userdata('empleado_id')
            );
            $data = array_merge($data, $data_update);
            $this->empleados_model->insertar($data);    
        }
                
        $jsondata = array(
            'success'   =>  true,
            'message'   =>  'Operación correcta'
        );
        echo json_encode($jsondata);
    }


}