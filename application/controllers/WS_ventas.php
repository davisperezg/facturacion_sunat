<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_ventas extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('ventas_model');
        $this->load->model('variables_diversas_model');
        $this->load->model('entidades_model');
        $this->load->model('productos_model');
        $this->load->model('anulaciones_model');
        $this->load->model('cuotas_model');
        $this->load->model('tipo_ncreditos_model');
        $this->load->model('tipo_ndebitos_model');
        
        $this->load->helper('ayuda');
    }
    
    public function select_by_campo(){
        $venta_id = $this->uri->segment(3);
        $campo = $this->uri->segment(4);
        
        $data = $this->ventas_model->select(2, array($campo), array('id' => $venta_id));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    //todos los documento
    //segun entidad y/o tipo de documento
    public function ws_select_entidad_documento(){
        $entidad_id = $this->uri->segment(3);
        $tipo_documento_id = $this->uri->segment(4);
        
        $condicion = array();
        $condicion = (isset($entidad_id) && ($entidad_id != '')) ? array_merge($condicion, array('entidad_id' => $entidad_id)) : $condicion;
        $condicion = (isset($tipo_documento_id) && ($tipo_documento_id != '')) ? array_merge($condicion, array('tipo_documento_id' => $tipo_documento_id)) : $condicion;

        $data = $this->ventas_model->select(3, array('id', 'moneda_id', 'serie', 'numero', 'total_a_pagar'), $condicion);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_select(){
        $pagina                 = $this->uri->segment(3);
        $filas_por_pagina       = $this->uri->segment(4);
        
        $entidad_id             = $this->uri->segment(5);
        $tipo_documento         = $this->uri->segment(6);
        $serie                  = $this->uri->segment(7);
        $numero                 = $this->uri->segment(8);
        $fecha_emision_inicio   = $this->uri->segment(9);
        $fecha_emision_final    = $this->uri->segment(10);
        $moneda_id              = $this->uri->segment(11);        
        $operacion              = $this->uri->segment(12);
        
        $condicion = array();
        $condicion = ($entidad_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('entidad_id' => '='.$entidad_id)) : $condicion;
        $condicion = ($tipo_documento != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('tipo_documento_id' => '='.$tipo_documento)) : $condicion;
        $condicion = ($serie != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('serie' => '='."'".$serie."'")) : $condicion;
        $condicion = ($numero != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('numero' => '='.$numero)) : $condicion;
        $condicion = (($fecha_emision_inicio != $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final == $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('fecha_emision' => '>='."'".format_fecha_0000_00_00($fecha_emision_inicio)."'")) : $condicion;
        $condicion = (($fecha_emision_inicio == $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final != $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('fecha_emision' => '<='."'".format_fecha_0000_00_00($fecha_emision_final)."'")) : $condicion;
        $condicion = (($fecha_emision_inicio != $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final != $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('fecha_emision' => 'BETWEEN '."'".format_fecha_0000_00_00($fecha_emision_inicio)."' AND "."'".format_fecha_0000_00_00($fecha_emision_final)."'")) : $condicion;
        $condicion = ($moneda_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('moneda_id' => '='.$moneda_id)) : $condicion;
        $condicion = array_merge($condicion, array('operacion' => '='.$operacion));
        
        $data = $this->ventas_model->ws_select(3, '', $pagina, $filas_por_pagina, $condicion, ' ORDER BY ven.id DESC');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } 
    
    public function buscador_entidad() {
        $param = $this->input->get('term');
        $data = $this->entidades_model->ws_buscador($param);
        echo json_encode($data);
    }
    
    public function calendario() {        
        $star = $_GET['start'];
        $end = $_GET['end'];
        $condicion = array(' fecha_emision >' => "= '".$star."' "  ,  ' fecha_emision ' => " <= '".$end."'");
        //var_dump($condicion);exit;
        
        $data = $this->ventas_model->select2(3, array('id', "CONCAT(serie, '-', numero, ': ', total_a_pagar ) title", 'fecha_emision AS start'), $condicion);
        echo json_encode($data);
    }
    
    public function buscador_item() {
        $param = $this->input->get('term');       
        $data = $this->productos_model->select_buscador_completo($param);
        //$data = $this->productos_model->selectAutocompleteprodSC($param);
        echo json_encode($data);
    }
    
    public function maximo_numero(){       
        $data['maximo_numero'] = $this->ventas_model->ws_selectMaximoNumero($this->uri->segment(3), $this->uri->segment(4));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function maximo_numero_documento(){
        $operacion          = $this->uri->segment(3);
        $tipo_documento_id  = $this->uri->segment(4);
        $serie              = $this->uri->segment(5);
        $data = $this->ventas_model->ultimoNumeroDeSerie($operacion, $tipo_documento_id, $serie);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_cabecera(){
        $venta_id = $this->uri->segment(3);
        $data = $this->ventas_model->query_cabecera($venta_id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function updateEstadoOperacion(){
        $this->ventas_model->modificar($this->uri->segment(3), array('estado_operacion' => 1));
        echo json_encode(array('message' => 'actualización correcta'), JSON_UNESCAPED_UNICODE);
    }
    
    public function updateEstadoAnulacion(){
        $venta_id = $this->uri->segment(3);
        $this->ventas_model->modificar($venta_id, array('estado_anulacion' => 1));        
        
        echo json_encode(array('message' => 'actualización correcta'), JSON_UNESCAPED_UNICODE);
    }

    public function actualizacion_forma_pago(){
        $venta_id = $this->uri->segment(3);
        $forma_pago_id = $this->uri->segment(4);
        $this->ventas_model->modificar($venta_id, array('forma_pago_id' => $forma_pago_id));
        echo json_encode(array('message' => 'actualización correcta'), JSON_UNESCAPED_UNICODE);
    }    
    
    public function suma_mensual(){        
        $data = $this->ventas_model->suma_mensual($this->uri->segment(3));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function data_ws_monstruo(){
        $venta_id = $this->uri->segment(3);
        
        $datos_empresa = $this->empresas_model->query_api();                         
        $empresa['ruc']                                 = $datos_empresa['ruc'];
        $empresa['razon_social']                        = $datos_empresa['razon_social'];
        $empresa['nombre_comercial']                    = $datos_empresa['nombre_comercial'];        
        $empresa['domicilio_fiscal']                    = $datos_empresa['domicilio_fiscal'];
        $empresa['ubigeo']                              = $datos_empresa['ubigeo'];
        $empresa['urbanizacion']                        = $datos_empresa['urbanizacion'];
        $empresa['distrito']                            = $datos_empresa['distrito'];
        $empresa['provincia']                           = $datos_empresa['provincia'];
        $empresa['departamento']                        = $datos_empresa['departamento'];                
        $empresa['modo']                                = $datos_empresa['modo'];        //1 beta, 2 produccion
        $empresa['usu_secundario_produccion_user']      = $datos_empresa['usu_secundario_produccion_user'];
        $empresa['usu_secundario_produccion_password']  = $datos_empresa['usu_secundario_produccion_password'];
        
        $datos_comprobante = $this->ventas_model->cabecera_api($venta_id);
        $cliente['razon_social_nombres']    = $datos_comprobante['entidad'];
        $cliente['numero_documento']        = $datos_comprobante['numero_documento'];
        $cliente['codigo_tipo_entidad']     = $datos_comprobante['codigo_tipo_entidad']; //catalogo 06 (DNI 1, RUC 6)
        
        $venta['serie']                  = $datos_comprobante['serie'];
        $venta['numero']                 = $datos_comprobante['numero'];
        $venta['fecha_emision']          = $datos_comprobante['fecha_emision'];
        $venta['hora_emision']           = $datos_comprobante['hora_emision'];
        $venta['fecha_vencimiento']      = $datos_comprobante['fecha_vencimiento'];
        $venta['total_gravada']          = $datos_comprobante['total_gravada'];
        $venta['total_igv']              = $datos_comprobante['total_igv'];
        $venta['total_exonerada']        = $datos_comprobante['total_exonerada'];
        $venta['total_inafecta']         = $datos_comprobante['total_inafecta'];
        $venta['total_a_pagar']          = $datos_comprobante['total_a_pagar'];
        $venta['tipo_documento_codigo']  = $datos_comprobante['tipo_documento_codigo']; //catalogo 01 (Para facturas 01, boletas 03, nota de credito 07, nota de debito 08)                
        
        $detalle = $this->venta_detalles_model->query_detalle_api($venta_id);        
        $indice = 0;
        foreach ($detalle as $value_detalle){
            $items[$indice]['producto']          = $value_detalle['producto'];
            $items[$indice]['cantidad']          = $value_detalle['cantidad'];
            $items[$indice]['precio_base']       = $value_detalle['precio_base'];
            $items[$indice]['codigo_sunat']      = $value_detalle['codigo_sunat'];
            $items[$indice]['codigo_producto']   = $value_detalle['codigo_producto'];            
            $items[$indice]['codigo_unidad']     = $value_detalle['codigo_unidad'];   //catalogo 3 (para bienes NIU, servicios ZZ, kilogramo KGM), revisar tabla unidades de BBDD monstruo7.0
            $items[$indice]['tipo_igv_codigo']   = $value_detalle['tipo_igv_codigo']; //catalogo 7 (generalmente con IGV se pone 10)                        
            $indice ++;
        }        

        $jsondata = array(
            'empresa'   =>  $empresa,
            'cliente'   =>  $cliente,
            'venta'     =>  $venta,            
            'items'     =>  $items            
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }
    
    public function data_ws_monstruo_pro(){        
        //$data = $this->ventas_model->select(3, '', array('id' => $this->uri->segment(3)));
        $venta_id = $this->uri->segment(3);
        $venta = $this->ventas_model->cabecera_api($venta_id);
        
        $anticipos = array();
        if(($venta_id != null) && ($venta_id != '') && ($venta_id > 0)){
            $anticipos = $this->venta_anticipos_model->select_anticipo_ventas(3, array('ventas.serie serie, ventas.numero numero, ventas.total_a_pagar total_a_pagar, ventas.id'), array('venta_anticipos.venta_id' => $venta_id));
        }
        
        $empresa = $this->empresas_model->query_api();
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
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }
    
    public function data_ws_monstruo2(){                
        $jsondata = array(
            'documento'     =>      '20604051984-03-B001-7.xml'
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }
    
}