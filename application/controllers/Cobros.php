<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cobros extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');        
        $this->load->model('cobros_model');
        $this->load->model('cuotas_model');
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
        $this->load->view('cobros/index');
        $this->load->view('templates/footer');
    }
    
    public function nuevo(){
        $this->accesos_model->menuGeneral();
        $this->load->view('cobros/nuevo');
        $this->load->view('templates/footer');
    }
    
    public function modal_operacion(){
        $this->load->view('cobros/modal_operacion');
    }
    
    public function modal_imagen(){
        $this->load->view('cobros/modal_imagen');
    }
    
    public function modal_detalle(){
        $this->load->view('cobros/modal_detalle');
    }
        
    public function operaciones(){
        $data = array(
            'venta_id'      =>  $_REQUEST['venta_id'],
            'modo_pago_id'  =>  $_REQUEST['modo_pago_id'],
            'monto'         =>  $_REQUEST['monto'],        
            'fecha_pago'    =>  $_REQUEST['fecha_pago'],
            'nota'          =>  $_REQUEST['nota'],        
        );
        
        //echo 'cobro_id:'.$_GET['cobro_id']."--";
        if($_GET['cobro_id'] != ''){
            $data_update = array(
                'fecha_update'      =>  date("Y-m-d H:i:s"),
                'empleado_update'   =>  $this->session->userdata('empleado_id')
            );
            $data = array_merge($data, $data_update);            
            $this->cobros_model->modificar($_GET['cobro_id'], $data);
        }else{
            $data_insert = array(
                'fecha_insert'      =>  date("Y-m-d H:i:s"),
                'empleado_insert'   =>  $this->session->userdata('empleado_id')
            );
            $data = array_merge($data, $data_insert);
            $this->cobros_model->insertar($data);    
        }
                
        $jsondata = array(
            'success'       =>  true,
            'message'       =>  'Operación correcta'
        );
        echo json_encode($jsondata);
    }
    
    public function guardar_imagen(){
        $carpeta = "images/cobros/";
        opendir($carpeta);
        $destino = $carpeta.$_FILES['imagen']['name'];
        if(copy($_FILES['imagen']['tmp_name'], $destino)){ 
            $data = array(
                'mostrar_imagen' => base_url()."images/cobros/".$_FILES['imagen']['name']
            );
            echo json_encode($data);
        }else{
            echo "problema al cargar";
        }
    }
    
    public function pdf_a4($param_cobro_id = '', $guardar_pdf = ''){
        $param_cobro_id = ($param_cobro_id != '') ? $param_cobro_id : $this->uri->segment(3);
        $guardar_pdf = ($guardar_pdf != '') ? $guardar_pdf : $this->uri->segment(4);
        $data['cobro'] = $this->cobros_model->reporte_cobro($param_cobro_id);
        $data['cuotas'] = $this->cuotas_model->select(3, '', array('venta_id' => $data['cobro']['venta_id']));
        $data['cobros'] = $this->cobros_model->select_cobros(3, '', array('venta_id' => '='.$data['cobro']['venta_id']));
        
        //var_dump($data['cobro']);exit;
        //var_dump($data['cobros']);exit;

        $html = $this->load->view("cobros/pdf_a4.php",$data,true);
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        
        $nombre_documento = 'cobro_n_'.$data['cobro']['cobro_id'];
        if($guardar_pdf == 1){
            $output = $this->pdf->output();
            file_put_contents('files/pdf/ventas/'.$nombre_documento.'.pdf', $output);
        }else{
            $this->pdf->stream("$nombre_documento.pdf",
                array("Attachment"=>0)
            );
        }
        //////////////////////////////////////////
    }
}