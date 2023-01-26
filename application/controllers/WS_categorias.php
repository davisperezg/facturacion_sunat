<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_categorias extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('variables_diversas_model');
        $this->load->model('categorias_model');
    }
    
    public function select_item(){
        $categoria_id = $this->uri->segment(3);
        if(isset($categoria_id) && ($categoria_id != '')){
            $jsondata = $this->categorias_model->ws_item($categoria_id);
            echo json_encode($jsondata);
        }
    }
    
    public function ws_select_all(){
        $data['categorias'] = $this->categorias_model->ws_select_all();
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_select(){                       
        $pagina = $this->uri->segment(3);
        $filas_por_pagina = $this->uri->segment(4);        
        $categoria_id = $this->uri->segment(5);
        
        $condicion = array();
        $condicion = ($categoria_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('id' => '='.$categoria_id)) : $condicion;
                        
        $data = $this->categorias_model->ws_select($pagina, $filas_por_pagina, $condicion, ' ORDER BY id desc');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function buscador_categoria() {
        $param = $this->input->get('term');
        $data = $this->categorias_model->ws_buscador($param);
        echo json_encode($data);
    }
    
    public function delete_item(){
        $categoria_id = $this->uri->segment(3);
        if(isset($categoria_id) && ($categoria_id != '')){
            $data = array(
                'eliminado' => 1
            );
            $this->categorias_model->modificar($categoria_id, $data);
            $jsondata = array(
                'msg' => 'operación correcta'
            );
            echo json_encode($jsondata);
        }
    }

    public function select_registro(){
        $data = $this->categorias_model->select(2, '', array('id' => $this->uri->segment(3)));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_update_campo_item(){
        $categoria_id = $this->uri->segment(3);
        $field = $this->uri->segment(4);
        $value = $this->uri->segment(5);
        
        if(isset($categoria_id) && ($categoria_id != '')){            
            $data = array($field => $value);            
            $jsondata = $this->categorias_model->modificar($categoria_id, $data);
            echo json_encode($jsondata);
        }
    }
}