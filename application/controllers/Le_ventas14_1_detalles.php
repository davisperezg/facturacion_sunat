<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Endroid\QrCode\QrCode;

class le_ventas14_1_detalles extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('le_ventas14_1_detalles_model');
        $this->load->model('ventas_model');
        $this->load->model('accesos_model');
        $this->load->model('variables_diversas_model');
        $this->load->library('pdf');        
        $this->load->helper('ayuda');
        
        require_once (APPPATH .'libraries/efactura.php');

        $empleado_id = $this->session->userdata('empleado_id');
        if (empty($empleado_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }    

    public function modal_operacion(){
        $this->load->view('le_ventas14_1/modal_operacion');
    }
    
    public function modal_importar_excel(){
        $this->load->view('le_ventas14_1/modal_importar_excel');
    }
    
    public function modal_detalle(){
        $this->load->view('le_ventas14_1/modal_detalle');
    }

    public function operaciones(){
        $data = array(
            'periodo'                       => $_GET['periodo'],
            'codigo_unico'                  => $_GET['codigo_unico'],
            'numero_correlativo'            => $_GET['numero_correlativo'],            
            'tipo_documento'                => $_GET['tipo_documento'],
            'serie'                         => $_GET['serie'],
            'numero'                        => $_GET['numero'],
            'numero_final'                  => $_GET['numero_final'],
            'tipo_cliente'                  => $_GET['tipo_cliente'],
            'numero_documento'              => $_GET['numero_documento'],
            'cliente'                       => $_GET['cliente'],
            'exportacion'                   => $_GET['exportacion'],
            'base_imponible'                => $_GET['base_imponible'],
            'base_imponible_descuento'      => $_GET['base_imponible_descuento'],
            'igv'                           => $_GET['igv'],
            'igv_descuento'                 => $_GET['igv_descuento'],
            'exonerado'                     => $_GET['exonerado'],
            'inafecto'                      => $_GET['inafecto'],
            'isc'                           => $_GET['isc'],
            'arroz_pillado_base_disponible' => $_GET['arroz_pillado_base_disponible'],
            'arroz_pillado_igv'             => $_GET['arroz_pillado_igv'],
            'ICBPER'                        => $_GET['ICBPER'],
            'otros_conceptos'               => $_GET['otros_conceptos'],
            'importe_total'                 => $_GET['importe_total'],
            'codigo_moneda'                 => $_GET['codigo_moneda'],
            'tipo_cambio'                   => $_GET['tipo_cambio'],
            'da_tipo_documento'             => $_GET['da_tipo_documento'],
            'da_serie'                      => $_GET['da_serie'],
            'da_numero'                     => $_GET['da_numero'],
            'identificacion_contrato'       => $_GET['identificacion_contrato'],
            'error_tipo_1'                  => $_GET['error_tipo_1'],
            'medio_pago_cancelacion'        => $_GET['medio_pago_cancelacion'],
            'estado'                        => $_GET['estado'],
            'insercion_automatica'          =>  0
        );        

        if($_GET['fecha_emision'] != '') $data = array_merge($data, array('fecha_emision' => format_fecha_0000_00_00($_GET['fecha_emision'])));
        if($_GET['fecha_vencimiento'] != '') $data = array_merge($data, array('fecha_vencimiento' => format_fecha_0000_00_00($_GET['fecha_vencimiento'])));
        if($_GET['da_fecha_emision'] != '') $data = array_merge($data, array('da_fecha_emision' => format_fecha_0000_00_00($_GET['da_fecha_emision'])));

        if($_GET['id'] != ''){
            $this->le_ventas14_1_detalles_model->modificar($_GET['id'], $data);
        }else{
            $this->le_ventas14_1_detalles_model->insertar($data);
        }

        $jsondata = array(
            'success'       =>  true,
            'message'       =>  'Operación correcta'
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }
    
    function ingresarDatosLibros(){
        $mes = $this->uri->segment(3);
        $mes = (strlen($mes) == 1) ? "0".$mes : $mes;
        $anio = $this->uri->segment(4);        
        
        $condicion = array(
            'MONTH(fecha_emision)' => "=".$mes, 
            'YEAR(fecha_emision)' => "=".$anio
        );        
        
        //Se hace los insert si No existen datos del mes y anio en tabla le_ventas14_1_detalles
        $datos_libro = $this->le_ventas14_1_detalles_model->select(3, '', array('periodo' => $anio.$mes."00"));
        if(count($datos_libro) == 0){
            $ventas = $this->ventas_model->query_standar(3, '', '', '', $condicion, ' ORDER BY ven.id DESC');            
            $contador = 1;                        
            
            foreach($ventas as $value){
                $icbper = ( $value['total_bolsa'] == null ) ? 0 : $value['total_bolsa'];
                $tipo_de_cambio = ( $value['tipo_de_cambio'] == null ) ? 1 : $value['tipo_de_cambio'];
                $data = array(
                    'periodo'                       =>  $anio.$mes."00",
                    'codigo_unico'                  =>  $contador,
                    'numero_correlativo'            =>  'M'.str_pad($contador, 6, '0', STR_PAD_LEFT),
                    'fecha_emision'                 =>  $value['fecha_emision'],
                    'fecha_vencimiento'             =>  $value['fecha_vencimiento'],
                    'tipo_documento'                =>  $value['tipo_documento_codigo'],
                    'serie'                         =>  $value['serie'],
                    'numero'                        =>  $value['numero'],
                    'tipo_cliente'                  =>  $value['tipo_entidad_codigo'],
                    'numero_documento'              =>  $value['numero_documento'],
                    'cliente'                       =>  $value['entidad'],
                    'base_imponible'                =>  $value['total_gravada'],
                    'base_imponible_descuento'      =>  '',
                    'igv'                           =>  $value['total_igv'],
                    'igv_descuento'                 =>  '',
                    'exonerado'                     =>  $value['total_exonerada'],
                    'inafecto'                      =>  $value['total_inafecta'],
                    'ICBPER'                        =>  $icbper,
                    'importe_total'                 =>  $value['total_a_pagar'],
                    'codigo_moneda'                 =>  $value['moneda_abreviatura'],
                    'tipo_cambio'                   =>  $tipo_de_cambio,
                    'estado'                        =>  '1',
                    'venta_id'                      =>  $value['venta_id'],
                    'insercion_automatica'          =>  '1'
                );
                
                
                
                if( ($value['tipo_documento_codigo'] == '07') || ($value['tipo_documento_codigo'] == '08') ){
                    
                    $data_nota = $this->ventas_model->select_venta_con_tipo_documentos(2, array('fecha_emision', 'codigo', 'serie', 'numero'), array('ven.id' => $value['venta_relacionado_id']));
                    $array_nota_credito = array(
                        'da_fecha_emision'  => $data_nota['fecha_emision'],
                        'da_tipo_documento' => $data_nota['codigo'],
                        'da_serie'          => $data_nota['serie'],
                        'da_numero'         => $data_nota['numero'],
                        
                        'base_imponible'    =>  -$value['total_gravada'],
                        'igv'               =>  -$value['total_igv'],
                        'importe_total'     =>  -$value['total_a_pagar'],
                    );
                    $data = array_merge($data, $array_nota_credito);                    
                }
                
                //datos q por ahora no se usan.
//                'isc'                           =>  '',
//                'arroz_pillado_base_disponible' =>  '',
//                'arroz_pillado_igv'             =>  '',
//                'otros_conceptos'               =>  '',
//                'identificacion_contrato'       =>  '',
//                'error_tipo_1'                  =>  '',
//                'medio_pago_cancelacion'        =>  '',
                

                //if($value['total_exportacion'] != null) $data = array_merge($data, array('total_exportacion' => $value['total_exportacion']));
                $this->le_ventas14_1_detalles_model->insertar($data);
                $contador ++;
            }
        }
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('b')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('c')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('d')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('e')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('f')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('g')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('h')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('i')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('j')->setWidth(8);
        $objPHPExcel->getActiveSheet()->getColumnDimension('k')->setWidth(12);
        $objPHPExcel->getActiveSheet()->getColumnDimension('l')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('m')->setWidth(70);
        $objPHPExcel->getActiveSheet()->getColumnDimension('n')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('o')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('p')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('q')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('r')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('s')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('t')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('u')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('v')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('w')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('y')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('z')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(20);
                
        $objPHPExcel->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
        
        $objPHPExcel->getActiveSheet()->setCellValue('A1', "N"); 
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "Periodo");
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "C. único");
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "N. Correlativo");
        
        $objPHPExcel->getActiveSheet()->setCellValue('E1', "F. Emisión");
        $objPHPExcel->getActiveSheet()->setCellValue('F1', "F. Vencimiento");
        $objPHPExcel->getActiveSheet()->setCellValue('G1', "T. Documento");
        $objPHPExcel->getActiveSheet()->setCellValue('H1', "Serie");
        $objPHPExcel->getActiveSheet()->setCellValue('I1', "Número");
        $objPHPExcel->getActiveSheet()->setCellValue('J1', "N. final");
        
        $objPHPExcel->getActiveSheet()->setCellValue('K1', "T.D. Cliente");
        $objPHPExcel->getActiveSheet()->setCellValue('L1', "N. documento");
        $objPHPExcel->getActiveSheet()->setCellValue('M1', "Razón social/Nombres");
        
        $objPHPExcel->getActiveSheet()->setCellValue('N1', "T. Exportación");
        $objPHPExcel->getActiveSheet()->setCellValue('O1', "Base imponible");
        $objPHPExcel->getActiveSheet()->setCellValue('P1', "Base I. Descuento");
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', "IGV");
        $objPHPExcel->getActiveSheet()->setCellValue('R1', "Igv Descuento");
        $objPHPExcel->getActiveSheet()->setCellValue('S1', "Exonerado");
        $objPHPExcel->getActiveSheet()->setCellValue('T1', "Inafecto");
        $objPHPExcel->getActiveSheet()->setCellValue('U1', "I.S.C.");
        $objPHPExcel->getActiveSheet()->setCellValue('V1', "Arroz Pillado B.I.");
        $objPHPExcel->getActiveSheet()->setCellValue('W1', "Arroz Pillado-IGV");
        $objPHPExcel->getActiveSheet()->setCellValue('X1', "ICBPER");
        $objPHPExcel->getActiveSheet()->setCellValue('Y1', "Otros conceptos");
        $objPHPExcel->getActiveSheet()->setCellValue('Z1', "Importe total");
        
        $objPHPExcel->getActiveSheet()->setCellValue('AA1', "C. moneda");
        $objPHPExcel->getActiveSheet()->setCellValue('AB1', "Tipo de cambio");
        
        $objPHPExcel->getActiveSheet()->setCellValue('AC1', "D.R. Fecha de emisión");
        $objPHPExcel->getActiveSheet()->setCellValue('AD1', "D.R. Tipo documento");
        $objPHPExcel->getActiveSheet()->setCellValue('AE1', "D.R. serie");
        $objPHPExcel->getActiveSheet()->setCellValue('AF1', "D.R. número");
        
        $objPHPExcel->getActiveSheet()->setCellValue('AG1', "I. Contrato");
        $objPHPExcel->getActiveSheet()->setCellValue('AH1', "Error Tipo I");
        $objPHPExcel->getActiveSheet()->setCellValue('AI1', "Medio de Pago");
        $objPHPExcel->getActiveSheet()->setCellValue('AJ1', "Estado");
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////                
        
        $mes = $this->uri->segment(3);
        $mes = (strlen($mes) == 1) ? "0".$mes : $mes;
        $anio = $this->uri->segment(4);        
        
        //Se hace los insert si No existen datos del mes y anio en tabla le_ventas14_1_detalles
        $datos_libro = $this->le_ventas14_1_detalles_model->select(3, '', array('periodo' => $anio.$mes."00"));
        
        $i = 2;
        foreach ($datos_libro as $value){            
            
            $fecha_emision      = ($value['fecha_emision'] != '') ? format_fecha_00_00_0000($value['fecha_emision']) : '';
            $fecha_vencimiento  = ($value['fecha_vencimiento'] != '') ? format_fecha_00_00_0000($value['fecha_vencimiento']) : '';
            $da_fecha_emision  = ($value['da_fecha_emision'] != '') ? format_fecha_00_00_0000($value['da_fecha_emision']) : '';
            
            $objPHPExcel->getActiveSheet()
                        ->setCellValue('A' . $i, $i - 1)
                        ->setCellValue('B' . $i, $value['periodo'])
                        ->setCellValue('C' . $i, $value['codigo_unico'])
                        ->setCellValue('D' . $i, $value['numero_correlativo'])
                  
                        ->setCellValue('E' . $i, $fecha_emision)
                        ->setCellValue('F' . $i, $fecha_vencimiento)
                        ->setCellValue('G' . $i, $value['tipo_documento'])
                        ->setCellValue('H' . $i, $value['serie'])
                        ->setCellValue('I' . $i, $value['numero'])
                        ->setCellValue('J' . $i, $value['numero_final'])
                             
                        ->setCellValue('K' . $i, $value['tipo_cliente'])
                        ->setCellValue('L' . $i, $value['numero_documento'])
                        ->setCellValue('M' . $i, $value['cliente'])
          
                        ->setCellValue('N' . $i, $value['exportacion'])
                        ->setCellValue('O' . $i, $value['base_imponible'])
                        ->setCellValue('p' . $i, $value['base_imponible_descuento'])
                        ->setCellValue('Q' . $i, $value['igv'])
                        ->setCellValue('R' . $i, $value['igv_descuento'])
                        ->setCellValue('S' . $i, $value['exonerado'])
                        ->setCellValue('T' . $i, $value['inafecto'])
                        ->setCellValue('U' . $i, $value['isc'])
                        ->setCellValue('V' . $i, $value['arroz_pillado_base_disponible'])
                        ->setCellValue('W' . $i, $value['arroz_pillado_igv'])
                        ->setCellValue('X' . $i, $value['ICBPER'])
                        ->setCellValue('Y' . $i, $value['otros_conceptos'])
                        ->setCellValue('Z' . $i, $value['importe_total'])
                        
                        ->setCellValue('AA' . $i, $value['codigo_moneda'])
                        ->setCellValue('AB' . $i, $value['tipo_cambio'])
                                
                        ->setCellValue('AC' . $i, $da_fecha_emision)
                        ->setCellValue('AD' . $i, $value['da_tipo_documento'])
                        ->setCellValue('AE' . $i, $value['da_serie'])
                        ->setCellValue('AF' . $i, $value['da_numero'])
                                
                        ->setCellValue('AG' . $i, $value['identificacion_contrato'])
                        ->setCellValue('AH' . $i, $value['error_tipo_1'])
                        ->setCellValue('AI' . $i, $value['medio_pago_cancelacion'])
                        ->setCellValue('AJ' . $i, $value['estado']);
            $i ++;
        }

        $filename = 'Registro_ventas' . $mes . '-' . $anio .'.xls'; //save our workbook as this file name
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
    
    public function importarExcel(){
        $carpeta = "files/libros_electronicos/ventas14.1/excel/";
        $archivo = $_GET['imagen'];
        $this->load->library('excel');
        
        $excel = PHPExcel_IOFactory::load($carpeta.$archivo);
        $excel->SetActiveSheetIndex(0);
        $numero_fila = $excel->setActiveSheetIndex(0)->getHighestRow();
        
        for($i = 2; $i <= $numero_fila; $i++){
            $idproducto = $excel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
            
            $fecha_emision      = ($excel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue() != '') ? format_fecha_0000_00_00($excel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue()) : '';
            $fecha_vencimiento  = ($excel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue() != '') ? format_fecha_0000_00_00($excel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue()) : '';
            $da_fecha_emision   = ($excel->getActiveSheet()->getCell('AC'.$i)->getCalculatedValue() != '') ? format_fecha_0000_00_00($excel->getActiveSheet()->getCell('AC'.$i)->getCalculatedValue()) : '';
            
            $data = array(
                'periodo'                       => $excel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue(),
                'codigo_unico'                  => $excel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue(),
                'numero_correlativo'            => $excel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue(),
                'fecha_emision'                 => $fecha_emision,
                'fecha_vencimiento'             => $fecha_vencimiento,
                'tipo_documento'                => $excel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue(),
                'serie'                         => $excel->getActiveSheet()->getCell('H'.$i)->getCalculatedValue(),
                'numero'                        => $excel->getActiveSheet()->getCell('I'.$i)->getCalculatedValue(),
                'numero_final'                  => $excel->getActiveSheet()->getCell('J'.$i)->getCalculatedValue(),
                'tipo_cliente'                  => $excel->getActiveSheet()->getCell('K'.$i)->getCalculatedValue(),
                'numero_documento'              => $excel->getActiveSheet()->getCell('L'.$i)->getCalculatedValue(),
                'cliente'                       => $excel->getActiveSheet()->getCell('M'.$i)->getCalculatedValue(),
                'exportacion'                   => $excel->getActiveSheet()->getCell('N'.$i)->getCalculatedValue(),
                'base_imponible'                => $excel->getActiveSheet()->getCell('O'.$i)->getCalculatedValue(),
                'base_imponible_descuento'      => $excel->getActiveSheet()->getCell('P'.$i)->getCalculatedValue(),
                'igv'                           => $excel->getActiveSheet()->getCell('Q'.$i)->getCalculatedValue(),
                'igv_descuento'                 => $excel->getActiveSheet()->getCell('R'.$i)->getCalculatedValue(),
                'exonerado'                     => $excel->getActiveSheet()->getCell('S'.$i)->getCalculatedValue(),
                'inafecto'                      => $excel->getActiveSheet()->getCell('T'.$i)->getCalculatedValue(),
                'isc'                           => $excel->getActiveSheet()->getCell('U'.$i)->getCalculatedValue(),
                'arroz_pillado_base_disponible' => $excel->getActiveSheet()->getCell('V'.$i)->getCalculatedValue(),
                'arroz_pillado_igv'             => $excel->getActiveSheet()->getCell('W'.$i)->getCalculatedValue(),
                'ICBPER'                        => $excel->getActiveSheet()->getCell('X'.$i)->getCalculatedValue(),
                'otros_conceptos'               => $excel->getActiveSheet()->getCell('Y'.$i)->getCalculatedValue(),
                'importe_total'                 => $excel->getActiveSheet()->getCell('Z'.$i)->getCalculatedValue(),
                'codigo_moneda'                 => $excel->getActiveSheet()->getCell('AA'.$i)->getCalculatedValue(),
                'tipo_cambio'                   => $excel->getActiveSheet()->getCell('AB'.$i)->getCalculatedValue(),
                'da_fecha_emision'              => $da_fecha_emision,
                'da_tipo_documento'             => $excel->getActiveSheet()->getCell('AD'.$i)->getCalculatedValue(),
                'da_serie'                      => $excel->getActiveSheet()->getCell('AE'.$i)->getCalculatedValue(),
                'da_numero'                     => $excel->getActiveSheet()->getCell('AF'.$i)->getCalculatedValue(),
                'identificacion_contrato'       => $excel->getActiveSheet()->getCell('AG'.$i)->getCalculatedValue(),
                'error_tipo_1'                  => $excel->getActiveSheet()->getCell('AH'.$i)->getCalculatedValue(),
                'medio_pago_cancelacion'        => $excel->getActiveSheet()->getCell('AI'.$i)->getCalculatedValue(),
                'estado'                        => $excel->getActiveSheet()->getCell('AJ'.$i)->getCalculatedValue(),
                'insercion_automatica'          =>  0
            );
            
            
            $this->le_ventas14_1_detalles_model->insertar($data);
        }
        $jsondata = array(
            'success'       =>  true,
            'message'       =>  'Operación correcta'
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }
        
    public function guardar_file_excel(){
        $carpeta = "files/libros_electronicos/ventas14.1/excel/";
        
        opendir($carpeta);
        $destino = $carpeta.$_FILES['imagen']['name'];
        if(copy($_FILES['imagen']['tmp_name'], $destino)){ 
            $data = array(
                'respuesta' => 'ok'
            );
            echo json_encode($data);
        }else{
            echo "problema al cargar";
        }
    }
        
    public function delete_item(){
        $id = $this->uri->segment(3);
        if(isset($id) && ($id != '')){            
            $this->le_ventas14_1_detalles_model->delete($id);
            $jsondata = array(
                'success'       =>  true,
                'message'       =>  'Operación correcta'
            );
            echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
        }
    }
    
    public function descargarTxt(){        
        $carpeta = "files/libros_electronicos/ventas14.1/txt/";
        $mes = $this->uri->segment(3);
        $mes = (strlen($mes) == 1) ? "0".$mes : $mes;
        $anio = $this->uri->segment(4);
        $ruc_empresa = $this->uri->segment(5);
        
        $condicion = array(
            'MONTH(fecha_emision)' => "=".$mes, 
            'YEAR(fecha_emision)' => "=".$anio
        );        
        
        //Se hace los insert si No existen datos del mes y anio en tabla le_ventas14_1_detalles
        $datos_libro = $this->le_ventas14_1_detalles_model->select(3, '', array('periodo' => $anio.$mes."00"));
        //LE    RRRRRRRRRRR AAAAMM00        14010000       OIM1.TXT
        
        $informacion = (count($datos_libro) == 0) ? 0 : 1;
        $name_archivo = "LE".$ruc_empresa.$anio.$mes."00140100001".$informacion."11.txt";
        $file = fopen($carpeta.$name_archivo, "w");
        
        foreach ($datos_libro as $value){
            fwrite($file, $value['periodo'] ."|".
                $value['codigo_unico'] ."|".
                $value['numero_correlativo'] ."|".                    

                $value['fecha_emision_cf_raya'] ."|".
                $value['fecha_vencimiento_cf_raya'] ."|".
                $value['tipo_documento'] ."|".
                $value['serie'] ."|".
                $value['numero'] ."|".
                $value['numero_final'] ."|".

                $value['tipo_cliente'] ."|".
                $value['numero_documento'] ."|".
                $value['cliente'] ."|".

                $value['exportacion'] ."|".
                $value['base_imponible'] ."|".
                $value['base_imponible_descuento'] ."|".
                $value['igv'] ."|".
                $value['igv_descuento'] ."|".
                $value['exonerado'] ."|".
                $value['inafecto'] ."|".
                $value['isc'] ."|".
                $value['arroz_pillado_base_disponible'] ."|".
                $value['arroz_pillado_igv'] ."|".
                $value['ICBPER'] ."|".
                $value['otros_conceptos'] ."|".
                $value['importe_total'] ."|".

                $value['codigo_moneda'] ."|".
                $value['tipo_cambio'] ."|".

                $value['da_fecha_emision_cf_raya'] ."|".
                $value['da_tipo_documento'] ."|".
                $value['da_serie'] ."|".
                $value['da_numero'] ."|".                    

                $value['identificacion_contrato'] ."|".
                $value['error_tipo_1'] ."|".
                $value['medio_pago_cancelacion'] ."|".
                $value['estado'] ."|".PHP_EOL);    
        }        
        fclose($file);                
        
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=\"$name_archivo\"");
        readfile($carpeta.$name_archivo);
        
//        $jsondata = array(
//            'success'       =>  true,
//            'message'       =>  'Operación correcta'
//        );
//        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }
}
?>