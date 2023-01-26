<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_guias extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('guias_model');
        $this->load->model('guia_detalles_model');
        $this->load->model('variables_diversas_model');
        $this->load->model('ubigeo_departamentos_model');
        $this->load->helper('ayuda');
    }
    
    public function ws_select(){
        $pagina = $this->uri->segment(3);
        $filas_por_pagina = $this->uri->segment(4);
        $entidad_id = $this->uri->segment(5);
        $guia_serie = $this->uri->segment(6);
        $guia_numero = $this->uri->segment(7);
        $fecha_emision_inicio = $this->uri->segment(8);
        $fecha_emision_final = $this->uri->segment(9);
        
        $condicion = array();
        $condicion = ($entidad_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('ent.id' => '='.$entidad_id)) : $condicion;
        $condicion = ($guia_serie != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('gui.serie' => '='."'".$guia_serie."'")) : $condicion;
        $condicion = ($guia_numero != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('gui.numero' => '='.$guia_numero)) : $condicion;
        $condicion = (($fecha_emision_inicio != $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final == $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('gui.fecha_emision' => '>='."'".format_fecha_0000_00_00($fecha_emision_inicio)."'")) : $condicion;
        $condicion = (($fecha_emision_inicio == $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final != $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('gui.fecha_emision' => '<='."'".format_fecha_0000_00_00($fecha_emision_final)."'")) : $condicion;
        $condicion = (($fecha_emision_inicio != $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final != $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('gui.fecha_emision' => 'BETWEEN '."'".format_fecha_0000_00_00($fecha_emision_inicio)."' AND "."'".format_fecha_0000_00_00($fecha_emision_final)."'")) : $condicion;        

        $data = $this->guias_model->ws_select($pagina, $filas_por_pagina, $condicion, $order = '');        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function select_entidad(){
        $entidad_id = $this->uri->segment(3);
        $data = $this->guias_model->select(3, '', array('destinatario_id' => $entidad_id), ' ORDER BY id DESC');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function cargaDepartamentos(){        
        $data['departamentos'] = $this->ubigeo_departamentos_model->select(3, '', '', ' ORDER BY id ASC');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);        
    }
    
    public function cargaProvincias(){        
        $data['provincias'] = $this->ubigeo_departamentos_model->select_pronvincias($this->uri->segment(3));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);        
    }
    
    public function cargaDistritos(){        
        $data['distritos'] = $this->ubigeo_departamentos_model->select_distritos($this->uri->segment(3));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);        
    }
        
    public function ws_cabecera(){
        $guia_id = $this->uri->segment(3);
        $data = $this->guias_model->query_cabecera($guia_id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }    
    
    public function api_guias(){
        $guia_id = $this->uri->segment(3);
        
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
        $empresa['guias_client_id']                     = $datos_empresa['guias_client_id'];
        $empresa['guias_client_secret']                 = $datos_empresa['guias_client_secret'];
        
        //var_dump($empresa);exit;
        
        $datos_comprobante = $this->guias_model->query_standar_cabecera($guia_id);
        
        $guia['serie']                      = $datos_comprobante['serie'];
        $guia['numero']                     = $datos_comprobante['numero'];
        $guia['fecha_emision']              = $datos_comprobante['fecha_emision'];
        $guia['fecha_traslado']             = $datos_comprobante['fecha_traslado'];
        $guia['guia_motivo_traslado_id']    = $datos_comprobante['guia_motivo_traslado_id'];
        $guia['guia_modalidad_traslado_id'] = $datos_comprobante['guia_modalidad_traslado_id'];
        $guia['entidad_id_transporte']      = $datos_comprobante['entidad_id_transporte'];
        $guia['numero_mtc_transporte']      = $datos_comprobante['numero_mtc_transporte'];
        
        $guia['conductor_dni']              = $datos_comprobante['conductor_dni'];
        $guia['conductor_nombres']          = $datos_comprobante['conductor_nombres'];
        $guia['conductor_apellidos']        = $datos_comprobante['conductor_apellidos'];
        $guia['conductor_licencia']         = $datos_comprobante['conductor_licencia'];
        $guia['vehiculo_placa']             = $datos_comprobante['vehiculo_placa'];
        
        $guia['destinatario_tipo']          = $this->variables_diversas_model->tipo_documento($datos_comprobante['tipo_entidad_id']);
        $guia['destinatario_numero_documento']  = $datos_comprobante['numero_documento'];
        $guia['destinatario_nombres_razon']     = $datos_comprobante['entidad'];
        
        $guia['partida_ubigeo']                 = $datos_comprobante['partida_ubigeo'];
        $guia['partida_direccion']              = $datos_comprobante['partida_direccion'];
        
        $guia['llegada_ubigeo']                 = $datos_comprobante['llegada_ubigeo'];
        $guia['llegada_direccion']              = $datos_comprobante['llegada_direccion'];
        
        $guia['peso_total']                     = $datos_comprobante['peso_total'];
        $guia['numero_bultos']                  = $datos_comprobante['numero_bultos'];
        $guia['notas']                          = $datos_comprobante['notas'];
        
        $detalle = $this->guia_detalles_model->query_api($guia_id);        
        $indice = 0;
        foreach ($detalle as $value_detalle){
            $items[$indice]['cantidad']     = $value_detalle['cantidad'];
            $items[$indice]['descripcion']  = $value_detalle['producto'];
            $items[$indice]['codigo']       = $value_detalle['producto_codigo'];            
            $indice ++;
        }        

        $jsondata = array(
            'empresa'   =>  $empresa,
            'guia'      =>  $guia,
            'items'     =>  $items
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }
    
    public function solicito_token(){                
        $datos_empresa = $this->empresas_model->select(2, array('guias_client_id', 'guias_client_secret', 'ruc', 'usu_secundario_produccion_user', 'usu_secundario_produccion_password'));
                
        $post = [
            'guias_client_id'                       => $datos_empresa['guias_client_id'],
            'guias_client_secret'                   => $datos_empresa['guias_client_secret'],
            'ruc'                                   => $datos_empresa['ruc'],
            'usu_secundario_produccion_user'        => $datos_empresa['usu_secundario_produccion_user'],
            'usu_secundario_produccion_password'    => $datos_empresa['usu_secundario_produccion_password']
        ];

        $ch = curl_init('https://facturacionintegral.com/aplicaciones_sistemas/API_SUNAT_GUIAS/1_solicito_token.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        $response = curl_exec($ch);

        curl_close($ch);
        echo ($response);
    }
    
    public function envio_xml_recibe_ticket(){
        //$guia_id = $this->uri->segment(3);
        $guia_id = 13;
        
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
        $empresa['modo']                                = 1;        //1 beta, 2 produccion
        $empresa['usu_secundario_produccion_user']      = $datos_empresa['usu_secundario_produccion_user'];
        $empresa['usu_secundario_produccion_password']  = $datos_empresa['usu_secundario_produccion_password'];
        $empresa['guias_client_id']                     = $datos_empresa['guias_client_id'];
        $empresa['guias_client_secret']                 = $datos_empresa['guias_client_secret'];        
        //var_dump($empresa);exit;
        
        $datos_comprobante = $this->guias_model->query_standar_cabecera(2, '', array('gui.id' => $guia_id));
        //var_dump($datos_comprobante);exit;
        
        $guia['serie']                      = $datos_comprobante['serie'];
        $guia['numero']                     = $datos_comprobante['numero'];
        $guia['fecha_emision']              = $datos_comprobante['fecha_emision_sf'];
        $guia['fecha_traslado']             = $datos_comprobante['fecha_traslado_sf'];
        $guia['guia_motivo_traslado_id']    = $datos_comprobante['guia_motivo_traslado_id'];
        $guia['guia_modalidad_traslado_id'] = $datos_comprobante['guia_modalidad_traslado_id'];
        $guia['entidad_id_transporte']      = $datos_comprobante['entidad_id_transporte'];
        $guia['numero_mtc_transporte']      = $datos_comprobante['numero_mtc_transporte'];
        
        $guia['conductor_dni']              = $datos_comprobante['conductor_dni'];
        $guia['conductor_nombres']          = $datos_comprobante['conductor_nombres'];
        $guia['conductor_apellidos']        = $datos_comprobante['conductor_apellidos'];
        $guia['conductor_licencia']         = $datos_comprobante['conductor_licencia'];
        $guia['vehiculo_placa']             = $datos_comprobante['vehiculo_placa'];
        
        $guia['destinatario_tipo']          = $this->variables_diversas_model->tipo_documento($datos_comprobante['tipo_entidad_id']);
        $guia['destinatario_numero_documento']  = $datos_comprobante['numero_documento'];
        $guia['destinatario_nombres_razon']     = $datos_comprobante['entidad'];
        
        $guia['partida_ubigeo']                 = $datos_comprobante['partida_ubigeo'];
        $guia['partida_direccion']              = $datos_comprobante['partida_direccion'];
        
        $guia['llegada_ubigeo']                 = $datos_comprobante['llegada_ubigeo'];
        $guia['llegada_direccion']              = $datos_comprobante['llegada_direccion'];
        
        $guia['peso_total']                     = $datos_comprobante['peso_total'];
        $guia['numero_bultos']                  = $datos_comprobante['numero_bultos'];
        $guia['notas']                          = $datos_comprobante['notas'];
        
        
        
        $detalle = $this->guia_detalles_model->query_api($guia_id);
        $indice = 0;
        $items = array();
        foreach ($detalle as $value_detalle){
            $items[$indice]['cantidad']         = $value_detalle['cantidad'];
            $items[$indice]['descripcion']      = $value_detalle['producto'];
            $items[$indice]['codigo']           = $value_detalle['producto_codigo'];
            $items[$indice]['codigo_unidad']    = $value_detalle['codigo_unidad'];
            $indice ++;
        }

        $data = array(
            'empresa'   =>  $empresa,
            'guia'      =>  $guia,
            'items'     =>  $items,
            'token'     =>  'eyJraWQiOiJhcGkuc3VuYXQuZ29iLnBlLmtpZDAwMSIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0.eyJzdWIiOiIxMDQ4MTIxMTY0MSIsImF1ZCI6Ilt7XCJhcGlcIjpcImh0dHBzOlwvXC9hcGktY3BlLnN1bmF0LmdvYi5wZVwiLFwicmVjdXJzb1wiOlt7XCJpZFwiOlwiXC92MVwvY29udHJpYnV5ZW50ZVwvZ2VtXCIsXCJpbmRpY2Fkb3JcIjpcIjFcIixcImd0XCI6XCIxMDAwMDBcIn1dfV0iLCJ1c2VyZGF0YSI6eyJudW1SVUMiOiIxMDQ4MTIxMTY0MSIsInRpY2tldCI6IjE5MTk0NDEyMDM5IiwibnJvUmVnaXN0cm8iOiIiLCJhcGVNYXRlcm5vIjoiIiwibG9naW4iOiIxMDQ4MTIxMTY0MU1VTk8yMDE1Iiwibm9tYnJlQ29tcGxldG8iOiJTQU5DSEVaIE1Vw5FPWiBBTkdFTCBKRVNVUyIsIm5vbWJyZXMiOiJTQU5DSEVaIE1Vw5FPWiBBTkdFTCBKRVNVUyIsImNvZERlcGVuZCI6IjAwMjMiLCJjb2RUT3BlQ29tZXIiOiIiLCJjb2RDYXRlIjoiIiwibml2ZWxVTyI6MCwiY29kVU8iOiIiLCJjb3JyZW8iOiIiLCJ1c3VhcmlvU09MIjoiTVVOTzIwMTUiLCJpZCI6IiIsImRlc1VPIjoiIiwiZGVzQ2F0ZSI6IiIsImFwZVBhdGVybm8iOiIiLCJpZENlbHVsYXIiOm51bGwsIm1hcCI6eyJpc0Nsb24iOmZhbHNlLCJkZHBEYXRhIjp7ImRkcF9udW1ydWMiOiIxMDQ4MTIxMTY0MSIsImRkcF9udW1yZWciOiIwMDIzIiwiZGRwX2VzdGFkbyI6IjAwIiwiZGRwX2ZsYWcyMiI6IjAwIiwiZGRwX3ViaWdlbyI6IjE1MDEyNSIsImRkcF90YW1hbm8iOiIwMyIsImRkcF90cG9lbXAiOiIwMiIsImRkcF9jaWl1IjoiMzYxMDQifSwiaWRNZW51IjoiMTkxOTQ0MTIwMzkiLCJqbmRpUG9vbCI6InAwMDIzIiwidGlwVXN1YXJpbyI6IjEiLCJ0aXBPcmlnZW4iOiJJVCIsInByaW1lckFjY2VzbyI6dHJ1ZX19LCJuYmYiOjE2NzMzMTE0NTIsImNsaWVudElkIjoiMjVjZTM0NDEtZjMwZC00ZDBhLWIxODEtMTAwNzM1ZWRmMjgwIiwiaXNzIjoiaHR0cHM6XC9cL2FwaS1zZWd1cmlkYWQuc3VuYXQuZ29iLnBlXC92MVwvY2xpZW50ZXNzb2xcLzI1Y2UzNDQxLWYzMGQtNGQwYS1iMTgxLTEwMDczNWVkZjI4MFwvb2F1dGgyXC90b2tlblwvIiwiZXhwIjoxNjczMzE1MDUyLCJncmFudFR5cGUiOiJwYXNzd29yZCIsImlhdCI6MTY3MzMxMTQ1Mn0.VBx2b6ztfK5wVSnnkTkfB5bk_PkP0XeUJTb_losTzjFgRwoSByo6-2jIybyTmGs5F4gJibxNPfrB2myKAnlSvCXRNhORkh_FIU9-khVqY6hQSXZ1O32oM3afDIjK1jiJVdDm-E4ccu_azun9SgyuPIpA4d2Uk6F-ineqi3o4vRqaD0EXqmZzHn_eGyScIUswki_Z-ppxlzQNP2gNho3VGxH0d27qqORXngGaP6f-XbfKA3xmnB7BDoqiz_4RW8OoX1uQzu2Xro9DkzdcDSeqQQu4KSutDiBK4R8eSSp43h11AdAhbwALKxb01dK8FqS6I7XND47Ad_g6pMNsM-l2iA'
        );        
        //var_dump($data);exit;

        $ch = curl_init('https://facturacionintegral.com/aplicaciones_sistemas/API_SUNAT_GUIAS/2_envio_xml_recibe_ticket.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($ch);
        curl_close($ch);
        
        var_dump($response);exit;
    }
    
    public function envio_ticket() {        
        $post = [
            'ticket'        =>  '9999b072-31e2-4d2f-96bb-305b1aae0ae8',
            'token_access'  =>  'eyJraWQiOiJhcGkuc3VuYXQuZ29iLnBlLmtpZDAwMSIsInR5cCI6IkpXVCIsImFsZyI6IlJTMjU2In0.eyJzdWIiOiIxMDQ4MTIxMTY0MSIsImF1ZCI6Ilt7XCJhcGlcIjpcImh0dHBzOlwvXC9hcGktY3BlLnN1bmF0LmdvYi5wZVwiLFwicmVjdXJzb1wiOlt7XCJpZFwiOlwiXC92MVwvY29udHJpYnV5ZW50ZVwvZ2VtXCIsXCJpbmRpY2Fkb3JcIjpcIjFcIixcImd0XCI6XCIxMDAwMDBcIn1dfV0iLCJ1c2VyZGF0YSI6eyJudW1SVUMiOiIxMDQ4MTIxMTY0MSIsInRpY2tldCI6IjE5MTk0NDEyMDM5IiwibnJvUmVnaXN0cm8iOiIiLCJhcGVNYXRlcm5vIjoiIiwibG9naW4iOiIxMDQ4MTIxMTY0MU1VTk8yMDE1Iiwibm9tYnJlQ29tcGxldG8iOiJTQU5DSEVaIE1Vw5FPWiBBTkdFTCBKRVNVUyIsIm5vbWJyZXMiOiJTQU5DSEVaIE1Vw5FPWiBBTkdFTCBKRVNVUyIsImNvZERlcGVuZCI6IjAwMjMiLCJjb2RUT3BlQ29tZXIiOiIiLCJjb2RDYXRlIjoiIiwibml2ZWxVTyI6MCwiY29kVU8iOiIiLCJjb3JyZW8iOiIiLCJ1c3VhcmlvU09MIjoiTVVOTzIwMTUiLCJpZCI6IiIsImRlc1VPIjoiIiwiZGVzQ2F0ZSI6IiIsImFwZVBhdGVybm8iOiIiLCJpZENlbHVsYXIiOm51bGwsIm1hcCI6eyJpc0Nsb24iOmZhbHNlLCJkZHBEYXRhIjp7ImRkcF9udW1ydWMiOiIxMDQ4MTIxMTY0MSIsImRkcF9udW1yZWciOiIwMDIzIiwiZGRwX2VzdGFkbyI6IjAwIiwiZGRwX2ZsYWcyMiI6IjAwIiwiZGRwX3ViaWdlbyI6IjE1MDEyNSIsImRkcF90YW1hbm8iOiIwMyIsImRkcF90cG9lbXAiOiIwMiIsImRkcF9jaWl1IjoiMzYxMDQifSwiaWRNZW51IjoiMTkxOTQ0MTIwMzkiLCJqbmRpUG9vbCI6InAwMDIzIiwidGlwVXN1YXJpbyI6IjEiLCJ0aXBPcmlnZW4iOiJJVCIsInByaW1lckFjY2VzbyI6dHJ1ZX19LCJuYmYiOjE2NzMzMTE0NTIsImNsaWVudElkIjoiMjVjZTM0NDEtZjMwZC00ZDBhLWIxODEtMTAwNzM1ZWRmMjgwIiwiaXNzIjoiaHR0cHM6XC9cL2FwaS1zZWd1cmlkYWQuc3VuYXQuZ29iLnBlXC92MVwvY2xpZW50ZXNzb2xcLzI1Y2UzNDQxLWYzMGQtNGQwYS1iMTgxLTEwMDczNWVkZjI4MFwvb2F1dGgyXC90b2tlblwvIiwiZXhwIjoxNjczMzE1MDUyLCJncmFudFR5cGUiOiJwYXNzd29yZCIsImlhdCI6MTY3MzMxMTQ1Mn0.VBx2b6ztfK5wVSnnkTkfB5bk_PkP0XeUJTb_losTzjFgRwoSByo6-2jIybyTmGs5F4gJibxNPfrB2myKAnlSvCXRNhORkh_FIU9-khVqY6hQSXZ1O32oM3afDIjK1jiJVdDm-E4ccu_azun9SgyuPIpA4d2Uk6F-ineqi3o4vRqaD0EXqmZzHn_eGyScIUswki_Z-ppxlzQNP2gNho3VGxH0d27qqORXngGaP6f-XbfKA3xmnB7BDoqiz_4RW8OoX1uQzu2Xro9DkzdcDSeqQQu4KSutDiBK4R8eSSp43h11AdAhbwALKxb01dK8FqS6I7XND47Ad_g6pMNsM-l2iA',
            'ruc'           =>  '10481211641',
            'serie'         =>  'TV50',
            'numero'        =>  '11',
        ];

        $ch = curl_init('https://facturacionintegral.com/aplicaciones_sistemas/API_SUNAT_GUIAS/3_envio_ticket.php');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);

        curl_close($ch);
        echo ($response);                
    }
    
}