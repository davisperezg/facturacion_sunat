<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pedido_almacenes extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');        
        $this->load->model('pedido_almacenes_model');
        $this->load->model('Pedido_almacen_detalles_model');
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
        $this->load->view('pedido_almacenes/index');
        $this->load->view('templates/footer');
    }
    
    public function nuevo(){
        $this->accesos_model->menuGeneral();
        $this->load->view('pedido_almacenes/nuevo');
        $this->load->view('templates/footer');
    }
    
    public function operaciones(){        
        
        $data = array(
            'numero'                =>  $_GET['numero'],
            'fecha_pedido'          =>  date("Y-m-d"),
            'fecha_insert'          =>  date("Y-m-d H:i:s"),
            'empleado_insert'       =>  $this->session->userdata('empleado_id')
        );
        if($_GET['notas'] != '') $data = array_merge($data, array('notas' => $_GET['notas']));                                
        //$venta_id = (isset($_GET['venta_id']) && ($_GET['venta_id'] != '')) ? $_GET['venta_id'] : null;
                
        //este select impedirá que se guarden multiples registros(Boot medio pendex)
        $select_id = $this->pedido_almacenes_model->select(1, array('id'), array('numero' => $_GET['numero']));
        if($select_id == ''){                
            $this->db->insert('pedido_almacenes', $data);
            $pedido_almacen_id = $this->pedido_almacenes_model->select_max_id();

            for($i = 0; $i < count($_GET['producto_id']); $i++){                    
                $data_detalle = array(
                    'pedido_almacen_id' => $pedido_almacen_id,
                    'producto_id'       => $_GET['producto_id'][$i],
                    'producto'          => $_GET['producto'][$i],
                    'cantidad'          => $_GET['cantidad'][$i]                        
                );                    

                $this->Pedido_almacen_detalles_model->insertar($data_detalle);
                // ponemos el "-" a $_GET['cantidad'][$i], ya q vamos a devolver el stock en el update
                $this->productos_model->variar_stock($_GET['producto_id'][$i], -$_GET['cantidad'][$i]);
            }
        }else{
            $this->Pedido_almacen_detalles_model->insertar($data_detalle);
        }
        
        $jsondata = array(
            'venta_id'      =>  $pedido_almacen_id,
            'success'       =>  true,
            'message'       =>  'Operación correcta'
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }
    

}