<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_entidades extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('variables_diversas_model');
        $this->load->model('entidades_model');
        $this->load->model('back_empresas_model');
        
        $this->load->helper('ayuda');
    }
    
    public function ws_select(){
        $pagina = $this->uri->segment(3);
        $filas_por_pagina = $this->uri->segment(4);        
        $entidad_id = $this->uri->segment(5);
        $tipo_entidad_id = $this->uri->segment(6);
        
        $condicion = array();
        $condicion = ($entidad_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('ent.id' => '='.$entidad_id)) : $condicion;
        $condicion = ($tipo_entidad_id != $this->variables_diversas_model->param_stand_url) ? array_merge($condicion, array('tip.id' => '='.$tipo_entidad_id)) : $condicion;
                
        $data = $this->entidades_model->ws_select($pagina, $filas_por_pagina, $condicion, ' ORDER BY ent.id desc');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function select(){
        $entidad_id = $this->uri->segment(3);
        $data = $this->entidades_model->select(2, array('numero_documento', 'entidad'), array('id' => $entidad_id));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function by_field(){
        $select =   $this->uri->segment(3);
        $where  =   $this->uri->segment(4);
        $valor  =   $this->uri->segment(5);
        
        $campo  =   $this->entidades_model->select2(1, array($select), array($where => "=".$valor));
        echo json_encode($campo);
    }
    
    public function operaciones(){
        $data = array(
            'tipo_entidad_id'   =>  $_GET['tipo_entidades'],
            'numero_documento'  =>  $_GET['numero_documento'],
            'entidad'           =>  $_GET['entidad'],
            'direccion'         =>  $_GET['direccion'],
            'telefono_movil_1'  =>  $_GET['telefono_movil_1']
        );        
        
        if(isset($_GET['entidad_id']) && ($_GET['entidad_id'] != '')){
            $entidad_id = $_GET['entidad_id'];
            $data_update = array(
                'fecha_update'      =>  date("Y-m-d H:i:s"),
                'empleado_update'   =>  $this->session->userdata('empleado_id')
            );
            $data = array_merge($data, $data_update);            
            $this->entidades_model->modificar($_GET['entidad_id'], $data);
        }else{//puede que el cliente que se quiere ingresar nuevo Ya exista, en ese caso solo se actualizará.
            $entidad_id = $this->entidades_model->select_deliverys(1, array('id'), array($_GET['numero_documento']));
            if($entidad_id == ''){
                $data_insert = array(
                    'fecha_insert'      =>  date("Y-m-d H:i:s"),
                    'empleado_insert'   =>  $this->session->userdata('empleado_id')
                );                
                $data = array_merge($data, $data_insert);
                $this->entidades_model->insertar($data);
                $entidad_id = $this->entidades_model->select_max_id();            
            }elseif($entidad_id > 0){
                $data_update = array(
                    'fecha_update'      =>  date("Y-m-d H:i:s"),
                    'empleado_update'   =>  $this->session->userdata('empleado_id')
                );
                $data = array_merge($data, $data_update);
                $this->entidades_model->modificar($entidad_id, $data);
            }            
        }
                
        $jsondata = array(
            'success'           =>  true,
            'message'           =>  'Operación correcta',
            'entidad_id'        =>  $entidad_id
        );
        echo json_encode($jsondata);
    }

    public function ws_back_empresas(){
        $data = $this->back_empresas_model->select(2, array('ruc', 'razon_social', 'direccion'), array('ruc' => "=".$this->uri->segment(3)), '', " LIMIT 1");
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function select_item(){
        $entidad_id = $this->uri->segment(3);
        if(isset($entidad_id) && ($entidad_id != '')){
            $jsondata = $this->entidades_model->ws_item($entidad_id);
            echo json_encode($jsondata);
        }
    }
    
    public function ws_select_item(){
        $entidad_id = $this->uri->segment(3);
        if(isset($entidad_id) && ($entidad_id != '')){
            $jsondata = $this->entidades_model->ws_select_item($entidad_id);
            echo json_encode($jsondata);
        }
    }    
    //return un dato especificado
    public function select_unDato(){
        $entidad_id = $this->uri->segment(3);
        $dato_select = $this->uri->segment(4);
        $jsondata = $this->entidades_model->select(2, array($dato_select), array('id' => $entidad_id));
        echo json_encode($jsondata);        
    }    
    //update field received
    public function ws_update_campo_item(){
        $producto_id = $this->uri->segment(3);
        $field = $this->uri->segment(4);
        $value = $this->uri->segment(5);
        if(isset($producto_id) && ($producto_id != '')){
            $data = array($field => $value);
            $jsondata = $this->productos_model->modificar($producto_id, $data);
            echo json_encode($jsondata);
        }
    }    
    
    public function delete_item(){
        $entidad_id = $this->uri->segment(3);
        if(isset($entidad_id) && ($entidad_id != '')){
            $data = array(
                'fecha_delete' => date("Y-m-d H:i:s"),
                'empleado_delete' => $this->session->userdata('empleado_id')
            );
            $this->entidades_model->modificar($entidad_id, $data);
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

    public function buscador_entidad() {
        $param = $this->input->get('term');
        $tipo_entidad_id = $this->uri->segment(3);
        $data = $this->entidades_model->ws_buscador($param, $tipo_entidad_id);
        echo json_encode($data);
    }
    
    public function buscador_entidad_2() {
        $param = $this->input->get('term');
        $data = $this->entidades_model->ws_buscador($param);
        echo json_encode($data);
    }
    
    public function buscador_externo_ruc(){
        
        $stream_opts = [
            "ssl" => [
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ]
        ];  

        $numero_documento = $this->uri->segment(3);
        
        if(strlen($numero_documento) == 11){
//            $url = 'https://api.sunat.cloud/ruc/';
//            $data = file_get_contents($url.$numero_documento,false, stream_context_create($stream_opts));        
//            $info = json_decode($data, TRUE);
//            $tipo_entidad_id = 2;
            
            //$url = 'https://consultaruc.win/api/ruc/';
            $url = 'https://www.facturacionintegral.com/ws_entidades/?ruc=' . $numero_documento;            
            $data = file_get_contents($url);        
            $info = json_decode($data, TRUE);
            $tipo_entidad_id = 2;                        
            
            $datos = array(
                "ruc"                   =>  $numero_documento,
                "entidad"               =>  $info["razon_social"],
                "direccion"             =>  $info["direccion"]
            );
            echo json_encode($datos);
        }
    }
    
    public function buscador_externo_dni(){
        
//        $stream_opts = [
//            "ssl" => [
//                "verify_peer"=>false,
//                "verify_peer_name"=>false,
//            ]
//        ];
        $dni = $this->uri->segment(3);
        $url = 'https://consultaruc.win/api/dni/';
                        
        //$data = file_get_contents($url.$ruc,false, stream_context_create($stream_opts));                
        $data = file_get_contents($url.$dni);        
        $info = json_decode($data, TRUE);
        
        $datos = array(
            "dni"           =>  $dni,
            "entidad"       =>  $info['result']["Paterno"]." ".$info['result']["Materno"].", ".$info['result']["Nombre"],
        );             
        echo json_encode($datos);
    }
    
    //buscador de RUC para ventas
    //sino encuentra => graba
    //si encuentra  => actualiza
    public function buscador_externo_ruc_ventas__(){
        
        $stream_opts = [
            "ssl" => [
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ]
        ];  

        $numero_documento = $this->uri->segment(3);
        
        if(strlen($numero_documento) == 11){
            //$url = base_url()."index.php/WS_entidades/ws_back_empresas/" . $numero_documento;
            $url = 'https://facturacionintegral.com/aplicaciones_sistemas/back_empresas/index.php?ruc=' . $numero_documento;
            $data = file_get_contents($url);        
            $info = json_decode($data, TRUE);
            $tipo_entidad_id = 2;                        

            $entidad_id = $this->entidades_model->select(1, array('id'), array('numero_documento' => $numero_documento));
            if($entidad_id == ''){
                $data = array(
                    "numero_documento"  =>  $numero_documento,
                    "tipo_entidad_id"   =>  $tipo_entidad_id,
                    'entidad'           =>  $info["razon_social"],
                    'direccion'         =>  $info["direccion"],
                );
                $this->entidades_model->insertar($data);
                $entidad_id = $this->entidades_model->select_max_id();
            }

            $datos = array(
                "ruc"                   =>  $numero_documento,
                "entidad"               =>  $info["razon_social"]."-".$numero_documento,
                "entidad_id"            =>  $entidad_id,
                "direccion"             =>  $info["direccion"]
            );
            echo json_encode($datos);
        }
        
        if(strlen($numero_documento) == 8){           
            $numero_documento   = $this->uri->segment(3);
            $url                = 'https://consultaruc.win/api/dni/';            
            $data               = file_get_contents($url.$numero_documento);        
            $info               = json_decode($data, TRUE);
            $tipo_entidad_id    = 1;

            $entidad_id = $this->entidades_model->select(1, array('id'), array('numero_documento' => $numero_documento));        
            if($entidad_id == ''){
                $datos = array(
                    "numero_documento"  =>  $info['result']["DNI"],
                    "tipo_entidad_id"   =>  $tipo_entidad_id,
                    "entidad"           =>  $info['result']["Paterno"]." ".$info['result']["Materno"].", ".$info['result']["Nombre"]
                );
                $this->entidades_model->insertar($datos);
                $entidad_id = $this->entidades_model->select_max_id();
            }elseif($entidad_id > 0){
                $datos = array(
                    "numero_documento"  =>  $info['result']["DNI"],
                    "tipo_entidad_id"   =>  $tipo_entidad_id,
                    "entidad"           =>  $info['result']["Paterno"]." ".$info['result']["Materno"].", ".$info['result']["Nombre"]
                );
                $this->entidades_model->modificar($entidad_id, $datos);
            }

            $datos = array(
                "entidad"               =>  $info['result']["Paterno"]." ".$info['result']["Materno"].", ".$info['result']["Nombre"]." - ".$info['result']["DNI"],
                "entidad_id"            =>  $entidad_id,
                "domicilio_fiscal"      =>  ''
            );
            echo json_encode($datos);
        }
        
    }
    
    public function buscador_externo_ruc_ventas(){
        $dni_ruc = $this->uri->segment(3);
        //echo $dni_ruc;exit;
        $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';
        if(strlen($dni_ruc) == 8){

            // Iniciar llamada a API
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.apis.net.pe/v1/dni?numero=' . $dni_ruc,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 2,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_SSL_VERIFYPEER => false,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Referer: https://apis.net.pe/consulta-dni-api',
                'Authorization: Bearer ' . $token
              ),
            ));
            
            $response = curl_exec($curl);            
            $data = json_decode($response);
            
            $entidad_id = $this->entidades_model->select2(1, array('id'), array('numero_documento' => '= '.$dni_ruc, 'fecha_delete' => 'IS '." NULL"));
            $datos_dni = array(
                "numero_documento"  =>  $data->numeroDocumento,
                "tipo_entidad_id"   =>  1,
                "entidad"           =>  $data->apellidoPaterno . " ". $data->apellidoMaterno . " " . $data->nombres
            );
            if($entidad_id == ''){//inserta                
                $this->entidades_model->insertar($datos_dni);
                $entidad_id = $this->entidades_model->select_max_id();
            }elseif($entidad_id > 0){//modifica
                $this->entidades_model->modificar($entidad_id, $datos_dni);
            }
            
            $datos = array(
                "entidad"       =>  $data->apellidoPaterno . " ". $data->apellidoMaterno . " " . $data->nombres,
                "entidad_id"    =>  $entidad_id,
                "direccion"     =>  ''
            );                        
            echo json_encode($datos);
        }else{

            // Iniciar llamada a API
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.apis.net.pe/v1/ruc?numero=' . $dni_ruc,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_SSL_VERIFYPEER => false,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Referer: http://apis.net.pe/api-ruc',
                'Authorization: Bearer ' . $token
              ),
            ));
            $response = curl_exec($curl);
            $data = json_decode($response);            
            
            $entidad_id = $this->entidades_model->select2(1, array('id'), array('numero_documento' => '= '.$dni_ruc, 'fecha_delete' => 'IS '." NULL"));
            $datos_ruc = array(
                "numero_documento"  =>  $data->numeroDocumento,
                "tipo_entidad_id"   =>  2,
                "entidad"           =>  $data->nombre
            );
            $datos_ruc = ($data->direccion != '') ? array_merge($datos_ruc, array('direccion' => $data->direccion)) : $datos_ruc;

            if($entidad_id == ''){//inserta                
                $this->entidades_model->insertar($datos_ruc);
                $entidad_id = $this->entidades_model->select_max_id();
            }elseif($entidad_id > 0){//modifica
                $this->entidades_model->modificar($entidad_id, $datos_ruc);
            }                        
                        
            $datos = array(
                "ruc"           =>  $data->numeroDocumento,
                "entidad"       =>  $data->nombre,
                "entidad_id"    =>  $entidad_id,
                "direccion"     =>  $data->direccion
            );
            echo json_encode($datos);            
        }
    }
        
    public function buscador_externo_ruc_ventas__3(){
        $dni_ruc = $this->uri->segment(3);
        $grabando = $this->uri->segment(4);//$grabando = 0, no graba el cliente o proveedor en la BBDD, solo sería busqueda; //$grabando = 1, busca y graba en la BBDD
        
        if(strlen($dni_ruc) == 8){            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://apiperu.dev/api/dni/".$dni_ruc."?api_token=e774c925b8c95da5281ef28924bf8112f942d6f1ffd2dec20f8ebae0d309cd0a",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYPEER => false
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {            
                $datos = json_decode($response);                
                $data = $datos->data;

                $entidad_id = $this->entidades_model->select2(1, array('id'), array('numero_documento' => '= '.$dni_ruc, 'fecha_delete' => 'IS '." NULL"));
                if($grabando == 1){
                    $this->operar_entidad($entidad_id, 1, $dni_ruc, $data->apellido_paterno . " ". $data->apellido_materno . " " . $data->nombres, $data->direccion_completa);
                }                

                $datos_salida = array(
                    "entidad"               =>  $data->apellido_paterno . " ". $data->apellido_materno . " " . $data->nombres,
                    "entidad_id"            =>  $entidad_id,
                    "direccion"             =>  $data->direccion_completa
                );
            }
            echo json_encode($datos_salida);
        }else{
            // Iniciar llamada a API                        
            $curl = curl_init();            
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://apiperu.dev/api/ruc/".$dni_ruc."?api_token=e774c925b8c95da5281ef28924bf8112f942d6f1ffd2dec20f8ebae0d309cd0a",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYPEER => false
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $datos = json_decode($response);
                $data = $datos->data;                
                
                $entidad_id = $this->entidades_model->select2(1, array('id'), array('numero_documento' => '= '.$dni_ruc, 'fecha_delete' => 'IS '." NULL"));
                if($grabando == 1){
                    $this->operar_entidad($entidad_id, 1, $dni_ruc, $data->nombre_o_razon_social, $data->direccion_completa);
                }

                $datos_salida = array(
                    "ruc"                   =>  $data->ruc,
                    "entidad"               =>  $data->nombre_o_razon_social,
                    "entidad_id"            =>  $entidad_id,
                    "direccion"             =>  $data->direccion_completa
                );
            }
            echo json_encode($datos_salida);
        }
    }
    
    public function operar_entidad($entidad_id, $tipo_entidad_id, $numero_documento, $entidad, $direccion){
        $datos_dni = array(
            "numero_documento"  =>  $numero_documento,
            "tipo_entidad_id"   =>  $tipo_entidad_id,
            "entidad"           =>  $entidad,
            "direccion"         =>  $direccion
        );
        if($entidad_id == ''){//inserta                
            $this->entidades_model->insertar($datos_dni);
            $entidad_id = $this->entidades_model->select_max_id();
        }elseif($entidad_id > 0){//modifica
            $this->entidades_model->modificar($entidad_id, $datos_dni);
        }
        return $entidad_id;
    }
    
}