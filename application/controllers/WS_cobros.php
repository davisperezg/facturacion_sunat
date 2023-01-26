<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_cobros extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('cobros_model');
        $this->load->model('cuotas_model');
        $this->load->model('ventas_model');
        $this->load->model('variables_diversas_model');        
        
        $this->load->helper('ayuda');
    }
        
    public function ws_select(){
        $pagina                 = $this->uri->segment(3);
        $filas_por_pagina       = $this->uri->segment(4);
        $entidad_id             = $this->uri->segment(5);
        $tipo_documento         = $this->uri->segment(6);
        $numero                 = $this->uri->segment(7);
        $fecha_emision_inicio   = $this->uri->segment(8);
        $fecha_emision_final    = $this->uri->segment(9);
        $modo_pago_id           = $this->uri->segment(10);
        $moneda_id              = $this->uri->segment(11);
        
        $condicion = array();
        $condicion = ($entidad_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('entidad_id' => '='.$entidad_id)) : $condicion;
        $condicion = ($tipo_documento != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('tipo_documento_id' => '='.$tipo_documento)) : $condicion;
        $condicion = ($numero != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('cob.id' => '='.$numero)) : $condicion;
        $condicion = (($fecha_emision_inicio != $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final == $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('cob.fecha_pago' => '>='."'".format_fecha_0000_00_00($fecha_emision_inicio)."'")) : $condicion;
        $condicion = (($fecha_emision_inicio == $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final != $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('cob.fecha_pago' => '<='."'".format_fecha_0000_00_00($fecha_emision_final)."'")) : $condicion;
        $condicion = (($fecha_emision_inicio != $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final != $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('cob.fecha_pago' => 'BETWEEN '."'".format_fecha_0000_00_00($fecha_emision_inicio)."' AND "."'".format_fecha_0000_00_00($fecha_emision_final)."'")) : $condicion;
        $condicion = ($modo_pago_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('cob.modo_pago_id' => '='.$modo_pago_id)) : $condicion;
        $condicion = ($moneda_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('ven.moneda_id' => '='.$moneda_id)) : $condicion;        

        $data['registros'] = $this->cobros_model->ws_select($pagina, $filas_por_pagina, 3, array('cob.id cobro_id', 'mpa.modo_pago', 'cob.fecha_pago', 'cob.monto cobro_monto', 'cob.archivo_adjunto cobro_archivo_adjunto', 'ent.entidad', 'ven.serie venta_serie', 'ven.numero venta_numero', 'ven.id venta_id'), $condicion , ' ORDER BY cob.id DESC ');
        $data['total_filas'] = $this->cobros_model->total_filas($condicion);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_select_cobro_id(){
        $cobro_id = $this->uri->segment(3);
        $data = $this->cobros_model->select(2, '', array('id' => '='.$cobro_id), ' ORDER BY id DESC ');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_select_cobro(){
        $venta_id = $this->uri->segment(3);
        $data = $this->cobros_model->select_cobros(2, '', array('venta_id' => '='.$venta_id), ' ORDER BY cob.id DESC ');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_select_cobros(){
        $venta_id = $this->uri->segment(3);
        $data = $this->cobros_model->select_cobros(3, '', array('venta_id' => '='.$venta_id), ' ORDER BY cob.id DESC ');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function documentos_impagos(){
        $entidad_id = $this->uri->segment(3);                
        $data = $this->ventas_model->documentos_impagos(3, array('id','serie', 'numero'), array('entidad_id' => '='.$entidad_id), ' ORDER BY id DESC');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }    
        
    public function ws_update_campo_item(){
        $cobro_id = $this->uri->segment(3);
        $field = $this->uri->segment(4);
        $value = $this->uri->segment(5);
        
        if(isset($cobro_id) && ($cobro_id != '')){            
            $data = array($field => $value);            
            $jsondata = $this->cobros_model->modificar($cobro_id, $data);
            echo json_encode($jsondata);
        }
    }
    
    public function reporte_cobro(){
        $cobro_id = $this->uri->segment(3);
        $data = $this->cobros_model->reporte_cobro($cobro_id);        
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function delete_cobro(){
        $cobro_id = $this->uri->segment(3);
        if(isset($cobro_id) && ($cobro_id != '')){            
            $this->cobros_model->delete($cobro_id);
            $jsondata = array(
                'msg' => 'operación correcta'
            );
            echo json_encode($jsondata);
        }
    }
    
}