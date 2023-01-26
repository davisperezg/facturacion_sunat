<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_producto_movimientos extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('producto_movimientos_model');
        $this->load->model('variables_diversas_model');
    }
    
    public function ws_select_movimiento(){
        $data = $this->producto_movimientos_model->movimientos();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_select(){
        $pagina                 = $this->uri->segment(3);
        $filas_por_pagina       = $this->uri->segment(4);
        
        $producto_id            = $this->uri->segment(5);
        $movimiento             = $this->uri->segment(6);        
        $fecha_inicio           = $this->uri->segment(7);
        $fecha_final            = $this->uri->segment(8);        
        $producto_movimiento_id = $this->uri->segment(9);        
        
        $condicion = array();
        $condicion = ($producto_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('producto_id' => '='.$producto_id)) : $condicion;
        $condicion = ($movimiento != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('movimiento' => '='.$movimiento)) : $condicion;
        $condicion = (($fecha_inicio != $this->variables_diversas_model->param_stand_url) && ($fecha_final == $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('mov.fecha_insert' => '>='."'".format_fecha_0000_00_00($fecha_inicio)." 00:00:00'")) : $condicion;
        $condicion = (($fecha_inicio == $this->variables_diversas_model->param_stand_url) && ($fecha_final != $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('mov.fecha_insert' => '<='."'".format_fecha_0000_00_00($fecha_final)." 23:59:59'")) : $condicion;
        $condicion = (($fecha_inicio != $this->variables_diversas_model->param_stand_url) && ($fecha_final != $this->variables_diversas_model->param_stand_url)) ? array_merge($condicion, array('mov.fecha_insert' => 'BETWEEN '."'".format_fecha_0000_00_00($fecha_inicio)." 00:00:00' AND "."'".format_fecha_0000_00_00($fecha_final)." 23:59:59'")) : $condicion;
        $condicion = ($producto_movimiento_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('mov.id' => '='.$producto_movimiento_id)) : $condicion;
        $movimientos = $this->producto_movimientos_model->standar(3, '', $pagina, $filas_por_pagina, $condicion, ' ORDER BY mov.id DESC');
        $total_filas = $this->producto_movimientos_model->total_filas($condicion);
        
        $jsondata = array(
            'movimientos'       =>  $movimientos,
            'total_filas'       =>  $total_filas
        );
                
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }

}