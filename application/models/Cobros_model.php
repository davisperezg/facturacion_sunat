<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Cobros_model extends CI_Model{

    public $tabla = 'cobros';

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
    
    public function delete($cobro_id) {        
        $this->db->where('id', $cobro_id);
        $this->db->delete($this->tabla);
    }
    
    //se utilizara generalmente en el index
    function query_standar_1(){
        $sql = " FROM cobros cob
        JOIN `modo_pagos` mpa ON mpa.`id` = cob.`modo_pago_id`
        JOIN ventas ven ON ven.`id` = cob.`venta_id`
        JOIN entidades ent ON ent.`id` = ven.`entidad_id`";
        return $sql;
    }

    public function ws_select($pagina, $filas_por_pagina, $modo, $select = array(), $condicion = array(), $order = '') {

        if ($select == '')
            $select = array();
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            $where .= " AND $key $value ";
        }
        
        $limit = '';
        if(($pagina != '') && ($filas_por_pagina != '')){
            $inicio = ($pagina - 1)*$filas_por_pagina;
            $limit = "LIMIT $inicio, $filas_por_pagina";
        }
        
        $query_1 = $this->query_standar_1();
        $campos = ($select == array()) ? '*' : implode(", ", $select);
        $sql = "SELECT " . $campos . " $query_1 WHERE 1 = 1 " . $where . " " . $order . " " . $limit;
        //echo $sql."<br>";
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
    
    public function total_filas($condicion) {
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            $where .= " AND $key $value ";
        }        
        $query_1 = $this->query_standar_1();
        $sql = "select count(cob.id) total_filas $query_1 WHERE 1 = 1 " . $where;
        $query = $this->db->query($sql);

        $resultado = 0;
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            $resultado = $row['total_filas'];
        }
        return $resultado;
    }        
    
    public function select_cobros($modo, $select = array(), $condicion = array(), $order = '') {
        if ($select == '')
            $select = array();
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            $where .= " AND $key $value ";
        }

        $campos = ($select == array()) ? '*' : implode(", ", $select);        
        $sql = "SELECT " . $campos . " FROM cobros cob JOIN modo_pagos mpa ON cob.modo_pago_id = mpa.id WHERE 1 = 1 " . $where . " " . $order;
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
    
    public function delete_venta_id($venta_id) {        
        $this->db->where('venta_id', $venta_id);
        $this->db->delete($this->tabla);
    }
    
    public function reporte_cobro($cobro_id){
        $sql = "SELECT 
        entidades.entidad, entidades.id entidad_id,
        ventas.serie, ventas.numero, ventas.id venta_id,
        cobros.id cobro_id, cobros.fecha_pago, cobros.monto, cobros.nota, cobros.archivo_adjunto,
        modo_pagos.modo_pago, modo_pagos.id modo_pago_id
        FROM entidades
        INNER JOIN `ventas`  ON (`entidades`.`id` = `ventas`.`entidad_id`)
        INNER JOIN `cobros`  ON (`ventas`.`id` = `cobros`.`venta_id`)
        INNER JOIN `modo_pagos` ON (`cobros`.`modo_pago_id` = `modo_pagos`.`id`)
        WHERE cobros.`id` = " .$cobro_id;
        $query = $this->db->query($sql);
        
        $row = array();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        }
        return $row;
    }
    

}