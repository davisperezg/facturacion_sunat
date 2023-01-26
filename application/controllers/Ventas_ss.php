<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Ventas_ss extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');
        $this->load->model('ventas_model');
        $this->load->model('venta_detalles_model');
        $this->load->model('empresas_model');
        $this->load->model('correos_model');
        $this->load->model('entidades_model');
        $this->load->model('tipo_documentos_model');
        $this->load->model('tipo_ncreditos_model');
        $this->load->model('tipo_ndebitos_model');
        $this->load->model('anulaciones_model');
        $this->load->model('cuotas_model');
        $this->load->model('venta_guias_model');
        $this->load->model('venta_anticipos_model');
        $this->load->model('productos_model');
        $this->load->model('categorias_model');
        
        $this->load->model('variables_diversas_model');
        $this->load->library('pdf');
        $this->load->helper('ayuda');
        
        require_once (APPPATH .'libraries/Numletras.php');
        require_once (APPPATH .'libraries/efactura.php');
        require_once (APPPATH .'libraries/qr/phpqrcode/qrlib.php');
    }
    
    public function pedido_virtual_nuevo(){
        $this->load->view('templates/header_sin_menu_2');
        $this->load->view('pedido_virtual/nuevo.html');
        $this->load->view('templates/footer');
    }

    public function pedido_virtual_nuevo_producto(){
        $this->load->view('templates/header_sin_menu_2');
        $this->load->view('pedido_virtual/nuevo_producto.html');
        $this->load->view('templates/footer');
    }
    
    public function detalle(){
        $this->load->view('templates/header_sin_menu_2');
        $this->load->view('pedido_virtual/detalle.html');
        $this->load->view('templates/footer');
    }
    
    public function carrito_compras(){
        $this->load->view('templates/header_sin_menu_2');
        $this->load->view('pedido_virtual/carrito_compras.html');
        $this->load->view('templates/footer');
    }
    
    public function carrito_compras_nombres(){
        $this->load->view('templates/header_sin_menu_2');
        $this->load->view('pedido_virtual/carrito_compras_nombres.html');
        $this->load->view('templates/footer');
    }
    
    public function carrito_compras_gg(){        
        $this->load->view('templates/header_sin_menu_2');
        $this->load->view('pedido_virtual/carrito_compras_gg.html');
        $this->load->view('templates/footer');
    }
    
    public function pedido_virtual(){
        $textoQR = base_url().'index.php/ventas_ss/pedido_virtual_nuevo';
        $ruta = FCPATH."images/pedido_virtual/pedido_virtual_nuevo";
        $extension = 'png';
        $this->ventas_model->QrPedidoVirtual($textoQR, $ruta, $extension);
        
        $data['empresa']    = $this->empresas_model->select(2);
        $data['rutaqr']     = $ruta.".".$extension;
        
        $html = $this->load->view("pedido_virtual/index",$data,true);
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        $this->pdf->stream("1245.pdf", array("Attachment"=>0));
    }
    
    public function operaciones(){
        $numero_pedido = $this->ventas_model->ultimoNumeroDeSerie(2, '', '') + 1;
        $entidad_id = $this->crear_entidad($_GET['nombres']);
        $data = array(
            'entidad_id'            =>  $entidad_id,
            'fecha_emision'         =>  date("Y-m-d"),
            'hora_emision'          =>  date("H:i:s"),
            'operacion'             =>  2,
            'numero'                =>  $numero_pedido,
            'moneda_id'             =>  1,
            'porcentaje_igv'        =>  $_GET['porcentaje_igv'],
            'notas'                 =>  $_GET['notas'],
            
            'total_gravada'         =>  $_GET['total_gravada'],
            'total_igv'             =>  $_GET['total_gravada'] * $_GET['porcentaje_igv'],
            'total_a_pagar'         =>  $_GET['total_gravada'] * (1 + $_GET['porcentaje_igv']),
            
            'UBLVersionID'          =>  $this->variables_diversas_model->UBLVersionID(),
            'CustomizationID'       =>  $this->variables_diversas_model->CustomizationID(),
            'fecha_insert'          =>  date("Y-m-d H:i:s"),
            'empleado_insert'       =>  1
        );

        $this->db->insert('ventas', $data);
        $venta_id = $this->ventas_model->select_max_id();
        
        for($i = 0; $i < count($_GET['producto_id']); $i++){
            $data_detalle = array(
                'venta_id'      => $venta_id,
                'producto_id'   => $_GET['producto_id'][$i],
                'producto'      => $_GET['producto'][$i],
                'cantidad'      => $_GET['cantidad'][$i],
                'precio_base'   => $_GET['precio_base'][$i],
                'tipo_igv_id'   => 1
            );
            $this->venta_detalles_model->insertar($data_detalle);
        }

        $jsondata = array(
            'numero_pedido' =>  $numero_pedido,
            'message'       =>  'Operación correcta'
        );
        echo json_encode($jsondata);
    }
    
    private function crear_entidad($entidad){
        $data = array(
            'tipo_entidad_id'   => 1,
            'entidad'           => $entidad,
            'fecha_insert'      =>  date("Y-m-d H:i:s"),
            'empleado_insert'   =>  1
        );
        $this->entidades_model->insertar($data);
        return $this->entidades_model->select_max_id();
    }        

    public function ws_porcentaje_valor_igv(){
        $data = $this->variables_diversas_model->porcentaje_valor_igv;
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_empresa(){
        $data = $this->empresas_model->select(2, array('empresa', 'foto'));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_categorias_by_id(){
        $data = $this->categorias_model->select2(2,'', array('id' => "=".$this->uri->segment(3)));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_categorias(){
        $data = $this->categorias_model->select2(3,'', array('eliminado' => "=".'0', 'id' => ' > '.'1'));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_productos_by_categoria_id(){
        $data = $this->productos_model->select2(3,'', array('categoria_id' => "=".$this->uri->segment(3), 'fecha_delete' => 'IS '." NULL"));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_productos_by_id(){
        $data = $this->productos_model->select2(2,'', array('id' => "=".$this->uri->segment(3)));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

}