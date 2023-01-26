<?PHP
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

class WS_correos extends CI_Controller {
    
    public function __construct() {        
        parent::__construct();
        date_default_timezone_set('America/Lima');
        
        $this->load->model('correos_model');
        $this->load->model('ventas_model');
        $this->load->model('empresas_model');
        $this->load->model('entidades_model');
        $this->load->model('tipo_documentos_model');
        
        require_once (APPPATH .'libraries/qr/phpqrcode/qrlib.php');
    }
    
    public function select_all(){       
        $data = $this->correos_model->select(2,'','');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function ws_select(){
        $correo_id = $this->uri->segment(3);       
        $data = $this->correos_model->select(2, '', array('id' => $correo_id));
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    
    public function Send_Mail(){
        require_once (APPPATH .'libraries/phpMailer/Exception.php');
        require_once (APPPATH .'libraries/phpMailer/PHPMailer.php');
        require_once (APPPATH .'libraries/phpMailer/SMTP.php');
                
        $venta_id = $this->uri->segment(3);
        $venta = $this->ventas_model->select(2,'',array('id' => $venta_id));
        $tipo_documento_codigo = $this->tipo_documentos_model->select2(1, array('codigo'), array('id' => $venta['tipo_documento_id']));
        $correo = $this->correos_model->select(2);
        $empresa = $this->empresas_model->select(2);
        $entidad = $this->entidades_model->select(2, '', array('id' => $venta['entidad_id']));        
        
        $mail = new PHPMailer;
        $mail->isSMTP();

        $mail->Host = $correo['host'];
        $mail->Port = $correo['port'];
        $mail->SMTPSecure = $correo['correo_cifrado'];

        $mail->SMTPAuth = true;
        $mail->Username = $correo['user'];
        $mail->Password = $correo['pass'];
        
        //echo $tipo_documento_codigo;exit;
        //var_dump($venta);exit;
        if ($entidad['email_1']=='') {
            sendJsonData(['status'=>STATUS_FAIL,'msg'=>'El cliente no tiene correo']);
            exit();
        }

        //echo $comprobante['cli_email'];exit;
        $mail->setFrom($correo['user'], $empresa['empresa']);
        $mail->AddAddress($entidad['email_1'],'Facturacion Electronica');

        //asunto
        $mail->Subject = 'Comprobante Electronico '. ' - ' . $entidad['entidad'];
        
        $body = '<h2>Comprobante de Pago Electrónico</h2>';
        $body .= 'Estimado Cliente, '. '<br>';
        $body .= 'Sr(es). '.$entidad['entidad']. '<br>';
        $body .= 'RUC '. $entidad['numero_documento']. '<br>';
        $body .= 'Adjuntamos comprobante electrónico: '.$venta['serie'].'-'.$venta['numero']. '<br><br>';
        
        if($correo['notas'] != null && $correo['notas'] != ''){
            $body .= '<br>'. $correo['notas']. '<br><br><br>';
        }
        
        $body .= 'Saluda atentamente,<br>';
        $body .= '<b>' . $empresa['empresa'] .' </b><br><br>';

        //Replace the plain text body with one created manually
        $mail->AltBody = 'ALT BODY';
        $mail->Body = $body;

        $guardar_pdf = 1;
        $this->ventas_model->pdf_a4($venta_id, $guardar_pdf);
        $file_pdf = "files/pdf/ventas/" .$empresa['ruc'].'-'.$tipo_documento_codigo.'-'. $venta['serie'] .'-'. $venta['numero'] . ".pdf";
        $file_xml = FCPATH."files/facturacion_electronica/XML/" .$empresa['ruc'].'-'.$tipo_documento_codigo.'-'. $venta['serie'] .'-'. $venta['numero'] . ".xml";
        $file_cdr = FCPATH."files/facturacion_electronica/FIRMA/R-" .$empresa['ruc'].'-'.$tipo_documento_codigo.'-'. $venta['serie'] .'-'. $venta['numero'] . ".zip";
        //echo $file_xml;
        $mail->addAttachment($file_pdf);
        $mail->addAttachment($file_xml);
        $mail->addAttachment($file_cdr);

        //send the message, check for errors
        $respuesta = (!$mail->send()) ? 'Mailer Error: '. $mail->ErrorInfo : 'Mensaje enviado--!';
        
        $jsondata = array(
            'success'       =>  $respuesta
        );
        echo json_encode($jsondata, JSON_UNESCAPED_UNICODE);
    }
    
}