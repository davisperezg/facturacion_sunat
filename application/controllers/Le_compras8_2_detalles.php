<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use Endroid\QrCode\QrCode;

class le_compras8_2_detalles extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('le_compras8_2_detalles_model');
        $this->load->model('compras_model');
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
        $this->load->view('le_compras8_2/modal_operacion');
    }

    public function modal_importar_excel(){
        $this->load->view('le_compras8_2/modal_importar_excel');
    }

    public function modal_detalle(){
        $this->load->view('le_compras8_2/modal_detalle');
    }

    public function operaciones(){
                
        $data = array(
            'periodo'                           => $_GET['periodo'],
            'codigo_unico'                      => $_GET['codigo_unico'],
            'numero_correlativo'                => $_GET['numero_correlativo'],
                                    
            'fecha_emision'                     => $_GET['fecha_emision'],
            'tipo_documento'                    => $_GET['tipo_documento'],
            
            'serie'                             => $_GET['serie'],
            'numero'                            => $_GET['numero'],
            'adquisicion'                       => $_GET['adquisicion'],
            'otros_conceptos'                   => $_GET['otros_conceptos'],
            'importe_total'                     => $_GET['importe_total'],
            
            'tipo_comprobante_pago'             => $_GET['tipo_comprobante_pago'],
            'serie_pago'                        => $_GET['serie_pago'],
            'anio_dua'                          => $_GET['anio_dua'],
            'numero_pago'                       => $_GET['numero_pago'],
            'retencion_igv'                     => $_GET['retencion_igv'],

            'codigo_moneda'                     => $_GET['codigo_moneda'],
            'tipo_cambio'                       => $_GET['tipo_cambio'],
            
            'pais_sujeto'                       => $_GET['pais_sujeto'],
            'razon_sujeto'                      => $_GET['razon_sujeto'],
            'domicilio_sujeto'                  => $_GET['domicilio_sujeto'],
            'numero_documento_sujeto'           => $_GET['numero_documento_sujeto'],
                        
            'numero_documento_beneficiario'     => $_GET['numero_documento_beneficiario'],
            'razon_beneficiario'                => $_GET['razon_beneficiario'],
            'pais_beneficiario'                 => $_GET['pais_beneficiario'],
            'vinculo'                           => $_GET['vinculo'],

            'renta_bruta'                       => $_GET['renta_bruta'],
            'deduccion'                         => $_GET['deduccion'],                        
            'renta_neta'                        => $_GET['renta_neta'],
            'taza_retencion'                    => $_GET['taza_retencion'],
            'impuesto_retenido'                 => $_GET['impuesto_retenido'],
            
            'doble_disposicion'                 => $_GET['doble_disposicion'],
            'exoneracion_aplicada'              => $_GET['exoneracion_aplicada'],            
            'tipo_renta'                        => $_GET['tipo_renta'],
            'modalidad'                         => $_GET['modalidad'],
            'aplica_ley'                        => $_GET['aplica_ley'],
            'estado'                            => $_GET['estado'],
            
            'insercion_automatica'              =>  0
        );

        if($_GET['fecha_emision'] != '')    $data = array_merge($data, array('fecha_emision' => format_fecha_0000_00_00($_GET['fecha_emision'])));
        
        if($_GET['id'] != ''){
            $this->le_compras8_2_detalles_model->modificar($_GET['id'], $data);
        }else{
            $this->le_compras8_2_detalles_model->insertar($data);
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
        
        //Se hace los insert si No existen datos del mes y anio en tabla le_compras8_2_detalles
        $datos_libro = $this->le_compras8_2_detalles_model->select(3, '', array('periodo' => $anio.$mes."00"));
        if(count($datos_libro) == 0){
            $compras = $this->compras_model->query_standar(3, '', '', '', $condicion, ' ORDER BY com.id DESC');            
            $contador = 1;
            foreach($compras as $value){
                $icbper         = ( $value['total_bolsa'] == null ) ? 0 : $value['total_bolsa'];
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
                    
                    'tipo_documento_proveedor'      =>  $value['tipo_entidad_codigo'],
                    'numero_documento'              =>  $value['numero_documento'],
                    'razon_social'                  =>  $value['entidad'],
                    
                    'base_imponible_tipo_1'         =>  $value['total_gravada'],
                    'igv_tipo_1'                    =>  $value['total_igv'],
                    'no_grabadas'                   =>  ($value['total_exonerada'] + $value['total_inafecta'] + $value['total_gratuita'] + $value['total_exportacion']),
                    'ICBPER'                        =>  $icbper,
                    'importe_total'                 =>  $value['total_a_pagar'],
                    
                    'codigo_moneda'                 =>  $value['moneda_abreviatura'],
                    'tipo_cambio'                   =>  $tipo_de_cambio,
                    
                    'estado'                        =>  '1',
                    'compra_id'                     =>  $value['compra_id'],
                    'insercion_automatica'          =>  '1'
                );
                
                if( ($value['tipo_documento_codigo'] == '07') || ($value['tipo_documento_codigo'] == '08') ){
                    
                    $data_nota = $this->compras_model->select_compra_con_tipo_documentos(2, array('fecha_emision', 'codigo', 'serie', 'numero'), array('com.id' => $value['compra_relacionado_id']));
                    $array_nota_credito = array(
                        'da_fecha_emision'      => $data_nota['fecha_emision'],
                        'da_tipo_documento'     => $data_nota['codigo'],
                        'da_serie'              => $data_nota['serie'],
                        'da_numero'             => $data_nota['numero'],
                        
                        'base_imponible_tipo_1' =>  -$value['total_gravada'],
                        'igv_tipo_1'            =>  -$value['total_igv'],                        
                        'importe_total'         =>  -$value['total_a_pagar'],
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
                $this->le_compras8_2_detalles_model->insertar($data);
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('m')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('n')->setWidth(70);
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
        $objPHPExcel->getActiveSheet()->setCellValue('F1', "Tipo documento");
        $objPHPExcel->getActiveSheet()->setCellValue('G1', "Serie");
        $objPHPExcel->getActiveSheet()->setCellValue('H1', "Número");        
        
        $objPHPExcel->getActiveSheet()->setCellValue('I1', "Adquisición");
        $objPHPExcel->getActiveSheet()->setCellValue('J1', "Otros conceptos");
        $objPHPExcel->getActiveSheet()->setCellValue('K1', "Importe total");        

        $objPHPExcel->getActiveSheet()->setCellValue('L1', "Tipo comprobante Pago");        
        $objPHPExcel->getActiveSheet()->setCellValue('M1', "Serie pago");
        $objPHPExcel->getActiveSheet()->setCellValue('N1', "Año DUA");                                                
        $objPHPExcel->getActiveSheet()->setCellValue('O1', "Número Pago");

        $objPHPExcel->getActiveSheet()->setCellValue('P1', "Retención IGV");                
        
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', "Código Moneda");
        $objPHPExcel->getActiveSheet()->setCellValue('R1', "Tipo cambio");
        
        $objPHPExcel->getActiveSheet()->setCellValue('S1', "Pais sujeto");
        $objPHPExcel->getActiveSheet()->setCellValue('T1', "Razón sujeto");
        $objPHPExcel->getActiveSheet()->setCellValue('U1', "Domicilio del sujeto");
        $objPHPExcel->getActiveSheet()->setCellValue('V1', "Número documento sujeto");
        
        $objPHPExcel->getActiveSheet()->setCellValue('W1', "Número documento beneficiario");
        $objPHPExcel->getActiveSheet()->setCellValue('X1', "Razón beneficiario");
        $objPHPExcel->getActiveSheet()->setCellValue('Y1', "País beneficiario");
                
        $objPHPExcel->getActiveSheet()->setCellValue('Z1', "Vinculo");
        
        $objPHPExcel->getActiveSheet()->setCellValue('AA1', "Renta bruta");                                
        $objPHPExcel->getActiveSheet()->setCellValue('AB1', "Deducción");
        $objPHPExcel->getActiveSheet()->setCellValue('AC1', "Renta neta");
        
        $objPHPExcel->getActiveSheet()->setCellValue('AD1', "Taza retención");
        $objPHPExcel->getActiveSheet()->setCellValue('AE1', "Impuesto retenido");
        
        $objPHPExcel->getActiveSheet()->setCellValue('AF1', "Doble disposición");
        $objPHPExcel->getActiveSheet()->setCellValue('AG1', "Exoneración aplicada");
        $objPHPExcel->getActiveSheet()->setCellValue('AH1', "Tipo renta");
        $objPHPExcel->getActiveSheet()->setCellValue('AI1', "Modalidad");                
        $objPHPExcel->getActiveSheet()->setCellValue('AJ1', "Aplica ley");
        $objPHPExcel->getActiveSheet()->setCellValue('AK1', "Estado");                
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////                
        
        $mes = $this->uri->segment(3);
        $mes = (strlen($mes) == 1) ? "0".$mes : $mes;
        $anio = $this->uri->segment(4);        
        
        //Se hace los insert si No existen datos del mes y anio en tabla le_compras8_2_detalles
        $datos_libro = $this->le_compras8_2_detalles_model->select(3, '', array('periodo' => $anio.$mes."00"));
        
        $i = 2;
        foreach ($datos_libro as $value){            
            
            $fecha_emision      = ($value['fecha_emision'] != '') ? format_fecha_00_00_0000($value['fecha_emision']) : '';
            $fecha_vencimiento  = ($value['fecha_vencimiento'] != '') ? format_fecha_00_00_0000($value['fecha_vencimiento']) : '';
            $da_fecha_emision   = ($value['da_fecha_emision'] != '') ? format_fecha_00_00_0000($value['da_fecha_emision']) : '';
            $fecha_emision_detraccion   = ($value['fecha_emision_detraccion'] != '') ? format_fecha_00_00_0000($value['fecha_emision_detraccion']) : '';
            
            $objPHPExcel->getActiveSheet()
                        ->setCellValue('A' . $i, $i - 1)
                        ->setCellValue('B' . $i, $value['periodo'])
                        ->setCellValue('C' . $i, $value['codigo_unico'])
                        ->setCellValue('D' . $i, $value['numero_correlativo'])                                                                           
                    
                        ->setCellValue('E' . $i, $fecha_emision)
                        ->setCellValue('F' . $i, $value['tipo_documento'])
                        ->setCellValue('G' . $i, $value['serie'])                                        
                        ->setCellValue('H' . $i, $value['numero'])
                                                                                          
                        ->setCellValue('I' . $i, $value['adquisicion'])
                        ->setCellValue('J' . $i, $value['otros_conceptos'])
                        ->setCellValue('K' . $i, $value['importe_total'])
                                                                                                
                        ->setCellValue('L' . $i, $value['tipo_comprobante_pago'])
                        ->setCellValue('M' . $i, $value['serie_pago'])
                        ->setCellValue('N' . $i, $value['anio_dua'])
                        ->setCellValue('O' . $i, $value['numero_pago'])
                                              
                        ->setCellValue('P' . $i, $value['retencion_igv'])
                                                                   
                        ->setCellValue('Q' . $i, $value['codigo_moneda'])
                        ->setCellValue('R' . $i, $value['tipo_cambio'])
                                                                     
                        ->setCellValue('S' . $i, $value['pais_sujeto'])
                        ->setCellValue('T' . $i, $value['razon_sujeto'])
                        ->setCellValue('U' . $i, $value['domicilio_sujeto'])
                        ->setCellValue('V' . $i, $value['numero_documento_sujeto'])
                                                                     
                        ->setCellValue('W' . $i, $value['numero_documento_beneficiario'])
                        ->setCellValue('X' . $i, $value['razon_beneficiario'])
                        ->setCellValue('Y' . $i, $value['pais_beneficiario'])

                        ->setCellValue('Z' . $i, $value['vinculo'])
                                                                                        
                        ->setCellValue('AA' . $i, $value['renta_bruta'])
                        ->setCellValue('AB' . $i, $value['deduccion'])
                        ->setCellValue('AC' . $i, $value['renta_neta'])

                        ->setCellValue('AD' . $i, $value['taza_retencion'])
                        ->setCellValue('AE' . $i, $value['impuesto_retenido'])                                        
                        ->setCellValue('AF' . $i, $value['doble_disposicion'])
                        ->setCellValue('AG' . $i, $value['exoneracion_aplicada'])
                        ->setCellValue('AH' . $i, $value['tipo_renta'])
                        ->setCellValue('AI' . $i, $value['modalidad'])
                        ->setCellValue('AJ' . $i, $value['aplica_ley'])
                        ->setCellValue('AK' . $i, $value['estado'])
                        ;
            $i ++;
        }

        $filename = 'Registro_compras' . $mes . '-' . $anio .'.xls'; //save our workbook as this file name
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function importarExcel(){
        $carpeta = "files/libros_electronicos/compras8.2/excel/";
        $archivo = $_GET['imagen'];
        $this->load->library('excel');
        
        $excel = PHPExcel_IOFactory::load($carpeta.$archivo);
        $excel->SetActiveSheetIndex(0);
        $numero_fila = $excel->setActiveSheetIndex(0)->getHighestRow();
        
        for($i = 2; $i <= $numero_fila; $i++){
            $idproducto = $excel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();            
            $fecha_emision              = ($excel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue() != '') ? format_fecha_0000_00_00($excel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue()) : '';                                                                                                                              

            $data = array(
                'periodo'                       => $excel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue(),
                'codigo_unico'                  => $excel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue(),                
                'numero_correlativo'            => $excel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue(),
                
                'fecha_emision'                 => $fecha_emision,
                'tipo_documento'                => $excel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue(),
                'serie'                         => $excel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue(),
                'numero'                        => $excel->getActiveSheet()->getCell('H'.$i)->getCalculatedValue(),
                                
                'adquisicion'                   => $excel->getActiveSheet()->getCell('I'.$i)->getCalculatedValue(),
                'otros_conceptos'               => $excel->getActiveSheet()->getCell('J'.$i)->getCalculatedValue(),
                'importe_total'                 => $excel->getActiveSheet()->getCell('K'.$i)->getCalculatedValue(),                               
                
                'tipo_comprobante_pago'         => $excel->getActiveSheet()->getCell('L'.$i)->getCalculatedValue(),
                'serie_pago'                    => $excel->getActiveSheet()->getCell('M'.$i)->getCalculatedValue(),
                'anio_dua'                      => $excel->getActiveSheet()->getCell('N'.$i)->getCalculatedValue(),
                'numero_pago'                   => $excel->getActiveSheet()->getCell('O'.$i)->getCalculatedValue(),
                                
                'retencion_igv'                 => $excel->getActiveSheet()->getCell('P'.$i)->getCalculatedValue(),
                'codigo_moneda'                 => $excel->getActiveSheet()->getCell('Q'.$i)->getCalculatedValue(),
                'tipo_cambio'                   => $excel->getActiveSheet()->getCell('R'.$i)->getCalculatedValue(),                
                
                'pais_sujeto'                   => $excel->getActiveSheet()->getCell('S'.$i)->getCalculatedValue(),
                'razon_sujeto'                  => $excel->getActiveSheet()->getCell('T'.$i)->getCalculatedValue(),
                'domicilio_sujeto'              => $excel->getActiveSheet()->getCell('U'.$i)->getCalculatedValue(),
                'numero_documento_sujeto'       => $excel->getActiveSheet()->getCell('V'.$i)->getCalculatedValue(),                
                
                'numero_documento_beneficiario' => $excel->getActiveSheet()->getCell('W'.$i)->getCalculatedValue(),
                'razon_beneficiario'            => $excel->getActiveSheet()->getCell('X'.$i)->getCalculatedValue(),
                'pais_beneficiario'             => $excel->getActiveSheet()->getCell('Y'.$i)->getCalculatedValue(),                                
                
                'vinculo'                       => $excel->getActiveSheet()->getCell('Z'.$i)->getCalculatedValue(),
                'renta_bruta'                   => $excel->getActiveSheet()->getCell('AA'.$i)->getCalculatedValue(),                
                'deduccion'                     => $excel->getActiveSheet()->getCell('AB'.$i)->getCalculatedValue(),
                'renta_neta'                    => $excel->getActiveSheet()->getCell('AC'.$i)->getCalculatedValue(),                
                
                'taza_retencion'                => $excel->getActiveSheet()->getCell('AD'.$i)->getCalculatedValue(),
                'impuesto_retenido'             => $excel->getActiveSheet()->getCell('AE'.$i)->getCalculatedValue(),
                'doble_disposicion'             => $excel->getActiveSheet()->getCell('AF'.$i)->getCalculatedValue(),
                'exoneracion_aplicada'          => $excel->getActiveSheet()->getCell('AG'.$i)->getCalculatedValue(),
                'tipo_renta'                    => $excel->getActiveSheet()->getCell('AH'.$i)->getCalculatedValue(),
                'modalidad'                     => $excel->getActiveSheet()->getCell('AI'.$i)->getCalculatedValue(),                                                            
                'aplica_ley'                    => $excel->getActiveSheet()->getCell('AJ'.$i)->getCalculatedValue(),
                'estado'                        => $excel->getActiveSheet()->getCell('AK'.$i)->getCalculatedValue()
            );
            $this->le_compras8_2_detalles_model->insertar($data);
        }
        $jsondata = array(
            'success'       =>  true,
            'message'       =>  'Operación correcta'
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }

    public function guardar_file_excel(){
        $carpeta = "files/libros_electronicos/compras8.2/excel/";
        
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
            $this->le_compras8_2_detalles_model->delete($id);
            $jsondata = array(
                'success'       =>  true,
                'message'       =>  'Operación correcta'
            );
            echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
        }
    }
    
    public function descargarTxt(){        
        $carpeta = "files/libros_electronicos/compras8.2/txt/";
        $mes = $this->uri->segment(3);
        $mes = (strlen($mes) == 1) ? "0".$mes : $mes;
        $anio = $this->uri->segment(4);
        $ruc_empresa = $this->uri->segment(5);
        
        $condicion = array(
            'MONTH(fecha_emision)' => "=".$mes, 
            'YEAR(fecha_emision)' => "=".$anio
        );        
        
        //Se hace los insert si No existen datos del mes y anio en tabla le_compras8_2_detalles
        $datos_libro = $this->le_compras8_2_detalles_model->select(3, '', array('periodo' => $anio.$mes."00"));
        //LE    RRRRRRRRRRR AAAAMM00        14010000       OIM1.TXT
        
        $informacion = (count($datos_libro) == 0) ? 0 : 1;
        $name_archivo = "LE".$ruc_empresa.$anio.$mes."00080200001".$informacion."11.txt";
        $file = fopen($carpeta.$name_archivo, "w");
        //var_dump($datos_libro);exit;
        
        foreach ($datos_libro as $value){
            fwrite($file, $value['periodo'] ."|".
                            $value['codigo_unico'] ."|".
                            $value['numero_correlativo'] ."|".                    
                    
                            $value['fecha_emision_cf_raya'] ."|".
                            $value['tipo_documento'] ."|".
                            $value['serie'] ."|".
                            $value['numero'] ."|".                    
                    
                            $value['adquisicion'] ."|".
                            $value['otros_conceptos'] ."|".
                            $value['importe_total'] ."|".  
                                        
                            $value['tipo_comprobante_pago'] ."|".
                            $value['serie_pago'] ."|".
                            $value['anio_dua'] ."|".
                            $value['numero_pago'] ."|".
                                        
                            $value['retencion_igv'] ."|".
                            $value['codigo_moneda'] ."|".
                            $value['tipo_cambio'] ."|".

                            $value['pais_sujeto'] ."|".
                            $value['razon_sujeto'] ."|".
                            $value['domicilio_sujeto'] ."|".
                            $value['numero_documento_sujeto'] ."|".
                    
                            $value['numero_documento_beneficiario'] ."|".
                            $value['razon_beneficiario'] ."|".
                            $value['pais_beneficiario'] ."|".                    
                            $value['vinculo'] ."|".
                            $value['renta_bruta'] ."|".
                            $value['deduccion'] ."|".
                            $value['renta_neta'] ."|".
                    
                            $value['taza_retencion'] ."|".
                            $value['impuesto_retenido'] ."|".
                            $value['doble_disposicion'] ."|".
                            $value['exoneracion_aplicada'] ."|".
                            $value['tipo_renta'] ."|".                    
                            $value['modalidad'] ."|".
                            $value['aplica_ley'] ."|".
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