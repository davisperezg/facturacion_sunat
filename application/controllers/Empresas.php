<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Empresas extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');        
        $this->load->model('empresas_model');
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
        $this->load->view('empresas/index');
        $this->load->view('templates/footer');
    }    
    
    public function modal_operacion(){
        $this->load->view('empresas/modal_operacion');
    }
    
    public function modal_detalle(){
        $this->load->view('empresas/modal_detalle');
    }
    
    public function modal_foto(){
        $this->load->view('empresas/modal_foto');
    }
    
    public function modal_entorno(){
        $this->load->view('empresas/modal_entorno');
    }
    
    public function modal_certificado_prueba(){
        $this->load->view('empresas/modal_certificado_prueba');
    }
    
    public function modal_certificado_produccion(){
        $this->load->view('empresas/modal_certificado_produccion');
    }
    
    public function operaciones(){
        
        $data = array(
            'empresa'           =>  trim($_GET['empresa']),
            'nombre_comercial'  =>  trim($_GET['nombre_comercial']),
            'ruc'               =>  trim($_GET['ruc']),
            'domicilio_fiscal'  =>  $_GET['domicilio_fiscal'],
            'telefono_fijo'     =>  $_GET['telefono_fijo'],
            'telefono_fijo2'    =>  $_GET['telefono_fijo2'],
            'telefono_movil'    =>  $_GET['telefono_movil'],
            'telefono_movil2'   =>  $_GET['telefono_movil2'],
            'correo'            =>  $_GET['correo'],
            'ubigeo'            =>  $_GET['ubigeo'],
            'urbanizacion'      =>  $_GET['urbanizacion'],
            'usu_secundario_produccion_user'        =>  $_GET['usu_secundario_produccion_user'],
            'usu_secundario_produccion_password'    =>  $_GET['usu_secundario_produccion_password'],
            'regimen_id'                            =>  $_GET['regimen_id'],
            'codigo_sucursal_sunat'                 =>  $_GET['codigo_sucursal_sunat']
        );
        
        if(isset($_GET['empresa_id']) && ($_GET['empresa_id'] != '')){            
            $this->empresas_model->modificar($_GET['empresa_id'], $data);
        }
                
        $jsondata = array(
            'success'   =>  true,
            'message'   =>  'Operación correcta'
        );
        echo json_encode($jsondata);
    }
    
    public function activar_produccion(){
        $this->empresas_model->modificar(1, array('modo' => 1));
        $jsondata = array(
            'success'   =>  true,
            'message'   =>  'Operación correcta'
        );
        echo json_encode($jsondata);
    }
    
    public function guardar_foto(){
        $carpeta = "images/empresas/";
        opendir($carpeta);
        $destino = $carpeta.$_FILES['imagen']['name'];

        if(copy($_FILES['imagen']['tmp_name'], $destino)){
            $data_update = array('foto' => $_POST['nombre_foto']);            
            $this->empresas_model->modificar(1, $data_update);
            
            $data = array(
                'mostrar_imagen' => base_url()."images/empresas/".$_FILES['imagen']['name']
            );
            echo json_encode($data);
        }else{
            echo "problema al cargar";
        }
    }
    
    public function guardar_certificado_prueba(){
        $carpeta = "application/libraries/certificado_digital/prueba/";
        opendir($carpeta);
        $destino = $carpeta.$_FILES['imagen']['name'];

        if(copy($_FILES['imagen']['tmp_name'], $destino)){
            $data_update = array(
                'certi_prueba_nombre'   => $_POST['certi_prueba_nombre'],
                'certi_prueba_password' => $_POST['certi_prueba_password']
            );
            $this->empresas_model->modificar(1, $data_update);
            $data = array(
                'mostrar_imagen'    => base_url() . $carpeta . $_FILES['imagen']['name'],
                'nombre_imagen'     => $_FILES['imagen']['name']
            );
            echo json_encode($data);
        }else{
            echo "problema al cargar";
        }
    }
    
    public function guardar_certificado_produccion(){
        $carpeta = "application/libraries/certificado_digital/produccion/";
        opendir($carpeta);
        $destino = $carpeta.$_FILES['imagen']['name'];

        if(copy($_FILES['imagen']['tmp_name'], $destino)){
            $data_update = array(
                'certi_produccion_nombre'   => $_POST['certi_produccion_nombre'],
                'certi_produccion_password' => $_POST['certi_produccion_password']
            );
            $this->empresas_model->modificar(1, $data_update);
            $data = array(
                'mostrar_imagen'    => base_url() . $carpeta . $_FILES['imagen']['name'],
                'nombre_imagen'     => $_FILES['imagen']['name']
            );
            echo json_encode($data);
        }else{
            echo "problema al cargar";
        }
    }

}