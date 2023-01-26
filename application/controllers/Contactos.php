<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contactos extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');        
        $this->load->model('contactos_model');
        $this->load->model('variables_diversas_model');
        $this->load->library('pdf');        
        $this->load->helper('ayuda');

        $empleado_id = $this->session->userdata('empleado_id');
        if (empty($empleado_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    
    public function modal_contactos(){
        $this->load->view('entidades/modal_contactos');
    }
    
    public function save(){

        $data = array(
            'entidad_id'            =>  $_GET['entidad_id'],
            'apellido_paterno'      =>  $_GET['apellido_paterno'],
            'apellido_materno'      =>  $_GET['apellido_materno'],
            'nombres'               =>  $_GET['nombres'],
            'celular'               =>  $_GET['celular'],
            'correo'                =>  $_GET['correo'],
            'comentario'            =>  $_GET['comentario'],
            'fecha_insert'          =>  date("Y-m-d H:i:s"),
            'empleado_insert'       =>  $this->session->userdata('empleado_id')
        );                       
        $this->contactos_model->insertar($data);
        
        $max_contacto_id = $this->contactos_model->max_id();
        $jsondata = array(
            'success'   =>  true,
            'message'   =>  'Operación correcta',
            'contacto_id'  => $max_contacto_id
        );
        echo json_encode($jsondata);
    }
    

}