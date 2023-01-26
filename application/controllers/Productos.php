<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Productos extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('accesos_model');                
        $this->load->model('productos_model');
        $this->load->model('categorias_model');
        $this->load->model('unidades_model');
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
        $this->load->view('productos/index');
        $this->load->view('templates/footer');
    }
    
    public function nuevo(){
        $this->accesos_model->menuGeneral();
        $this->load->view('productos/nuevo');
        $this->load->view('templates/footer');
    }
    
    public function modal_operacion(){
        $this->load->view('productos/modal_operacion');
    }
    
    public function modal_detalle(){
        $this->load->view('productos/modal_detalle');
    }
    
    public function modal_imagen(){
        $this->load->view('productos/modal_imagen');
    }
    
    public function guardar_imagen(){
        $carpeta = "images/productos/";
        opendir($carpeta);
        $destino = $carpeta.$_FILES['imagen']['name'];
        if(copy($_FILES['imagen']['tmp_name'], $destino)){ 
            $data = array(
                'mostrar_imagen' => base_url()."images/productos/".$_FILES['imagen']['name']
            );
            echo json_encode($data);
        }else{
            echo "problema al cargar";
        }
    }
    
    //al momento de vender.
    public function operacion_fast(){
        $data = array(
            'codigo_sunat'      =>  $_GET['codigo_sunat'],
            'codigo'            =>  $_GET['codigo'],
            'producto'          =>  $_GET['producto'],
            'precio_base_venta' =>  $_GET['precio_base_venta'],
            'categoria_id'      =>  $_GET['categoria_id'],
            'unidad_id'         =>  $_GET['unidad_id'],
            'stock_inicial'     =>  1,
            'stock_actual'      =>  1,
            'fecha_insert'      =>  date("Y-m-d H:i:s"),
            'empleado_insert'   =>  $this->session->userdata('empleado_id')
        );

        $this->productos_model->insertar($data);
        $producto_id = $this->productos_model->max_producto_id();
                
        $jsondata = array(
            'success'       =>  true,
            'message'       =>  'Operación correcta',
            'producto_id'   =>  $producto_id
        );
        echo json_encode($jsondata);
    }
    
    public function operacion_fast_compra(){
        $data = array(
            'codigo_sunat'      =>  $_GET['codigo_sunat'],
            'codigo'            =>  $_GET['codigo'],
            'producto'          =>  $_GET['producto'],
            'precio_costo'      =>  $_GET['precio_costo'],
            'precio_base_venta' =>  $_GET['precio_base_venta'],
            'categoria_id'      =>  $_GET['categoria_id'],
            'unidad_id'         =>  $_GET['unidad_id'],
            'stock_inicial'     =>  1,
            'stock_actual'      =>  1,
            'fecha_insert'      =>  date("Y-m-d H:i:s"),
            'empleado_insert'   =>  $this->session->userdata('empleado_id')
        );

        $this->productos_model->insertar($data);
        $producto_id = $this->productos_model->max_producto_id();
                
        $jsondata = array(
            'success'       =>  true,
            'message'       =>  'Operación correcta',
            'producto_id'   =>  $producto_id
        );
        echo json_encode($jsondata);
    }

    public function operaciones(){
        $data = array(
            'codigo_sunat'      =>  $_GET['codigo_sunat'],
            'codigo'            =>  $_GET['codigo'],
            'producto'          =>  $_GET['producto'],
            'descripcion'       =>  $_GET['descripcion'],
            'precio_base_venta' =>  $_GET['precio_base_venta'],
            'precio_costo'      =>  $_GET['precio_costo'],
            'categoria_id'      =>  $_GET['categoria_id'],
            'unidad_id'         =>  $_GET['unidad_id']
        );
        
        $producto_id = $_GET['producto_id'];
        if($producto_id != ''){//update
            $data_update = array(
                'fecha_update'      =>  date("Y-m-d H:i:s"),
                'empleado_update'   =>  $this->session->userdata('empleado_id')
            );
            $data = array_merge($data, $data_update);
            $this->productos_model->modificar($producto_id, $data);
        }else{//insert
            $data_insert = array(
                'stock_inicial'     =>  $_GET['stock_inicial'],
                'stock_actual'      =>  $_GET['stock_inicial'],
                'fecha_insert'      =>  date("Y-m-d H:i:s"),
                'empleado_insert'   =>  $this->session->userdata('empleado_id')
            );
            $data = array_merge($data, $data_insert);
            $this->productos_model->insertar($data);
            $producto_id = $this->productos_model->max_producto_id();
        }
                
        $jsondata = array(
            'success'       =>  true,
            'message'       =>  'Operación correcta',
            'producto_id'   =>  $producto_id
        );
        echo json_encode($jsondata);
    }
    
    public function kardex_promedio_ponderado(){
        $this->accesos_model->menuGeneral();
        $this->load->view('productos/kardex_promedio_ponderado');
        $this->load->view('templates/footer');
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
        $objPHPExcel->getActiveSheet()->getColumnDimension('d')->setWidth(60);
        $objPHPExcel->getActiveSheet()->getColumnDimension('e')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('f')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('g')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('h')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('i')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('j')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('k')->setWidth(15);
        
        
        $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
        
        $objPHPExcel->getActiveSheet()->setCellValue('A1', "N"); 
        $objPHPExcel->getActiveSheet()->setCellValue('B1', "Código Sunat");
        $objPHPExcel->getActiveSheet()->setCellValue('C1', "Código");
        $objPHPExcel->getActiveSheet()->setCellValue('D1', "Descripción");
        $objPHPExcel->getActiveSheet()->setCellValue('E1', "Categoria");
        $objPHPExcel->getActiveSheet()->setCellValue('F1', "Unidad");
        $objPHPExcel->getActiveSheet()->setCellValue('G1', "P. costo");
        $objPHPExcel->getActiveSheet()->setCellValue('H1', "P. base");
        $objPHPExcel->getActiveSheet()->setCellValue('I1', "P. lista");
        $objPHPExcel->getActiveSheet()->setCellValue('J1', "Stock Inicial");
        $objPHPExcel->getActiveSheet()->setCellValue('K1', "Stock Actual");
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $producto_id    = $this->uri->segment(3);
        $categoria_id   = $this->uri->segment(4);
        $unidad_id      = $this->uri->segment(5);
        
        $condicion = array();
        $condicion = ($producto_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('pro.id' => '='.$producto_id)) : $condicion;
        $condicion = ($categoria_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('cat.id' => '='.$categoria_id)) : $condicion;
        $condicion = ($unidad_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('und.id' => '='.$unidad_id)) : $condicion;
                
        $productos = $this->productos_model->select_productos_all($condicion, ' ORDER BY pro.id desc');
        $i = 2;
        foreach ($productos as $value){
            $objPHPExcel->getActiveSheet()
                        ->setCellValue('A' . $i, $i - 1)
                        ->setCellValue('B' . $i, $value['codigo_sunat'])
                        ->setCellValue('C' . $i, $value['codigo'])
                        ->setCellValue('D' . $i, $value['producto'])
                        ->setCellValue('E' . $i, $value['categoria'])
                        ->setCellValue('F' . $i, $value['unidad'])
                        ->setCellValue('G' . $i, $value['precio_costo'])
                        ->setCellValue('H' . $i, $value['precio_base_venta'])
                        ->setCellValue('I' . $i, number_format(($value['precio_base_venta'] * 1.18), 2))
                        ->setCellValue('J' . $i, $value['stock_inicial'])
                        ->setCellValue('K' . $i, $value['stock_actual']);
            $i ++;
        }                        

        //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_end_clean();
        
        //header('Content-Type: application/txt'); //mime type
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        
        //$extension = '.xls';
        $extension = '.xlsx';
        $filename = 'Reporte_productos_' . date("d-m-Y") . '---' . rand(1000, 9999) . $extension; //save our workbook as this file name
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
        
        header('Cache-Control: max-age=0'); //no cache
        
        $objWriter->save('php://output');
    }
    
    public function modal_importar_excel(){
        $this->load->view('productos/modal_importar_excel');
    }
    
    public function guardar_file_excel(){
        $carpeta = "files/productos/excel/";
        
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
    
    public function importarExcel(){        
        $carpeta = "files/productos/excel/";
        $archivo = $_GET['imagen'];
        $this->load->library('excel');
        
        $excel = PHPExcel_IOFactory::load($carpeta.$archivo);
        $excel->SetActiveSheetIndex(0);
        $numero_fila = $excel->setActiveSheetIndex(0)->getHighestRow();
        
        $categorias_all = $this->categorias_model->select(3, array('id', 'codigo'));
        $categorias_format = $this->categorias_model->format($categorias_all);
        //var_dump($categorias_format);exit;
        
        $unidades_all = $this->unidades_model->select(3, array('id', 'codigo'));
        $unidades_format = $this->unidades_model->format($unidades_all);                
        
        for($i = 2; $i <= $numero_fila; $i++){            
            if(isset($categorias_format[$excel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue()])){
                $categoria_id = $categorias_format[trim($excel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue())];
            }else{
                $categoria_id = 1;
            }
            
            if(isset($unidades_format[$excel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue()])){
                $unidad_id = $unidades_format[trim($excel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue())];
            }else{
                $unidad_id = 58;
            }           
            
            $data = array(
                'codigo_sunat'          => $excel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue(),
                'codigo'                => $excel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue(),
                'producto'              => $excel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue(),
                
                'categoria_id'          => $categoria_id,
                'unidad_id'             => $unidad_id,
                
                'precio_costo'          => $excel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue(),
                'precio_base_venta'     => $excel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue(),
                'stock_inicial'         => $excel->getActiveSheet()->getCell('I'.$i)->getCalculatedValue(),
                'stock_actual'          => $excel->getActiveSheet()->getCell('I'.$i)->getCalculatedValue(),                
                'fecha_insert'          => date("Y-m-d H:i:s"),
                'empleado_insert'       => $this->session->userdata('empleado_id'),                
            );                        
            $this->productos_model->insertar($data);
        }
        $jsondata = array(
            'success'       =>  true,
            'message'       =>  'Operación correcta'
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }
    
    public function kardex_mensual(){
        $this->accesos_model->menuGeneral();
        $this->load->view('productos/kardex_mensual');
        $this->load->view('templates/footer');
    }
    
    
    public function barcode_get(){
        $filepath = (isset($_GET["filepath"])?$_GET["filepath"]:"");
        $text = (isset($_GET["text"])?$_GET["text"]:"0");
        $size = (isset($_GET["size"])?$_GET["size"]:"20");
        $orientation = (isset($_GET["orientation"])?$_GET["orientation"]:"horizontal");
        $code_type = (isset($_GET["codetype"])?$_GET["codetype"]:"code128");
        $print = (isset($_GET["print"])&&$_GET["print"]=='true'?true:false);
        $sizefactor = (isset($_GET["sizefactor"])?$_GET["sizefactor"]:"1");

        // This function call can be copied into your project and can be made from anywhere in your code
        $this->barcode( $filepath, $text, $size, $orientation, $code_type, $print, $sizefactor );
    }    

    function barcode( $filepath="", $text="0", $size="20", $orientation="horizontal", $code_type="code128", $print=false, $SizeFactor=1 ) {
            $code_string = "";
            // Translate the $text into barcode the correct $code_type
            if ( in_array(strtolower($code_type), array("code128", "code128b")) ) {
                    $chksum = 104;
                    // Must not change order of array elements as the checksum depends on the array's key to validate final code
                    $code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","\`"=>"111422","a"=>"121124","b"=>"121421","c"=>"141122","d"=>"141221","e"=>"112214","f"=>"112412","g"=>"122114","h"=>"122411","i"=>"142112","j"=>"142211","k"=>"241211","l"=>"221114","m"=>"413111","n"=>"241112","o"=>"134111","p"=>"111242","q"=>"121142","r"=>"121241","s"=>"114212","t"=>"124112","u"=>"124211","v"=>"411212","w"=>"421112","x"=>"421211","y"=>"212141","z"=>"214121","{"=>"412121","|"=>"111143","}"=>"111341","~"=>"131141","DEL"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","FNC 4"=>"114131","CODE A"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
                    $code_keys = array_keys($code_array);
                    $code_values = array_flip($code_keys);
                    for ( $X = 1; $X <= strlen($text); $X++ ) {
                            $activeKey = substr( $text, ($X-1), 1);
                            $code_string .= $code_array[$activeKey];
                            $chksum=($chksum + ($code_values[$activeKey] * $X));
                    }
                    $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

                    $code_string = "211214" . $code_string . "2331112";
            } elseif ( strtolower($code_type) == "code128a" ) {
                    $chksum = 103;
                    $text = strtoupper($text); // Code 128A doesn't support lower case
                    // Must not change order of array elements as the checksum depends on the array's key to validate final code
                    $code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","NUL"=>"111422","SOH"=>"121124","STX"=>"121421","ETX"=>"141122","EOT"=>"141221","ENQ"=>"112214","ACK"=>"112412","BEL"=>"122114","BS"=>"122411","HT"=>"142112","LF"=>"142211","VT"=>"241211","FF"=>"221114","CR"=>"413111","SO"=>"241112","SI"=>"134111","DLE"=>"111242","DC1"=>"121142","DC2"=>"121241","DC3"=>"114212","DC4"=>"124112","NAK"=>"124211","SYN"=>"411212","ETB"=>"421112","CAN"=>"421211","EM"=>"212141","SUB"=>"214121","ESC"=>"412121","FS"=>"111143","GS"=>"111341","RS"=>"131141","US"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","CODE B"=>"114131","FNC 4"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
                    $code_keys = array_keys($code_array);
                    $code_values = array_flip($code_keys);
                    for ( $X = 1; $X <= strlen($text); $X++ ) {
                            $activeKey = substr( $text, ($X-1), 1);
                            $code_string .= $code_array[$activeKey];
                            $chksum=($chksum + ($code_values[$activeKey] * $X));
                    }
                    $code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];

                    $code_string = "211412" . $code_string . "2331112";
            } elseif ( strtolower($code_type) == "code39" ) {
                    $code_array = array("0"=>"111221211","1"=>"211211112","2"=>"112211112","3"=>"212211111","4"=>"111221112","5"=>"211221111","6"=>"112221111","7"=>"111211212","8"=>"211211211","9"=>"112211211","A"=>"211112112","B"=>"112112112","C"=>"212112111","D"=>"111122112","E"=>"211122111","F"=>"112122111","G"=>"111112212","H"=>"211112211","I"=>"112112211","J"=>"111122211","K"=>"211111122","L"=>"112111122","M"=>"212111121","N"=>"111121122","O"=>"211121121","P"=>"112121121","Q"=>"111111222","R"=>"211111221","S"=>"112111221","T"=>"111121221","U"=>"221111112","V"=>"122111112","W"=>"222111111","X"=>"121121112","Y"=>"221121111","Z"=>"122121111","-"=>"121111212","."=>"221111211"," "=>"122111211","$"=>"121212111","/"=>"121211121","+"=>"121112121","%"=>"111212121","*"=>"121121211");

                    // Convert to uppercase
                    $upper_text = strtoupper($text);

                    for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
                            $code_string .= $code_array[substr( $upper_text, ($X-1), 1)] . "1";
                    }

                    $code_string = "1211212111" . $code_string . "121121211";
            } elseif ( strtolower($code_type) == "code25" ) {
                    $code_array1 = array("1","2","3","4","5","6","7","8","9","0");
                    $code_array2 = array("3-1-1-1-3","1-3-1-1-3","3-3-1-1-1","1-1-3-1-3","3-1-3-1-1","1-3-3-1-1","1-1-1-3-3","3-1-1-3-1","1-3-1-3-1","1-1-3-3-1");

                    for ( $X = 1; $X <= strlen($text); $X++ ) {
                            for ( $Y = 0; $Y < count($code_array1); $Y++ ) {
                                    if ( substr($text, ($X-1), 1) == $code_array1[$Y] )
                                            $temp[$X] = $code_array2[$Y];
                            }
                    }

                    for ( $X=1; $X<=strlen($text); $X+=2 ) {
                            if ( isset($temp[$X]) && isset($temp[($X + 1)]) ) {
                                    $temp1 = explode( "-", $temp[$X] );
                                    $temp2 = explode( "-", $temp[($X + 1)] );
                                    for ( $Y = 0; $Y < count($temp1); $Y++ )
                                            $code_string .= $temp1[$Y] . $temp2[$Y];
                            }
                    }

                    $code_string = "1111" . $code_string . "311";
            } elseif ( strtolower($code_type) == "codabar" ) {
                    $code_array1 = array("1","2","3","4","5","6","7","8","9","0","-","$",":","/",".","+","A","B","C","D");
                    $code_array2 = array("1111221","1112112","2211111","1121121","2111121","1211112","1211211","1221111","2112111","1111122","1112211","1122111","2111212","2121112","2121211","1121212","1122121","1212112","1112122","1112221");

                    // Convert to uppercase
                    $upper_text = strtoupper($text);

                    for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
                            for ( $Y = 0; $Y<count($code_array1); $Y++ ) {
                                    if ( substr($upper_text, ($X-1), 1) == $code_array1[$Y] )
                                            $code_string .= $code_array2[$Y] . "1";
                            }
                    }
                    $code_string = "11221211" . $code_string . "1122121";
            }

            // Pad the edges of the barcode
            $code_length = 20;
            if ($print) {
                    $text_height = 30;
            } else {
                    $text_height = 0;
            }

            for ( $i=1; $i <= strlen($code_string); $i++ ){
                    $code_length = $code_length + (integer)(substr($code_string,($i-1),1));
            }

            if ( strtolower($orientation) == "horizontal" ) {
                    $img_width = $code_length*$SizeFactor;
                    $img_height = $size;
            } else {
                    $img_width = $size;
                    $img_height = $code_length*$SizeFactor;
            }

            $image = imagecreate($img_width, $img_height + $text_height);
            $black = imagecolorallocate ($image, 0, 0, 0);
            $white = imagecolorallocate ($image, 255, 255, 255);

            imagefill( $image, 0, 0, $white );
            if ( $print ) {
                    imagestring($image, 5, 31, $img_height, $text, $black );
            }

            $location = 10;
            for ( $position = 1 ; $position <= strlen($code_string); $position++ ) {
                    $cur_size = $location + ( substr($code_string, ($position-1), 1) );
                    if ( strtolower($orientation) == "horizontal" )
                            imagefilledrectangle( $image, $location*$SizeFactor, 0, $cur_size*$SizeFactor, $img_height, ($position % 2 == 0 ? $white : $black) );
                    else
                            imagefilledrectangle( $image, 0, $location*$SizeFactor, $img_width, $cur_size*$SizeFactor, ($position % 2 == 0 ? $white : $black) );
                    $location = $cur_size;
            }

            // Draw barcode to the screen or save in a file
            if ( $filepath=="" ) {
                    header ('Content-type: image/png');
                    imagepng($image);
                    imagedestroy($image);
            } else {
                    imagepng($image,$filepath);
                    imagedestroy($image);		
            }
    }
    

}