<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//use Endroid\QrCode\QrCode;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

//use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
//require __DIR__ . '/../ticket/autoload.php';
//use Mike42\Escpos\Printer;
//use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
//use Mike42\Escpos\EscposImage;

class Ventas extends CI_Controller {

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
        
        $this->load->model('variables_diversas_model');
        $this->load->library('pdf');
        $this->load->helper('ayuda');
        
        require_once (APPPATH .'libraries/Numletras.php');
        require_once (APPPATH .'libraries/efactura.php');
        require_once (APPPATH .'libraries/qr/phpqrcode/qrlib.php');
        require_once (APPPATH .'libraries/fpdf/fpdf.php');
        
        $data_actual = strtotime(date("Y-m-d"));
        if($data_actual >= strtotime('2023-04-12')){
            echo "actualizar datos";exit;
        }

        $empleado_id = $this->session->userdata('empleado_id');
        if (empty($empleado_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    
    public function index(){
        $this->accesos_model->menuGeneral();
        $this->load->view('ventas/index');
        $this->load->view('templates/footer');
    }
    
    public function nuevo(){
        $this->accesos_model->menuGeneral();
        $this->load->view('ventas/nuevo');
        $this->load->view('templates/footer');
    }
    
    public function tipo_pagos(){
        $this->accesos_model->menuGeneral();
        $this->load->view('ventas/tipo_pagos');
        $this->load->view('templates/footer');
    }

    public function modal_detalle(){
        $this->load->view('ventas/modal_detalle');
    }
    
    public function modal_nueva_entidad(){
        $this->load->view('ventas/modal_nueva_entidad');
    }
    
    public function modal_nuevo_producto(){
        $this->load->view('ventas/modal_nuevo_producto');
    }
    
    public function operaciones(){
        $operacion          = $_GET['operacion'];
        $enviar_a_facturar  = $_GET['enviar_a_facturar'];
        
        $direccion_cliente  = $_GET['direccion_cliente'];
        $entidad_id         = $_GET['entidad_id'];

        $data = array(
            'entidad_id'            =>  $entidad_id,
            'direccion_cliente'     =>  $direccion_cliente,
            'fecha_emision'         =>  format_fecha_0000_00_00($_GET['fecha_emision']),
            'hora_emision'          =>  date("H:i:s"),            
            'moneda_id'             =>  $_GET['moneda_id'],
            'porcentaje_igv'        =>  $_GET['porcentaje_igv'],            
            'total_a_pagar'         =>  $_GET['total_a_pagar'],
            'UBLVersionID'          =>  $this->variables_diversas_model->UBLVersionID(),
            'CustomizationID'       =>  $this->variables_diversas_model->CustomizationID()
        );
        
        if($_GET['operacion'] == 1){
            switch ($_GET['tipo_documento_id']) {
                case '7':                
                $data = (($_GET['venta_relacionado_id'] != '') && ($_GET['venta_relacionado_id'] != null)) ? array_merge($data, array('venta_relacionado_id' => $_GET['venta_relacionado_id'], 'tipo_ncredito_id' => $_GET['tipo_ncredito_id'])) : $data;
                break;

                case '8':
                $data = (($_GET['venta_relacionado_id'] != '') && ($_GET['venta_relacionado_id'] != null)) ? array_merge($data, array('venta_relacionado_id' => $_GET['venta_relacionado_id'], 'tipo_ndebito_id' => $_GET['tipo_ndebito_id'])) : $data;
                break;
            }
        }

        $data = ($_GET['fecha_vencimiento'] != '') ? array_merge($data, array('fecha_vencimiento' => format_fecha_0000_00_00($_GET['fecha_vencimiento']))) : $data;
        $data = ($_GET['tipo_de_cambio'] != '') ? array_merge($data, array('tipo_de_cambio' => $_GET['tipo_de_cambio'])) : $data;
        
        $data = ($_GET['total_gravada'] != '') ? array_merge($data, array('total_gravada' => $_GET['total_gravada'])) : $data;
        $data = ($_GET['total_igv'] != '') ? array_merge($data, array('total_igv' => $_GET['total_igv'])) : $data;
        
        if(isset($_GET['detraccion_codigo']) && ($_GET['detraccion_codigo'] != '')){
            $data = array_merge($data, array('detraccion_codigo' => $_GET['detraccion_codigo']));    
        }
        
        if( isset($_GET['detraccion_porcentaje']) && ($_GET['detraccion_porcentaje'] != '') && ($_GET['detraccion_porcentaje'] > 0) ){
            $data = array_merge($data, array('detraccion_porcentaje' => $_GET['detraccion_porcentaje']));    
        }
        
        if( isset($_GET['retencion_porcentaje']) && ($_GET['retencion_porcentaje'] != '') && ($_GET['retencion_porcentaje'] > 0) ){
            $data = array_merge($data, array('retencion_porcentaje' => $_GET['retencion_porcentaje']));    
        }
                        
        if($_GET['PrepaidAmount'] != ''){
            $data = array_merge($data, array('PrepaidAmount' => $_GET['PrepaidAmount']));
            $data = array_merge($data, array('total_a_pagar' => $_GET['total_gravada'] + $_GET['total_igv']));            
        }

        $data = (($_GET['total_descuentos'] != '') && ($_GET['total_descuentos'] > 0)) ? array_merge($data, array('total_descuentos' => $_GET['total_descuentos'])) : $data;
        $data = ($_GET['total_gratuita'] != '') ? array_merge($data, array('total_gratuita' => $_GET['total_gratuita'])) : $data;
        $data = ($_GET['total_exportacion'] != '') ? array_merge($data, array('total_exportacion' => $_GET['total_exportacion'])) : $data;
        $data = ($_GET['total_exonerada'] != '') ? array_merge($data, array('total_exonerada' => $_GET['total_exonerada'])) : $data;
        $data = ($_GET['total_inafecta'] != '') ? array_merge($data, array('total_inafecta' => $_GET['total_inafecta'])) : $data;
        
        $array_bolsa = array('total_bolsa' => $_GET['total_bolsa'], 'bolsa_monto_unitario' => $_GET['bolsa_monto_unitario']);
        $data = ($_GET['total_bolsa'] != '') ? array_merge($data,  $array_bolsa): $data;                
        $data = ($_GET['notas'] != '') ? array_merge($data, array('notas' => $_GET['notas'])) : $data;
        
        if($operacion == 1){//solo para facturas o boletas o notas, NO para cotizaciones o pedido de venta.
            $data = ($_GET['forma_pago_id'] != '') ? array_merge($data, array('forma_pago_id' => $_GET['forma_pago_id'])) : $data;            
            if($_GET['forma_pago_id'] == '1'){
                $data = array_merge($data, array('modo_pago_id' => $_GET['modo_pago_id']));
            }
                        
            $data = ($_GET['orden_compra'] != '') ? array_merge($data, array('orden_compra' => $_GET['orden_compra'])) : $data;
            $data = ($_GET['numero_guia'] != '') ? array_merge($data, array('numero_guia' => $_GET['numero_guia'])) : $data;
            $data = ($_GET['condicion_venta'] != '') ? array_merge($data, array('condicion_venta' => $_GET['condicion_venta'])) : $data;
            $data = ($_GET['nota_venta'] != '') ? array_merge($data, array('nota_venta' => $_GET['nota_venta'])) : $data;
            $data = ($_GET['numero_pedido'] != '') ? array_merge($data, array('numero_pedido' => $_GET['numero_pedido'])) : $data;
        }        
        
        $venta_id = (isset($_GET['venta_id']) && ($_GET['venta_id'] != '')) ? $_GET['venta_id'] : null;
                
        //PARA INSERTAR
        //enviar_a_facturar = 1, SE USA para crear facturas o boletas apartir de una cotizacion o nota de venta
        if(($enviar_a_facturar == 1) || ($venta_id == null)){
            if($enviar_a_facturar == 1) $operacion = 1;
            
            $data = ($_GET['forma_pago_id'] != '') ? array_merge($data, array('forma_pago_id' => $_GET['forma_pago_id'])) : $data;            
            if($_GET['forma_pago_id'] == '1'){
                $data = array_merge($data, array('modo_pago_id' => $_GET['modo_pago_id']));
            }
            
            $tipo_documento_id = ($operacion == 1) ? $_GET['tipo_documento_id'] : '';
            $serie = ($operacion == 1) ? $_GET['serie'] : '';
            $data_insert = array(
                'numero'            =>  $this->ventas_model->ultimoNumeroDeSerie($operacion, $tipo_documento_id, $serie) + 1,
                'fecha_insert'      =>  date("Y-m-d H:i:s"),
                'empleado_insert'   =>  $this->session->userdata('empleado_id'),
                'operacion'         =>  $operacion,
            );
            $data = array_merge($data, $data_insert);
            
            if($operacion == 1){
                $data_operacion = array(
                    'tipo_documento_id'     =>  $_GET['tipo_documento_id'],
                    'serie'                 =>  $_GET['serie'],
                    'tipo_operacion'        =>  $_GET['tipo_operacion'],
                );
                $data = array_merge($data, $data_operacion);                
            }

            //este select impedirá que se guarden multiples registros(Boot medio pendex)
            $select_id = $this->ventas_model->select(1, array('id'), 
                        array(
                            'tipo_documento_id'     =>  $tipo_documento_id,
                            'serie'                 =>  $serie,
                            'numero'                =>  $_GET['numero'],
                        )
                    );
            if($select_id == ''){
                //actualizo para operaciones q contengan anticipos.
                if( isset($_GET['tipo_operacion']) && ($_GET['tipo_operacion'] == $this->variables_diversas_model->valor_para_ventas_con_anticipo)){
                    $data = array_merge($data, array('tipo_operacion'   =>  '0101'));
                }
                ////
                
                $this->db->insert('ventas', $data);
                $venta_id_cotizacion = $venta_id;
                $venta_id = $this->ventas_model->select_max_id();
                
                //para insertar anticipos.
                if($operacion == 1){
                    if(isset($_GET['documento_id_anticipos']) && (count($_GET['documento_id_anticipos']) > 0) && ($_GET['tipo_operacion'] == $this->variables_diversas_model->valor_para_ventas_con_anticipo)){
                        for($i =0; $i < count($_GET['documento_id_anticipos']); $i++){                            
                            $this->venta_anticipos_model->insertar(array('venta_id' => $venta_id, 'anticipo_id' => $_GET['documento_id_anticipos'][$i]));
                        }
                        $data = array_merge($data, array('tipo_operacion'   =>  '0101'));
                    }
                }                                
                //cuano se envia a facturar la cotizacion o nota de venta. Se modifica el registro de la nota de venta o cotizacion
                if($enviar_a_facturar == 1){
                    $data_operacion = array(
                        'operacion_id'     =>  $venta_id_cotizacion,
                    );                    
                    $this->ventas_model->modificar($venta_id, $data_operacion);                    
                }

                for($i = 0; $i < count($_GET['producto_id']); $i++){
                    //si no existe producto lo creo.
                    $producto_id = $_GET['producto_id'][$i];                    
                    
                    if($producto_id == ''){
                        $data_producto = array(
                            'codigo_sunat'      =>  '-',
                            'codigo'            =>  '-',
                            'producto'          =>  $_GET['producto'][$i],
                            'precio_base_venta' =>  $_GET['precio_base'][$i],
                            'stock_inicial'     =>  1,
                            'stock_actual'      =>  1,
                            'categoria_id'      =>  1,
                            'unidad_id'         =>  $_GET['unidad'][$i],
                            'fecha_insert'      =>  date("Y-m-d H:i:s"),
                            'empleado_insert'   =>  $this->session->userdata('empleado_id')                            
                        );
                        $this->productos_model->insertar($data_producto);
                        $producto_id = $this->productos_model->max_producto_id();
                    }                    
                    
                    $data_detalle = array(
                        'venta_id'      => $venta_id,
                        'producto_id'   => $producto_id,
                        'producto'      => $_GET['producto'][$i],
                        'cantidad'      => $_GET['cantidad'][$i],
                        'precio_base'   => $_GET['precio_base'][$i],
                        'tipo_igv_id'   => $_GET['tipo_igv_id'][$i]
                    );                    
                    $data_detalle = ($_GET['impuesto_bolsa'][$i] != '') ? array_merge($data_detalle, array('impuesto_bolsa' => $_GET['impuesto_bolsa'][$i])) : $data_detalle;
                    $data_detalle = (($_GET['descuento'][$i] != '') && ($_GET['descuento'][$i] > 0)) ? array_merge($data_detalle, array('descuento' => $_GET['descuento'][$i])) : $data_detalle;

                    $this->venta_detalles_model->insertar($data_detalle);
                    // ponemos el "-" a $_GET['cantidad'][$i], ya q vamos a devolver el stock en el update
                    $this->productos_model->variar_stock($producto_id, -$_GET['cantidad'][$i]);
                }
            }
        }else{//PARA MODIFICAR
            $venta_id = $_GET['venta_id'];            
            //actualizo a null datos de montos (total_igv, total_a_pagar, etc...) en la cabecera
            $this->ventas_model->setear_datos_totales($venta_id);
                        
            $data_update = array(
                'numero'            =>  $_GET['numero'],
                'fecha_update'      =>  date("Y-m-d H:i:s"),
                'empleado_update'   =>  $this->session->userdata('empleado_id')
            );
            $data = array_merge($data, $data_update);            
                                    
            if($operacion == 1){//solo para facturas o boletas o notas, NO para cotizaciones o pedido de venta.
                $data_operacion = array(            
                        'tipo_operacion'        =>  $_GET['tipo_operacion'],
                );
                $data = array_merge($data, $data_operacion); 
            }
            
            $this->ventas_model->modificar($venta_id, $data);
            
            //borro el detalle guardado para insertar items del formulario que viene
            $this->devolver_stock($venta_id);
            $this->venta_detalles_model->delete_venta_id($venta_id);
            for($i = 0; $i < count($_GET['producto_id']); $i++){
                $data_detalle = array(
                    'venta_id' => $venta_id,
                    'producto_id' => $_GET['producto_id'][$i],
                    'producto' => $_GET['producto'][$i],
                    'cantidad' => $_GET['cantidad'][$i],
                    'precio_base' => $_GET['precio_base'][$i],
                    'tipo_igv_id' => $_GET['tipo_igv_id'][$i]
                );
                $data_detalle = ($_GET['impuesto_bolsa'][$i] != '') ? array_merge($data_detalle, array('impuesto_bolsa' => $_GET['impuesto_bolsa'][$i])) : $data_detalle;
                $data_detalle = (($_GET['descuento'][$i] != '') && ($_GET['descuento'][$i] > 0)) ? array_merge($data_detalle, array('descuento' => $_GET['descuento'][$i])) : $data_detalle;
                
                $this->venta_detalles_model->insertar($data_detalle);
                // ponemos el "-" a $_GET['cantidad'][$i], ya q vamos a devolver el stock en el update
                $this->productos_model->variar_stock($_GET['producto_id'][$i], -$_GET['cantidad'][$i]);
            }
            if($operacion == 1){//solo para facturas o boletas o notas, NO para cotizaciones o pedido de venta.
                $nombre_archivo = $_GET['ruc'].'-0'.$_GET['tipo_documento_id'].'-'.$_GET['serie'].'-'.$_GET['numero'];        
                $this->delete_file($nombre_archivo);
            }
        }
        //PARA ADJUNTAR LA GUIA A LA FACTURA
//        if($_GET['guia_id'] != ''){
//            $this->venta_guias_model->insertar(array('venta_id' => $venta_id, 'guia_id' => $_GET['guia_id']));
//        }        
        
        //actualizo direccion cliente.
        $this->entidades_model->actualizoDireccion($entidad_id, $_GET['select_evento'], $_GET['direccion_cliente_incial'], $direccion_cliente);        
        
        $jsondata = array(
            'venta_id'      =>  $venta_id,
            'success'       =>  true,
            'message'       =>  'Operación correcta'
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }    
    
    public function devolver_stock($venta_id){
        $items = $this->venta_detalles_model->select(3, array('id', 'venta_id', 'producto_id', 'cantidad'), array('venta_id' => $venta_id));        
        foreach ($items as $value){
            $this->productos_model->variar_stock($value['producto_id'], $value['cantidad']);
        }        
    }

    public function pdf_a4($param_venta_id = '', $guardar_pdf = ''){
        $this->ventas_model->pdf_a4($param_venta_id = '', $guardar_pdf = '');
    }
    
    public function pdf_a5($param_venta_id = '', $guardar_pdf = ''){
        $this->ventas_model->pdf_a5($param_venta_id = '', $guardar_pdf = '');
    }
    
    public function pdf_ticket(){
        $venta_id = $this->uri->segment(3);        
        $this->ventas_model->pdf_ticket($venta_id);                
    }
    
    //$pdf->Cell(60,10,'Hecho con FPDF.',0,1,'C');
    //0 indica sin borde
    //1 indica salto de linea
    //C centrado
    public function fpdf_ticket(){
        $venta_id   = $this->uri->segment(3);
        
        $empresa    = $this->empresas_model->select(2);
        $cabecera   = $this->ventas_model->query_cabecera($venta_id);
        $detalle    = $this->venta_detalles_model->query_detalle($venta_id);
        $rutaqr     = $this->ventas_model->GetImgQr($cabecera, $empresa);
        
        $num = new Numletras();
        $totalVenta = explode(".", $cabecera['total_a_pagar']);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = 'Son: '.$totalLetras.' con '.$totalVenta[1].'/100 '.$cabecera['moneda'];
        
        if($cabecera['tipo_ncredito_id'] != null){
            $data['nota_credito'] = $this->datos_nc($venta_id);
        }        
        
        $fijo = 233 + 10;
        $ancho = 8.4;
        $numero_filas = count($detalle);        
        $total_y = $fijo + $ancho * $numero_filas; 
        
        $pdf = new FPDF('P', 'mm', array(80, $total_y));
        $pdf->SetMargins(2, 2, 2);
        $pdf->AddPage();
        
        $tamano_x = 60;
        $tamano_y = $this->variables_diversas_model->dimension_proporcion($rutaqr, $tamano_x);        
        $ruta_foto = FCPATH."images/empresas/".$empresa['foto'];                

        $pdf->Image($ruta_foto,10,0,$tamano_x,$tamano_y);
        $pdf->Ln($tamano_y);        
        
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(74, 6, $empresa["nombre_comercial"], 'B', 1, 'C');
        
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(74, 6, $empresa["empresa"], 0, 1, 'C');
        
        $pdf->Cell(74,6,"RUC: ".$empresa["ruc"],0,1,'C');
        $pdf->MultiCell(74,5, utf8_decode($empresa["domicilio_fiscal"]));
        $pdf->Cell(74,1,"-----------------------------------------------------------------------------",0,0,'C');
        $pdf->Ln(4);
        
        switch ($cabecera['operacion']) {
            case 1:
                switch ($cabecera['tipo_documento_id']) {
                    case 1:
                        $tipo_documento = "FACTURA";
                        break;
                    case 3:
                        $tipo_documento = "BOLETA";
                        break;
                    case 7:
                        $tipo_documento = "NOTA DE CREDITO";
                        $data['tipo_nota'] = '-';
                        $data['comp_adjunto'] = '-';
                        break;
                    case 8:
                        $tipo_documento = "NOTA DE DEBITO";
                        $data['tipo_nota'] = '-';
                        $data['comp_adjunto'] = '-';
                        break;
                }                        
                break;
            case 2:
              $tipo_documento = 'NOTA DE VENTA';
              break;
            case 3:
              $tipo_documento = 'COTIZACION';
              break;           
        }        
        
        $pdf->Cell(74,7,utf8_decode($tipo_documento." DE VENTA ELECTRÓNICA"),0,0,'L');
        $pdf->Ln(5);
        $pdf->Cell(74,7,$cabecera["serie"]."-".$cabecera["numero"],0,0,'L');
        $pdf->Ln(5);
        $pdf->Cell(74,7,"Fecha/hora emision:".$cabecera["fecha_emision"],0,0,'L');
        $pdf->Ln(5);
        $pdf->Cell(74,7,"Vendedor:".$this->session->userdata('usuario'). " ". $this->session->userdata('apellido_paterno'),0,0,'L');
        $pdf->Ln(5);
        $pdf->Cell(74,1,"-----------------------------------------------------------------------------",0,0,'C');        
        $pdf->Ln(4);
        
        $pdf->MultiCell(74,5, utf8_decode("Cliente: " . $cabecera["entidad"]));
        $pdf->Cell(74, 7,utf8_decode($cabecera['tipo_entidad'] . ": ". $cabecera['numero_documento']),0,1,'L');
        $pdf->MultiCell(74,5, utf8_decode("DIRECCIÓN: ".$cabecera['direccion_entidad']));
        $pdf->Cell(74,1,"-----------------------------------------------------------------------------",0,1,'C');
        
        
        $pdf->Cell(16,7,"Productos",'B',0,'L');
        $pdf->Cell(45,7,"",0,0,'L');
        $pdf->Cell(9,7,"Total",'B',0,'R');
        $pdf->Ln(4);
        foreach($detalle as $value){
            $impuesto_bolsa_item = ($cabecera['total_bolsa'] != null) ? number_format($value['impuesto_bolsa']*$value['cantidad'],2) : 0;
            $impuesto = ($value['tipo_igv_id'] == 1) ? (1+$cabecera['porcentaje_igv']) : 1;
            
            $pdf->Cell(60,10,utf8_decode($value['producto']),0,0,'L');
            $pdf->Cell(10,10,number_format(($value['cantidad']*($value['precio_base']*$impuesto) + $impuesto_bolsa_item), 2),0,0,'R');
            $pdf->Ln(4);
            
            $pdf->Cell(12,10,number_format($value['precio_base']*$impuesto,2)." x ".$value['cantidad'],0,0,'L');
            $pdf->Ln(4);
        }
        $pdf->Ln(4);
        $pdf->Cell(74,1,"-----------------------------------------------------------------------------",0,1,'C');
        
        $pdf->SetFont('Arial','',11);
        if($cabecera['total_gravada'] != null){
            $pdf->Cell(30,7,"",0,0,'R');
            $pdf->Cell(20,7,"Gravada: ",0,0,'L');
            $pdf->Cell(20,7,$cabecera['simbolo_moneda']." ".$cabecera['total_gravada'],0,0,'R');
            $pdf->Ln(6);
        }
        
        if($cabecera['total_gravada'] != null){
            $pdf->Cell(30,7,"",0,0,'R');
            $pdf->Cell(20,7,"IGV: ".($cabecera['porcentaje_igv']* 100)."% ",0,0,'L');
            $pdf->Cell(20,7,$cabecera['simbolo_moneda']." ".$cabecera['total_igv'],0,0,'R');
            $pdf->Ln(7);
        }
        
        if($cabecera['total_a_pagar'] != null){            
            $pdf->Cell(30,7,"",0,0,'R');
            $pdf->Cell(20,7,"Total:",0,0,'L');
            $pdf->Cell(20,7,$cabecera['simbolo_moneda']." ".$cabecera['total_a_pagar'],0,1,'R');
            $pdf->Ln(4);
        }
        
        $pdf->MultiCell(0,5, utf8_decode($totalLetras));
                
        //$pdf->Ln(20);
        $tamano_x = 30;
        $tamano_y = $this->variables_diversas_model->dimension_proporcion($rutaqr, $tamano_x);
        $pdf->Image($rutaqr, 25,$total_y - $tamano_y - 10,$tamano_x,$tamano_y);
        
        $pdf->Ln(5);
        $pdf->SetFont('Arial','B',7);
        $pdf->MultiCell(74,5, utf8_decode("EMITIDO MEDIANTE PROVEEDOR AUTORIZADO POR LA SUNAT RESOLUCION N.° 097- 2012/SUNAT"));
        
        $pdf->Output();                        
    }
    
    public function pdf_58(){
        $venta_id = $this->uri->segment(3);
        $this->ventas_model->pdf_58($venta_id);                
    }

    public function crear_xml($venta_id){
        $venta = $this->ventas_model->query_cabecera($venta_id);
        
        $anticipos = array();
        if($venta['venta_id'] != null && $venta['venta_id'] != ''){
            $anticipos = $this->venta_anticipos_model->select_anticipo_ventas(3, array('ventas.serie serie, ventas.numero numero, ventas.total_igv, ventas.total_a_pagar total_a_pagar, ventas.id'), array('venta_anticipos.venta_id' => $venta['venta_id']));
        }
        
        $empresa = $this->empresas_model->query_standar();
        $detalle = $this->venta_detalles_model->query_detalle($venta_id);
        $cuotas = $this->cuotas_model->select(3, '', array('venta_id' => $venta_id), ' ORDER BY id DESC');
        
        $venta_relacionado = '';
        $motivo_nc = '';
        $motivo_nd = '';
        if(( ($venta['tipo_documento_id'] == 7) || ($venta['tipo_documento_id'] == 8) ) && ($venta['venta_relacionado_id'] != null)){
            $venta_relacionado = $this->ventas_model->venta_documento($venta['venta_relacionado_id']);
            
            if($venta['tipo_documento_id'] == 7){
                $motivo_nc = $this->tipo_ncreditos_model->select(2,'',array('id' => $venta['tipo_ncredito_id']));
            }            
            if($venta['tipo_documento_id'] == 8){
                $motivo_nd = $this->tipo_ndebitos_model->select(2,'',array('id' => $venta['tipo_ndebito_id']));
            }            
        }
        
        $xml = $this->desarrollo_xml($empresa, $venta, $detalle, $venta_relacionado, $motivo_nc, $motivo_nd, $cuotas, $anticipos);
        $nombre_archivo = $empresa['ruc'].'-'.$venta['tipo_documento_codigo'].'-'.$venta['serie'].'-'.$venta['numero'];                
        
        $nombre = FCPATH."/files/facturacion_electronica/XML/".$nombre_archivo.".xml";
        $archivo = fopen($nombre, "w+");
        fwrite($archivo, utf8_decode($xml));
        fclose($archivo);        
                
        return array(
            'nombre_archivo'=> $nombre_archivo, 
            'modo'          => $empresa['modo'],
            'venta_id'      => $venta_id,
            'empresa'       => $empresa
        );
    }
    
    public function delete_file($nombre_archivo){
        //si existe lo elimino xml
        $nombre = FCPATH."files/facturacion_electronica/XML/".$nombre_archivo.".xml";
        if (file_exists($nombre)) {
           unlink($nombre);
        }
        
        //si existe lo elimino xml firmado
        $nombre_firmado = FCPATH."files/facturacion_electronica/FIRMA/".$nombre_archivo.".xml";                
        if (file_exists($nombre_firmado)) {
           unlink($nombre_firmado);
        }
        
        //si existe lo elimino zip
        $nombre_zip = FCPATH."files/facturacion_electronica/FIRMA/".$nombre_archivo.".zip";                
        if (file_exists($nombre_zip)) {
           unlink($nombre_zip);
        }        
    }
       
    public function firmar_xml($name_file, $entorno, $baja = ''){
        $carpeta_baja = ($baja != '') ? 'BAJA/':'';
        $carpeta = "files/facturacion_electronica/$carpeta_baja";
        $dir = base_url().$carpeta."XML/".$name_file;
        $xmlstr = file_get_contents($dir);

        $domDocument = new \DOMDocument();
        $domDocument->loadXML($xmlstr);
        $factura  = new Factura();
        $xml = $factura->firmar($domDocument, '', $entorno);
        $content = $xml->saveXML();
        file_put_contents($carpeta."FIRMA/".$name_file, $content);              
    }
    
    public function ws_sunat($venta_id, $empresa, $nombre_archivo){
        
        //enviar a Sunat
        //cod_1: Select web Service: 1 factura, boletas --- 9 es para guias
        //cod_2: Entorno:  0 Beta, 1 Produccion
        //cod_3: ruc
        //cod_4: usuario secundario USU(segun seha beta o producción)
        //cod_5: usuario secundario PASSWORD(segun seha beta o producción)
        //cod_6: Accion:   1 enviar documento a Sunat --  2 enviar a anular  --  3 enviar ticket
        //cod_7: serie de documento
        //cod_8: numero ticket
        $user_sec_usu = ($empresa['modo'] == 1) ? $empresa['usu_secundario_produccion_user'] : $empresa['usu_secundario_prueba_user'];
        $user_sec_pass = ($empresa['modo'] == 1) ? $empresa['usu_secundario_produccion_password'] : $empresa['usu_secundario_prueba_passoword'];
        $url = base_url()."ws_sunat/index.php?numero_documento=".$nombre_archivo."&cod_1=1&cod_2=".$empresa['modo']."&cod_3=".$empresa['ruc']."&cod_4=".$user_sec_usu."&cod_5=".$user_sec_pass."&cod_6=1";
        
        $data = file_get_contents($url);       
        $info = json_decode($data, TRUE);
        
        
        $respuesta_codigo = '';
        $respuesta_mensaje = '';
        if($info['error_existe'] == 0){
            $respuesta_sunat = $this->leerRespuestaSunat($nombre_archivo.".xml");
            if($respuesta_sunat != null){
                $this->ventas_model->modificar($venta_id, $respuesta_sunat);
                $this->ventas_model->errores_sunat($respuesta_sunat['respuesta_sunat_codigo'], $venta_id);
            }
            //var_dump($respuesta_sunat);
            $respuesta_mensaje = ($respuesta_sunat != null) ? $respuesta_sunat['respuesta_sunat_descripcion']: '';
            $respuesta_codigo = ($respuesta_sunat != null) ? $respuesta_sunat['respuesta_sunat_codigo']: '';
        }
        $jsondata = array(
            'success'       =>  true,
            'codigo'        =>  $respuesta_codigo,
            'error_existe'  =>  $info['error_existe'],
            'message'       =>  $respuesta_mensaje.$info['error_mensaje']
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }
    
    public function enviar_sunat(){                
        //en caso ya esté creado el xml, se enviará directo a la ws
        $nombre_archivo = $this->uri->segment(4);
        $ruta_xml = FCPATH."files/facturacion_electronica/FIRMA/".$nombre_archivo.".xml";
        if(file_exists($ruta_xml)){
            unlink($ruta_xml);
            $venta_id = $this->uri->segment(3);                        
            
            $rpta = $this->crear_xml($this->uri->segment(3));            
            $this->firmar_xml($rpta['nombre_archivo'].".xml", $rpta['modo']);
            
            $empresa = $this->empresas_model->query_standar();
            $venta = $this->ventas_model->query_cabecera($venta_id);
            $this->ws_sunat($venta_id, $empresa, $nombre_archivo);
        }else{
            $rpta = $this->crear_xml($this->uri->segment(3));            
            $this->firmar_xml($rpta['nombre_archivo'].".xml", $rpta['modo']);
            $this->ws_sunat($rpta['venta_id'], $rpta['empresa'], $rpta['nombre_archivo']);
        }
    }
            
    function desarrollo_xml($empresa, $venta, $detalles, $venta_relacionado, $motivo_nc, $motivo_nd, $cuotas, $anticipos = array()){
        
        $totalVenta = explode(".",  $venta['total_a_pagar']);
        if($totalVenta[0] == 0){
            $venta['total_letras'] = '0 '.$venta['moneda'];
        }else{
            $num = new Numletras();        
            $totalLetras = $num->num2letras($totalVenta[0]);
            $venta['total_letras'] = $totalLetras.' con '.$totalVenta[1].'/100 '.$venta['moneda'];
        }                
        
        $linea_inicio   = '';
        $linea_fin   = '';
        $tag_total_pago = '';
        $dato_nc = '';
        switch ($venta['tipo_documento_id']) {
            case '1':
            $linea_inicio   = '<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
            $linea_fin   = 'Invoice';
            $InvoiceTypeCode = '<cbc:InvoiceTypeCode listID="' . $venta['tipo_operacion'] . '" listAgencyName="PE:SUNAT" listName="Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01" name="Tipo de Operacion" listSchemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo51">' . $venta['tipo_documento_codigo'] . '</cbc:InvoiceTypeCode>';
            $tag_total_pago = 'LegalMonetaryTotal';
            break;

            case '3':
            $linea_inicio   = '<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
            $linea_fin   = 'Invoice';
            $InvoiceTypeCode = '<cbc:InvoiceTypeCode listID="' . $venta['tipo_operacion'] . '" listAgencyName="PE:SUNAT" listName="Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01" name="Tipo de Operacion" listSchemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo51">' . $venta['tipo_documento_codigo'] . '</cbc:InvoiceTypeCode>';
            $tag_total_pago = 'LegalMonetaryTotal';
            break;

            case '7':
            $linea_inicio   = '<CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">';
            $linea_fin   = 'CreditNote';
            $InvoiceTypeCode = '';
            
            $dato_nc = '<cac:DiscrepancyResponse>
                <cbc:ReferenceID>'.$venta_relacionado['serie'].'-'.$venta_relacionado['numero'].'</cbc:ReferenceID>
                <cbc:ResponseCode>'.$motivo_nc['codigo'].'</cbc:ResponseCode>
                <cbc:Description>'.$motivo_nc['tipo_ncredito'].'</cbc:Description>
            </cac:DiscrepancyResponse>
            <cac:BillingReference>
                <cac:InvoiceDocumentReference>
                    <cbc:ID>'.$venta_relacionado['serie'].'-'.$venta_relacionado['numero'].'</cbc:ID>
                    <cbc:DocumentTypeCode>'.$venta_relacionado['codigo'].'</cbc:DocumentTypeCode>
                </cac:InvoiceDocumentReference>
            </cac:BillingReference>';
            $tag_total_pago = 'LegalMonetaryTotal';
            break;

            case '8':
            $linea_inicio   = '<DebitNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:DebitNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
            $linea_fin   = 'DebitNote';
            $InvoiceTypeCode = '';
            $dato_nc = '<cac:DiscrepancyResponse>
                <cbc:ReferenceID>'.$venta_relacionado['serie'].'-'.$venta_relacionado['numero'].'</cbc:ReferenceID>
                <cbc:ResponseCode>'.$motivo_nd['codigo'].'</cbc:ResponseCode>
                <cbc:Description>'.$motivo_nd['tipo_ndebito'].'</cbc:Description>
            </cac:DiscrepancyResponse>
            <cac:BillingReference>
                <cac:InvoiceDocumentReference>
                    <cbc:ID>'.$venta_relacionado['serie'].'-'.$venta_relacionado['numero'].'</cbc:ID>
                    <cbc:DocumentTypeCode>'.$venta_relacionado['codigo'].'</cbc:DocumentTypeCode>
                </cac:InvoiceDocumentReference>
            </cac:BillingReference>';
            $tag_total_pago = 'RequestedMonetaryTotal';
            break;
        }                
        
        $xml =  '<?xml version="1.0" encoding="ISO-8859-1" standalone="no"?>'.$linea_inicio.'<ext:UBLExtensions>
                        <ext:UBLExtension>
                            <ext:ExtensionContent></ext:ExtensionContent>
                        </ext:UBLExtension>
                    </ext:UBLExtensions>
                    <cbc:UBLVersionID>'.$venta['UBLVersionID'].'</cbc:UBLVersionID>
                    <cbc:CustomizationID>'.$venta['CustomizationID'].'</cbc:CustomizationID>
                    <cbc:ID>'.$venta['serie'].'-'.$venta['numero'].'</cbc:ID>
                    <cbc:IssueDate>'.$venta['fecha_emision_sf'].'</cbc:IssueDate>
                    <cbc:IssueTime>'.$venta['hora_emision'].'</cbc:IssueTime>';
                    if(($venta['fecha_vencimiento'] != null) && (($venta['tipo_documento_id'] == 1) || ($venta['tipo_documento_id'] == 3))) {
                        $xml .= '<cbc:DueDate>' . $venta['fecha_vencimiento_sf'] . '</cbc:DueDate>';
                    };
        $xml .= $InvoiceTypeCode.'<cbc:Note languageLocaleID="1000">'.$venta['total_letras'].'</cbc:Note>
                <cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listName="Currency" listAgencyName="United Nations Economic Commission for Europe">'.$venta['abrstandar'].'</cbc:DocumentCurrencyCode>'.$dato_nc;
        
        //--Para anticipos INICIO--
        $ij = 1;
        foreach ($anticipos as $value_anticipos){
            //codigo documento relacionado (Catalogo 12 Sunat)
            if(substr($value_anticipos['serie'], 0, 1) == 'F'){
                $codigo_documento_relacionado = '02';
            }elseif(substr($value_anticipos['serie'], 0, 1) == 'B'){
                $codigo_documento_relacionado = '03';
            }
            $xml .= '<cac:AdditionalDocumentReference>
                    <cbc:ID schemeID="01">'.$value_anticipos['serie'].'-'.$value_anticipos['numero'].'</cbc:ID>
                    <cbc:DocumentTypeCode listAgencyName="PE:SUNAT" listName="Documento Relacionado" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo12">'.$codigo_documento_relacionado.'</cbc:DocumentTypeCode>
                    <cbc:DocumentType>ANTICIPO</cbc:DocumentType>
                    <cbc:DocumentStatusCode listAgencyName="PE:SUNAT" listName="Anticipo">'.$ij.'</cbc:DocumentStatusCode>
                    <cac:IssuerParty>
                            <cac:PartyIdentification>
                                    <cbc:ID schemeAgencyName="PE:SUNAT" schemeID="'.$venta['codigo_tipo_entidad'].'" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$venta['numero_documento'].'</cbc:ID>
                            </cac:PartyIdentification>
                    </cac:IssuerParty>
                </cac:AdditionalDocumentReference>';
            $ij ++;
        }
        //--Para anticipos FIN--
                
        /////--Orden de compra  -- INICIO
        if(($venta['orden_compra'] != null) && (($venta['tipo_documento_id'] == 1) || ($venta['tipo_documento_id'] == 3))) {
                $xml .= '<cac:OrderReference>
                <cbc:ID>'.$venta['orden_compra'].'</cbc:ID>
                </cac:OrderReference>';
        };
        /////--Orden de compra  -- FIN
                    
                    $xml .= '<cac:Signature>
                            <cbc:ID>'.$empresa['ruc'].'</cbc:ID>
                            <cac:SignatoryParty>
                                <cac:PartyIdentification>
                                    <cbc:ID>'.$empresa['ruc'].'</cbc:ID>
                                </cac:PartyIdentification>
                                <cac:PartyName>
                                    <cbc:Name><![CDATA['.$empresa['empresa'].']]></cbc:Name>
                                </cac:PartyName>
                            </cac:SignatoryParty>
                            <cac:DigitalSignatureAttachment>
                                <cac:ExternalReference>
                                    <cbc:URI>'.$empresa['ruc'].'</cbc:URI>
                                </cac:ExternalReference>
                            </cac:DigitalSignatureAttachment>
                        </cac:Signature>                        
                        <cac:AccountingSupplierParty>
                            <cac:Party>
                                <cac:PartyIdentification>
                                    <cbc:ID schemeID="6">'.$empresa['ruc'].'</cbc:ID>
                                </cac:PartyIdentification>
                                <cac:PartyName>
                                    <cbc:Name><![CDATA['.$empresa['nombre_comercial'].']]></cbc:Name>                                    
                                </cac:PartyName>
                                <cac:PartyLegalEntity>
                                    <cbc:RegistrationName><![CDATA['.$empresa['empresa'].']]></cbc:RegistrationName>
                                    <cac:RegistrationAddress>
                                        <cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">'.$empresa['ubigeo'].'</cbc:ID>
                                        <cbc:AddressTypeCode listAgencyName="PE:SUNAT" listName="Establecimientos anexos">0000</cbc:AddressTypeCode>
                                        <cbc:CityName>'.$empresa['provincia'].'</cbc:CityName>
                                        <cbc:CountrySubentity>'.$empresa['departamento'].'</cbc:CountrySubentity>
                                        <cbc:District>'.$empresa['distrito'].'</cbc:District>
                                        <cac:AddressLine>
                                            <cbc:Line>'.$empresa['domicilio_fiscal'].'</cbc:Line>
                                        </cac:AddressLine>
                                        <cac:Country>
                                            <cbc:IdentificationCode listID="ISO 3166-1" listAgencyName="United Nations Economic Commission for Europe" listName="Country">PE</cbc:IdentificationCode>
                                        </cac:Country>
                                    </cac:RegistrationAddress>
                                </cac:PartyLegalEntity>
                            </cac:Party>
                        </cac:AccountingSupplierParty>                        
                        <cac:AccountingCustomerParty>
                            <cac:Party>
                                <cac:PartyIdentification>
                                    <cbc:ID schemeID="'.$venta['codigo_tipo_entidad'].'" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$venta['numero_documento'].'</cbc:ID>
                                </cac:PartyIdentification>
                                <cac:PartyLegalEntity>
                                    <cbc:RegistrationName><![CDATA['.$venta['entidad'].']]></cbc:RegistrationName>
                                </cac:PartyLegalEntity>
                            </cac:Party>
                        </cac:AccountingCustomerParty>'; 
                    
                    
            ////////////////////////////////////////INICO - DETRACCION
            if(($venta['detraccion_porcentaje'] != '') && ($venta['detraccion_porcentaje'] != null) && ($venta['detraccion_porcentaje'] > 0)){
                $xml .= '<cac:PaymentTerms>
                        <cbc:ID schemeName="SUNAT:Codigo de detraccion" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo54">'.$venta['detraccion_codigo'].'</cbc:ID>
                        <cbc:PaymentPercent>'.$venta['detraccion_porcentaje'].'</cbc:PaymentPercent>
                        <cbc:Amount currencyID="PEN">'.$venta['total_a_pagar']*$venta['detraccion_porcentaje']*(0.01).'</cbc:Amount>
                    </cac:PaymentTerms>';
            }                        
            ////////////////////////////////////////FIN - DETRACCION
                        
                       
            /////////////Forma de pago  --  INICIO   - solo para facturas y boletas.
            if( ($venta['tipo_documento_id'] == 1) || ($venta['tipo_documento_id'] == 3) || (($venta['tipo_documento_id'] == 7) && $venta['tipo_ncredito_id'] == 13)){
                if($venta['forma_pago_id'] == 1){
                    $xml .= '<cac:PaymentTerms>
                                <cbc:ID>FormaPago</cbc:ID>
                                <cbc:PaymentMeansID>Contado</cbc:PaymentMeansID>
                            </cac:PaymentTerms>';
                }
                if($venta['forma_pago_id'] == 2){                
                    $total_pagar_credito = ($venta['tipo_ncredito_id'] == '13') ? number_format($cuotas[0]['monto'], 2, '.', '') : number_format($venta['total_a_pagar'], 2, '.', '');                

                    $xml .= '<cac:PaymentTerms>
                            <cbc:ID>FormaPago</cbc:ID>
                            <cbc:PaymentMeansID>Credito</cbc:PaymentMeansID>
                            <cbc:Amount currencyID="'.$venta['abrstandar'].'">' . $total_pagar_credito . '</cbc:Amount>
                        </cac:PaymentTerms>';

                    $contar_cuota = 1;
                    foreach($cuotas as $value_cuotas){
                        $xml .= '<cac:PaymentTerms>
                                    <cbc:ID>FormaPago</cbc:ID>
                                    <cbc:PaymentMeansID>Cuota00'.$contar_cuota.'</cbc:PaymentMeansID>
                                    <cbc:Amount currencyID="'.$venta['abrstandar'].'">' . number_format($value_cuotas['monto'], 2, '.', '') . '</cbc:Amount>
                                    <cbc:PaymentDueDate>' . $value_cuotas['fecha_cuota'] . '</cbc:PaymentDueDate>
                                </cac:PaymentTerms>';
                        $contar_cuota ++;
                    }
                }
            }
            
            /////////////Forma de pago  --  FIN
            
            
            //--Para anticipos INICIO--
            $ij = 1;
            foreach ($anticipos as $value_anticipos){
                $xml .= '<cac:PrepaidPayment>
                            <cbc:ID schemeAgencyName="PE:SUNAT" schemeName="Anticipo">'.$ij.'</cbc:ID>
                            <cbc:PaidAmount currencyID="'.$venta['abrstandar'].'">'.$value_anticipos['total_a_pagar'].'</cbc:PaidAmount>
                        </cac:PrepaidPayment>';                
            }                        
            //--Para anticipos FIN-- 
            
            if( count($anticipos) > 0){
                $xml .= '<cac:AllowanceCharge>
                <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
                <cbc:AllowanceChargeReasonCode listAgencyName="PE:SUNAT" listName="Cargo/descuento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo53">04</cbc:AllowanceChargeReasonCode>
                <cbc:Amount currencyID="PEN">'.($value_anticipos['total_a_pagar'] - $value_anticipos['total_igv']).'</cbc:Amount>
                <cbc:BaseAmount currencyID="PEN">'.($value_anticipos['total_a_pagar'] - $value_anticipos['total_igv'] + $venta['total_a_pagar'] - $venta['total_igv']).'</cbc:BaseAmount>
                </cac:AllowanceCharge>';
            }
            $anticipo_igv = isset($value_anticipos['total_igv']) ? $value_anticipos['total_igv'] : 0;
            $anticipo_grabada = isset($value_anticipos['total_igv']) ? ($value_anticipos['total_a_pagar'] - $value_anticipos['total_igv']) : 0;
            $anticipo_total = isset($value_anticipos['total_a_pagar']) ? $value_anticipos['total_a_pagar'] : 0;
            
            //////////////Descuentos Globales  -- INICIO
            if( ($venta['total_descuentos'] != null) && ($venta['total_descuentos'] > 0)){
            $codigo_cargos = ( $venta['total_igv'] > 0 ) ? '02' : '03';
            $xml .= '<cac:AllowanceCharge>
                <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
                <cbc:AllowanceChargeReasonCode listAgencyName="PE:SUNAT" listName="Cargo/descuento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo53">'.$codigo_cargos.'</cbc:AllowanceChargeReasonCode>
                <cbc:MultiplierFactorNumeric>' . number_format(( $venta['total_descuentos'] / ($venta['total_gravada'] + $venta['total_descuentos'])), 5,'.', ''). '</cbc:MultiplierFactorNumeric>
                <cbc:Amount currencyID="'.$venta['abrstandar'].'">' . $venta['total_descuentos'] . '</cbc:Amount>
                <cbc:BaseAmount currencyID="'.$venta['abrstandar'].'">' . number_format(($venta['total_gravada'] + $venta['total_descuentos']), 2,'.', ''). '</cbc:BaseAmount>
            </cac:AllowanceCharge>';
            }                        
            //////////////Descuentos Globales -- FIN

            //////////////Descuentos ITEM  -- INICIO
            $suma_descuento_lineal = 0;
            $tipo_igv_id_item = 1;//suponemos q por defecto será con IGV. igual luego se reemplazará en el caso tenga descuento por Item
            foreach ($detalles as $datos_descuento){
                $suma_descuento_lineal += $datos_descuento['descuento'];
                $tipo_igv_id_item = $datos_descuento['tipo_igv_id'];
            }
            
            if($suma_descuento_lineal > 0){
                $codigo_cargos = ($tipo_igv_id_item == 1) ? '00' : '01';
                $xml .= '<cac:AllowanceCharge>
                    <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
                    <cbc:AllowanceChargeReasonCode listAgencyName="PE:SUNAT" listName="Cargo/descuento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo53">'.$codigo_cargos.'</cbc:AllowanceChargeReasonCode>
                    <cbc:MultiplierFactorNumeric>' . number_format(( $suma_descuento_lineal / ($venta['total_gravada'] + $suma_descuento_lineal)), 5,'.', ''). '</cbc:MultiplierFactorNumeric>
                    <cbc:Amount currencyID="'.$venta['abrstandar'].'">' . $suma_descuento_lineal . '</cbc:Amount>
                    <cbc:BaseAmount currencyID="'.$venta['abrstandar'].'">' . number_format(($venta['total_gravada'] + $suma_descuento_lineal), 2,'.', ''). '</cbc:BaseAmount>
                </cac:AllowanceCharge>';
            }
            //////////////Descuentos ITEM -- FIN                
            
            ////////////////////////////////////////PERCENCIÓN            
            if(($venta['retencion_porcentaje'] != '') && ($venta['retencion_porcentaje'] != null) && ($venta['retencion_porcentaje'] > 0)){                            
            $xml .= '<cac:AllowanceCharge> 
		<cbc:ChargeIndicator>false</cbc:ChargeIndicator> 
		<cbc:AllowanceChargeReasonCode listAgencyName="PE:SUNAT" listName="Cargo/descuento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo53">62</cbc:AllowanceChargeReasonCode> 
		<cbc:MultiplierFactorNumeric>' . $venta['retencion_porcentaje'] * (0.01) . '</cbc:MultiplierFactorNumeric>
		<cbc:Amount currencyID="PEN">' . $venta['total_a_pagar'] * $venta['retencion_porcentaje'] * (0.01) . '</cbc:Amount> 
		<cbc:BaseAmount currencyID="PEN">' . $venta['total_a_pagar'] . '</cbc:BaseAmount>  
            </cac:AllowanceCharge>';
            }
            ////////////////////////////////////////            
                
                $total_igv = ($venta['total_igv'] != null) ? ($venta['total_igv'] - $anticipo_igv) : 0.0;
                $xml .=  '<cac:TaxTotal>
                            <cbc:TaxAmount currencyID="'.$venta['abrstandar'].'">'. $total_igv .'</cbc:TaxAmount>';
                
                    if($venta['total_gravada'] != null){                                            
                    $xml .=  '<cac:TaxSubtotal>
                                <cbc:TaxableAmount currencyID="'.$venta['abrstandar'].'">' . ($venta['total_gravada'] - $anticipo_grabada) . '</cbc:TaxableAmount>
                                <cbc:TaxAmount currencyID="'.$venta['abrstandar'].'">' . $total_igv . '</cbc:TaxAmount>
                                <cac:TaxCategory>
                                    <cac:TaxScheme>
                                        <cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">1000</cbc:ID>
                                        <cbc:Name>IGV</cbc:Name>
                                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                                    </cac:TaxScheme>
                                </cac:TaxCategory>
                            </cac:TaxSubtotal>';
                    };
                    
                    if($venta['total_exportacion'] != null){                                            
                    $xml .=  '<cac:TaxSubtotal>
                                <cbc:TaxableAmount currencyID="'.$venta['abrstandar'].'">' . $venta['total_exportacion'] . '</cbc:TaxableAmount>
                                <cbc:TaxAmount currencyID="'.$venta['abrstandar'].'">0.00</cbc:TaxAmount>
                                <cac:TaxCategory>
                                    <cac:TaxScheme>
                                        <cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">9995</cbc:ID>
                                        <cbc:Name>EXP</cbc:Name>
                                        <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                                    </cac:TaxScheme>
                                </cac:TaxCategory>
                            </cac:TaxSubtotal>';
                    };                    
                    if($venta['total_gratuita'] != null){                                            
                    $xml .=  '<cac:TaxSubtotal>
                                <cbc:TaxableAmount currencyID="'.$venta['abrstandar'].'">' . $venta['total_gratuita'] . '</cbc:TaxableAmount>
                                <cbc:TaxAmount currencyID="'.$venta['abrstandar'].'">0.00</cbc:TaxAmount>
                                <cac:TaxCategory>
                                    <cac:TaxScheme>
                                        <cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">9996</cbc:ID>
                                        <cbc:Name>GRA</cbc:Name>
                                        <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                                    </cac:TaxScheme>
                                </cac:TaxCategory>
                            </cac:TaxSubtotal>';
                    };                    
                    if($venta['total_exonerada'] != null){                                            
                    $xml .=  '<cac:TaxSubtotal>
                                <cbc:TaxableAmount currencyID="'.$venta['abrstandar'].'">' . $venta['total_exonerada'] . '</cbc:TaxableAmount>
                                <cbc:TaxAmount currencyID="'.$venta['abrstandar'].'">0.00</cbc:TaxAmount>
                                <cac:TaxCategory>
                                    <cac:TaxScheme>
                                        <cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">9997</cbc:ID>
                                        <cbc:Name>EXO</cbc:Name>
                                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                                    </cac:TaxScheme>
                                </cac:TaxCategory>
                            </cac:TaxSubtotal>';
                    };                    
                    if($venta['total_inafecta'] != null){                                            
                    $xml .=  '<cac:TaxSubtotal>
                                <cbc:TaxableAmount currencyID="'.$venta['abrstandar'].'">' . $venta['total_inafecta'] . '</cbc:TaxableAmount>
                                <cbc:TaxAmount currencyID="'.$venta['abrstandar'].'">0.00</cbc:TaxAmount>
                                <cac:TaxCategory>
                                    <cac:TaxScheme>
                                        <cbc:ID schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">9998</cbc:ID>
                                        <cbc:Name>INA</cbc:Name>
                                        <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                                    </cac:TaxScheme>
                                </cac:TaxCategory>
                            </cac:TaxSubtotal>';
                    };                    
                    if($venta['total_bolsa'] != null){                                            
                    $xml .=  '<cac:TaxSubtotal>              
                                <cbc:TaxAmount currencyID="'.$venta['abrstandar'].'">' . $venta['total_bolsa'] . '</cbc:TaxAmount>
                                <cac:TaxCategory>       				  
                                    <cac:TaxScheme>
                                        <cbc:ID schemeAgencyName="PE:SUNAT" schemeName="Codigo de tributos" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">7152</cbc:ID>
                                        <!-- ICBPER: Nombre de ICBPER -->
                                        <cbc:Name>ICBPER</cbc:Name>
                                        <!-- ICBPER: Codigo Internacional de ICBPER -->
                                        <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>
                                    </cac:TaxScheme>
                                </cac:TaxCategory>
                            </cac:TaxSubtotal>';
                    };                      
                $total_gravada      = ($venta['total_gravada'] == null)     ? 0 : $venta['total_gravada'];
                $total_exportacion  = ($venta['total_exportacion'] == null) ? 0 : $venta['total_exportacion'];
                $total_exonerada    = ($venta['total_exonerada'] == null)   ? 0 : $venta['total_exonerada'];
                $total_inafecta     = ($venta['total_inafecta'] == null)    ? 0 : $venta['total_inafecta'];
                $xml .=  '</cac:TaxTotal>';
                
                $xml .=  '<cac:'.$tag_total_pago.'>                                
                            <cbc:LineExtensionAmount currencyID="'.$venta['abrstandar'].'">' . number_format(($total_gravada + $total_exportacion + $total_exonerada + $total_inafecta), 2, '.', '') . '</cbc:LineExtensionAmount>
                            <cbc:TaxInclusiveAmount currencyID="'.$venta['abrstandar'].'">' . number_format($venta['total_a_pagar'], 2, '.', '') . '</cbc:TaxInclusiveAmount>
                            <cbc:AllowanceTotalAmount currencyID="'.$venta['abrstandar'].'">'.number_format($suma_descuento_lineal, 2, '.', '').'</cbc:AllowanceTotalAmount>
                            <cbc:ChargeTotalAmount currencyID="'.$venta['abrstandar'].'">0.00</cbc:ChargeTotalAmount>
                            <cbc:PrepaidAmount currencyID="'.$venta['abrstandar'].'">' . number_format($anticipo_total, 2, '.', '') . '</cbc:PrepaidAmount>
                            <cbc:PayableAmount currencyID="'.$venta['abrstandar'].'">' . number_format($venta['total_a_pagar'] - $anticipo_total, 2, '.', ''). '</cbc:PayableAmount>
                        </cac:'.$tag_total_pago.'>';                
                
                $i = 1;
                $percent = $venta['porcentaje_igv'];
                foreach($detalles as $value){                            
                    $icbper = ($value['impuesto_bolsa'] != null) ? $value['impuesto_bolsa'] : 00.00;
                    $priceAmount = $this->variables_diversas_model->priceAmount($value['precio_base'], $value['codigo_de_tributo'], $percent, $icbper, $value['descuento']);
                    $PriceTypeCode = ($value['codigo_de_tributo'] == 9996) ? '02' : '01';
                    $taxAmount = $this->variables_diversas_model->taxAmount($value['cantidad'], $value['precio_base'], $value['codigo_de_tributo'], $percent, $value['descuento']);
                    $price_priceAmount = $this->variables_diversas_model->price_priceAmount($value['precio_base'], $value['codigo_de_tributo'], $value['descuento']);
                    //sale del catalgo16
                    //PriceAmount precio unitario (precio base x (1 + IGV)) + impuesto por 1 bolsa. (en caso no se pague IGV sera 1 + 0).

                    $linea = '';
                    $cantidad = '';
                    switch ($venta['tipo_documento_id']) {
                        case '1':
                        $linea      = 'InvoiceLine';
                        $cantidad   = 'InvoicedQuantity';
                        break;

                        case '3':
                        $linea      = 'InvoiceLine';
                        $cantidad   = 'InvoicedQuantity';
                        break;

                        case '7':
                        $linea      = 'CreditNoteLine';
                        $cantidad   = 'CreditedQuantity';
                        break;

                        case '8':
                        $linea      = 'DebitNoteLine';
                        $cantidad   = 'DebitedQuantity';
                        break;
                    }

                    $xml .= '<cac:'.$linea.'>
                            <cbc:ID>'.$i.'</cbc:ID>
                            <cbc:'.$cantidad.' unitCode="NIU">'. number_format($value['cantidad'], 2, '.', '') .'</cbc:'.$cantidad.'>
                            <cbc:LineExtensionAmount currencyID="'.$venta['abrstandar'].'">'. number_format($value['cantidad'] * ($value['precio_base'] - $value['descuento']), 2, '.', '').'</cbc:LineExtensionAmount>
                            <cac:PricingReference>
                                <cac:AlternativeConditionPrice>
                                    <cbc:PriceAmount currencyID="'.$venta['abrstandar'].'">' . abs(number_format($priceAmount, 6, '.', '')) .'</cbc:PriceAmount>
                                    <cbc:PriceTypeCode listName="Tipo de Precio" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">' . $PriceTypeCode . '</cbc:PriceTypeCode>
                                </cac:AlternativeConditionPrice>
                            </cac:PricingReference>';

                    if($value['descuento'] != ''){
                    //chekar catalogo 53
                        $codigo_cargos = ($value['tipo_igv_id'] == 1) ? '00' : '01';
                        $xml .= '<cac:AllowanceCharge>
                                    <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
                                    <cbc:AllowanceChargeReasonCode listAgencyName="PE:SUNAT" listName="Cargo/descuento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo53">'.$codigo_cargos.'</cbc:AllowanceChargeReasonCode>
                                    <cbc:MultiplierFactorNumeric>' . number_format(( $value['descuento'] / ($value['precio_base'])), 5,'.', ''). '</cbc:MultiplierFactorNumeric>
                                    <cbc:Amount currencyID="'.$venta['abrstandar'].'">'.$value['descuento'].'</cbc:Amount>
                                    <cbc:BaseAmount currencyID="'.$venta['abrstandar'].'">'. number_format($value['precio_base'], 2) .'</cbc:BaseAmount>
                                </cac:AllowanceCharge>';                                                        
                    }

                    $xml .=     '<cac:TaxTotal>
                                    <cbc:TaxAmount currencyID="'.$venta['abrstandar'].'">'. number_format(($taxAmount + $icbper * $value['cantidad']), 2, '.', '') .'</cbc:TaxAmount>                                                
                                    <cac:TaxSubtotal>
                                        <cbc:TaxableAmount currencyID="'.$venta['abrstandar'].'">' . number_format(($value['precio_base'] - $value['descuento']) * $value['cantidad'] ,2, '.', '') . '</cbc:TaxableAmount>
                                        <cbc:TaxAmount currencyID="'.$venta['abrstandar'].'">'. number_format($taxAmount, 2, '.', '') .'</cbc:TaxAmount>
                                        <cac:TaxCategory>
                                            <cbc:Percent>'. $percent * 100 .'</cbc:Percent>
                                            <cbc:TaxExemptionReasonCode>'.$value['tipo_igv_codigo'].'</cbc:TaxExemptionReasonCode>
                                            <cac:TaxScheme>
                                                <cbc:ID>'.$value['codigo_de_tributo'].'</cbc:ID>                                                    
                                                <cbc:Name>'.$value['nombre_tributo'].'</cbc:Name>                                                    
                                                <cbc:TaxTypeCode>'.$value['codigo_internacional'].'</cbc:TaxTypeCode>
                                            </cac:TaxScheme>
                                        </cac:TaxCategory>
                                    </cac:TaxSubtotal>';                            
                    if($value['impuesto_bolsa'] != null){ 
                            $xml .= '<cac:TaxSubtotal>
                                        <cbc:TaxAmount currencyID="'.$venta['abrstandar'].'">' . $icbper * $value['cantidad'] . '</cbc:TaxAmount>
                                        <cbc:BaseUnitMeasure unitCode="NIU">' . number_format($value['cantidad'], 0, '','') . '</cbc:BaseUnitMeasure>
                                        <cac:TaxCategory>
                                            <cbc:PerUnitAmount currencyID="'.$venta['abrstandar'].'">' . $icbper . '</cbc:PerUnitAmount>
                                            <cac:TaxScheme>
                                                <cbc:ID schemeAgencyName="PE:SUNAT" schemeName="Codigo de tributos" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo05">7152</cbc:ID>
                                                <cbc:Name>ICBPER</cbc:Name>
                                                <cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>	
                                            </cac:TaxScheme>
                                        </cac:TaxCategory>
                                    </cac:TaxSubtotal>';
                    }                            
                    $xml .=     '</cac:TaxTotal>                                    
                                <cac:Item>                                    
                                    <cbc:Description><![CDATA['.$value['producto'].']]></cbc:Description>
                                    <cac:SellersItemIdentification>
                                        <cbc:ID>'.$value['codigo_producto'].'</cbc:ID>
                                    </cac:SellersItemIdentification>
                                    <cac:CommodityClassification>                                        
                                        <cbc:ItemClassificationCode>'.$value['codigo_sunat'].'</cbc:ItemClassificationCode>
                                    </cac:CommodityClassification>
                                </cac:Item>
                                <cac:Price>
                                    <cbc:PriceAmount currencyID="'.$venta['abrstandar'].'">' . abs($price_priceAmount) . '</cbc:PriceAmount>
                                </cac:Price>
                        </cac:'.$linea.'>
                        ';
                    $i++;
                }
                $xml .=  '</'.$linea_fin.'>';
                return $xml;
    }
    
    public function leerRespuestaSunat($nombre_archivo){

        $nombre = FCPATH."ws_sunat/R-".$nombre_archivo;                
        $resultado = array();
        //echo "abc";exit;        
        if(file_exists($nombre)){            
            $library = new SimpleXMLElement($nombre, null, true);
            
            $ns = $library->getDocNamespaces();
            $ext1 = $library->children($ns['cac']);
            $ext2 = $ext1->DocumentResponse;
            $ext3 = $ext2->children($ns['cac']);            
            $ext4 = $ext3->children($ns['cbc']);

            $resultado = array(
                'respuesta_sunat_codigo' => trim($ext4->ResponseCode),
                'respuesta_sunat_descripcion' => trim($ext4->Description)
            );
        }
        return $resultado;        
    }        
    
    function baja($numero_documento){
        $venta_id = $this->uri->segment(3);
        $venta = $this->ventas_model->query_cabecera($venta_id);
        $empresa = $this->empresas_model->select(2);
        
        $anulaciones_dia = $this->anulaciones_model->maximo_numero(date("Y-m-d"));
        $nombre_archivo = $empresa['ruc'].'-RA-'.date("Ymd").'-'.($anulaciones_dia + 1);
        $anulacion_previa = $this->anulaciones_model->select(2, array('numero', 'fecha'), array('venta_id' => $venta_id ));
        $this->ventas_model->modificar($venta_id, array('estado_anulacion' => 0));
        
        if($anulacion_previa == array()){
            ////////CREO XML              
            $xml = $this->desarrollo_xml_baja($empresa, $venta, ($anulaciones_dia + 1));
            
            $nombre = FCPATH."files/facturacion_electronica/BAJA/XML/".$nombre_archivo.".xml"; 
            $archivo = fopen($nombre, "w+");
            fwrite($archivo, $xml);
            fclose($archivo);

            $this->firmar_xml($nombre_archivo.".xml", $empresa['modo'], 1);
            /////////GUARDO EN BBDD
            $data = array(
                'fecha'     =>  date("Y-m-d"),
                'venta_id'  =>  $venta_id,
                'numero'    =>  ($anulaciones_dia + 1)
            );
            $this->anulaciones_model->insertar($data);
        }else{
            $fecha = str_replace("-","",$anulacion_previa['fecha']);
            $nombre_archivo = $empresa['ruc'].'-RA-'.$fecha.'-'.($anulacion_previa['numero']);
        }
        ///////////////////////////////////////////////////////////////////////////////////////
        
        //enviar a Sunat       
        //cod_1: Select web Service: 1 factura, boletas --- 9 es para guias
        //cod_2: Entorno:  0 Beta, 1 Produccion
        //cod_3: ruc
        //cod_4: usuario secundario USU(segun seha beta o producción)
        //cod_5: usuario secundario PASSWORD(segun seha beta o producción)
        //cod_6: Accion:   1 enviar documento a Sunat --  2 enviar a anular  --  3 enviar ticket
        //cod_7: serie de documento
        //cod_8: numero ticket
        
        $user_sec_usu = ($empresa['modo'] == 1) ? $empresa['usu_secundario_produccion_user'] : $empresa['usu_secundario_prueba_user'];
        $user_sec_pass = ($empresa['modo'] == 1) ? $empresa['usu_secundario_produccion_password'] : $empresa['usu_secundario_prueba_passoword'];        
        $ws = base_url()."ws_sunat/index.php?numero_documento=".$nombre_archivo."&cod_1=1&cod_2=".$empresa['modo']."&cod_3=".$empresa['ruc']."&cod_4=".$user_sec_usu."&cod_5=".$user_sec_pass."&cod_6=1";
        
        $jsondata = array(
            'numero_documento'  =>  $nombre_archivo,
            'cod_1'             =>  1,
            'cod_2'             =>  $empresa['modo'],
            'cod_3'             =>  $empresa['ruc'],
            'cod_4'             =>  $user_sec_usu,
            'cod_5'             =>  $user_sec_pass,
            'cod_6'             =>  2,
            'cod_7'             =>  $venta['serie'],
            'message'           =>  'creo xml y firmo'
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }

    function baja_enviar_ticket(){
        $venta_id = $this->uri->segment(3);
        $empresa = $this->empresas_model->select(2);
        $anulaciones = $this->anulaciones_model->select(2,'', array('venta_id' => $venta_id));

        $fecha_anulacion = substr($anulaciones['fecha'], 0, 4).substr($anulaciones['fecha'], 5, 2).substr($anulaciones['fecha'], 8, 2);
        $nombre_archivo = $empresa['ruc'].'-RA-'.$fecha_anulacion.'-'.$anulaciones['numero'];
        
        //enviar a Sunat       
        //cod_1: Select web Service: 1 factura, boletas --- 9 es para guias
        //cod_2: Entorno:  0 Beta, 1 Produccion
        //cod_3: ruc
        //cod_4: usuario secundario USU(segun seha beta o producción)
        //cod_5: usuario secundario PASSWORD(segun seha beta o producción)
        //cod_6: Accion:   1 enviar documento a Sunat --  2 enviar a anular  --  3 enviar ticket
        //cod_7: serie de documento
        //cod_8: numero ticket
        
        $user_sec_usu = ($empresa['modo'] == 1) ? $empresa['usu_secundario_produccion_user'] : $empresa['usu_secundario_prueba_user'];
        $user_sec_pass = ($empresa['modo'] == 1) ? $empresa['usu_secundario_produccion_password'] : $empresa['usu_secundario_prueba_passoword'];        
        
        $url = base_url()."ws_sunat/index.php?numero_documento=".$nombre_archivo."&cod_1=1&cod_2=".$empresa['modo']."&cod_3=".$empresa['ruc']."&cod_4=".$user_sec_usu."&cod_5=".$user_sec_pass."&cod_6=3&cod_7=---&cod_8=".$anulaciones['ticket'];        
        $data = file_get_contents($url);        
        $info = json_decode($data, TRUE);
        //var_dump($info);
        $respuesta_codigo = '';
        $respuesta_mensaje = '';
        if($info['error_existe'] == 0){
            $respuesta_sunat = $this->leerRespuesta_baja($nombre_archivo.".xml");
            if($respuesta_sunat != null){
                $this->ventas_model->modificar($venta_id, $respuesta_sunat);
                if($respuesta_sunat['respuesta_anulacion_codigo'] == '0')
                    $this->ventas_model->modificar($venta_id, array('estado_anulacion' => 1));
            }
            //var_dump($respuesta_sunat);
            $respuesta_mensaje = ($respuesta_sunat != null) ? $respuesta_sunat['respuesta_anulacion_descripcion']: '';
            $respuesta_codigo = ($respuesta_sunat != null) ? $respuesta_sunat['respuesta_anulacion_codigo']: '';
        }
        
        $jsondata = array(
            'success'       =>  true,
            'codigo'        =>  $respuesta_codigo,
            'error_existe'  =>  $info['error_existe'],
            'message'       =>  $respuesta_mensaje.$info['error_mensaje']
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);                
    }
    
    public function leerRespuesta_baja($nombre_archivo){
        $nombre = FCPATH."ws_sunat/R-".$nombre_archivo;        
        $resultado = array();
        if(file_exists($nombre)){
            $library = new SimpleXMLElement($nombre, null, true);
            
            $ns = $library->getDocNamespaces();
            $ext1 = $library->children($ns['cac']);
            $ext2 = $ext1->DocumentResponse;
            $ext3 = $ext2->children($ns['cac']);            
            $ext4 = $ext3->children($ns['cbc']);

            $resultado = array(
                'respuesta_anulacion_codigo' => trim($ext4->ResponseCode),
                'respuesta_anulacion_descripcion' => trim($ext4->Description)
            );
        }
        return $resultado;        
    }
    
    public function desarrollo_xml_baja($empresa, $venta, $identificador){
        $xml = '<?xml version="1.0" encoding="ISO-8859-1"?><VoidedDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:VoidedDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:ds="http://www.w3.org/2000/09/xmldsig#">
                <ext:UBLExtensions>
                    <ext:UBLExtension>
                        <ext:ExtensionContent></ext:ExtensionContent>
                    </ext:UBLExtension>
                </ext:UBLExtensions>
                <cbc:UBLVersionID>2.0</cbc:UBLVersionID>
                <cbc:CustomizationID>1.0</cbc:CustomizationID>
                <cbc:ID>RA-'.date("Ymd").'-'.$identificador.'</cbc:ID>
                <cbc:ReferenceDate>'.$venta['fecha_emision_sf'].'</cbc:ReferenceDate>
                <cbc:IssueDate>'.date("Y-m-d").'</cbc:IssueDate>
                <cac:Signature>
                    <cbc:ID>'.$empresa['ruc'].'</cbc:ID>
                    <cac:SignatoryParty>
                        <cac:PartyIdentification>
                            <cbc:ID>'.$empresa['ruc'].'</cbc:ID>
                        </cac:PartyIdentification>
                        <cac:PartyName>
                            <cbc:Name>'.$empresa['empresa'].'</cbc:Name>
                        </cac:PartyName>
                    </cac:SignatoryParty>
                    <cac:DigitalSignatureAttachment>
                        <cac:ExternalReference>
                            <cbc:URI>'.$empresa['ruc'].'</cbc:URI>
                        </cac:ExternalReference>
                    </cac:DigitalSignatureAttachment>
                </cac:Signature>
                <cac:AccountingSupplierParty>
                    <cbc:CustomerAssignedAccountID>'.$empresa['ruc'].'</cbc:CustomerAssignedAccountID>
                    <cbc:AdditionalAccountID>6</cbc:AdditionalAccountID>
                    <cac:Party>
                        <cac:PartyLegalEntity>
                            <cbc:RegistrationName>'.$empresa['empresa'].'</cbc:RegistrationName>
                        </cac:PartyLegalEntity>
                    </cac:Party>
                </cac:AccountingSupplierParty>
                <sac:VoidedDocumentsLine>
                    <cbc:LineID>1</cbc:LineID>
                    <cbc:DocumentTypeCode>'.$venta['tipo_documento_codigo'].'</cbc:DocumentTypeCode>
                    <sac:DocumentSerialID>'.$venta['serie'].'</sac:DocumentSerialID>
                    <sac:DocumentNumberID>'.$venta['numero'].'</sac:DocumentNumberID>
                    <sac:VoidReasonDescription>Anulacion de la Operacion</sac:VoidReasonDescription>
                </sac:VoidedDocumentsLine>
            </VoidedDocuments>';
        return $xml;
    }
    
    public function getDatosXML(){
        $venta_id = $this->uri->segment(3);
        $venta_g = $this->ventas_model->query_cabecera($venta_id);
        $detalle_g = $this->venta_detalles_model->query_detalle($venta_id);
        $empresa_g = $this->empresas_model->query_standar();
        
        $num = new Numletras();
        $totalVenta = explode(".",  $venta_g['total_a_pagar']);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $venta_g['total_letras'] = $totalLetras.' con '.$totalVenta[1].'/100 '.$venta_g['moneda'];

        $venta['venta_id']              = $venta_id;
        $venta['UBLVersionID']          = $venta_g['UBLVersionID'];
        $venta['CustomizationID']       = $venta_g['CustomizationID'];
        $venta['serie']                 = $venta_g['serie'];
        $venta['numero']                = $venta_g['numero'];
        $venta['fecha_emision_sf']      = $venta_g['fecha_emision_sf'];
        $venta['hora_emision']          = $venta_g['hora_emision'];
        $venta['fecha_emision']         = $venta_g['fecha_emision'];        
        $venta['tipo_operacion']        = $venta_g['tipo_operacion'];
        $venta['tipo_documento_codigo'] = $venta_g['tipo_documento_codigo'];
        $venta['total_letras']          = $venta_g['total_letras'];
        $venta['abrstandar']            = $venta_g['abrstandar'];
        $venta['numero_documento']      = $venta_g['numero_documento'];
        $venta['total_a_pagar']         = $venta_g['total_a_pagar'];
        $venta['porcentaje_igv']        = $venta_g['porcentaje_igv'];
        $venta['entidad']               = $venta_g['entidad'];
        $venta['codigo_tipo_entidad']   = $venta_g['codigo_tipo_entidad'];
        
        $venta['fecha_vencimiento']     = $venta_g['fecha_vencimiento'];
        $venta['total_igv']             = $venta_g['total_igv'];
        $venta['total_gravada']         = $venta_g['total_gravada'];
        $venta['total_exportacion']     = $venta_g['total_exportacion'];
        $venta['total_gratuita']        = $venta_g['total_gratuita'];
        $venta['total_exonerada']       = $venta_g['total_exonerada'];
        $venta['total_inafecta']        = $venta_g['total_inafecta'];
        $venta['total_bolsa']           = $venta_g['total_bolsa'];
        
        $empresa['ruc']                 = $empresa_g['ruc'];
        $empresa['empresa']             = $empresa_g['empresa'];
        $empresa['nombre_comercial']    = $empresa_g['nombre_comercial'];
        $empresa['ubigeo']              = $empresa_g['ubigeo'];
        $empresa['provincia']           = $empresa_g['provincia'];
        $empresa['departamento']        = $empresa_g['departamento'];
        $empresa['distrito']            = $empresa_g['distrito'];
        $empresa['domicilio_fiscal']    = $empresa_g['domicilio_fiscal'];
        $empresa['modo']                                = $empresa_g['modo'];
        $empresa['usu_secundario_produccion_user']      = $empresa_g['usu_secundario_produccion_user'];
        $empresa['usu_secundario_produccion_password']  = $empresa_g['usu_secundario_produccion_password'];
        $empresa['usu_secundario_prueba_user']          = $empresa_g['usu_secundario_prueba_user'];
        $empresa['usu_secundario_prueba_passoword']     = $empresa_g['usu_secundario_prueba_passoword'];        
        
        $jsondata = array(
            'venta'     =>  $venta,
            'detalle'   =>  $detalle_g,
            'empresa'   =>  $empresa
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }
        
    public function priceAmount($precio_base, $codigo_de_tributo, $percent, $icbper){
        $precio_base = floatval($precio_base);
        $codigo_de_tributo = floatval($codigo_de_tributo);
        $percent = floatval($percent);
        $icbper = floatval($icbper);
        $priceAmount = '';
        if($codigo_de_tributo == 1000){
            $priceAmount = number_format(($precio_base * ( 1 + $percent)),2, '.', '') + $icbper;            
        }else{
            $priceAmount = $precio_base + $icbper;
        }
        return $priceAmount;
    }
    
    public function price_priceAmount($precio_base, $codigo_de_tributo){
        $price_priceAmount = '';
        switch ($codigo_de_tributo) {
            case 1000:
              $price_priceAmount = number_format($precio_base,2, '.', '');
              break;
            case 9995:
              $price_priceAmount = $precio_base;
              break;
            case 9996:
              $price_priceAmount = 0;
              break;
            case 9997:
              $price_priceAmount = $precio_base;
              break;
            case 9998:
              $price_priceAmount = $precio_base;
              break;
        }        
        return $price_priceAmount;
    }
    
    public function taxAmount($cantidad, $precio_base, $codigo_de_tributo, $percent){
        $taxAmount = '';        
        switch ($codigo_de_tributo) {
            case 1000:
              $taxAmount = $cantidad*$precio_base*$percent;
              break;
            case 9995:
              $taxAmount = 0.0;
              break;
            case 9996:
              $taxAmount = $cantidad*($precio_base/(1 + $percent))*$percent;
              break;
            case 9997:
              $taxAmount = 0.0;
              break;
            case 9998:
              $taxAmount = 0.0;
              break;
        }        
        return $taxAmount;        
    }
    
    public function exportarExcel(){                
        $this->load->library('excel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('COMPROBANTES');

        $objPHPExcel->getActiveSheet()->getColumnDimension('a')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('b')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('c')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('d')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('e')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('f')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('g')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('h')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('i')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('j')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('k')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('l')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('m')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('n')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('o')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('p')->setWidth(12);
        
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
        
        $objPHPExcel->getActiveSheet()->setCellValue('A1', "N"); 
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "Fecha Emisión");
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "Estado");
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "Moneda");
        $objPHPExcel->getActiveSheet()->setCellValue('E1', "T.C.");
        $objPHPExcel->getActiveSheet()->setCellValue('F1', "Serie");
        $objPHPExcel->getActiveSheet()->setCellValue('G1', "Número");
        $objPHPExcel->getActiveSheet()->setCellValue('H1', "Cliente");
        $objPHPExcel->getActiveSheet()->setCellValue('I1', "T.Doc.");
        $objPHPExcel->getActiveSheet()->setCellValue('J1', "N.Doc.");
        $objPHPExcel->getActiveSheet()->setCellValue('K1', "Orden Compra.");
        $objPHPExcel->getActiveSheet()->setCellValue('L1', "N. Guia.");
        $objPHPExcel->getActiveSheet()->setCellValue('M1', "Condicion Venta.");
        $objPHPExcel->getActiveSheet()->setCellValue('N1', "Grabada");
        $objPHPExcel->getActiveSheet()->setCellValue('O1', "IGV");
        $objPHPExcel->getActiveSheet()->setCellValue('P1', "Exonerada");
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', "Inafecta");
        $objPHPExcel->getActiveSheet()->setCellValue('R1', "Gratuito");
        $objPHPExcel->getActiveSheet()->setCellValue('S1', "Exportación");
        $objPHPExcel->getActiveSheet()->setCellValue('T1', "Bolsa");
        $objPHPExcel->getActiveSheet()->setCellValue('U1', "Total a Pagar");
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        $entidad_id                 = $this->uri->segment(3);
        $tipo_documento_id          = $this->uri->segment(4);        
        $serie                      = $this->uri->segment(5);
        $numero                     = $this->uri->segment(6);
        $fecha_emision_inicio       = $this->uri->segment(7);
        $fecha_emision_final        = $this->uri->segment(8);
        $moneda                     = $this->uri->segment(9);
        $operacion                  = $this->uri->segment(10);
        
        $condicion = array();
        $condicion = ($entidad_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('ent.id' => '='.$entidad_id)) : $condicion;
        $condicion = ($tipo_documento_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('tdc.id' => '='.$tipo_documento_id)) : $condicion;
        $condicion = ($serie != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('ven.serie' => '='.$serie)) : $condicion;
        $condicion = ($numero != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('ven.numero' => '='.$numero)) : $condicion;
        
        $condicion = (($fecha_emision_inicio != $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final == $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('fecha_emision' => '>='."'".format_fecha_0000_00_00($fecha_emision_inicio)."'")) : $condicion;
        $condicion = (($fecha_emision_inicio == $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final != $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('fecha_emision' => '<='."'".format_fecha_0000_00_00($fecha_emision_final)."'")) : $condicion;
        $condicion = (($fecha_emision_inicio != $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final != $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('fecha_emision' => 'BETWEEN '."'".format_fecha_0000_00_00($fecha_emision_inicio)."' AND "."'".format_fecha_0000_00_00($fecha_emision_final)."'")) : $condicion;
        
        $condicion = ($moneda != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('ven.moneda_id' => '='.$moneda)) : $condicion;
        $condicion = ($operacion != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('ven.operacion' => '='.$operacion)) : $condicion;
                                                             
        $ventas = $this->ventas_model->query_standar(3, '', '', '', $condicion, '');
        $i = 2;
        foreach ($ventas as $value){
            $result = $this->estadoAnulacion($value['estado_anulacion'], $value['estado_operacion'], $value['total_gravada'], $value['total_igv'], $value['total_exonerada'], $value['total_inafecta'], $value['total_gratuita'], $value['total_exportacion'], $value['total_bolsa'], $value['total_a_pagar']);
            
            $objPHPExcel->getActiveSheet()
                        ->setCellValue('A' . $i, $i - 1)
                        ->setCellValue('B' . $i, $value['fecha_emision_cf'])
                        ->setCellValue('C' . $i, $result['estado'])
                        ->setCellValue('D' . $i, $value['moneda'])
                        ->setCellValue('E' . $i, $value['abreviado'])
                        ->setCellValue('F' . $i, $value['serie'])
                        ->setCellValue('G' . $i, $value['numero'])
                        ->setCellValue('H' . $i, $value['entidad'])
                        ->setCellValue('I' . $i, $value['tipo_entidad_abreviatura'])
                        ->setCellValue('J' . $i, $value['numero_documento'])
                        ->setCellValue('K' . $i, $value['orden_compra'])
                        ->setCellValue('L' . $i, $value['numero_guia'])
                        ->setCellValue('M' . $i, $value['condicion_venta'])
                        ->setCellValue('N' . $i, $result['total_gravada'])
                        ->setCellValue('O' . $i, $result['total_igv'])
                        ->setCellValue('p' . $i, $result['total_exonerada'])
                        ->setCellValue('Q' . $i, $result['total_inafecta'])
                        ->setCellValue('R' . $i, $result['total_gratuita'])
                        ->setCellValue('S' . $i, $result['total_exportacion'])
                        ->setCellValue('T' . $i, $result['total_bolsa'])
                        ->setCellValue('U' . $i, $result['total_a_pagar']);
            $i ++;
        }                        

        //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
        
        //header('Content-Type: application/txt'); //mime type
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        //$extension = '.xls';
        $extension = '.xlsx';
        $filename = 'Reporte_Comprobantes_' . date("d-m-Y") . '---' . rand(1000, 9999) . $extension; //save our workbook as this file name
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        
        header('Cache-Control: max-age=0'); //no cache
        
        $objWriter->save('php://output');
    }
    
    public function estadoAnulacion($estado_anulacion, $estado_operacion, $total_gravada, $total_igv, $total_exonerada, $total_inafecta, $total_gratuita, $total_exportacion, $total_bolsa, $total_a_pagar){
        $resultado['estado'] = '';
        $resultado['total_gravada'] = '';
        $resultado['total_igv'] = '';
        $resultado['total_exonerada'] = '';
        $resultado['total_inafecta'] = '';
        $resultado['total_gratuita'] = '';
        $resultado['total_exportacion'] = '';
        $resultado['total_bolsa'] = '';       
        $resultado['total_a_pagar'] = '';       
        
        if($estado_anulacion == 1){
            $resultado['estado'] = 'Anulado';
        }else{
            switch ($estado_operacion){
                case '0':
                    $resultado['estado'] = 'No enviado';
                break;

                case '1':
                    $resultado['estado']            = 'aceptado';
                    $resultado['total_gravada']     = $total_gravada;
                    $resultado['total_igv']         = $total_igv;
                    $resultado['total_exonerada']   = $total_exonerada;
                    $resultado['total_inafecta']    = $total_inafecta;
                    $resultado['total_gratuita']    = $total_gratuita;
                    $resultado['total_exportacion'] = $total_exportacion;
                    $resultado['total_bolsa']       = $total_bolsa;
                    $resultado['total_a_pagar']     = $total_a_pagar;
                break;

                case '2':
                    $resultado['estado'] = 'Rechazado';
                break;                        
            }                    
        }
        return $resultado;        
    }
    
    public function calendario(){
        $this->accesos_model->menuGeneral();
        $this->load->view('ventas/calendario');
        $this->load->view('templates/footer');
    }        
    
    public function get_status_cdr(){
        $empresa = $this->empresas_model->select(2, array('usu_secundario_produccion_user', 'usu_secundario_prueba_user', 'usu_secundario_produccion_password', 'usu_secundario_prueba_passoword', 'modo'));
        
        $ruc            = $this->uri->segment(3);
        $tipo_documento = $this->uri->segment(4);
        $serie          = $this->uri->segment(5);
        $numero         = $this->uri->segment(6);
        $venta_id       = $this->uri->segment(7);
        
        $user_sec_usu = ($empresa['modo'] == 1) ? $empresa['usu_secundario_produccion_user'] : $empresa['usu_secundario_prueba_user'];
        $user_sec_pass = ($empresa['modo'] == 1) ? $empresa['usu_secundario_produccion_password'] : $empresa['usu_secundario_prueba_passoword'];
        $url = base_url()."greenter/consulta_cdr.php?ruc=".$ruc."&tipo=".$tipo_documento."&serie=".$serie."&numero=".$numero."&user_sec_usu=".$user_sec_usu."&user_sec_pass=".$user_sec_pass;
        file_get_contents($url);
        
        $nombre_archivo = 'R-'.$ruc.'-'.$tipo_documento.'-'.$serie.'-'.$numero;
        $nombre = FCPATH."files/facturacion_electronica/FIRMA/".$nombre_archivo.".zip";

        if (file_exists($nombre)) {
            require_once('ws_sunat/lib/pclzip.lib.php');
            
            chmod($nombre, 0777);                                    
            $archive = new PclZip($nombre);
            if ($archive->extract('ws_sunat') == 0) {
                die("Error : " . $archive->errorInfo(true));
            } else {                
                $respuesta_sunat = $this->leerRespuestaSunat($ruc.'-'.$tipo_documento.'-'.$serie.'-'.$numero.".xml");            
                
                if($respuesta_sunat != null){  
                    $this->ventas_model->modificar($venta_id, $respuesta_sunat);
                    if($respuesta_sunat['respuesta_sunat_codigo'] == '0')
                        $this->ventas_model->modificar($venta_id, array('estado_operacion' => 1));    
                }
                //var_dump($respuesta_sunat);
                $respuesta_mensaje = ($respuesta_sunat != null) ? $respuesta_sunat['respuesta_sunat_descripcion']: '';
                $respuesta_codigo = ($respuesta_sunat != null) ? $respuesta_sunat['respuesta_sunat_codigo']: '';
                    
                $jsondata = array(
                    'success'       =>  true,
                    'codigo'        =>  $respuesta_codigo,
                    'error_existe'  =>  'error_existe',
                    'message'       =>  $respuesta_mensaje
                );
                echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
            }
        }
    }        
    
    public function mas_vendidos_cantidad(){
        $this->accesos_model->menuGeneral();
        $this->load->view('ventas/mas_vendidos_cantidad');
        $this->load->view('templates/footer');
    }
    
    public function mas_vendidos_monto(){
        $this->accesos_model->menuGeneral();
        $this->load->view('ventas/mas_vendidos_monto');
        $this->load->view('templates/footer');
    }        
        
    public function baja_resumen(){        
        $rpta = $this->resumen_crear_xml($this->uri->segment(3), $this->uri->segment(4));            
        //$this->resumen_firmar_xml($rpta['nombre_archivo'].".xml", $rpta['modo']);
        //$this->resumen_ws_sunat($rpta['venta_id'], $rpta['empresa'], $rpta['nombre_archivo']);        
    }
    
    public function resumen_crear_xml($venta_id, $ruc_emisor){
        $venta = $this->ventas_model->query_cabecera($venta_id);
        $empresa = $this->empresas_model->query_standar();
                                
        $xml = $this->resumen_desarrollo_xml($venta, $empresa);
        $nombre_archivo = $ruc_emisor.'-RC-'.date("Ymd").'-1000';
        
        $nombre = FCPATH."/files/facturacion_electronica/RESUMEN/XML/".$nombre_archivo.".xml";
        $archivo = fopen($nombre, "w+");
        fwrite($archivo, utf8_decode($xml));
        fclose($archivo);
    }
    
    public function resumen_desarrollo_xml($venta, $empresa){
        $total_gravada      = ($venta['total_gravada'] == null)     ? 0 : $venta['total_gravada'];
        $total_gravada      = ($venta['total_gravada'] == null)     ? 0 : $venta['total_gravada'];
        $xml    = '<SummaryDocuments xmlns="urn:sunat:names:specification:ubl:peru:schema:xsd:SummaryDocuments-1" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        $xml    .= '<cbc:UBLVersionID>2.0</cbc:UBLVersionID>
                    <cbc:CustomizationID>1.1</cbc:CustomizationID>
                    <cbc:ID>RC-'.date("Ymd").'-1000</cbc:ID>
                    <cbc:ReferenceDate>'.$venta['fecha_emision_sf'].'</cbc:ReferenceDate>
                    <cbc:IssueDate>'.date("Y-m-d").'</cbc:IssueDate>
                    <cac:Signature>
                        <cbc:ID>'.$empresa['ruc'].'</cbc:ID>
                        <cac:SignatoryParty>
                            <cac:PartyIdentification>
                                <cbc:ID>'.$empresa['ruc'].'</cbc:ID>
                            </cac:PartyIdentification>
                            <cac:PartyName>
                                <cbc:Name>'.$empresa['empresa'].'</cbc:Name>
                            </cac:PartyName>
                        </cac:SignatoryParty>
                        <cac:DigitalSignatureAttachment>
                            <cac:ExternalReference>
                                <cbc:URI>'.$empresa['ruc'].'</cbc:URI>
                            </cac:ExternalReference>
                        </cac:DigitalSignatureAttachment>
                    </cac:Signature>
                    <cac:AccountingSupplierParty>
                        <cbc:CustomerAssignedAccountID>'.$empresa['ruc'].'</cbc:CustomerAssignedAccountID>
                        <cbc:AdditionalAccountID>"6"</cbc:AdditionalAccountID>
                        <cac:Party>
                            <cac:PartyLegalEntity>
                                <cbc:RegistrationName><![CDATA['.$empresa['empresa'].']]></cbc:RegistrationName>
                            </cac:PartyLegalEntity>
                        </cac:Party>
                    </cac:AccountingSupplierParty>
                    
                    <sac:SummaryDocumentsLine>
                        <cbc:LineID>1</cbc:LineID>
                        <cbc:DocumentTypeCode>'.$venta['tipo_documento_codigo'].'</cbc:DocumentTypeCode>
                        <cbc:ID>'.$venta['serie'].'-'.$venta['numero'].'</cbc:ID>
                        <cac:AccountingCustomerParty>
                            <cbc:CustomerAssignedAccountID>'.$venta['numero_documento'].'</cbc:CustomerAssignedAccountID>
                            <cbc:AdditionalAccountID>'.$venta['codigo_tipo_entidad'].'</cbc:AdditionalAccountID>
                        </cac:AccountingCustomerParty>
                        <cac:Status>
                            <cbc:ConditionCode>3</cbc:ConditionCode>
                        </cac:Status>
                        <sac:TotalAmount currencyID="PEN">'.number_format($venta['total_a_pagar'], 2, '.', '').'</sac:TotalAmount>
                        <sac:BillingPayment>
                            <cbc:PaidAmount currencyID="PEN">'.number_format($total_gravada, 2, '.', '').'</cbc:PaidAmount>
                            <cbc:InstructionID>01</cbc:InstructionID>
                        </sac:BillingPayment>
                        <cac:TaxTotal>
                            <cbc:TaxAmount currencyID="PEN">'.number_format($venta['total_a_pagar'] - $total_gravada, 2, '.', '').'</cbc:TaxAmount>
                            <cac:TaxSubtotal>
                                <cbc:TaxAmount currencyID="PEN">'.number_format($venta['total_a_pagar'] - $total_gravada, 2, '.', '').'</cbc:TaxAmount>
                                <cac:TaxCategory>
                                    <cac:TaxScheme>
                                        <cbc:ID>1000</cbc:ID>
                                        <cbc:Name>IGV</cbc:Name>
                                        <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                                    </cac:TaxScheme>
                                </cac:TaxCategory>
                            </cac:TaxSubtotal>
                        </cac:TaxTotal>
                    </sac:SummaryDocumentsLine>
                    <!--  GENERADO DESDE WWW.NUBEFACT.COM  -->
                </SummaryDocuments>';
        return $xml;
    }
    
    public function datos_curl($venta_id){
        $data = $this->ventas_model->select(3, '', array('id' => $venta_id));
        $venta = $this->ventas_model->query_cabecera($venta_id);
        
        $anticipos = array();
        if($venta['venta_id'] != null && $venta['venta_id'] != ''){
            $anticipos = $this->venta_anticipos_model->select_anticipo_ventas(3, array('ventas.serie serie, ventas.numero numero, ventas.total_a_pagar total_a_pagar, ventas.id'), array('venta_anticipos.venta_id' => $venta['venta_id']));
        }
        
        $empresa = $this->empresas_model->query_standar();
        $detalle = $this->venta_detalles_model->query_detalle($venta_id);
        $cuotas = $this->cuotas_model->select(3, '', array('venta_id' => $venta_id), ' ORDER BY id DESC');
        
        $venta_relacionado = '';
        $motivo_nc = '';
        $motivo_nd = '';
        if(( ($venta['tipo_documento_id'] == 7) || ($venta['tipo_documento_id'] == 8) ) && ($venta['venta_relacionado_id'] != null)){
            $venta_relacionado = $this->ventas_model->venta_documento($venta['venta_relacionado_id']);
            
            if($venta['tipo_documento_id'] == 7){
                $motivo_nc = $this->tipo_ncreditos_model->select(2,'',array('id' => $venta['tipo_ncredito_id']));
            }
            if($venta['tipo_documento_id'] == 8){
                $motivo_nd = $this->tipo_ndebitos_model->select(2,'',array('id' => $venta['tipo_ndebito_id']));
            }
        }

        $jsondata = array(
            'empresa'           =>  $empresa,
            'venta'             =>  $venta,
            'detalle'           =>  $detalle,
            'venta_relacionado' =>  $venta_relacionado,
            'motivo_nc'         =>  $motivo_nc,
            'motivo_nd'         =>  $motivo_nd,
            'cuotas'            =>  $cuotas,
            'anticipos'         =>  $anticipos
        );
        return $jsondata;
    }
    
    public function curl (){
        

        //$post = $this->datos_curl(23);
        
        $post = [
            'username' => array('uno' => 'dos'),
            'password' => 'passuser1',
            'gender'   => 1,
        ];
        $ch = curl_init('https://grupofact.com/API_SUNAT/ws/index.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        // execute!
        $response = curl_exec($ch);

        // close the connection, release resources used
        curl_close($ch);
        
        exit;
        
        
        
        
        
        $url = 'https://grupofact.com/API_SUNAT/ws/index.php';

        //create a new cURL resource
        $ch = curl_init($url);
        
        $datos = $this->datos_curl(23);        
        $dataJ = json_encode($datos, JSON_UNESCAPED_UNICODE);

        //attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataJ);

        //set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute the POST request
        $result = curl_exec($ch);

        //close cURL resource
        curl_close($ch);
    }
    
    public function imprimirTicket_POS() {
        
        $connector = new WindowsPrintConnector("XP-80");
        //$connector = new NetworkPrintConnector("192.168.1.50", 9100);
        $printer = new Printer($connector);        
        $venta_id = $this->uri->segment(3);                
        
        $empresa    = $this->empresas_model->select(2);
        $cabecera   = $this->ventas_model->query_cabecera($venta_id);
        $detalle    = $this->venta_detalles_model->query_detalle($venta_id);
        $rutaqr     = $this->ventas_model->GetImgQr($cabecera, $empresa);
        
        $num = new Numletras();
        $totalVenta = explode(".", $cabecera['total_a_pagar']);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = 'Son: '.$totalLetras.' con '.$totalVenta[1].'/100 '.$cabecera['moneda'];
                
        $ruta_foto = FCPATH."images/empresas/".$empresa['foto'];
        try {
            $printer->setJustification(Printer::JUSTIFY_CENTER);            
            $logo = EscposImage::load($ruta_foto, false);
            $imgModes = array(
                Printer::IMG_DEFAULT,
                /*Printer::IMG_DOUBLE_WIDTH,
                Printer::IMG_DOUBLE_HEIGHT,
                Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT*/
            );
            foreach ($imgModes as $mode) {
                $printer->bitImage($logo, $mode);
            }
        } catch (Exception $e) {/* $printer->text($e->getMessage() . "\n"); */ }
        $printer->text("\n");
                                
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setFont(Printer::FONT_B);
        $printer->setTextSize(3, 3);
        $printer->text($empresa['empresa']. "\n\n");
                
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setFont(Printer::FONT_A);
        $printer->setTextSize(1, 1);
        $printer->text("RUC: ".$empresa['ruc']. "\n");
                                
        $printer->text("Domicilio: ".$this->quitar_acentos($empresa['domicilio_fiscal']). "\n");
        $printer->text("---------------------------------\n\n");
                
        $printer->text($this->get_documento($cabecera['operacion'], $cabecera['tipo_documento_id']). " DE VENTA ELECTRONICA \n");
        $printer->text($cabecera['serie'] ."-". $cabecera['numero']. "\n");
        $printer->text("Fecha/hora emision: ". $cabecera['fecha_emision']. "\n");
        $printer->text("Vendedor: ". $this->session->userdata('usuario'). " ". $this->session->userdata('apellido_paterno'). "\n");        
        $printer->text("---------------------------------\n\n");
                
        $printer->text("Cliente: ". $this->quitar_acentos($cabecera['entidad']). "\n");
        $printer->text($cabecera['tipo_entidad'] . ": ". $cabecera['numero_documento']. "\n");
        $printer->text("direccion: ". $this->quitar_acentos($cabecera['direccion_entidad']). "\n");
        $printer->text("---------------------------------\n\n");
        
        $printer->text("Producto/precio/cantidad                  Total\n");
        $tamanio_total = 47;
        foreach($detalle as $value){
            $impuesto_bolsa_item = ($cabecera['total_bolsa'] != null) ? number_format($value['impuesto_bolsa']*$value['cantidad'],2) : 0;
            $impuesto = ($value['tipo_igv_id'] == 1) ? (1+$cabecera['porcentaje_igv']) : 1;
            $total_item = number_format(($value['cantidad']*($value['precio_base']*$impuesto) + $impuesto_bolsa_item), 2);
            
            $longitud_blanco = $tamanio_total - strlen($value['producto']) - strlen($total_item);
            $espacio = $this->encadenar($longitud_blanco);                        
            
            $printer->text($value['producto']);
            $printer->text($espacio);
            $printer->text($total_item);
            $printer->text("\n");
            
            $printer->text(number_format($value['precio_base']*$impuesto,2) . " x " . $value['cantidad']);
            $printer->text("\n");
        }
        $printer->text("\n");
        
        $printer->setTextSize(2, 2);
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text("Grabada: ". $cabecera['simbolo_moneda'] . " " . $cabecera['total_gravada']."\n");
        $printer->text("IGV:  ". $cabecera['simbolo_moneda'] . " " . $cabecera['total_igv']."\n");
        $printer->text("Total: ". $cabecera['simbolo_moneda'] . " " . $cabecera['total_a_pagar']."\n\n");
        
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->setTextSize(1, 1);
        $printer->text($totalLetras."\n\n");
        
                /*imprimeir imagen*/
        try {
            $printer->setJustification(Printer::JUSTIFY_CENTER);            
            $logo = EscposImage::load($rutaqr, false);
            $imgModes = array(
                Printer::IMG_DEFAULT,
                /*Printer::IMG_DOUBLE_WIDTH,
                Printer::IMG_DOUBLE_HEIGHT,
                Printer::IMG_DOUBLE_WIDTH | Printer::IMG_DOUBLE_HEIGHT*/
            );
            foreach ($imgModes as $mode) {
                $printer->bitImage($logo, $mode);
            }
        } catch (Exception $e) {/* $printer->text($e->getMessage() . "\n"); */ }
        
        
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("EMITIDO MEDIANTE PROVEEDOR AUTORIZADO POR LA SUNAT RESOLUCION N.° 097- 2012/SUNAT\n");        
        
        
        $printer->text("\n\n\n");
//        $printer->setJustification(Printer::JUSTIFY_LEFT);
//        $printer->text("Total Descuentos ");
//        $printer->setJustification(Printer::JUSTIFY_RIGHT);
//        $printer->text($desc . "\n");                
        
        $printer->feed(3);
        $printer->cut();
        $printer->pulse();
        $printer->close();                
    }
    
    public function get_documento($operacion, $tipo_documento_id){
        switch ($operacion) {
            case 1:
                switch ($tipo_documento_id) {
                    case 1:
                        $tipo_documento = "FACTURA";
                        break;
                    case 3:
                        $tipo_documento = "BOLETA";
                        break;
                    case 7:
                        $tipo_documento = "NOTA DE CREDITO";                        
                        break;
                    case 8:
                        $tipo_documento = "NOTA DE DEBITO";                        
                        break;
                }                        
                break;
            case 2:
              $tipo_documento = 'NOTA DE VENTA';
              break;
            case 3:
              $tipo_documento = 'COTIZACION';
              break;           
        }
        
        return $tipo_documento;
        
    }
            
    function quitar_acentos($cadena){
        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby';
        $cadena = utf8_decode($cadena);
        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        return utf8_encode($cadena);
    }
    
    function encadenar($nespacios){
        $espacios = '';
        for($i=0;$i<$nespacios;$i++){
            $espacios = $espacios . " ";//voy sumando espacios...
        }
        return $espacios;//devuelvo la cadena con todos los espacios
    }
    
    function fpdf(){
        $pdf = new FPDF('P', 'mm', array(100,150));

        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(40,10,'aa');
        $pdf->Output();
    }
        
}