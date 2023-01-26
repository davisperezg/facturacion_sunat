<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_pedido_almacenes extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('pedido_almacenes_model');
        $this->load->model('variables_diversas_model');        
        $this->load->helper('ayuda');
    }

    public function maximo_numero_documento(){        
        $data = $this->pedido_almacenes_model->ultimoNumeroDeSerie();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function select(){
        $data = $this->pedido_almacenes_model->select(2, array('notas'), array('id' => $this->uri->segment(3)));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_select(){
        $pagina = $this->uri->segment(3);
        $filas_por_pagina = $this->uri->segment(4);
        
        $empleado_id            = $this->uri->segment(5);
        $numero                 = $this->uri->segment(6);
        $fecha_emision_inicio   = $this->uri->segment(7);
        $fecha_emision_final    = $this->uri->segment(8);
        
        $condicion = array();
        $condicion = ($empleado_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('empleado_insert' => '='.$empleado_id)) : $condicion;
        $condicion = ($numero != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('numero' => '='.$numero)) : $condicion;
        $condicion = (($fecha_emision_inicio != $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final == $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('fecha_emision' => '>='."'".format_fecha_0000_00_00($fecha_emision_inicio)."'")) : $condicion;
        $condicion = (($fecha_emision_inicio == $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final != $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('fecha_emision' => '<='."'".format_fecha_0000_00_00($fecha_emision_final)."'")) : $condicion;
        $condicion = (($fecha_emision_inicio != $this->variables_diversas_model->param_stand_url) && ($fecha_emision_final != $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('fecha_emision' => 'BETWEEN '."'".format_fecha_0000_00_00($fecha_emision_inicio)."' AND "."'".format_fecha_0000_00_00($fecha_emision_final)."'")) : $condicion;
        $data['registros'] = $this->pedido_almacenes_model->ws_select($pagina, $filas_por_pagina, 3, 
                array('ped.id pedido_almacen_id', 'ped.numero numero_pedido', 'ped.fecha_insert', 'ped.fecha_aceptado', 'ped.notas', 'epl.nombres empleado'), $condicion , ' ORDER BY ped.id DESC ');
        $data['total_filas'] = $this->pedido_almacenes_model->total_filas($condicion);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}