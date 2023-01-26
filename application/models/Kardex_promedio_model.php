<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Kardex_promedio_model extends CI_Model{

    public $tabla = 'kardex_promedio';

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
    
    public function reporte_mensual($anio, $mes_inicial, $mes_final) {
        $sql = "SELECT kar.id, IFNULL(entrada_costo, IFNULL(salida_costo, 0)) precio_costo, (final_cantidad - IFNULL(entrada_cantidad, 0) + IFNULL(salida_cantidad, 0)) stock_inicial, producto_id, pro.codigo codigo, producto, und.unidad, SUM(`entrada_cantidad`) entrada, SUM(`salida_cantidad`) salida FROM `kardex_promedio` kar 
        JOIN productos pro ON pro.id = kar.`producto_id`
        JOIN unidades und ON und.`id` = pro.`unidad_id`
        WHERE YEAR(kar.`fecha`) = $anio AND kar.fecha BETWEEN $mes_inicial AND $mes_final GROUP BY kar.producto_id";
        $query = $this->db->query($sql);

        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;        
    }
    
    public function mas_vendidos_cantidad($mes_inicial, $mes_final) {
        $sql = "SELECT producto, id producto_id, codigo, table1.suma_cantidad suma_cantidad 
        FROM productos pro
        JOIN (SELECT  SUM(salida_cantidad) suma_cantidad, producto_id FROM kardex_promedio kar 
        WHERE `compra_venta` = 2 AND kar.fecha BETWEEN $mes_inicial AND $mes_final
        GROUP BY producto_id) table1
        ON pro.`id` = table1.producto_id
        WHERE fecha_delete IS NULL
        ORDER BY suma_cantidad DESC";
        $query = $this->db->query($sql);

        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function insertar($data, $mensaje = '') {
        $this->db->insert($this->tabla, $data);
    }           

    public function modificar($id, $data, $mensaje='') {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);
    }

    public function actualizar_datos(){
        $sql = "TRUNCATE TABLE `kardex_temporal`";
        $this->db->query($sql);        
        $sql = "CALL `sp_kardex_temporal`";
        $this->db->query($sql);
        
        $sql = "TRUNCATE TABLE `kardex_promedio`";
        $this->db->query($sql);        
        $sql = "CALL `sp_kardex_promedio`";
        $this->db->query($sql);        
    }

}