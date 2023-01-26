<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_compras extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('compras_model');
        $this->load->model('variables_diversas_model');
        $this->load->model('entidades_model');
        $this->load->model('productos_model');
        $this->load->model('anulaciones_model');
        
        $this->load->helper('ayuda');
    }
    
    public function select_by_campo(){
        $compra_id = $this->uri->segment(3);
        $campo = $this->uri->segment(4);
        
        $data = $this->compras_model->select(2, array($campo), array('id' => $compra_id));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    //todos los documento
    //segun entidad y tipo de documento
    public function ws_select_entidad_documento(){
        $entidad_id = $this->uri->segment(3);
        $tipo_documento_id = $this->uri->segment(4);
        
        $condicion = array();
        $condicion = array_merge($condicion, array('entidad_id' => $entidad_id));
        $condicion = ($tipo_documento_id != '') ? array_merge($condicion, array('tipo_documento_id' => $tipo_documento_id)) : $condicion;
        
        $data = $this->compras_model->select(3, array('id', 'moneda_id', 'serie', 'numero'), $condicion);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_select(){
        $pagina = $this->uri->segment(3);
        $filas_por_pagina = $this->uri->segment(4);
        
        $entidad_id = $this->uri->segment(5);
        $tipo_documento = $this->uri->segment(6);
        $serie = $this->uri->segment(7);
        $numero = $this->uri->segment(8);
        $fecha_emision_inicio = $this->uri->segment(9);
        $fecha_emision_final = $this->uri->segment(10);
        $moneda_id = $this->uri->segment(11);        
        $operacion = $this->uri->segment(12);
        
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
        
        $data = $this->compras_model->ws_select(3, '', $pagina, $filas_por_pagina, $condicion, ' ORDER BY com.id DESC');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } 
    
    public function buscador_entidad() {
        $param = $this->input->get('term');
        $data = $this->entidades_model->ws_buscador($param);
        echo json_encode($data);
    }
    
    public function buscador_item() {
        $param = $this->input->get('term');       
        $data = $this->productos_model->select_buscador_completo($param);
        //$data = $this->productos_model->selectAutocompleteprodSC($param);
        echo json_encode($data);
    }
    
    public function maximo_numero(){       
        $data['maximo_numero'] = $this->compras_model->ws_selectMaximoNumero($this->uri->segment(3), $this->uri->segment(4));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function maximo_numero_documento(){
        $operacion          = $this->uri->segment(3);
        $tipo_documento_id  = $this->uri->segment(4);
        $serie              = $this->uri->segment(5);
        $data = $this->compras_model->ultimoNumeroDeSerie($operacion, $tipo_documento_id, $serie);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_cabecera(){
        $compra_id = $this->uri->segment(3);
        $data = $this->compras_model->query_cabecera($compra_id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function updateEstadoOperacion(){
        $this->compras_model->modificar($this->uri->segment(3), array('estado_operacion' => 1));
        echo json_encode(array('message' => 'actualización correcta'), JSON_UNESCAPED_UNICODE);
    }
    
    public function updateEstadoAnulacion(){
        $compra_id = $this->uri->segment(3);
        $this->compras_model->modificar($compra_id, array('estado_anulacion' => 1));        
        
        echo json_encode(array('message' => 'actualización correcta'), JSON_UNESCAPED_UNICODE);
    }    
    
}