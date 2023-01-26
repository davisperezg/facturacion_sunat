<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Anulaciones extends CI_Controller {

    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');

        $this->load->model('anulaciones_model');        
        $this->load->model('variables_diversas_model');

        $empleado_id = $this->session->userdata('empleado_id');
        if (empty($empleado_id)) {
            $this->session->set_flashdata('mensaje', 'No existe sesion activa');
            redirect(base_url());
        }
    }
    
    public function guarda_ticket(){
        $this->anulaciones_model->actualizar_ticket($this->uri->segment(3), $this->uri->segment(4));
    }
   
    


}