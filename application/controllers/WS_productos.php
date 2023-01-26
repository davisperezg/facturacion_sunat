<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_productos extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('variables_diversas_model');
        $this->load->model('productos_model');
        
        $this->load->helper('ayuda');
    }
    
    public function ws_select(){
        $pagina = $this->uri->segment(3);
        $filas_por_pagina = $this->uri->segment(4);        
        $producto_id = $this->uri->segment(5);
        $categoria_id = $this->uri->segment(6);
        $unidad_id = $this->uri->segment(7);
        
        $condicion = array();
        $condicion = ($producto_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('pro.id' => '='.$producto_id)) : $condicion;
        $condicion = ($categoria_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('cat.id' => '='.$categoria_id)) : $condicion;
        $condicion = ($unidad_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('und.id' => '='.$unidad_id)) : $condicion;
                
        $data = $this->productos_model->ws_select($pagina, $filas_por_pagina, $condicion, ' ORDER BY pro.id desc');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function select_item(){
        $producto_id = $this->uri->segment(3);
        if(isset($producto_id) && ($producto_id != '')){
            $jsondata = $this->productos_model->ws_item($producto_id);
            echo json_encode($jsondata);
        }
    }
    
    public function ws_select_item(){
        $producto_id = $this->uri->segment(3);
        if(isset($producto_id) && ($producto_id != '')){
            $jsondata = $this->productos_model->ws_select_item($producto_id);
            echo json_encode($jsondata);
        }
    }    
    //update field received
    public function ws_update_campo_item(){
        $producto_id = $this->uri->segment(3);
        $field = $this->uri->segment(4);
        $value = $this->uri->segment(5);
        
        if(isset($producto_id) && ($producto_id != '')){            
            $data = array($field => $value);
			//var_dump($data);exit;
            $jsondata = $this->productos_model->modificar($producto_id, $data);
            echo json_encode($jsondata);
        }
    }    
    
    public function delete_item(){
        $producto_id = $this->uri->segment(3);
        if(isset($producto_id) && ($producto_id != '')){
            $data = array(
                'fecha_delete' => date("Y-m-d H:i:s"),
                'empleado_delete' => $this->session->userdata('empleado_id')
            );
            $this->productos_model->modificar($producto_id, $data);
            $jsondata = array(
                'msg' => 'operación correcta'
            );
            echo json_encode($jsondata);
        }
    }
    
    public function devolver_valor_param(){
        $jsondata = array(
            'producto_id' => $this->uri->segment(3)
        );
        echo json_encode($jsondata);
    }

    public function buscador_producto() {
        $param = $this->input->get('term');
        $data = $this->productos_model->ws_buscador($param);
        echo json_encode($data);
    }
    
    public function max_producto_id() {
        $data = $this->productos_model->max_producto_id();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
}