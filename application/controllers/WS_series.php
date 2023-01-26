<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_series extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('series_model');
        $this->load->model('variables_diversas_model');
    }
    
    public function select_by_serie(){
        $serie = $this->uri->segment(3);
        $data= $this->series_model->select(2, array('id', 'serie'), array('serie' => $serie));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_select_byCampo(){
        $serie_id = $this->uri->segment(3);
        $campo = $this->uri->segment(4);
        $data= $this->series_model->select(2, array($campo), array('id' => $serie_id));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    //para la tabla
    public function ws_select(){
        $array_where = array();
        $tipo_documento_id = $this->uri->segment(3);
        if( ($tipo_documento_id != '') && ($tipo_documento_id > 0) ){
            $array_where = array('tipo_documento_id' => $tipo_documento_id);    
        }
                
        $data['ws_select_series'] = $this->series_model->query_standar(3, '', $array_where);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function series_defecto(){
        $array_where = array();
        $tipo_documento_id = $this->variables_diversas_model->tipo_documento_defecto_id;
        if( ($tipo_documento_id != '') && ($tipo_documento_id > 0) ){
            $array_where = array('tipo_documento_id' => $tipo_documento_id);    
        }

        $data['ws_select_series'] = $this->series_model->select(3, array('id', 'serie'), array('tipo_documento_id' => $tipo_documento_id, 'fecha_delete' => 'IS NULL'));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function series_defecto2(){
        $array_where = array();
        $tipo_documento_id = $this->variables_diversas_model->tipo_documento_defecto_id;
        if( ($tipo_documento_id != '') && ($tipo_documento_id > 0) ){
            $array_where = array('tipo_documento_id' => $tipo_documento_id);    
        }

        $data = $this->series_model->select(3, array('id', 'serie'), array('tipo_documento_id' => $tipo_documento_id, 'fecha_delete' => 'IS NULL'));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    //para el detalle y para el update......
    public function ws_select_item(){
        $serie_id = $this->uri->segment(3);
        if(isset($serie_id) && ($serie_id != '')){
            $jsondata = $this->series_model->query_standar(2, '', array('ser.id' => $serie_id));
            echo json_encode($jsondata);
        }
    }
    
    //para eliminar
    public function delete_item(){
        $serie_id = $this->uri->segment(3);
        if(isset($serie_id) && ($serie_id != '')){
            $data = array(
                'fecha_delete' => date("Y-m-d H:i:s")
            );
            $this->series_model->modificar($serie_id, $data);
            $jsondata = array(
                'msg' => 'operación correcta'
            );
            echo json_encode($jsondata);
        }
    } 
    
}