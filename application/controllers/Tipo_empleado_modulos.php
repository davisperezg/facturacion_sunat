<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tipo_empleado_modulos extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');
        $this->load->model('tipo_empleado_modulos_model');
        $this->load->model('tipo_empleados_model');
        $this->load->helper('ayuda');

        $empleado_id = $this->session->userdata('empleado_id');
        if (empty($empleado_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }

    public function index(){
        $this->accesos_model->menuGeneral();
        $this->load->view('perfiles/index');
        $this->load->view('templates/footer');
    }

    public function operaciones(){
        
        if($_GET['tipo_empleado_id'] != ''){
            $tipo_empleado_id = $_GET['tipo_empleado_id'];
            $this->tipo_empleados_model->modificar($tipo_empleado_id, array('tipo_empleado' => $_GET['tipo_empleado']));
            $this->tipo_empleado_modulos_model->delete_tipo_empleado_id($tipo_empleado_id);
        }else{
            $data = array('tipo_empleado'   => $_GET['tipo_empleado'],
                  'estado'          =>  1
            );
            $tipo_empleado_id = $this->tipo_empleados_model->insert_last_id($data);
        }

        for($i = 0; $i < count($_GET['check_modulo']); $i++){
            $data_detalle = array(
                'tipo_empleado_id'  =>  $tipo_empleado_id,
                'modulo_id'         =>  $_GET['check_modulo'][$i],
            );
            $this->tipo_empleado_modulos_model->insertar($data_detalle);
        }
        
        foreach($_SESSION['padres'] as $padre){
            for($i = 0; $i < count($_GET['check_modulo']); $i++){
                if(($_GET['check_modulo'][$i] > $padre['modulo_id'])  && ($_GET['check_modulo'][$i] < ($padre['modulo_id'] + 100))){
                    $data_papa = array(
                        'tipo_empleado_id'  =>  $tipo_empleado_id,
                        'modulo_id'         =>  $padre['modulo_id'],
                    );
                    $this->tipo_empleado_modulos_model->insertar($data_papa);
                    break;
                }
            }
        }
        
        $jsondata = array(
            'success'       =>  true,
            'message'       =>  'Operación correcta'
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }

}