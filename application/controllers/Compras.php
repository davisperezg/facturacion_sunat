<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Compras extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');        
        $this->load->model('compras_model');        
        $this->load->model('compra_detalles_model');        
        $this->load->model('empresas_model');        
        $this->load->model('correos_model');        
        $this->load->model('entidades_model');
        $this->load->model('tipo_documentos_model');
        $this->load->model('tipo_ncreditos_model');
        $this->load->model('tipo_ndebitos_model');
        $this->load->model('anulaciones_model');
        $this->load->model('productos_model');
        
        $this->load->model('variables_diversas_model');
        $this->load->library('pdf');        
        $this->load->helper('ayuda');
        
        require_once (APPPATH .'libraries/Numletras.php');
        require_once (APPPATH .'libraries/efactura.php');
        require_once (APPPATH .'libraries/qr/phpqrcode/qrlib.php');
        
        $data_actual = strtotime(date("Y-m-d"));
        if($data_actual >= strtotime('2023-01-12')){
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
        $this->load->view('compras/index');
        $this->load->view('templates/footer');
    }
    
    public function nuevo(){
        $this->accesos_model->menuGeneral();
        $this->load->view('compras/nuevo');
        $this->load->view('templates/footer');
    }
    
    public function nuevo_1(){
        $this->accesos_model->menuGeneral();
        $this->load->view('compras/nuevo_1');
        $this->load->view('templates/footer');
    }
    
    public function modal_detalle(){
        $this->load->view('compras/modal_detalle');
    }
    
    public function modal_nueva_entidad(){
        $this->load->view('compras/modal_nueva_entidad');
    }
    
    public function modal_nuevo_producto(){
        $this->load->view('compras/modal_nuevo_producto');
    }
    
    public function operaciones(){
        $operacion          = $_GET['operacion'];
        $enviar_a_facturar  = $_GET['enviar_a_facturar'];

        $data = array(
            'entidad_id'            =>  $_GET['entidad_id'],
            'direccion'             =>  $_GET['direccion'],
            'fecha_emision'         =>  format_fecha_0000_00_00($_GET['fecha_emision']),
            'hora_emision'          =>  date("H:i:s"),            
            'moneda_id'             =>  $_GET['moneda_id'],
            'porcentaje_igv'        =>  $_GET['porcentaje_igv'],            
            'total_a_pagar'         =>  $_GET['total_a_pagar'],            
        );
        
        if($_GET['operacion'] == 1){
            switch ($_GET['tipo_documento_id']) {
                case '7':                
                $data = (($_GET['compra_relacionado_id'] != '') && ($_GET['compra_relacionado_id'] != null)) ? array_merge($data, array('compra_relacionado_id' => $_GET['compra_relacionado_id'], 'tipo_ncredito_id' => $_GET['tipo_ncredito_id'])) : $data;
                break;

                case '8':
                $data = (($_GET['compra_relacionado_id'] != '') && ($_GET['compra_relacionado_id'] != null)) ? array_merge($data, array('compra_relacionado_id' => $_GET['compra_relacionado_id'], 'tipo_ndebito_id' => $_GET['tipo_ndebito_id'])) : $data;
                break;
            }
        }

        $data = ($_GET['fecha_vencimiento'] != '') ? array_merge($data, array('fecha_vencimiento' => format_fecha_0000_00_00($_GET['fecha_vencimiento']))) : $data;
        $data = ($_GET['tipo_de_cambio'] != '') ? array_merge($data, array('tipo_de_cambio' => $_GET['tipo_de_cambio'])) : $data;
        
        $data = ($_GET['total_gravada'] != '') ? array_merge($data, array('total_gravada' => $_GET['total_gravada'])) : $data;
        $data = ($_GET['total_igv'] != '') ? array_merge($data, array('total_igv' => $_GET['total_igv'])) : $data;
        
        $data = ($_GET['total_gratuita'] != '') ? array_merge($data, array('total_gratuita' => $_GET['total_gratuita'])) : $data;
        $data = ($_GET['total_exportacion'] != '') ? array_merge($data, array('total_exportacion' => $_GET['total_exportacion'])) : $data;
        $data = ($_GET['total_exonerada'] != '') ? array_merge($data, array('total_exonerada' => $_GET['total_exonerada'])) : $data;
        $data = ($_GET['total_inafecta'] != '') ? array_merge($data, array('total_inafecta' => $_GET['total_inafecta'])) : $data;
        
        $array_bolsa = array('total_bolsa' => $_GET['total_bolsa'], 'bolsa_monto_unitario' => $_GET['bolsa_monto_unitario']);
        $data = ($_GET['total_bolsa'] != '') ? array_merge($data,  $array_bolsa): $data;                
        $data = ($_GET['notas'] != '') ? array_merge($data, array('notas' => $_GET['notas'])) : $data;
        
        $compra_id = (isset($_GET['compra_id']) && ($_GET['compra_id'] != '')) ? $_GET['compra_id'] : null;
                
        //PARA INSERTAR
        //enviar_a_facturar = 1, SE USA para crear facturas o boletas apartir de una orden de compra
        if(($enviar_a_facturar == 1) || ($compra_id == null)){
            if($enviar_a_facturar == 1) $operacion = 1;
                                    
            if($operacion == 1){
                $data_insert = array(
                    'tipo_documento_id' =>  $_GET['tipo_documento_id'],
                    'serie'             =>  $_GET['serie'],
                    'numero'            =>  $_GET['numero']                    
                );
            }else{
                $data_insert = array(
                    'numero'    =>  $this->compras_model->ultimoNumeroDeSerie($operacion, '', '') + 1
                );                
            }
            $data = array_merge($data, $data_insert);
            
            $data_insert = array(                
                'fecha_insert'      =>  date("Y-m-d H:i:s"),
                'empleado_insert'   =>  $this->session->userdata('empleado_id'),
                'operacion'         =>  $operacion,                
            );
            $data = array_merge($data, $data_insert);
                                    
            //este select impedirá que se guarden multiples registros(Boot medio pendex)                                    
            $condicion = array();
            $condicion = ($operacion == 1) ? array_merge($condicion, array('tipo_documento_id' => $_GET['tipo_documento_id'])) : $condicion;
            $condicion = ($operacion == 1) ? array_merge($condicion, array('serie' => $_GET['serie'])) : $condicion;
            $numero = ($operacion == 1) ? $_GET['numero'] : $this->compras_model->ultimoNumeroDeSerie($operacion, '', '') + 1;
            $condicion = array_merge($condicion, array('numero' => $numero));
            
            $select_id = $this->compras_model->select(1, array('id'), $condicion);
            if($select_id == ''){
                
                $this->db->insert('compras', $data);
                $compra_id_insertada = $this->compras_model->select_max_id();
                
                //cuano se envia a facturar la cotizacion o nota de compra. Se modifica el registro de la nota de compra o cotizacion
                if($enviar_a_facturar == 1){
                    $data_operacion = array(
                        'operacion_id'     =>  $compra_id_insertada,
                    );                    
                    $this->compras_model->modificar($compra_id, $data_operacion);                    
                }

                for($i = 0; $i < count($_GET['producto_id']); $i++){
                    $data_detalle = array(
                        'compra_id' => $compra_id_insertada,
                        'producto_id' => $_GET['producto_id'][$i],
                        'producto' => $_GET['producto'][$i],
                        'cantidad' => $_GET['cantidad'][$i],
                        'precio_base' => $_GET['precio_base'][$i],
                        'tipo_igv_id' => $_GET['tipo_igv_id'][$i]
                    );
                    $data_detalle = ($_GET['impuesto_bolsa'][$i] != '') ? array_merge($data_detalle, array('impuesto_bolsa' => $_GET['impuesto_bolsa'][$i])) : $data_detalle;
                    $this->compra_detalles_model->insertar($data_detalle);
                    // ponemos el "-" a $_GET['cantidad'][$i], ya q vamos a devolver el stock en el update
                    $this->productos_model->variar_stock($_GET['producto_id'][$i], $_GET['cantidad'][$i]);
                }                
            }
        }else{//PARA MODIFICAR
            $compra_id = $_GET['compra_id'];
            
            if($operacion == 1){//modifico estos datos siempre q sehan facturas, boletas o notas (TODOS DE COMPRA)
                $data_update = array(
                    'tipo_documento_id' =>  $_GET['tipo_documento_id'],
                    'serie'             =>  $_GET['serie'],
                    'numero'            =>  $_GET['numero']                    
                );
                $data = array_merge($data, $data_update);
            }
            
            $data_update = array(                
                'fecha_update'      =>  date("Y-m-d H:i:s"),
                'empleado_update'   =>  $this->session->userdata('empleado_id')
            );
            $data = array_merge($data, $data_update);
            $this->compras_model->modificar($_GET['compra_id'], $data);
            
            //borro el detalle guardado para insertar items del formulario que viene
            $this->devolver_stock($compra_id);
            $this->compra_detalles_model->delete_compra_id($compra_id);
            for($i = 0; $i < count($_GET['producto_id']); $i++){
                $data_detalle = array(
                    'compra_id' => $compra_id,
                    'producto_id' => $_GET['producto_id'][$i],
                    'producto' => $_GET['producto'][$i],
                    'cantidad' => $_GET['cantidad'][$i],
                    'precio_base' => $_GET['precio_base'][$i],
                    'tipo_igv_id' => $_GET['tipo_igv_id'][$i]
                );
                $data_detalle = ($_GET['impuesto_bolsa'][$i] != '') ? array_merge($data_detalle, array('impuesto_bolsa' => $_GET['impuesto_bolsa'][$i])) : $data_detalle;
                $this->compra_detalles_model->insertar($data_detalle);
                // ponemos el "-" a $_GET['cantidad'][$i], ya q vamos a devolver el stock en el update
                $this->productos_model->variar_stock($_GET['producto_id'][$i], $_GET['cantidad'][$i]);
            }            
        }        
              
        $jsondata = array(
            'success'       =>  true,
            'message'       =>  'Operación correcta'
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }
    
    public function devolver_stock($compra_id){
        $items = $this->compra_detalles_model->select(3, array('id', 'compra_id', 'producto_id', 'cantidad'), array('compra_id' => $compra_id));        
        foreach ($items as $value){
            $this->productos_model->variar_stock($value['producto_id'], -$value['cantidad']);
        }        
    }
    
    public function pdf_a4($param_compra_id = '', $guardar_pdf = ''){
        $this->compras_model->pdf_a4($param_venta_id = '', $guardar_pdf = '');
    }
    
    public function pdf_ticket(){
        $compra_id = $this->uri->segment(3);
        $data['empresa'] = $empresa = $this->empresas_model->select(2);
        $data['cabecera'] = $cabecera = $this->compras_model->query_cabecera($compra_id);
        $data['detalle'] = $this->compra_detalles_model->query_detalle($compra_id);
        $data['rutaqr'] = $this->GetImgQr($cabecera, $empresa);        
        
        //convetimos el total en texto
        $num = new Numletras();
        $totalVenta = explode(".", $data['cabecera']['total_a_pagar']);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = 'Son: '.$totalLetras.' con '.$totalVenta[1].'/100 '.$data['cabecera']['moneda'];
        $data['totalLetras'] = $totalLetras;

        $html = $this->load->view("compras/pdf_ticket.php",$data,true);
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,440), 'portrait');
        $this->pdf->render();
        $nombre_documento = $data['cabecera']['tipo_documento']."-".$data['cabecera']['serie']."-".$data['cabecera']['numero'];
        $this->pdf->stream("T-$nombre_documento.pdf",
            array("Attachment"=>0)
        );        
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
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "-");
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "Moneda");
        $objPHPExcel->getActiveSheet()->setCellValue('E1', "T.C.");
        $objPHPExcel->getActiveSheet()->setCellValue('F1', "Serie");
        $objPHPExcel->getActiveSheet()->setCellValue('G1', "Número");
        $objPHPExcel->getActiveSheet()->setCellValue('H1', "Cliente");
        $objPHPExcel->getActiveSheet()->setCellValue('I1', "T.Doc.");
        $objPHPExcel->getActiveSheet()->setCellValue('J1', "N.Doc.");
        $objPHPExcel->getActiveSheet()->setCellValue('K1', "Grabada");
        $objPHPExcel->getActiveSheet()->setCellValue('L1', "IGV");
        $objPHPExcel->getActiveSheet()->setCellValue('M1', "Exonerada");
        $objPHPExcel->getActiveSheet()->setCellValue('N1', "Inafecta");
        $objPHPExcel->getActiveSheet()->setCellValue('O1', "Gratuito");
        $objPHPExcel->getActiveSheet()->setCellValue('P1', "Exportación");
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', "Bolsa");
        $objPHPExcel->getActiveSheet()->setCellValue('R1', "Total pagar");
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
        $condicion = ($serie != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('com.serie' => '='.$serie)) : $condicion;
        $condicion = ($numero != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('com.numero' => '='.$numero)) : $condicion;
        
        $condicion = (($fecha_emision_inicio != $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final == $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('fecha_emision' => '>='."'".format_fecha_0000_00_00($fecha_emision_inicio)."'")) : $condicion;
        $condicion = (($fecha_emision_inicio == $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final != $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('fecha_emision' => '<='."'".format_fecha_0000_00_00($fecha_emision_final)."'")) : $condicion;
        $condicion = (($fecha_emision_inicio != $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final != $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('fecha_emision' => 'BETWEEN '."'".format_fecha_0000_00_00($fecha_emision_inicio)."' AND "."'".format_fecha_0000_00_00($fecha_emision_final)."'")) : $condicion;
        
        $condicion = ($moneda != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('com.moneda_id' => '='.$moneda)) : $condicion;
        $condicion = ($operacion != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('com.operacion' => '='.$operacion)) : $condicion;
                                                             
        $compras = $this->compras_model->query_standar(3, '', '', '', $condicion, '', 0);
        $i = 2;
        //var_dump($compras);exit;
        foreach ($compras as $value){
            
            $objPHPExcel->getActiveSheet()
                        ->setCellValue('A' . $i, $i - 1)
                        ->setCellValue('B' . $i, $value['fecha_emision_cf'])
                        ->setCellValue('C' . $i, '-')
                        ->setCellValue('D' . $i, $value['moneda'])
                        ->setCellValue('E' . $i, $value['abreviado'])
                        ->setCellValue('F' . $i, $value['serie'])
                        ->setCellValue('G' . $i, $value['numero'])
                        ->setCellValue('H' . $i, $value['entidad'])
                        ->setCellValue('I' . $i, $value['tipo_entidad_abreviatura'])
                        ->setCellValue('J' . $i, $value['numero_documento'])
                        ->setCellValue('K' . $i, $value['total_gravada'])
                        ->setCellValue('L' . $i, $value['total_igv'])
                        ->setCellValue('M' . $i, $value['total_exonerada'])
                        ->setCellValue('N' . $i, $value['total_inafecta'])
                        ->setCellValue('O' . $i, $value['total_gratuita'])
                        ->setCellValue('P' . $i, $value['total_exportacion'])
                        ->setCellValue('Q' . $i, $value['total_bolsa'])
                        ->setCellValue('R' . $i, $value['total_a_pagar']);
            $i ++;                        
        }

        $filename = 'Reporte_Comprobantes_' . date("d-m-Y") . '---' . rand(1000, 9999) . '.xls'; //save our workbook as this file name
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}