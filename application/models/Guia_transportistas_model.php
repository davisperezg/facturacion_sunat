<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Guia_transportistas_model extends CI_Model{

    public $tabla = 'guia_transportistas';

    public function __construct() {
        parent::__construct(); 
        $this->load->model('empresas_model');
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
    
    public function query_standar($modo, $select = array(), $pagina, $filas_por_pagina, $condicion = array(), $order = '') {
        if ($select == '') $select = array();
        $campos_standar = array( 'gui.id guia_id', 'gui.serie', 'gui.numero', 'gui.fecha_emision', 'gui.fecha_traslado', 'ven.id venta_id', 'ven.serie venta_serie', 'ven.numero venta_numero', 'ent.id entidad_id', 'ent.entidad');
        $select = (count($select) == 0) ? $campos_standar : $select;
        $select = implode(", ", $select);
        
        if ($condicion == '')
        $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {            
            $where .= " AND $key $value ";
        }
        
        $inicio = ($pagina - 1)*$filas_por_pagina;        
        $sql = "SELECT $select FROM guias gui
        LEFT JOIN venta_guias veg ON veg.`guia_id` = gui.`id`
        LEFT JOIN ventas ven ON ven.`id` = veg.`venta_id`
        LEFT JOIN `entidades` ent ON ent.`id` = gui.`destinatario_id`
        JOIN `guia_motivo_traslados` gmt ON gmt.`id` = gui.`guia_motivo_traslado_id`
        JOIN `guia_modalidad_traslados` gmd ON gmd.`id` = gui.`guia_modalidad_traslado_id`
        WHERE 1 = 1 " . $where . " " . $order . "
        LIMIT $inicio, $filas_por_pagina";
                        
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
    
    public function format_query_standar($datos){
        $format = array();
        $guia_id_2 = '';
        $i = 0;
        foreach ($datos as $value){
            if(($guia_id_2 != '') && ($value['guia_id'] != $guia_id_2)){
                $i ++;
            }            
            
            $format[$i]['guia_id'] = $value['guia_id'];
            $format[$i]['serie'] = $value['serie'];
            $format[$i]['numero'] = $value['numero'];
            $format[$i]['fecha_emision'] = $value['fecha_emision'];
            $format[$i]['fecha_traslado'] = $value['fecha_traslado'];
            $format[$i]['entidad_id'] = $value['entidad_id'];
            $format[$i]['entidad'] = $value['entidad'];            
            
            if($value['venta_numero'] != NULL){
                if($value['guia_id'] != $guia_id_2){
                    $format[$i]['venta_numero'] = '';
                }
                $format[$i]['venta_numero'] .= $value['venta_serie'].'-'.$value['venta_numero'].",";
            }            
            $guia_id_2 = $value['guia_id'];            
        }
        return $format;
    }

    public function insertar($data) {
        $this->db->insert($this->tabla, $data);
    }
    
    public function select_max_id(){
        $sql = "SELECT MAX(id) guia_id FROM $this->tabla";
        $query = $this->db->query($sql);
        $resultado = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['guia_id'];
        }
        return $resultado;
    }        

    public function modificar($id, $data) {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);
    }
    
    public function ultimoNumeroDeSerie($serie) {
        $sql = "SELECT numero FROM $this->tabla WHERE serie = '$serie' ORDER BY id DESC LIMIT 1";
        $query = $this->db->query($sql);        
        
        $resultado = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['numero'];
        }
        return $resultado;
    }
    
    public function query_cabecera($guia_id){
        $sql = "SELECT numero_mtc_transporte, serie, numero, fecha_emision fecha_emision_sf, DATE_FORMAT(fecha_emision, '%d-%m-%Y') AS fecha_emision, fecha_traslado fecha_traslado_sf, DATE_FORMAT(fecha_traslado, '%d-%m-%Y') AS fecha_traslado, guia_motivo_traslado_id, guia_modalidad_traslado_id, entidad_id_transporte, conductor_dni, conductor_nombres, conductor_apellidos, conductor_licencia, vehiculo_placa, destinatario_id, partida_ubigeo, partida_direccion, llegada_ubigeo, llegada_direccion, peso_total, numero_bultos, notas, envio_sunat FROM guias WHERE id = " . $guia_id;
        $query = $this->db->query($sql);
        
        $row = array();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        }
        return $row;
    }

    public function query_standar_cabecera($modo, $select = array(), $condicion = array(), $order = '') {
        if ($select == '') $select = array();
        $campos_standar = array('gui.serie serie', 'gui.numero numero', 'gui.fecha_emision fecha_emision', 'gui.hora_emision hora_emision', 'gui.fecha_traslado fecha_traslado',
            'gui.respuesta_sunat_codigo', 'gui.ticket_guia',
        'gui.partida partida', 'gui.partida_ubigeo partida_ubigeo', 'gui.llegada llegada', 'gui.llegada_ubigeo llegada_ubigeo',
        'gui.numero_mtc numero_mtc', 'gui.peso_total peso_total', 'gui.observaciones observaciones',
        'gui.vehiculo_mtc vehiculo_mtc', 'gui.vehiculo_placa vehiculo_placa',
        'gui.conductor_apellidos', 'gui.conductor_nombres', 'gui.conductor_dni', 'gui.conductor_licencia',
        'rem.entidad remitente_entidad', 'rem.numero_documento remitente_numero_documento', 'tena.codigo remitente_codigo_tipo_entidad',
        'des.entidad destinatario_entidad', 'des.numero_documento destinatario_numero_documento', 'tenb.codigo destinatario_codigo_tipo_entidad',
        'gad.serie adjunto_serie', 'gad.numero adjunto_numero',
        'tdo.tipo_documento adjunto_tipo_documento', 'tdo.codigo adjunto_documento_codigo');
        $select = (count($select) == 0) ? $campos_standar : $select;
        $select = implode(", ", $select);
        
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

        $sql = "SELECT 
        $select
        FROM guia_transportistas gui
        JOIN `guia_transportista_adjuntos` gad ON gad.`guia_transportista_id` = gui.`id`
        JOIN `tipo_documentos` tdo ON tdo.`id` = gad.`tipo_documento_id`
        JOIN entidades rem ON gui.remitente_id = rem.id
        JOIN `tipo_entidades` tena ON tena.id = rem.tipo_entidad_id
        JOIN entidades des ON gui.destinatario_id = des.id
        JOIN `tipo_entidades` tenb ON tenb.id = des.tipo_entidad_id
        WHERE 1 = 1 " . $where . " " . $order;

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
    
    //con ubigeos
    public function query_standar_cabecera_ubigeos($modo, $select = array(), $condicion = array(), $order = '') {
        if ($select == '') $select = array();
        $campos_standar = array('gui.numero_mtc_transporte', 'conductor_dni', 'conductor_nombres', 'conductor_apellidos', 'conductor_licencia', 'partida_ubigeo', 'llegada_ubigeo', 'gui.id guia_id', 'gui.serie', 'gui.numero_bultos', 'gui.peso_total', 'gui.notas', 'gui.numero', 'gui.guia_motivo_traslado_id', 'gui.guia_modalidad_traslado_id', 'etr.entidad entidad_transporte' , 'etr.numero_documento entidad_transporte_numero_documento', 'conductor_nombres', 'conductor_apellidos', 'conductor_licencia', 'vehiculo_placa', 'DATE_FORMAT(fecha_emision, "%d-%m-%Y") AS fecha_emision', 'DATE_FORMAT(fecha_traslado, "%d-%m-%Y") AS fecha_traslado', 'dep.departamento', 'pro.provincia', 'dis.distrito', 'partida_direccion', 'dep_lle.departamento departamento_llegada', 'pro_lle.provincia provincia_llegada', 'dis_lle.distrito distrito_llegada', 'llegada_direccion', 'ent.id entidad_id', 'ent.entidad', 'ent.numero_documento', 'gmt.guia_modalidad_traslado', 'gmo.guia_motivo_traslado');
        $select = (count($select) == 0) ? $campos_standar : $select;
        $select = implode(", ", $select);
        
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

        $sql = "SELECT $select FROM guias gui
        JOIN entidades ent ON ent.`id` = gui.`destinatario_id`
        LEFT JOIN entidades etr ON etr.id = gui.entidad_id_transporte
        JOIN `guia_modalidad_traslados` gmt ON gmt.`id` = gui.`guia_modalidad_traslado_id`
        JOIN `guia_motivo_traslados` gmo ON gmo.`id` = gui.`guia_motivo_traslado_id`
        JOIN `ubigeo_distritos` dis ON dis.`id` = gui.`partida_ubigeo`
        JOIN `ubigeo_provincias` pro ON pro.`id` = SUBSTRING(dis.id, 1, 4)
        JOIN `ubigeo_departamentos` dep ON dep.`id` = SUBSTRING(pro.id, 1, 2)
        JOIN `ubigeo_distritos` dis_lle ON dis_lle.`id` = gui.`llegada_ubigeo`
        JOIN `ubigeo_provincias` pro_lle ON pro_lle.`id` = SUBSTRING(dis_lle.id, 1, 4)
        JOIN `ubigeo_departamentos` dep_lle ON dep_lle.`id` = SUBSTRING(pro_lle.id, 1, 2)
        WHERE 1 = 1 " . $where . " " . $order;

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
    
    public function select_guias($pagina, $filas_por_pagina, $condicion = array(), $order = '') {
        if ($condicion == '')
        $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {            
            $where .= " AND $key $value ";
        }
        if($order == '')
        $order = ' ORDER BY gui.id desc ';
        $inicio = ($pagina - 1)*$filas_por_pagina;        
        
        $sql = "SELECT
            gui.id guia_id
            ,gui.serie
            , gui.estado_operacion
            , gui.numero
            , gui.fecha_emision
            , gui.hora_emision
            , gui.fecha_traslado
            , ent.entidad
        FROM guia_transportistas gui
        INNER JOIN entidades ent
        ON (gui.remitente_id = ent.id)
        WHERE ent.fecha_delete IS NULL " . $where . "
        $order
        LIMIT $inicio, $filas_por_pagina";        
       
        $query = $this->db->query($sql);
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    public function total_filas($condicion) {
        if ($condicion == '')
        $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {            
            $where .= " AND $key $value ";
        }
        
        $sql = "SELECT
        COUNT(gui.id) total_filas
        FROM guia_transportistas gui
        INNER JOIN entidades ent
        ON (gui.remitente_id = ent.id)
        WHERE ent.fecha_delete IS NULL " . $where;         
                
        $query = $this->db->query($sql);

        $resultado = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['total_filas'];
        }
        return $resultado;
    }
    
    public function ws_select($pagina, $filas_por_pagina, $condicion = array(), $order = ''){                
        $data = $this->select_guias($pagina, $filas_por_pagina, $condicion, $order);
        $total_filas = $this->total_filas($condicion);
        $ruc_empresa = $this->empresas_model->select(1, array('ruc'));

        $datos = array();
        foreach ($data as $value){  
            $ruta_xml = '';
            $ruta_cdr = '';
            
            $numero_venta = $ruc_empresa . '-09-' . $value['serie'] . '-' . $value['numero'];
            $path_xml = "files/guia_electronica_transportista/FIRMA/" . $numero_venta . '.xml';
            $ruta_xml = FCPATH . $path_xml;
            $ruta_xml = (file_exists(FCPATH . $path_xml)) ? base_url() . $path_xml : '';   

            $path_cdr = "files/guia_electronica_transportista/FIRMA/R-" . $numero_venta . '.zip';
            if(file_exists(FCPATH . $path_cdr)){
                $ruta_cdr = base_url() . $path_cdr;
                $respuesta_sunat = $this->leerRespuestaSunat($numero_venta.".xml");
                if($respuesta_sunat != null){
                    $this->guias_model->modificar($value['guia_id'], $respuesta_sunat);
                    if($respuesta_sunat['respuesta_sunat_codigo'] == '0'){
                        $this->guias_model->modificar($value['guia_id'], array('estado_operacion' => 1));
                    }
                }
            }else{
                $ruta_cdr = '';
            }

            $datos[] = array(
                'guia_id'           =>  (int)$value['guia_id'],
                'estado_operacion'  =>  $value['estado_operacion'],
                'entidad'           =>  $value['entidad'],
                'fecha_emision'     =>  $value['fecha_emision'],
                'fecha_traslado'    =>  $value['fecha_traslado'],
                'serie'             =>  $value['serie'],
                'numero'            =>  $value['numero'],
                'ruta_xml'          =>  $ruta_xml,
                'ruta_cdr'          =>  $ruta_cdr
            );
        }

        $salida = array(
            'guias' => $datos,
            'total_filas' => $total_filas
        );
        return $salida;
    }
    
    public function leerRespuestaSunat($nombre_archivo){
        $nombre = FCPATH."ws_sunat_guia/R-".$nombre_archivo;

        $resultado['respuesta_sunat_codigo'] = null;
        $resultado['respuesta_sunat_descripcion'] = null;
        if(file_exists($nombre)){
            $library = new SimpleXMLElement($nombre, null, true);
            
            $ns = $library->getDocNamespaces();
            $ext1 = $library->children($ns['cac']);
            $ext2 = $ext1->DocumentResponse;
            $ext3 = $ext2->children($ns['cac']);            
            $ext4 = $ext3->children($ns['cbc']);

            $resultado = array(
                'respuesta_sunat_codigo' => trim($ext4->ResponseCode),
                'respuesta_sunat_descripcion' => trim($ext4->Description)
            );
        }
        return $resultado;        
    }
    
}