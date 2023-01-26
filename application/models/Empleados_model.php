<?PHP
if(!defined('BASEPATH')) exit ('No direct script access allowed');

class Empleados_model extends CI_Model{

    public $tabla = 'empleados';

    public function __construct() {
        parent::__construct();            
    }
    
    public function login($contrasena) {
        $rsUsuario = $this->db->select('emp.id empleado_id, emp.nombres, emp.dni, emp.apellido_paterno, emp.apellido_materno, emp.email_1, emp.email_2, tipo_empleado_id, tipo_empleado')
                          ->from("empleados as emp")
                          ->join("tipo_empleados as temp", "emp.tipo_empleado_id=temp.id")
                          ->where("emp.contrasena", $contrasena)
                          ->get()
                          ->row();
        //echo $this->db->last_query();
        //exit;        
        //var_dump($rsUsuario);exit;
        
        $data = [
            'empleado_id'       => $rsUsuario->empleado_id,
            'usuario'           => $rsUsuario->nombres,
            'dni'               => $rsUsuario->dni,
            'apellido_paterno'  => $rsUsuario->apellido_paterno,
            'apellido_materno'  => $rsUsuario->apellido_materno,
            'email_1'           => $rsUsuario->email_1,
            'email_2'           => $rsUsuario->email_2,
            'tipo_empleado_id'  => $rsUsuario->tipo_empleado_id,
            'tipo_empleado'     => $rsUsuario->tipo_empleado          
        ];

        $this->session->set_userdata($data);
        $this->session->set_flashdata('mensaje', 'Datos Correctos');
        return $rsUsuario->tipo_empleado_id;        
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
    
    //este query debe usarse tanto para la table, el detalle y el upate.
    public function query_standar($modo, $select = array(), $condicion = array(), $order = '') {
        if ($select == '')
            $select = array();
        $campos_standar = array('emp.id empleado_id', 'emp.apellido_paterno apellido_paterno', 'emp.apellido_materno apellido_materno', 'emp.nombres nombres', 'contrasena','emp.dni dni', 'emp.domicilio domicilio', 'DATE_FORMAT(emp.fecha_nacimiento, "%d/%m/%Y") fecha_nacimiento', 'emp.telefono_fijo telefono_fijo', 'emp.telefono_movil telefono_movil', 'emp.email_1 email_1', 'emp.email_2 email_2', 'emp.foto foto', 'tpe.id tipo_empleado_id', 'tpe.tipo_empleado tipo_empleado');
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
        if($order == '')
            $order = 'ORDER BY tipo_empleado_id, emp.`id` desc';
        
        $sql = "SELECT " . $select . "
        FROM empleados emp
        JOIN tipo_empleados tpe ON tpe.`id` = emp.`tipo_empleado_id`
        WHERE fecha_delete IS null " . $where . " " . $order;
        
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
                foreach ($query->result_array() as $key => $row) {
                    foreach ($row as $key => $value){
                        $supuesto_id = substr($key, -3);
                        if( ($supuesto_id == '_id') and is_numeric($value)){
                            $row[$key] = (int)$value;
                        }else{
                            $row[$key] = $value;
                        }
                    }
                    $rows[] = $row;
                }
                return $rows;
        }
    }

    public function insertar($data, $mensaje = '') {
        $this->db->insert($this->tabla, $data);
    }           

    public function modificar($id, $data, $mensaje='') {
        $this->db->where('id', $id);
        $this->db->update($this->tabla, $data);
    }

    public function set_datos_login($datos){                
        $ruta = './files/foto/';
        $ruta_foto = (file_exists($ruta . $this->session->userdata('foto')) && !empty($this->session->userdata('foto'))) ? $ruta.$this->session->userdata('foto') : $ruta . "sin_foto.jpg" ;
        
        $data = array(
            'empleado_id'       =>  $datos['id'],
            'usuario'           =>  $datos['nombres'],
            'dni'               =>  $datos['dni'],
            'apellido_paterno'  =>  $datos['apellido_paterno'],
            'apellido_materno'  =>  $datos['apellido_materno'],
            'email_1'           =>  $datos['email_1'],
            'email_2'           =>  $datos['email_2'],
            'tipo_empleado_id'  =>  $datos['tipo_empleado_id'],
            'foto'              =>  $datos['foto'],
            'ruta_foto'         =>  $ruta_foto
        );
        $this->session->set_userdata($data);
    }        

}