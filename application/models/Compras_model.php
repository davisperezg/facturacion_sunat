<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Compras_model extends CI_Model{

    public $tabla = 'compras';

    public function __construct() {
        parent::__construct();
        
        $this->load->model('empresas_model');
        $this->load->model('compra_detalles_model');
        $this->load->model('variables_diversas_model');
        $this->load->library('pdf');
        $this->load->helper('ayuda');
        
        require_once (APPPATH .'libraries/Numletras.php');
    }

    public function select($modo, $select = array(), $condicion = array(), $order = '') {

        if ($select == '')
            $select = array();
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            if ($value == 'IS NULL') {
                $where .= " AND $key " . $value;
            } else {
                $where .= " AND $key = '" . $value . "' ";
            }
        }

        $campos = ($select == array()) ? '*' : implode(", ", $select);
        $sql = "SELECT " . $campos . " FROM $this->tabla WHERE 1 = 1 " . $where . " " . $order;
        $query = $this->db->query($sql);

        switch ($modo) {
            case '1':
                $resultado = '';
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                    $resultado = $row[$campos];
                }
                return $resultado;

            case '2':
                $row = array();
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                }
                return $row;

            case '3':
                $rows = array();
                foreach ($query->result_array() as $row) {
                    $rows[] = $row;
                }
                return $rows;
        }
    }
    
    public function select_compra_con_tipo_documentos($modo, $select = array(), $condicion = array(), $order = '') {

        if ($select == '')
            $select = array();
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            if ($value == 'IS NULL') {
                $where .= " AND $key " . $value;
            } else {
                $where .= " AND $key = '" . $value . "' ";
            }
        }

        $campos = ($select == array()) ? '*' : implode(", ", $select);
        $sql = "SELECT " . $campos . " FROM compras com
        JOIN tipo_documentos tdc ON tdc.id = com.tipo_documento_id WHERE 1 = 1 " . $where . " " . $order;        
        $query = $this->db->query($sql);

        switch ($modo) {
            case '1':
                $resultado = '';
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                    $resultado = $row[$campos];
                }
                return $resultado;

            case '2':
                $row = array();
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                }
                return $row;

            case '3':
                $rows = array();
                foreach ($query->result_array() as $row) {
                    $rows[] = $row;
                }
                return $rows;
        }
    }

    public function insertar($data) {
        $this->db->insert($this->tabla, $data);
    }           

    public function modificar($id, $data) {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);
    }        
    //$cliente_id, $tipo_documento, $serie, $numero, $fecha_emision_inicio, $fecha_emision_final
    public function query_standar($modo, $select = array(), $pagina, $filas_por_pagina, $condicion = array(), $order = '', $nepe = '') {
        if ($select == '') $select = array();
        $campos_standar = array('com.moneda_id', 'com.tipo_de_cambio', 'ten.codigo tipo_entidad_codigo', 'mon.abrstandar moneda_abreviatura', 'mon.moneda moneda', 'mon.simbolo simbolo_moneda', 'ten.abreviatura tipo_entidad_abreviatura', 'com.id compra_id', 'com.total_bolsa total_bolsa', 'com.serie serie', 'com.numero numero', 'DATE_FORMAT(com.fecha_emision, "%d-%m-%Y") fecha_emision_cf', 'DATE_FORMAT(com.fecha_emision, "%d/%m/%Y") fecha_emision_cf_raya', 'DATE_FORMAT(com.fecha_vencimiento, "%d-%m-%Y") fecha_vencimiento_cf','DATE_FORMAT(com.fecha_vencimiento, "%d/%m/%Y") fecha_vencimiento_cf_raya',  'fecha_vencimiento', 'com.total_gravada total_gravada', 'com.total_igv total_igv', 'com.total_a_pagar total_a_pagar', 'tdc.abreviado abreviado', 'tdc.codigo tipo_documento_codigo', 'entidad', 'ent.nombre_comercial', 'ent.id entidad_id', 'ent.numero_documento numero_documento', 'com.total_gratuita total_gratuita', 'com.total_exportacion total_exportacion', 'com.total_exonerada total_exonerada', 'com.total_inafecta total_inafecta', 'fecha_emision', 'com.total_bolsa total_bolsa');
        $select = (count($select) == 0) ? $campos_standar : $select;
        $select = implode(", ", $select);
        
        if ($condicion == '')
        $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            $where .= " AND $key $value ";
        }
        
        $limit = '';
        if(($pagina != '') || ($filas_por_pagina != '')){
            $inicio = ($pagina - 1)*$filas_por_pagina;
            $limit = "LIMIT $inicio, $filas_por_pagina";
        }

        $sql = "SELECT $select FROM compras com
        JOIN entidades ent ON com.`entidad_id` = ent.`id`
        JOIN tipo_entidades ten ON ten.`id` = ent.`tipo_entidad_id`
        JOIN monedas mon ON mon.id = com.moneda_id
        LEFT JOIN `tipo_documentos` tdc ON tdc.`id` = com.`tipo_documento_id`        
        WHERE 1 = 1 " . $where . " " . $order . " " . $limit;        
        
        if($nepe ==1){
            echo $sql;exit;
        }
        
        $query = $this->db->query($sql);
        $rows = array();

        ////////////////////////////////////////////////////
        switch ($modo) {
            case '1':
                $resultado = '';
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                    $resultado = $row[$campos];
                }
                return $resultado;

            case '2':
                $row = array();
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                }
                return $row;

            case '3':
                $rows = array();
                foreach ($query->result_array() as $row) {
                    $rows[] = $row;
                }
                return $rows;
        }
    }
    
    public function query_detalle($compra_id){
        $sql = "SELECT com.id compra_id, com.`serie`, com.`numero`, com.`fecha_emision`, com.`fecha_vencimiento`, tipo_de_cambio, total_gravada, total_igv, total_gratuita, total_exportacion,
        total_exonerada, total_inafecta, total_bolsa, total_a_pagar, notas,
        entidad, nombre_comercial, numero_documento, direccion, ent.email_1 email_1_entidad, ent.email_2 email_2_entidad, telefono_fijo_1, telefono_fijo_2, telefono_movil_1, telefono_movil_2,
        cantidad, precio_base, tipo_igv_id, impuesto_bolsa,
        codigo_sunat, pro.codigo codigo_producto, pro.producto, precio_base_venta, comision_compra, imagen,
        und.codigo codigo_unidad, unidad,
        epl.`nombres`, epl.`apellido_paterno`, epl.`apellido_materno`, epl.`dni`
        FROM compras com
        JOIN monedas mon ON mon.`id` = com.`moneda_id`
        JOIN entidades ent ON ent.`id` =com.`entidad_id`
        JOIN compra_detalles com_det ON com_det.`compra_id` = com.`id`
        JOIN productos pro ON pro.`id` = com_det.`producto_id`
        JOIN unidades und ON und.`id` = pro.`unidad_id`
        JOIN empleados epl ON epl.`id` = com.`empleado_insert`
        WHERE com.id = $compra_id";
        $query = $this->db->query($sql);
        
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    public function query_cabecera($compra_id){
        $sql = "SELECT operacion, operacion_id, 
        com.id compra_id, com.compra_relacionado_id, com.tipo_ncredito_id, tipo_ndebito_id, com.`serie`, com.`numero`, com.porcentaje_igv porcentaje_igv, com.fecha_emision fecha_emision_sf, DATE_FORMAT(com.fecha_emision, '%d-%m-%Y') AS fecha_emision, hora_emision, DATE_FORMAT(com.fecha_vencimiento, '%d-%m-%Y') AS fecha_vencimiento, fecha_vencimiento fecha_vencimiento_sf, tipo_de_cambio, total_gravada, total_igv, total_gratuita, total_exportacion,
        total_exonerada, total_inafecta, total_bolsa, total_a_pagar, com.notas notas,
        mon.id moneda_id, mon.`moneda`, mon.`simbolo` simbolo_moneda, mon.abrstandar,
        ent.id entidad_id, ent.entidad entidad, ent.nombre_comercial, numero_documento, ent.direccion direccion_entidad, ent.email_1 email_1_entidad, ent.email_2 email_2_entidad, telefono_fijo_1, telefono_fijo_2, telefono_movil_1, telefono_movil_2,
        epl.`nombres`, epl.`apellido_paterno`, epl.`apellido_materno`, epl.`dni`,
        ten.id tipo_entidad_id, ten.`descripcion`, ten.`abreviatura` abreviatura_tipo_entidad, ten.`tipo_entidad`, ten.codigo codigo_tipo_entidad,
        tdo.id tipo_documento_id, tdo.`abreviado`, tdo.`codigo` tipo_documento_codigo, tdo.`tipo_documento`
        FROM compras com
        LEFT JOIN tipo_documentos tdo on tdo.`id` = com.`tipo_documento_id`
        JOIN monedas mon ON mon.`id` = com.`moneda_id`
        JOIN entidades ent ON ent.`id` =com.`entidad_id`
        JOIN tipo_entidades ten on ten.`id` = ent.`tipo_entidad_id`
        JOIN empleados epl ON epl.`id` = com.`empleado_insert`
        WHERE com.id = $compra_id";
        
        //echo $sql;
        $query = $this->db->query($sql);
        
        $row = array();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        }
        return $row;
    }
    
    public function total_filas($condicion) {
        if ($condicion == '')
        $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {            
            $where .= " AND $key $value ";
        }        
        $sql = "select count(id) total_filas from $this->tabla
        WHERE 1 = 1 " . $where;
        $query = $this->db->query($sql);

        $resultado = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['total_filas'];
        }
        return $resultado;
    }

    //$cliente_id, $tipo_documento, $serie, $numero, $fecha_emision_inicio, $fecha_emision_final
    public function ws_select($modo, $select, $pagina, $filas_por_pagina, $condicion = array(), $order = ''){
        $data = $this->query_standar($modo, $select, $pagina, $filas_por_pagina, $condicion, $order);
        $total_filas = $this->total_filas($condicion);

        $datos = array();
        foreach ($data as $value){
            $datos[] = array(
                'compra_id'              =>  (int)$value['compra_id'],
                'serie'                 =>  $value['serie'],
                'numero'                =>  $value['numero'],
                'fecha_emision'         =>  $value['fecha_emision'],
                'total_gravada'         =>  $value['total_gravada'],
                'total_igv'             =>  $value['total_igv'],
                'total_a_pagar'         =>  $value['total_a_pagar'],
                'total_bolsa'           =>  $value['total_bolsa'],
                'abreviado'             =>  $value['abreviado'],
                'entidad'               =>  $value['entidad'],
                'entidad_id'            =>  $value['entidad_id'],
                'tipo_documento_codigo' =>  $value['tipo_documento_codigo'],
                'simbolo_moneda'        =>  $value['simbolo_moneda']
            );
        }
        
        //$total_filas = $this->total_filas();        
        $salida = array(
            'ws_select_compras' => $datos,
            'total_filas' => $total_filas
        );
        return $salida;
    }   
    
    public function ultimoNumeroDeSerie($operacion, $tipo_documento_id, $serie) {        
        $where = '';
        if($operacion == 1){
            $where = "serie = '" . $serie . "' AND tipo_documento_id = " . $tipo_documento_id . " AND operacion = " . $operacion ;
        }else{
            $where = "serie IS NULL AND tipo_documento_id IS NULL AND operacion = " . $operacion ;
        }
        
        $sql = "SELECT numero FROM $this->tabla WHERE $where ORDER BY id DESC LIMIT 1";                
        $query = $this->db->query($sql);        
        
        $resultado = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['numero'];
        }
        return $resultado;
    }
    
    public function select_max_id(){
        $sql = "SELECT MAX(id) compra_id FROM $this->tabla";
        $query = $this->db->query($sql);
        $resultado = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['compra_id'];
        }
        return $resultado;
    }
    
    public function compra_documento($compra_id){
        $sql = "SELECT com.id compra_id, serie, numero, tdc.id tipo_documento_id, tdc.`codigo` codigo 
        FROM compras com
        JOIN tipo_documentos tdc ON tdc.`id` = com.`tipo_documento_id` 
        WHERE com.id =".$compra_id;
        $query = $this->db->query($sql);

        $row = array();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        }
        return $row;
    }
    
    public function datos_nc($compra_id){
        $sql = "SELECT com2.`serie`, com2.`numero`, tnc.`tipo_ncredito` FROM compras com
        JOIN `tipo_ncreditos` tnc ON com.`tipo_ncredito_id` = tnc.`id`
        JOIN compras com2 ON com2.id = com.`compra_relacionado_id`
        WHERE com.`id` = " . $compra_id;        
        $query = $this->db->query($sql);
        
        $row = array();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        }
        return $row;        
    }
    
    public function documentos_impagos($modo, $select = array(), $condicion = array(), $order = '') {
        if ($select == '')
            $select = array();
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            $where .= " AND $key $value ";
        }

        $campos = ($select == array()) ? '*' : implode(", ", $select);
        $sql = "SELECT " . $campos . " FROM $this->tabla WHERE forma_pago_id = 2 AND compra_pagada = 0 " . $where . " " . $order;        
        //echo $sql;
        $query = $this->db->query($sql);

        switch ($modo) {
            case '1':
                $resultado = '';
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                    $resultado = $row[$campos];
                }
                return $resultado;

            case '2':
                $row = array();
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                }
                return $row;

            case '3':
                $rows = array();
                foreach ($query->result_array() as $row) {
                    $rows[] = $row;
                }
                return $rows;
        }
    }
    
    public function pdf_a4($param_compra_id = '', $guardar_pdf = ''){
        $compra_id = ($param_compra_id != '') ? $param_compra_id : $this->uri->segment(3);
        $guardar_pdf = ($guardar_pdf != '') ? $guardar_pdf : $this->uri->segment(4);
                
        $data['empresa'] = $empresa = $this->empresas_model->select(2);
        $data['cabecera'] = $cabecera = $this->query_cabecera($compra_id);
        $data['detalle'] = $this->compra_detalles_model->query_detalle($compra_id);
        $data['rutaqr'] = $this->GetImgQr($cabecera, $empresa);        
        $data['orden_compra'] = $this->compras_model->select(2, array('numero'), array('operacion' => 2, 'operacion_id' => $compra_id));
        $data['catidad_decimales'] = $this->variables_diversas_model->catidad_decimales;

        if($data['cabecera']['tipo_ncredito_id'] != null){
            $data['nota_credito'] = $this->datos_nc($compra_id);
        }
        
        //convetimos el total en texto
        $num = new Numletras();
        $totalCompra = explode(".", $data['cabecera']['total_a_pagar']);
        $totalLetras = $num->num2letras($totalCompra[0]);
        $totalLetras = 'Son: '.$totalLetras.' con '.$totalCompra[1].'/100 '.$data['cabecera']['moneda'];
        $data['totalLetras'] = $totalLetras;

        $html = $this->load->view("compras/pdf_a4.php",$data,true);
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        
        $nombre_documento = $empresa['ruc'].'-'.$data['cabecera']['tipo_documento_codigo'].'-'. $data['cabecera']['serie'] .'-'. $data['cabecera']['numero'];
//        $output = $this->pdf->output();
//        file_put_contents('files/pdf/compras/'.$nombre_documento.'.pdf', $output);
        
        if($guardar_pdf == 1){
            $output = $this->pdf->output();
            file_put_contents('files/pdf/compras/'.$nombre_documento.'.pdf', $output);
        }else{            
            $this->pdf->stream("$nombre_documento.pdf",
                array("Attachment"=>0)
            );
        }
        //////////////////////////////////////////
    }
    
    public function pdf_ticket($compra_id){
        $data['empresa'] = $empresa = $this->empresas_model->select(2);
        $data['cabecera'] = $cabecera = $this->query_cabecera($compra_id);
        $data['detalle'] = $this->compra_detalles_model->query_detalle($compra_id);
        $data['rutaqr'] = $this->GetImgQr($cabecera, $empresa);        
        
        //convetimos el total en texto
        $num = new Numletras();
        $totalVenta = explode(".", $data['cabecera']['total_a_pagar']);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = 'Son: '.$totalLetras.' con '.$totalVenta[1].'/100 '.$data['cabecera']['moneda'];
        $data['totalLetras'] = $totalLetras;

        $html = $this->load->view("compras/pdf_ticket.php",$data,true);
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,440), 'portrait');
        $this->pdf->render();
        $nombre_documento = $data['cabecera']['tipo_documento']."-".$data['cabecera']['serie']."-".$data['cabecera']['numero'];
        $this->pdf->stream("T-$nombre_documento.pdf",
            array("Attachment"=>0)
        );        
    }
        
    public function GetImgQr($cabecera, $empresa)  {
        $textoQR = '';
        $textoQR .= $empresa['ruc']."|";//RUC EMPRESA
        
        $textoQR .= $cabecera['tipo_documento']."|";//TIPO DE DOCUMENTO 
        $textoQR .= $cabecera['serie']."|";//SERIE
        $textoQR .= $cabecera['numero']."|";//NUMERO
        $textoQR .= $cabecera['total_igv']."|";//MTO TOTAL IGV
        $textoQR .= $cabecera['total_a_pagar']."|";//MTO TOTAL DEL COMPROBANTE
        //$fechaEmision = (new DateTime($rsComprobante->fecha_de_emision))->format('d-m-Y');
        $textoQR .= $cabecera['fecha_emision']."|";//FECHA DE EMISION 
        //tipo de cliente
     
        $textoQR .= $cabecera['codigo_tipo_entidad']."|";//TIPO DE DOCUMENTO ADQUIRENTE 
        $textoQR .= $cabecera['numero_documento']."|";//NUMERO DE DOCUMENTO ADQUIRENTE 
        
        $nombreQR = $cabecera['tipo_documento_id'].'-'.$cabecera['serie'].'-'.$cabecera['numero'];
        QRcode::png($textoQR, FCPATH."images/qr/".$nombreQR.".png", QR_ECLEVEL_L, 10, 2);
        
        return FCPATH."images/qr/{$nombreQR}.png";                
    }

}