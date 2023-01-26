<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WS_tipo_cambios extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        $this->load->model('tipo_cambios_model');     
    }
    
    public function tipo_cambio_2(){        
        $moneda_id = $this->uri->segment(3);
        $fecha = $this->uri->segment(4);
        $data['tipo_cambios'] = $this->tipo_cambios_model->ws_select($moneda_id, $fecha);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function tipo_cambio(){
                
        $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';
        $fecha = $this->uri->segment(3);

        // Iniciar llamada a API
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.apis.net.pe/v1/tipo-cambio-sunat?fecha=' . $fecha,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 2,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Referer: https://apis.net.pe/tipo-de-cambio-sunat-api',
            'Authorization: Bearer ' . $token
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // Datos listos para usar
        $tipoCambioSunat = json_decode($response);
        //var_dump($tipoCambioSunat);exit;
        
        echo json_decode($tipoCambioSunat->venta);
    }

 
    
}