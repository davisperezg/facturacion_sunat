<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Ventas_model extends CI_Model{

    public $tabla = 'ventas';

    public function __construct() {
        parent::__construct();
        
        $this->load->model('empresas_model');
        $this->load->model('venta_detalles_model');
        $this->load->model('venta_guias_model');        
        $this->load->model('venta_anticipos_model');
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
    
    public function select2($modo, $select = array(), $condicion = array(), $order = '') {
        if ($select == '')
            $select = array();
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            $where .= " AND $key$value ";
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
    
    public function select_venta_con_tipo_documentos($modo, $select = array(), $condicion = array(), $order = '') {

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
        $sql = "SELECT " . $campos . " FROM ventas ven
        JOIN tipo_documentos tdc ON tdc.id = ven.tipo_documento_id WHERE 1 = 1 " . $where . " " . $order;        
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
    public function query_standar($modo, $select = array(), $pagina, $filas_por_pagina, $condicion = array(), $order = '') {
        if ($select == '') $select = array();
        $campos_standar = array('ven.venta_relacionado_id', 'ven.orden_compra', 'ven.numero_guia', 'ven.tipo_de_cambio tipo_de_cambio', 'ven.condicion_venta', 'mon.moneda moneda', 'mon.abrstandar moneda_abreviatura', 'mon.simbolo simbolo_moneda','ten.abreviatura tipo_entidad_abreviatura', 'ten.codigo tipo_entidad_codigo','estado_operacion' ,'respuesta_sunat_codigo' ,'respuesta_sunat_descripcion' ,'respuesta_anulacion_codigo' ,'respuesta_anulacion_descripcion' ,'estado_anulacion', 'ven.id venta_id', 'ven.total_bolsa total_bolsa', 'ven.serie serie', 'ven.numero numero', 'ven.estado_operacion', 'DATE_FORMAT(ven.fecha_emision, "%d-%m-%Y") fecha_emision_cf',  'fecha_emision', 'fecha_vencimiento', 'DATE_FORMAT(ven.fecha_vencimiento, "%d-%m-%Y") fecha_vencimiento_cf', 'ven.total_gravada total_gravada', 'ven.total_igv total_igv', 'ven.total_a_pagar total_a_pagar', 'tdc.abreviado abreviado', 'tdc.codigo tipo_documento_codigo', 'entidad', 'ent.nombre_comercial', 'ent.id entidad_id', 'ent.numero_documento numero_documento', 'ven.total_gratuita total_gratuita', 'ven.total_exportacion total_exportacion', 'ven.total_exonerada total_exonerada', 'ven.total_inafecta total_inafecta', 'ven.total_bolsa total_bolsa');
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

        $sql = "SELECT $select FROM ventas ven
        JOIN entidades ent ON ven.`entidad_id` = ent.`id`
        JOIN tipo_entidades ten ON ten.`id` = ent.`tipo_entidad_id`
        JOIN monedas mon ON mon.id = ven.moneda_id
        LEFT JOIN `tipo_documentos` tdc ON tdc.`id` = ven.`tipo_documento_id`        
        WHERE 1 = 1 " . $where . " " . $order . " " . $limit;
        //echo $sql;exit;
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
    
    public function query_detalle($venta_id){
        $sql = "SELECT estado_operacion, respuesta_sunat_codigo, respuesta_sunat_descripcion, respuesta_anulacion_codigo, respuesta_anulacion_descripcion, estado_anulacion, 
        ven.id venta_id, ven.`serie`, ven.`numero`, ven.`fecha_emision`, ven.`fecha_vencimiento`, tipo_de_cambio, total_gravada, total_igv, total_gratuita, total_exportacion,
        total_exonerada, total_inafecta, total_bolsa, total_a_pagar, estado_operacion, firma_sunat, orden_compra, notas, tipo_operacion,
        entidad, nombre_comercial, numero_documento, direccion, ent.email_1 email_1_entidad, ent.email_2 email_2_entidad, telefono_fijo_1, telefono_fijo_2, telefono_movil_1, telefono_movil_2,
        cantidad, precio_base, tipo_igv_id, impuesto_bolsa,
        codigo_sunat, pro.codigo codigo_producto, pro.producto, precio_base_venta, comision_venta, imagen,
        und.codigo codigo_unidad, unidad,
        epl.`nombres`, epl.`apellido_paterno`, epl.`apellido_materno`, epl.`dni`
        FROM ventas ven
        JOIN monedas mon ON mon.`id` = ven.`moneda_id`
        JOIN entidades ent ON ent.`id` =ven.`entidad_id`
        JOIN venta_detalles ven_det ON ven_det.`venta_id` = ven.`id`
        JOIN productos pro ON pro.`id` = ven_det.`producto_id`
        JOIN unidades und ON und.`id` = pro.`unidad_id`
        JOIN empleados epl ON epl.`id` = ven.`empleado_insert`
        WHERE ven.id = $venta_id";
        $query = $this->db->query($sql);
        
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }        
    
    public function query_cabecera($venta_id){
        $sql = "SELECT ven.retencion_porcentaje, ven.detraccion_codigo, ven.detraccion_porcentaje, mpa.`modo_pago`, ven.direccion_cliente direccion_cliente_de_venta, numero_pedido, nota_venta, numero_guia, condicion_venta, operacion, total_descuentos, operacion_id, respuesta_sunat_codigo, respuesta_sunat_descripcion, respuesta_anulacion_codigo, respuesta_anulacion_descripcion, estado_anulacion, 
        ven.id venta_id, YEAR(ven.fecha_emision) venta_anio, ven.forma_pago_id, ven.venta_relacionado_id, ven.tipo_ncredito_id, tipo_ndebito_id, ven.`serie`, ven.`numero`, ven.porcentaje_igv porcentaje_igv, ven.fecha_emision fecha_emision_sf, DATE_FORMAT(ven.fecha_emision, '%d-%m-%Y') AS fecha_emision, hora_emision, DATE_FORMAT(ven.fecha_vencimiento, '%d-%m-%Y') AS fecha_vencimiento, fecha_vencimiento fecha_vencimiento_sf, tipo_de_cambio, total_gravada, total_igv, total_gratuita, total_exportacion,
        total_exonerada, total_inafecta, total_bolsa, PrepaidAmount, total_a_pagar, estado_operacion, firma_sunat, orden_compra, ven.notas notas, tipo_operacion, UBLVersionID, CustomizationID,
        mon.id moneda_id, mon.`moneda`, mon.`simbolo` simbolo_moneda, mon.abrstandar,
        ent.id entidad_id, ent.entidad entidad, ent.nombre_comercial, numero_documento, direccion direccion_entidad, ent.email_1 email_1_entidad, ent.email_2 email_2_entidad, telefono_fijo_1, telefono_fijo_2, telefono_movil_1, telefono_movil_2,
        epl.`nombres`, epl.`apellido_paterno`, epl.`apellido_materno`, epl.`dni`,
        ten.id tipo_entidad_id, ten.`descripcion`, ten.`abreviatura` abreviatura_tipo_entidad, ten.`tipo_entidad`, ten.codigo codigo_tipo_entidad,
        tdo.id tipo_documento_id, tdo.`abreviado`, tdo.`codigo` tipo_documento_codigo, tdo.`tipo_documento`
        FROM ventas ven
        LEFT JOIN tipo_documentos tdo on tdo.`id` = ven.`tipo_documento_id`
        JOIN monedas mon ON mon.`id` = ven.`moneda_id`
        JOIN entidades ent ON ent.`id` =ven.`entidad_id`
        JOIN tipo_entidades ten on ten.`id` = ent.`tipo_entidad_id`
        JOIN empleados epl ON epl.`id` = ven.`empleado_insert`
        LEFT JOIN modo_pagos mpa ON ven.modo_pago_id = mpa.`id`
        WHERE ven.id = $venta_id";
        
        //echo $sql;
        $query = $this->db->query($sql);
        
        $row = array();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        }
        return $row;
    }
    
    public function cabecera_api($venta_id){
        $sql = "SELECT 
        ven.serie serie, ven.numero numero, ven.fecha_emision, hora_emision, fecha_vencimiento, 
        total_gravada, total_igv, total_gratuita, total_exonerada, total_inafecta, total_a_pagar,
        ent.entidad entidad, numero_documento,
        ten.codigo codigo_tipo_entidad,
        tdo.`codigo` tipo_documento_codigo
        FROM ventas ven
        LEFT JOIN tipo_documentos tdo on tdo.`id` = ven.`tipo_documento_id`
        JOIN entidades ent ON ent.`id` =ven.`entidad_id`
        JOIN tipo_entidades ten on ten.`id` = ent.`tipo_entidad_id`
        WHERE ven.id = $venta_id";
        
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

    public function ws_select($modo, $select, $pagina, $filas_por_pagina, $condicion = array(), $order = ''){
        $data = $this->query_standar($modo, $select, $pagina, $filas_por_pagina, $condicion, $order);
        $total_filas = $this->total_filas($condicion);
        
        $ruc_empresa = $this->empresas_model->select(1, array('ruc'));

        $datos = array();
        foreach ($data as $value){
            //solo para ventas aceptadas
            $ruta_xml = '';
            $ruta_cdr = '';
                        
            $numero_venta = $ruc_empresa . '-' . $value['tipo_documento_codigo'] . '-' . $value['serie'] . '-' . $value['numero'];
            $path_xml = "files/facturacion_electronica/FIRMA/" . $numero_venta . '.xml';
            $ruta_xml = FCPATH . $path_xml;
            $ruta_xml = (file_exists(FCPATH . $path_xml)) ? base_url() . $path_xml : '';
            
            if(($value['estado_operacion'] == 1) || ($value['estado_operacion'] == 2)){                                                                
                $path_cdr = "files/facturacion_electronica/FIRMA/R-" . $numero_venta . '.zip';
                $ruta_cdr = FCPATH . $path_cdr;
                $ruta_cdr = (file_exists(FCPATH . $path_cdr)) ? base_url() . $path_cdr : '';                
            }            
            
            $datos[] = array(
                'venta_id'                      =>  (int)$value['venta_id'],
                'serie'                         =>  $value['serie'],
                'numero'                        =>  $value['numero'],
                'fecha_emision'                 =>  $value['fecha_emision'],
                'total_gravada'                 =>  $value['total_gravada'],
                'total_igv'                     =>  $value['total_igv'],
                'total_a_pagar'                 =>  $value['total_a_pagar'],
                'total_bolsa'                   =>  $value['total_bolsa'],
                'abreviado'                     =>  $value['abreviado'],
                'entidad'                       =>  $value['entidad'],
                'entidad_id'                    =>  $value['entidad_id'],                
                'estado_operacion'              =>  (int)$value['estado_operacion'],
                'estado_anulacion'              =>  $value['estado_anulacion'],
                'tipo_documento_codigo'         =>  $value['tipo_documento_codigo'],
                'simbolo_moneda'                =>  $value['simbolo_moneda'],
                'respuesta_sunat_descripcion'   =>  $value['respuesta_sunat_descripcion'],
                'ruta_xml'                      =>  $ruta_xml,
                'ruta_cdr'                      =>  $ruta_cdr
            );
        }
        
        //$total_filas = $this->total_filas();        
        $salida = array(
            'ws_select_ventas' => $datos,
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
        
        $sql = "SELECT MAX(numero) numero FROM $this->tabla WHERE $where ORDER BY id DESC LIMIT 1";       
        $query = $this->db->query($sql);        
                
        $row = $query->row_array();
        $resultado = $row['numero'];            
        if($resultado == null) $resultado = 0;
            
        return $resultado;
    }
    
    public function select_max_id(){
        $sql = "SELECT MAX(id) venta_id FROM $this->tabla";
        $query = $this->db->query($sql);
        $resultado = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['venta_id'];
        }
        return $resultado;
    }
    
    public function venta_documento($venta_id){
        $sql = "SELECT ven.id venta_id, serie, numero, tdc.id tipo_documento_id, tdc.`codigo` codigo 
        FROM ventas ven
        JOIN tipo_documentos tdc ON tdc.`id` = ven.`tipo_documento_id` 
        WHERE ven.id =".$venta_id;
        $query = $this->db->query($sql);

        $row = array();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        }
        return $row;
    }
    
    public function datos_nc($venta_id){
        $sql = "SELECT ven2.`serie`, ven2.`numero`, tnc.`tipo_ncredito` FROM ventas ven
        JOIN `tipo_ncreditos` tnc ON ven.`tipo_ncredito_id` = tnc.`id`
        JOIN ventas ven2 ON ven2.id = ven.`venta_relacionado_id`
        WHERE ven.`id` = " . $venta_id;        
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
        $sql = "SELECT " . $campos . " FROM $this->tabla WHERE forma_pago_id = 2 AND venta_pagada = 0 " . $where . " " . $order;        
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
    
    public function pdf_a4($param_venta_id = '', $guardar_pdf = ''){                
        $venta_id = ($param_venta_id != '') ? $param_venta_id : $this->uri->segment(3);
        $guardar_pdf = ($guardar_pdf != '') ? $guardar_pdf : $this->uri->segment(4);
                
        $data['empresa'] = $empresa = $this->empresas_model->select(2);
        $data['cabecera'] = $cabecera = $this->query_cabecera($venta_id);
        $this->load->model('cuotas_model');
        $data['cuotas'] = $this->cuotas_model->select(3, '', array('venta_id' => $venta_id), ' ORDER BY id DESC');
        
        $data['detalle'] = $this->venta_detalles_model->query_detalle($venta_id);
        $data['venta_anticipos'] = $this->venta_anticipos_model->select_anticipos(3, array('ven2.serie', 'ven2.numero', 'ven2.total_a_pagar', 'ven2.total_igv'), array('van.venta_id' => $venta_id));
        $data['rutaqr'] = $this->GetImgQr($cabecera, $empresa);        
        $data['nota_venta'] = $this->ventas_model->select(2, array('numero'), array('operacion' => 2, 'operacion_id' => $venta_id));
        $data['cotizacion'] = $this->ventas_model->select(2, array('numero'), array('operacion' => 3, 'operacion_id' => $venta_id));
        $data['venta_guia'] = $this->venta_guias_model->select_guias(2, '', array('vgu.venta_id' => $venta_id));
        $data['catidad_decimales'] = $this->variables_diversas_model->catidad_decimales;

        if($data['cabecera']['tipo_ncredito_id'] != null){
            $data['nota_credito'] = $this->datos_nc($venta_id);
        }
                
        //convetimos el total en texto
        $totalVenta = explode(".", $data['cabecera']['total_a_pagar']);
        if($totalVenta[0] == 0){
            $data['totalLetras'] = '';
        }else{            
            $num = new Numletras();        
            $totalLetras = $num->num2letras($totalVenta[0]);
            $data['totalLetras'] = 'Son: '.$totalLetras.' con '.$totalVenta[1].'/100 '.$data['cabecera']['moneda'];
        }                

        //$html = $this->load->view("ventas/pdf_a4.php",$data,true);
        $html = $this->load->view("ventas/pdf_a4_3_model.php",$data,true);
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A4', 'portrait');
        $this->pdf->render();
        
        $nombre_documento = $empresa['ruc'].'-'.$data['cabecera']['tipo_documento_codigo'].'-'. $data['cabecera']['serie'] .'-'. $data['cabecera']['numero'];
//        $output = $this->pdf->output();
//        file_put_contents('files/pdf/ventas/'.$nombre_documento.'.pdf', $output);
        
        if($guardar_pdf == 1){
            $output = $this->pdf->output();
            file_put_contents('files/pdf/ventas/'.$nombre_documento.'.pdf', $output);
        }else{            
            $this->pdf->stream("$nombre_documento.pdf", array("Attachment"=>0));
        }
        //////////////////////////////////////////
    }
    
    public function pdf_a5($param_venta_id = '', $guardar_pdf = ''){
        $venta_id = ($param_venta_id != '') ? $param_venta_id : $this->uri->segment(3);
        $guardar_pdf = ($guardar_pdf != '') ? $guardar_pdf : $this->uri->segment(4);
                
        $data['empresa'] = $empresa = $this->empresas_model->select(2);
        $data['cabecera'] = $cabecera = $this->query_cabecera($venta_id);
        $data['detalle'] = $this->venta_detalles_model->query_detalle($venta_id);
        $data['venta_anticipos'] = $this->venta_anticipos_model->select_anticipos(3, array('ven2.serie', 'ven2.numero', 'ven2.total_a_pagar'), array('van.venta_id' => $venta_id));
        $data['rutaqr'] = $this->GetImgQr($cabecera, $empresa);
        $data['nota_venta'] = $this->ventas_model->select(2, array('numero'), array('operacion' => 2, 'operacion_id' => $venta_id));
        $data['cotizacion'] = $this->ventas_model->select(2, array('numero'), array('operacion' => 3, 'operacion_id' => $venta_id));
        $data['venta_guia'] = $this->venta_guias_model->select_guias(2, '', array('vgu.venta_id' => $venta_id));        
        $data['catidad_decimales'] = $this->variables_diversas_model->catidad_decimales;

        if($data['cabecera']['tipo_ncredito_id'] != null){
            $data['nota_credito'] = $this->datos_nc($venta_id);
        }
        
        //convetimos el total en texto
        $num = new Numletras();
        $totalVenta = explode(".", $data['cabecera']['total_a_pagar']);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = 'Son: '.$totalLetras.' con '.$totalVenta[1].'/100 '.$data['cabecera']['moneda'];
        $data['totalLetras'] = $totalLetras;

        $html = $this->load->view("ventas/pdf_a5.php",$data,true);
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper('A5', 'landscape');
        $this->pdf->render();        
        
        $nombre_documento = $empresa['ruc'].'-'.$data['cabecera']['tipo_documento_codigo'].'-'. $data['cabecera']['serie'] .'-'. $data['cabecera']['numero'];
//        $output = $this->pdf->output();
//        file_put_contents('files/pdf/ventas/'.$nombre_documento.'.pdf', $output);
        
        if($guardar_pdf == 1){
            $output = $this->pdf->output();
            file_put_contents('files/pdf/ventas/'.$nombre_documento.'.pdf', $output);
        }else{            
            $this->pdf->stream("$nombre_documento.pdf",
                array("Attachment"=>0)
            );
        }
        //////////////////////////////////////////
    }
    
    public function pdf_ticket($venta_id){
        $data['empresa'] = $empresa = $this->empresas_model->select(2);
        $data['cabecera'] = $cabecera = $this->query_cabecera($venta_id);
        $data['detalle'] = $this->venta_detalles_model->query_detalle($venta_id);
        $data['rutaqr'] = $this->GetImgQr($cabecera, $empresa);
        
        if($data['cabecera']['tipo_ncredito_id'] != null){
            $data['nota_credito'] = $this->datos_nc($venta_id);
        }
        
        //convetimos el total en texto
        $num = new Numletras();
        $totalVenta = explode(".", $data['cabecera']['total_a_pagar']);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = 'Son: '.$totalLetras.' con '.$totalVenta[1].'/100 '.$data['cabecera']['moneda'];
        $data['totalLetras'] = $totalLetras;

        $html = $this->load->view("ventas/pdf_ticket.php",$data,true);
        $this->pdf->loadHtml($html);
        $this->pdf->setPaper(array(0,0,85,440), 'portrait');
        $this->pdf->render();
        $nombre_documento = $data['cabecera']['tipo_documento']."-".$data['cabecera']['serie']."-".$data['cabecera']['numero'];
        $this->pdf->stream("T-$nombre_documento.pdf",
            array("Attachment"=>0)
        );        
    }
    
    public function pdf_58($venta_id){
        $data['empresa'] = $empresa = $this->empresas_model->select(2);
        $data['cabecera'] = $cabecera = $this->query_cabecera($venta_id);
        $data['detalle'] = $this->venta_detalles_model->query_detalle($venta_id);
        $data['rutaqr'] = $this->GetImgQr($cabecera, $empresa);        
        
        if($data['cabecera']['tipo_ncredito_id'] != null){
            $data['nota_credito'] = $this->datos_nc($venta_id);
        }
        
        //convetimos el total en texto
        $num = new Numletras();
        $totalVenta = explode(".", $data['cabecera']['total_a_pagar']);
        $totalLetras = $num->num2letras($totalVenta[0]);
        $totalLetras = 'Son: '.$totalLetras.' con '.$totalVenta[1].'/100 '.$data['cabecera']['moneda'];
        $data['totalLetras'] = $totalLetras;

        $ancho_fijo = 470;
        $ancho_item = 33;
        $cantidad_item = count($data['detalle']);
        $ancho_variable = $ancho_item * $cantidad_item;
        $html = $this->load->view("ventas/pdf_ticket_58.php",$data,true);
        $this->pdf->loadHtml($html);        
        
        $this->pdf->setPaper(array(0,0,110,$ancho_fijo + $ancho_variable), 'portrait');
        //$this->pdf->setPaper(array(0,0,110,520), 'portrait');
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
        $textoQR .= $cabecera['fecha_emision']."|";//FECHA DE EMISION 
        //tipo de cliente
     
        $textoQR .= $cabecera['codigo_tipo_entidad']."|";//TIPO DE DOCUMENTO ADQUIRENTE 
        $textoQR .= $cabecera['numero_documento']."|";//NUMERO DE DOCUMENTO ADQUIRENTE 
        
        $nombreQR = $cabecera['tipo_documento_id'].'-'.$cabecera['serie'].'-'.$cabecera['numero'];
        QRcode::png($textoQR, FCPATH."images/qr/".$nombreQR.".png", QR_ECLEVEL_L, 10, 2);
        
        return FCPATH."images/qr/{$nombreQR}.png";
    }
    
    public function QrPedidoVirtual($textoQR, $ruta, $extension){
        QRcode::png($textoQR, $ruta.".".$extension, QR_ECLEVEL_L, 10, 2);                        
    }
    
    public function setear_datos_totales($venta_id){
        $sql = "UPDATE ventas SET 
        tipo_de_cambio = NULL,
        total_gravada = NULL,
        total_igv = NULL,
        total_gratuita = NULL,
        total_exportacion = NULL,
        total_exonerada = NULL,
        total_inafecta = NULL,
        bolsa_monto_unitario = NULL,
        total_bolsa = NULL,
        total_otros_cargos = NULL,
        total_descuentos = NULL,
        PrepaidAmount = NULL,
        total_a_pagar = NULL,
        orden_compra = NULL,
        notas = NULL
        WHERE id = " . $venta_id;
        $query = $this->db->query($sql);
    }
    
    public function suma_mensual($anio) {
        $sql = "SELECT SUM(total_a_pagar) suma, YEAR(fecha_emision) anio, MONTH(fecha_emision) mes FROM ventas WHERE YEAR(fecha_emision) = $anio "
                . " AND estado_operacion = 1 AND estado_anulacion IS NULL GROUP BY YEAR(fecha_emision), MONTH(fecha_emision) ORDER BY mes ASC";
        $query = $this->db->query($sql);        
        $rows_ventas = array();
        
        foreach ($query->result_array() as $row) {
            $rows_ventas[$row['mes']]['mes']    = $row['mes'];
            $rows_ventas[$row['mes']]['suma']   = $row['suma'];
        }
        
        $sql = "SELECT SUM(total_a_pagar) suma, YEAR(fecha_emision) anio, MONTH(fecha_emision) mes FROM compras WHERE YEAR(fecha_emision) = $anio GROUP BY YEAR(fecha_emision), MONTH(fecha_emision)";
        $query = $this->db->query($sql);        
        $rows_compras = array();
        foreach ($query->result_array() as $row) {
            $rows_compras[$row['mes']]['mes']    = $row['mes'];
            $rows_compras[$row['mes']]['suma']   = $row['suma'];
        }
        
        $array_final = array();
        for($mes = 1; $mes <= date("m"); $mes++){
            if(isset($rows_ventas[$mes]['mes'])){
                $array_final[$mes]['mes']         = $rows_ventas[$mes]['mes'];
                $array_final[$mes]['suma_ventas'] = $rows_ventas[$mes]['suma'];
            }
            
            if(isset($rows_compras[$mes]['mes'])){                
                $array_final[$mes]['mes']          = (isset($array_final[$mes]['mes'])) ? $array_final[$mes]['mes'] : $rows_compras[$mes]['mes'];
                $array_final[$mes]['suma_compras'] = $rows_compras[$mes]['suma'];
            }            
        }
        return $array_final;
    }
    
    public function errores_sunat($codigo_error, $venta_id){
        if($codigo_error == '0'){
            $this->ventas_model->modificar($venta_id, array('estado_operacion' => 1));
        }
        
        //indicar como rechazado
        if(((int)$codigo_error >= 2010) && ((int)$codigo_error <= 2752)){
            $this->ventas_model->modificar($venta_id, array('estado_operacion' => 2));
        }
    }
    
    public function ws_sunat($venta_id){
        $sql = "SELECT *FROM ventas ven
        JOIN venta_detalles dve ON ven.`id` = dve.`venta_id`
        JOIN entidades ent ON ven.`entidad_id` = ent.`id`
        JOIN tipo_entidades ten ON ten.`id` = ent.`tipo_entidad_id`
        JOIN monedas mon ON mon.id = ven.moneda_id
        JOIN tipo_documentos tdc ON tdc.`id` = ven.`tipo_documento_id`        
        WHERE ven.`id` = " . $venta_id;
        $query = $this->db->query($sql);
        
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }

}