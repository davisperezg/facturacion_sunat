<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_cuotas extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('cuotas_model');
        $this->load->model('cobros_model');
        $this->load->model('variables_diversas_model');        
        
        $this->load->helper('ayuda');
    }
        
    public function ws_select(){
        $venta_id = $this->uri->segment(3);                        
        $data = $this->cuotas_model->select(3, '', array('venta_id' => $venta_id), ' ORDER BY id DESC ');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function delete_cuotas_pagos(){
        $venta_id = $this->uri->segment(3);                        
        $this->cuotas_model->delete_venta_id($venta_id);
        $this->cobros_model->delete_venta_id($venta_id);
        $data = array('resputa' => 'venta_ok');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        
    }
    
    
    
}