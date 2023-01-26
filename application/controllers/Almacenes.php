<?PHP
use PhpOffice\PhpSpreadsheet\Spreadsheet;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Almacenes extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();        
        date_default_timezone_set('America/Lima');
        $this->load->model('accesos_model'); 
        $this->load->model('almacenes_model');       
        $this->load->helper('ayuda');        
//
        $empleado_id = $this->session->userdata('empleado_id');
        $almacen_id = $this->session->userdata("almacen_id");
        if (empty($empleado_id) or empty($almacen_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    public function index()
    {
        $this->accesos_model->menuGeneral();
        $this->load->view('almacenes/basic_index');
        $this->load->view('templates/footer');    	
    }
    public function crear()
    {
    	$data = array();
    	echo $this->load->view('almacenes/modal_crear', $data);
    }
    public function editar($idAlmacen)
    {
    	$data['almacen'] = $this->almacenes_model->select($idAlmacen);
    	$this->load->view('almacenes/modal_crear', $data);
    }
    public function guardarAlmacen(){
    	$error = array();
    	if($_POST['nombre'] == '')
    	{
    		$error['nombre'] = 'falta ingresar nombre';
    	}

    	if(count($error) > 0)
    	{
    		$data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
    		sendJsonData($data);
    		exit();
    	}    

    	//guardamos el almacen
    	$result = $this->almacenes_model->guardar();
    	
    	if($result)
    	{
     		sendJsonData(['status'=>STATUS_OK]);
     		exit();
    	}else
    	{
    		sendJsonData(['status'=>STATUS_FAIL, 'tipo'=>2]);
    		exit();
    	}	
    }
    public function eliminar($idAlmacen)
    {
    	$result = $this->almacenes_model->eliminar($idAlmacen);
    	if($result)
    	{
     		sendJsonData(['status'=>STATUS_OK]);
     		exit();
    	}else
    	{
    		sendJsonData(['status'=>STATUS_FAIL]);
    		exit();
    	}    	
    }
    public function getMainList()
    {
    	$rsDatos = $this->almacenes_model->getMainList();
    	sendJsonData($rsDatos);
    }
}