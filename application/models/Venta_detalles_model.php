<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Venta_detalles_model extends CI_Model{

    public $tabla = 'venta_detalles';

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
    
    public function query_detalle($venta_id) {
        $sql = "SELECT 
        cantidad, precio_base, tipo_igv_id, impuesto_bolsa, descuento,
        pro.id producto_id, pro.codigo_sunat, pro.codigo codigo_producto, pro.producto producto, precio_base_venta, comision_venta, imagen,
        und.codigo codigo_unidad, unidad, und.id unidad_id,
        tig.codigo_de_tributo codigo_de_tributo, tig.codigo tipo_igv_codigo,
        tri.nombre nombre_tributo, tri.codigo_internacional
        FROM venta_detalles ven_det
        JOIN productos pro ON pro.`id` = ven_det.`producto_id`
        JOIN unidades und ON und.`id` = pro.`unidad_id`
        JOIN tipo_igvs tig ON tig.id = ven_det.tipo_igv_id
        JOIN codigo_tipo_tributos tri ON tri.codigo = tig.codigo_de_tributo
        WHERE ven_det.venta_id = $venta_id 
        ORDER BY ven_det.`id` ASC";
        //echo $sql;
        $query = $this->db->query($sql);
        
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    public function query_detalle_api($venta_id) {
        $sql = "SELECT 
        cantidad, precio_base, pro.codigo_sunat, pro.codigo codigo_producto, pro.producto producto, precio_base_venta,
        und.codigo codigo_unidad,
        tig.codigo tipo_igv_codigo
        FROM venta_detalles ven_det
        JOIN productos pro ON pro.`id` = ven_det.`producto_id`
        JOIN unidades und ON und.`id` = pro.`unidad_id`
        JOIN tipo_igvs tig ON tig.id = ven_det.tipo_igv_id
        WHERE ven_det.venta_id = $venta_id 
        ORDER BY ven_det.`id` ASC";
        //echo $sql;
        $query = $this->db->query($sql);
        
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }
            
    //consulta que se crea para guias
    public function query_detalle_guia($serie, $numero) {
        $sql = "SELECT
        ven.id venta_id,
        ven_det.id venta_detalle_id,
        cantidad, precio_base, tipo_igv_id, impuesto_bolsa,
        pro.id producto_id, pro.codigo_sunat, pro.codigo codigo_producto, pro.producto producto, precio_base_venta, comision_venta, imagen,
        und.codigo codigo_unidad, unidad, und.id unida_id
        FROM venta_detalles ven_det
        JOIN ventas ven ON ven.id = ven_det.venta_id
        JOIN productos pro ON pro.`id` = ven_det.`producto_id`
        JOIN unidades und ON und.`id` = pro.`unidad_id`
        WHERE ven.serie = '$serie' AND ven.numero = $numero
        ORDER BY ven_det.`id` DESC";
        //echo $sql;
        $query = $this->db->query($sql);
        
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function insertar($data) {
        $this->db->insert($this->tabla, $data);
    }           

    public function modificar($id, $data) {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);
    }
    
    public function delete_venta_id($venta_id) {        
        $this->db->where('venta_id', $venta_id);
        $this->db->delete($this->tabla);
    }

}