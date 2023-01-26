<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Entidades_model extends CI_Model{

    public $tabla = 'entidades';

    public function __construct() {
        parent::__construct();            
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
    
    public function select2($modo, $select = array(), $condicion = array(), $order = '') {
        if ($select == '')
            $select = array();
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            $where .= " AND $key $value ";            
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

    public function insertar($data) {
        $this->db->insert($this->tabla, $data);
    }           

    public function modificar($id, $data) {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);
    }
    
    public function select_entidades($pagina, $filas_por_pagina, $condicion = array(), $order = '') {
        if ($condicion == '')
        $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {            
            $where .= " AND $key $value ";
        }
        if($order == '')
        $order = ' ORDER BY ent.id desc ';
        $inicio = ($pagina - 1)*$filas_por_pagina;
        $sql = "SELECT 
        ent.id entidad_id, tipo_entidad_id, entidad, numero_documento, direccion, email_1, email_2, telefono_fijo_1, telefono_fijo_2, telefono_movil_1, telefono_movil_2, pagina_web, facebook, twitter, tipo_entidad
        FROM entidades ent
        JOIN tipo_entidades tip ON ent.tipo_entidad_id = tip.id
        WHERE ent.`fecha_delete` IS NULL " . $where . " " . $order . "
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
        $sql = "select count(ent.id) total_filas from $this->tabla ent
        JOIN tipo_entidades tip ON ent.`tipo_entidad_id` = tip.`id`
        WHERE ent.`fecha_delete` IS NULL " . $where;
        $query = $this->db->query($sql);

        $resultado = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['total_filas'];
        }
        return $resultado;
    }    

    public function ws_select($pagina, $filas_por_pagina, $condicion = array(), $order = ''){
        $data = $this->select_entidades($pagina, $filas_por_pagina, $condicion, $order);
        $total_filas = $this->total_filas($condicion);

        $datos = array();
        foreach ($data as $value){
            $datos[] = array(
                'entidad_id' => (int)$value['entidad_id'],
                'numero_documento'=>$value['numero_documento'],
                'entidad'=>$value['entidad'],
                'direccion'=>$value['direccion'],
                'email_1'=>$value['email_1'],
                'email_2'=>$value['email_2'],
                'telefono_fijo_1'=>$value['telefono_fijo_1'],
                'telefono_fijo_2'=>$value['telefono_fijo_2'],
                'telefono_movil_1'=>$value['telefono_movil_1'],
                'telefono_movil_2'=>$value['telefono_movil_2'],
                'pagina_web'=>$value['pagina_web'],
                'facebook'=>$value['facebook'],
                'twitter'=>$value['twitter'],
                'tipo_entidad'=>$value['tipo_entidad']
            );
        }

        $salida = array(
            'ws_select_entidades' => $datos,
            'total_filas' => $total_filas
        );
        return $salida;
    }
    
    public function select_buscador($buscar, $tipo_entidad_id = '') {
        $tipo_entidad_id = ($tipo_entidad_id == '') ? '' : ' AND tipo_entidad_id = ' . $tipo_entidad_id;
        $sql = "SELECT id entidad_id, tipo_entidad_id, numero_documento, entidad, direccion, telefono_movil_1 "
                . "FROM $this->tabla "
                . "WHERE (entidad LIKE '%$buscar%' OR numero_documento LIKE '%$buscar%' OR telefono_movil_1 LIKE '%$buscar%') AND `fecha_delete` IS NULL $tipo_entidad_id "
                . "ORDER BY entidad";
        //echo $sql;
        $query = $this->db->query($sql);

        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    public function ws_buscador($buscar, $tipo_empresa_id = ''){
        $data = $this->select_buscador($buscar, $tipo_empresa_id);

        $datos = array();
        foreach ($data as $value){
            $datos[] = array(
                'id'                =>  (int)$value['entidad_id'],
                'value'             =>  $value['entidad'].' - '.$value['numero_documento'].' - '.$value['telefono_movil_1'],
                'direccion'         =>  $value['direccion'],
                'telefono_movil_1'  =>  $value['telefono_movil_1'],
                'tipo_entidad_id'   =>  $value['tipo_entidad_id']
            );
        }        
        return $datos;
    }  
    
    public function ws_item($entidad_id){
        $data = $this->select(2, '', array('id' => $entidad_id));
        $datos[] = array(
            'entidad_id'        =>$data['id'],
            'tipo_entidad_id'   =>$data['tipo_entidad_id'],
            'entidad'           =>$data['entidad'],
            'numero_documento'  =>$data['numero_documento'],
            'direccion'         =>$data['direccion'],
            'email_1'           =>$data['email_1'],
            'email_2'           =>$data['email_2'],
            'telefono_fijo_1'   =>$data['telefono_fijo_1'],
            'telefono_fijo_2'   =>$data['telefono_fijo_2'],
            'telefono_movil_1'  =>$data['telefono_movil_1'],
            'telefono_movil_2'  =>$data['telefono_movil_2'],
            'pagina_web'        =>$data['pagina_web'],
            'facebook'          =>$data['facebook'],
            'twitter'           =>$data['twitter']
        );
        return $datos;        
    }
    
    public function select_item($entidad_id) {
        $sql = "SELECT ent.id entidad_id, entidad, numero_documento, direccion, email_1, email_2, telefono_fijo_1, telefono_fijo_2, telefono_movil_1, telefono_movil_2, pagina_web, facebook, twitter, tipo_entidad, tpe.codigo codigo
        FROM entidades ent
        JOIN tipo_entidades tpe ON tpe.`id` = ent.`tipo_entidad_id` 
        WHERE ent.`id` = $entidad_id";
        $query = $this->db->query($sql);

        $row = array();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        }
        return $row;
    }
    
    public function ws_select_item($entidad_id){
        $data = $this->select_item($entidad_id);
        $datos[] = array(        
            'entidad_id'        =>$data['entidad_id'],
            'entidad'           =>$data['entidad'],
            'numero_documento'  =>$data['numero_documento'],
            'direccion'         =>$data['direccion'],
            'email_1'           =>$data['email_1'],
            'email_2'           =>$data['email_2'],
            'telefono_fijo_1'   =>$data['telefono_fijo_1'],
            'telefono_fijo_2'   =>$data['telefono_fijo_2'],
            'telefono_movil_1'  =>$data['telefono_movil_1'],
            'telefono_movil_2'  =>$data['telefono_movil_2'],            
            'pagina_web'        =>$data['pagina_web'],
            'facebook'          =>$data['facebook'],
            'twitter'           =>$data['twitter'],
            'tipo_entidad'      =>$data['tipo_entidad'],
            'codigo'            =>$data['codigo'],
        );      
        return $datos;        
    }
    
    public function select_max_id(){
        $sql = "SELECT MAX(id) entidad_id FROM $this->tabla";
        $query = $this->db->query($sql);
        $resultado = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['entidad_id'];
        }
        return $resultado;
    }

    //generalmente se usa para insertar cuando el numero documento = '00000000', 
    //generalmente para deliverys (cuando no quiren dar DNI pero se debe guardar nombre y direccion para identificar y enviar)
    public function select_deliverys($numero_documento){        
        $sql = "SELECT id FROM entidades WHERE (numero_documento = $numero_documento ) AND ( $numero_documento != '00000000') AND fecha_delete IS NULL";
        $query = $this->db->query($sql);
        $resultado = '';
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['id'];
        }
        return $resultado;        
    }
    
    //se creo inicial para actualizar dirección q se pone al momento de vender.
    public function actualizoDireccion($entidad_id, $select_evento, $direccion_cliente_incial, $direccion_cliente_nueva) {        
        if($select_evento == 1){//si biene del autocomplete
            if(($direccion_cliente_nueva != '') && ($direccion_cliente_nueva != $direccion_cliente_incial)){
                $this->modificar($entidad_id, array('direccion' => $direccion_cliente_nueva));
            }
        }
        if($select_evento == 2){//si biene del buscador SUNAT
            if(($direccion_cliente_nueva != '') && ($direccion_cliente_nueva != '-')){
                $this->modificar($entidad_id, array('direccion' => $direccion_cliente_nueva));
            }
        }
        
    }
    
}