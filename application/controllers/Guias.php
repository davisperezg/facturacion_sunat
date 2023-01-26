<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Guias extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('guias_model');
        $this->load->model('guia_detalles_model');
        $this->load->model('accesos_model');
        
        $this->load->model('variables_diversas_model');
        $this->load->model('ubigeo_departamentos_model');
        $this->load->model('series_model');
        $this->load->model('venta_guias_model');
        $this->load->model('entidades_model');
        $this->load->model('empresas_model');
        $this->load->library('pdf');        
        $this->load->helper('ayuda');
                
        require_once (APPPATH .'libraries/Numletras.php');
        require_once (APPPATH .'libraries/efactura.php');
        require_once (APPPATH .'libraries/qr/phpqrcode/qrlib.php');

        $empleado_id = $this->session->userdata('empleado_id');
        if (empty($empleado_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    
    public function index(){
        $this->accesos_model->menuGeneral();
        $this->load->view('guias/index');
        $this->load->view('templates/footer');
    }
    
    public function operacion(){
        $this->accesos_model->menuGeneral();
        $this->load->view('guias/operacion');
        $this->load->view('templates/footer');
    }
    
    public function operaciones(){                
        $data = array(                        
            'fecha_emision'                 =>  format_fecha_0000_00_00($_GET['fecha_emision']),
            'fecha_traslado'                =>  format_fecha_0000_00_00($_GET['fecha_traslado']),
            'guia_motivo_traslado_id'       =>  $_GET['guia_motivo_traslado_id'],
            'guia_modalidad_traslado_id'    =>  $_GET['guia_modalidad_traslado_id'],            
            
            'destinatario_id'               =>  $_GET['destinatario_id'],
            'partida_ubigeo'                =>  $_GET['partida_ubigeo'],
            'partida_direccion'             =>  $_GET['partida_direccion'],
            'llegada_ubigeo'                =>  $_GET['llegada_ubigeo'],
            'llegada_direccion'             =>  $_GET['llegada_direccion'],
            
            'peso_total'                    =>  $_GET['peso_total'],
            'notas'                         =>  $_GET['notas'],
            
            'insert_fecha'                  =>  date("Y-m-d H:i:s"),
            'insert_empleado_id'            =>  $this->session->userdata('empleado_id')
        );
        
        //la serie y numero solo se coloca en el insert
        if(!isset($_GET['guia_id'])){
            $serie = $this->series_model->select(1, array('serie'), array('tipo_documento_id' => 9));        
            $data_identificacion = array(
                'serie'                         => $serie,
                'numero'                        => ($this->guias_model->ultimoNumeroDeSerie(9, $serie) + 1)
            );
            $data = array_merge($data, $data_identificacion);
        }
                
        if($_GET['guia_modalidad_traslado_id'] == 1){
            $data_modalidad = array(
                'entidad_id_transporte'         =>  $_GET['entidad_id_transporte'],
                'numero_mtc_transporte'         =>  $_GET['numero_mtc_transporte']
            );
        }elseif($_GET['guia_modalidad_traslado_id'] == 2){
            $data_modalidad = array(
                'vehiculo_placa'                =>  $_GET['vehiculo_placa'],
                'conductor_dni'                 =>  $_GET['conductor_dni'],
                'conductor_nombres'             =>  $_GET['conductor_nombres'],
                'conductor_apellidos'           =>  $_GET['conductor_apellidos'],
                'conductor_licencia'            =>  $_GET['conductor_licencia'],
            );
        }
        $data = array_merge($data, $data_modalidad);
        
        //para importaciones
        if($_GET['guia_motivo_traslado_id'] == 7){
            $data_motivo = array(
                'numero_bultos'                =>  $_GET['numero_bultos']
            );
            $data = array_merge($data, $data_motivo);
        }

        if(isset($_GET['guia_id'])){
            $guia_id = $_GET['guia_id'];                        
            $data = array_merge($data, array('respuesta_sunat_codigo' => null));
            $this->guias_model->modificar($_GET['guia_id'], $data);
            $this->guia_detalles_model->delete_guia_id($_GET['guia_id']);
            $this->venta_guias_model->delete_guia_id($_GET['guia_id']);
        }  else {
            $this->guias_model->insertar($data);
            $guia_id = $this->guias_model->select_max_id();
        }
        
        ////////////////////
        if($_GET['documentos_adjuntos_id'] != ""){
            $array_documentos_id = explode(",", substr($_GET['documentos_adjuntos_id'], 0, -1));
            foreach ($array_documentos_id as $value_documento_id){
                $data = array(
                    'venta_id'      =>  $value_documento_id,
                    'guia_id'       =>  $guia_id
                );  
                $this->venta_guias_model->insertar($data);                
            }
        }                
        
        for($i = 0; $i < count($_GET['producto_id']); $i++){
            $data_detalle = array(
                'guia_id' => $guia_id,
                'producto_id' => $_GET['producto_id'][$i],
                'cantidad' => $_GET['cantidad'][$i],
            );
            $this->guia_detalles_model->insertar($data_detalle);
        }

        $jsondata = array(
            'success'       =>  true,
            'message'       =>  'Operación correcta'
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }

    public function modal_nuevo_producto(){
        $this->load->view('guias/modal_nuevo_producto');
    }
    
    public function modal_detalle(){
        $this->load->view('guias/modal_detalle');
    }
    
    public function pdf_a4($param_guia_id = '', $guardar_pdf = ''){
        $guia_id        = ($param_guia_id != '') ? $param_guia_id : $this->uri->segment(3);
        $guardar_pdf    = ($guardar_pdf != '') ? $guardar_pdf : $this->uri->segment(4);
        
        $data['empresa']        = $this->empresas_model->select(2);
        $data['cabecera']       = $this->guias_model->query_standar_cabecera_ubigeos(2, '', array('gui.id' => $guia_id));
        $data['detalle']        = $this->guia_detalles_model->query_standar($guia_id);
        $data['venta_guias']    = $this->venta_guias_model->select_ventas($guia_id);
        
        $data['rutaqr'] = $this->GetImgQr($data['cabecera'], $data['empresa']);

        $html = $this->load->view("guias/pdf_a4.php",$data,true);
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
                
        $nombre_documento = $data['empresa']['ruc'].'-09-'.$data['cabecera']['serie'].'-'.$data['cabecera']['numero'];
        
        if($guardar_pdf == 1){
            $output = $this->pdf->output();
            file_put_contents('files/pdf/guias/'.$nombre_documento.'.pdf', $output);
        }else{            
            $this->pdf->stream("$nombre_documento.pdf",
                array("Attachment"=>0)
            );
        }
        //////////////////////////////////////////
    }
    
    public function enviarSunat(){
        $guia_id = $this->uri->segment(3);
        $guia = $this->guias_model->query_standar_cabecera(2, '', array('gui.id' => $guia_id));
        $detalle = $this->guia_detalles_model->query_standar($guia_id);                
        $empresa = $this->empresas_model->select(2, '', array('id' => 1));
        $venta_guias = $this->venta_guias_model->select_ventas($guia_id);        
        
        $nombre_archivo = $empresa['ruc'].'-09-'.$guia['serie'].'-'.$guia['numero'];
        $path = FCPATH."files/guia_electronica/";
        
        $token_access = $this->token($empresa['guias_client_id'], $empresa['guias_client_secret'], $empresa['ruc'].$empresa['usu_secundario_produccion_user'], $empresa['usu_secundario_produccion_password']);
        $numero_ticket = $guia['ticket_guia'];
        
        if($guia['respuesta_sunat_codigo'] == null){
            $this->crear_files($empresa, $guia, $detalle, $venta_guias, $nombre_archivo, $path);
            $respuesta = $this->envio_xml($path.'FIRMA/', $nombre_archivo, $token_access);        
            $this->guias_model->modificar($guia_id, array('ticket_guia' => $respuesta->numTicket));
            $numero_ticket = $respuesta->numTicket;
        }
        
        $respuesta_ticket = $this->envio_ticket($path.'CDR/', $numero_ticket, $token_access, $empresa['ruc'], $nombre_archivo);        
        $estado_operacion = ($respuesta_ticket['cdr_ResponseCode'] == '0') ? 1 : 0;        
        $data_modificar = array(
            'estado_operacion' => $estado_operacion, 
            'respuesta_sunat_codigo' => $respuesta_ticket['cdr_ResponseCode'],
            'respuesta_sunat_descripcion' => $respuesta_ticket['cdr_msj_sunat']
        );
        $this->guias_model->modificar($guia_id, $data_modificar);
        
        $jsondata = array(
            'success'       =>  true,
            'message'       =>  $respuesta_ticket['cdr_msj_sunat'],
            'codigo'        =>  $respuesta_ticket['cdr_ResponseCode'],
            'error_existe'  =>  $respuesta_ticket['numerror']            
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }
    
    public function enviarSunat_2(){
        $guia_id = $this->uri->segment(3);
        //echo "aaa";exit;
        $guia = $this->guias_model->query_standar_cabecera(2, '', array('gui.id' => $guia_id));
        $detalle = $this->guia_detalles_model->query_standar($guia_id);                
        $empresa = $this->empresas_model->select(2, '', array('id' => 1));
        $venta_guias = $this->venta_guias_model->select_ventas($guia_id);
        
        $xml = $this->desarrollo_xml($empresa, $guia, $detalle, $venta_guias);

        $nombre_archivo = $empresa['ruc'].'-09-'.$guia['serie'].'-'.$guia['numero'];
        $nombre = FCPATH."files/guia_electronica/XML/".$nombre_archivo.".xml";
        
        /////////////////////////////////////////////////////////////////////////////////////                

        $archivo = fopen($nombre, "w+");
        fwrite($archivo, $xml);
        fclose($archivo);

        $this->firmar_xml($nombre_archivo.".xml", $empresa['modo']);
        
        //se elimina en el caso exita el archivo zip, luego se volverá a crear.
        $ruta_zip = FCPATH."files/guia_electronica/FIRMA/".$nombre_archivo.".zip";
        if(file_exists($ruta_zip)){
            unlink($ruta_zip);
        }
        
        //enviar a Sunat       
        //cod_1: = 1 factura, boletas, = 9 es para guias
        //cod_2: = 0 Beta, 1 Produccion
        //cod_3: ruc
        //cod_4: usuario secundario USU(segun seha beta o producción)
        //cod_5: usuario secundario PASSWORD(segun seha beta o producción)
        //cod_6: anulacion -- 0: no anulacion -- 1: enviar anulacion
        
        $user_sec_usu = ($empresa['modo'] == 1) ? $empresa['usu_secundario_produccion_user'] : $empresa['usu_secundario_prueba_user'];
        $user_sec_pass = ($empresa['modo'] == 1) ? $empresa['usu_secundario_produccion_password'] : $empresa['usu_secundario_prueba_passoword'];
        $url = base_url()."ws_sunat_guia/index.php?numero_documento=".$nombre_archivo."&cod_1=9&cod_2=".$empresa['modo']."&cod_3=".$empresa['ruc']."&cod_4=".$user_sec_usu."&cod_5=".$user_sec_pass."&cod_6=0";
        //para testear
        //http://localhost:8080/monstruo6.0/ws_sunat_guia/index.php?numero_documento=20604051984-09-T001-1&cod_1=9&cod_2=0&cod_3=20604051984&cod_4=MODDATOS&cod_5=moddatos&cod_6=0
        //echo $url."<br>";
        $data = file_get_contents($url);        
        $info = json_decode($data, TRUE);        

        $respuesta_sunat = $this->leerRespuestaSunat($nombre_archivo.".xml");
        //var_dump($respuesta_sunat);
        if($respuesta_sunat['respuesta_sunat_codigo'] != null){
            $this->guias_model->modificar($guia_id, $respuesta_sunat);
            
            if($respuesta_sunat['respuesta_sunat_codigo'] == '0'){
                $this->guias_model->modificar($guia_id, array('estado_operacion' => 1));
            }            
        }                
        
        $jsondata = array(
            'success'       =>  true,
            'codigo'        =>  $respuesta_sunat['respuesta_sunat_codigo'],
            'error_existe'  =>  $info['error_existe'],
            'message'       =>  $respuesta_sunat['respuesta_sunat_descripcion'].$info['error_mensaje']
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }
    
    function desarrollo_xml($empresa, $guia, $detalles, $venta_guias){        
        $xml =  '<?xml version="1.0" encoding="UTF-8"?>
            <DespatchAdvice xmlns="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">                    
                    <ext:UBLExtensions>
                        <ext:UBLExtension>
                            <ext:ExtensionContent></ext:ExtensionContent>
                        </ext:UBLExtension>
                    </ext:UBLExtensions>
                    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
                    <cbc:CustomizationID>2.0</cbc:CustomizationID>
                    <cbc:ID>'.$guia['serie'].'-'.$guia['numero'].'</cbc:ID>
                    <cbc:IssueDate>'.$guia['fecha_emision_sf'].'</cbc:IssueDate>
                    <cbc:IssueTime>'.date("H:i:s").'</cbc:IssueTime>
                    <cbc:DespatchAdviceTypeCode>09</cbc:DespatchAdviceTypeCode>
                    <cac:Signature>
                      <cbc:ID>'.$empresa['ruc'].'</cbc:ID>
                      <cac:SignatoryParty>
                        <cac:PartyIdentification>
                          <cbc:ID>'.$empresa['ruc'].'</cbc:ID>
                        </cac:PartyIdentification>
                      </cac:SignatoryParty>
                      <cac:DigitalSignatureAttachment>
                        <cac:ExternalReference>
                          <cbc:URI>'.$empresa['ruc'].'</cbc:URI>
                        </cac:ExternalReference>
                      </cac:DigitalSignatureAttachment>
                    </cac:Signature>';
            $xml .= '<cac:DespatchSupplierParty>
                        <cac:Party>
                            <cac:PartyIdentification>
                                <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$empresa['ruc'].'</cbc:ID>
                            </cac:PartyIdentification>
                            <cac:PartyName>
                                <cbc:Name><![CDATA['.$empresa['empresa'].']]></cbc:Name>
                            </cac:PartyName>
                            <cac:PartyLegalEntity>
                                <cbc:RegistrationName><![CDATA['.$empresa['empresa'].']]></cbc:RegistrationName>
                            </cac:PartyLegalEntity>
                        </cac:Party>
                    </cac:DespatchSupplierParty>';
                    
         $xml .=    '<cac:DeliveryCustomerParty>
                        <cac:Party>
                            <cac:PartyIdentification>
                                <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$guia['numero_documento'].'</cbc:ID>
                            </cac:PartyIdentification>
                            <cac:PartyLegalEntity>
                                <cbc:RegistrationName><![CDATA['.$guia['entidad'].']]></cbc:RegistrationName>
                            </cac:PartyLegalEntity>
                        </cac:Party>
                    </cac:DeliveryCustomerParty>';
                    
            $xml .= '<cac:Shipment>
                        <cbc:ID>SUNAT_Envio</cbc:ID>
                        <cbc:HandlingCode listAgencyName="PE:SUNAT" listName="Motivo de traslado" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo20">01</cbc:HandlingCode>
                        <cbc:GrossWeightMeasure unitCode="KGM">'.$guia['peso_total'].'</cbc:GrossWeightMeasure>';
                        
                        if($guia['guia_motivo_traslado_id'] == 7){//importaciones
                $xml .= '<cbc:TotalTransportHandlingUnitQuantity>'.$guia['numero_bultos'].'</cbc:TotalTransportHandlingUnitQuantity>';
                        }
                        
                $xml .= '<cac:ShipmentStage>
                            <cbc:ID>1</cbc:ID>
                            <cbc:TransportModeCode listAgencyName="PE:SUNAT" listName="Modalidad de traslado" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo18">0'.$guia['guia_modalidad_traslado_id'].'</cbc:TransportModeCode>
                            <cac:TransitPeriod>
                                <cbc:StartDate>'.$guia['fecha_traslado_sf'].'</cbc:StartDate>
                            </cac:TransitPeriod>';
                
                if($guia['guia_modalidad_traslado_id'] == '1'){
                $xml .= '<cac:CarrierParty>
                                <cac:PartyIdentification>
                                    <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$guia['numero_documento_transporte'].'</cbc:ID>
                                </cac:PartyIdentification>
                                <cac:PartyLegalEntity>
                                    <cbc:RegistrationName><![CDATA['.$guia['entidad_transporte'].']]></cbc:RegistrationName>';
                                    if($guia['numero_mtc_transporte'] != ''){
                $xml .=                 '<cbc:CompanyID>'.$guia['numero_mtc_transporte'].'</cbc:CompanyID>';
                                    }
                $xml .=         '</cac:PartyLegalEntity>
                            </cac:CarrierParty>';
                }
                if($guia['guia_modalidad_traslado_id'] == '2'){
                $xml .= '<cac:DriverPerson>
                                <cbc:ID schemeID="1" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">'.$guia['conductor_dni'].'</cbc:ID>
                                <cbc:FirstName>'.$guia['conductor_nombres'].'</cbc:FirstName>
                                <cbc:FamilyName>'.$guia['conductor_apellidos'].'</cbc:FamilyName>
                                <cbc:JobTitle>Principal</cbc:JobTitle>
                                <cac:IdentityDocumentReference>
                                    <cbc:ID>'.$guia['conductor_licencia'].'</cbc:ID>
                                </cac:IdentityDocumentReference>
                            </cac:DriverPerson>';                                                                        
                }

                $xml .= '</cac:ShipmentStage>
                        <cac:Delivery>
                            <cac:DeliveryAddress>
                                <cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">'.$guia['llegada_ubigeo'].'</cbc:ID>
                                <cac:AddressLine>
                                    <cbc:Line>'.$guia['llegada_direccion'].'</cbc:Line>
                                </cac:AddressLine>
                            </cac:DeliveryAddress>
                            <cac:Despatch>
                                <cac:DespatchAddress>
                                    <cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">'.$guia['partida_ubigeo'].'</cbc:ID>
                                    <cac:AddressLine>
                                        <cbc:Line>'.$guia['partida_direccion'].'</cbc:Line>
                                    </cac:AddressLine>
                                </cac:DespatchAddress>
                            </cac:Despatch>
                        </cac:Delivery>';
                        
                        if($guia['guia_modalidad_traslado_id'] == '2'){
                $xml .= '<cac:TransportHandlingUnit>
                            <cac:TransportEquipment>
                                <cbc:ID>'.$guia['vehiculo_placa'].'</cbc:ID>
                            </cac:TransportEquipment>
                        </cac:TransportHandlingUnit>';
                        }
                $xml .= '</cac:Shipment>';        
                    
                    $i = 1;                        
                    foreach($detalles as $values){                    
                    $xml .=  '<cac:DespatchLine>
                        <cbc:ID>'.$i.'</cbc:ID>
                        <cbc:DeliveredQuantity unitCode="'.$values['codigo_unidad'].'">'.$values['cantidad'].'</cbc:DeliveredQuantity>
                        <cac:OrderLineReference>
                            <cbc:LineID>1</cbc:LineID>
                        </cac:OrderLineReference>
                        <cac:Item>
                            <cbc:Description>'.$values['producto'].'</cbc:Description>
                            <cac:SellersItemIdentification>
                            <cbc:ID>'.$values['producto_codigo'].'</cbc:ID>
                            </cac:SellersItemIdentification>
                        </cac:Item>
                    </cac:DespatchLine>';                        
                    $i++;                    
                    }
            $xml.=  '</DespatchAdvice>';
        return $xml;
    }
    
    public function  firmar_xml($name_file, $entorno, $baja = ''){        
        $carpeta_baja = ($baja != '') ? 'BAJA/':'';
        $carpeta = "files/guia_electronica/$carpeta_baja";
        $dir = base_url().$carpeta."XML/".$name_file;
        $xmlstr = file_get_contents($dir);

        $domDocument = new \DOMDocument();
        $domDocument->loadXML($xmlstr);
        $factura  = new Factura();
        $xml = $factura->firmar($domDocument, '', $entorno);
        $content = $xml->saveXML();
        file_put_contents($carpeta."FIRMA/".$name_file, $content);
    }
    
    public function leerRespuestaSunat($nombre_archivo){
        $nombre = FCPATH."ws_sunat_guia\R-".$nombre_archivo;

        $resultado['respuesta_sunat_codigo'] = null;
        $resultado['respuesta_sunat_descripcion'] = null;
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
    
    public function GetImgQr($cabecera, $empresa)  {
        $textoQR = '';
        $textoQR .= $empresa['ruc']."|";//RUC EMPRESA
        
        $textoQR .= "Guía de Remisión Remitente|";//TIPO DE DOCUMENTO 
        $textoQR .= $cabecera['serie']."|";//SERIE
        $textoQR .= $cabecera['numero']."|";//NUMERO
        $textoQR .= "---|";//MTO TOTAL IGV
        $textoQR .= "---|";//MTO TOTAL DEL COMPROBANTE
        //$fechaEmision = (new DateTime($rsComprobante->fecha_de_emision))->format('d-m-Y');
        $textoQR .= $cabecera['fecha_emision']."|";//FECHA DE EMISION 
        //tipo de cliente

     
        $textoQR .= "6|";//TIPO DE DOCUMENTO ADQUIRENTE 
        $textoQR .= $cabecera['numero_documento']."|";//NUMERO DE DOCUMENTO ADQUIRENTE         
        
        $nombreQR = '9-'.$cabecera['serie'].'-'.$cabecera['numero'];
        QRcode::png($textoQR, FCPATH."images/qr/guias/".$nombreQR.".png", QR_ECLEVEL_L, 10, 2);
        
        return FCPATH."images/qr/guias/{$nombreQR}.png";
    }
           
    function token($client_id, $client_secret, $usuario_secundario, $usuario_password){
        $url = "https://api-seguridad.sunat.gob.pe/v1/clientessol/".$client_id."/oauth2/token/";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_POST, true);

        $datos = array(
                'grant_type'    =>  'password',     
                'scope'         =>  'https://api-cpe.sunat.gob.pe',
                'client_id'     =>  $client_id,
                'client_secret' =>  $client_secret,
                'username'      =>  $usuario_secundario,
                'password'      =>  $usuario_password
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($datos));
        curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__.'/cookies.txt');

        $headers = array('Content-Type' => 'Application/json');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($result);
        return $response->access_token;  
    }
    
    function envio_xml($path, $nombre_file, $token_access){
        $curl = curl_init();
        $data = array(
                    'nomArchivo'  =>  $nombre_file.".zip",
                    'arcGreZip'   =>  base64_encode(file_get_contents($path.$nombre_file.'.zip')),
                    'hashZip'     =>  hash_file("sha256", $path.$nombre_file.'.zip')
                );
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/".$nombre_file,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode(array('archivo' => $data)),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '. $token_access,
                'Content-Type: application/json'
            ),
        ));

        $response2 = curl_exec($curl);
        curl_close($curl);
        return json_decode($response2);
    }

    function envio_ticket($ruta_archivo_cdr, $ticket, $token_access, $ruc, $nombre_file){
        if(($ticket == "") || ($ticket == null)){
            $mensaje['cdr_hash'] = '';
            $mensaje['cdr_msj_sunat'] = 'Ticket vacio';
            $mensaje['cdr_ResponseCode']  = null;
            $mensaje['numerror'] = null;
        }else{
        
            $mensaje['ticket'] = $ticket;
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/envios/'.$ticket,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'numRucEnvia: '.$ruc,
                    'numTicket: '.$ticket,
                    'Authorization: Bearer '. $token_access,
                ),
            ));
    
            $response_1  = curl_exec($curl);
            $response3  = json_decode($response_1);
            $codRespuesta = $response3->codRespuesta;
            curl_close($curl);                    
            //var_dump($response3);exit;
            
            $mensaje['ticket_rpta'] = $codRespuesta;
            if($codRespuesta == '99'){
                $error = $response3->error;
                $mensaje['cdr_hash'] = '';
                $mensaje['cdr_msj_sunat'] = $error->desError;
                $mensaje['cdr_ResponseCode'] = '99';
                $mensaje['numerror'] = $error->numError;            	            
            }else if($codRespuesta == '98'){
                $mensaje['cdr_hash'] = '';
                $mensaje['cdr_msj_sunat'] = 'Envío en proceso';
                $mensaje['cdr_ResponseCode']  = '98';
                $mensaje['numerror'] = '98';                        
            }else if($codRespuesta == '0'){
                $mensaje['arcCdr'] = $response3->arcCdr;
                $mensaje['indCdrGenerado'] = $response3->indCdrGenerado;
                file_put_contents($ruta_archivo_cdr . 'R-' . $nombre_file . '.ZIP', base64_decode($response3->arcCdr));
    
                //extraemos archivo zip a xml
                $zip = new ZipArchive;
                if ($zip->open($ruta_archivo_cdr . 'R-' . $nombre_file . '.ZIP') === TRUE) {
                    $zip->extractTo($ruta_archivo_cdr);
                    $zip->close();
                }
                //unlink($ruta_archivo_cdr . 'R-' . $nombre_file . '.ZIP');
    
             //=============hash CDR=================
                $doc_cdr = new DOMDocument();
                $doc_cdr->load($ruta_archivo_cdr . 'R-' . $nombre_file . '.xml');
                
                $mensaje['cdr_hash']            = $doc_cdr->getElementsByTagName('DigestValue')->item(0)->nodeValue;
                $mensaje['cdr_msj_sunat']       = $doc_cdr->getElementsByTagName('Description')->item(0)->nodeValue;
                $mensaje['cdr_ResponseCode']    = $doc_cdr->getElementsByTagName('ResponseCode')->item(0)->nodeValue;        
                $mensaje['numerror']            = '';
            }else{
                $mensaje['cdr_hash']            = '';
                $mensaje['cdr_msj_sunat']       = 'SUNAT FUERA DE SERVICIO';
                $mensaje['cdr_ResponseCode']    = '88';            
                $mensaje['numerror']            = '88';
            }
        }
        return $mensaje;
    }

    function crear_files($empresa, $guia, $detalle, $venta_guias, $nombre_archivo, $path){
        $xml = $this->desarrollo_xml($empresa, $guia, $detalle, $venta_guias);
        
        $archivo = fopen($path."XML/".$nombre_archivo.".xml", "w+");
        fwrite($archivo, $xml);
        fclose($archivo);
        $this->firmar_xml($nombre_archivo.".xml", $empresa['modo']);        
        
        $zip = new ZipArchive();
        if($zip->open($path."FIRMA/".$nombre_archivo.".zip", ZipArchive::CREATE) === true){
            $zip->addFile($path."FIRMA/".$nombre_archivo.".xml", $nombre_archivo.".xml");
        }
        return $nombre_archivo;
    }   
}