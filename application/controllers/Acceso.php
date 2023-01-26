<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Acceso extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('empleados_model');
        $this->load->model('accesos_model');
        $this->load->model('almacenes_model');
        $this->load->model('monedas_model');
        $this->load->model('modulos_model');
        $this->load->helper('cookie');
    }

    function index() {
        //$this->session->sess_destroy();
    
        $this->load->view('templates/header_sin_menu');
        $this->load->view('acceso/login');
        $this->load->view('templates/footer');
    }

    function inicio_administrador() {
        $this->accesos_model->menuGeneral();
        $this->load->view('acceso/inicio');
        $this->load->view('templates/footer');
    }
    
    function login(){        
        $datos_login = $this->empleados_model->select(2, '', array('contrasena' => "= '".$_POST['contrasena'] . "'", 'email_1' => "= '".$_POST['email_1'] . "'"));
        if(count($datos_login) > 0){
            session_start();
            
            $this->empleados_model->set_datos_login($datos_login);//setear datos             
            $this->modulos_model->format_modulos($datos_login['tipo_empleado_id']);                        

            redirect(base_url() . "index.php/acceso/inicio_administrador");
        }else{
            $this->session->set_flashdata('respuesta_login', 'Datos Incorrectos');
            redirect(base_url() . "index.php/acceso/logout");
        }
    }    

    function login2() {
        //echo $_POST['contrasena'];exit;
        if ($this->Empleados_model->login($_POST['contrasena'])) {
            
            
            $condicion = ($numero != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('ven.numero' => '='.$numero)) : $condicion;
            
            //para las imagenes.
            $user_foto = $this->session->userdata('foto');
            $filename = './files/foto/' . $user_foto;
            if(file_exists($filename) && !empty($user_foto)){
                $data = array(
                    'ruta_foto' => './files/foto/'.$this->session->userdata('foto'),
                    'title' => $this->session->userdata('usuario') . " " . $this->session->userdata('apellido_paterno')
                );
            }else{
                $data = array(
                    'ruta_foto' => "./files/foto/sin_foto.jpg",
                    'title'=>"sin foto"
                );
            }
            $this->session->set_userdata($data);
           
            session_start();
            redirect(base_url() . "index.php/acceso/inicio_administrador");
        } else {
            redirect(base_url() . "index.php/acceso/logout");
        }
    }

    function logout() {
        session_destroy();
        $this->session->sess_destroy();
        redirect(base_url());
//        $ruta_inicio_ultiempresa = 'http://localhost:8080/monstruo_multiempresa/';
//        redirect($ruta_inicio_ultiempresa);
    }
    
    function grafica(){
        $this->accesos_model->menuGeneral();
        $this->load->view('acceso/grafica');
        $this->load->view('templates/footer');
    }

}
