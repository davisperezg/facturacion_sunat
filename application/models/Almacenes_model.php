<?php
class Almacenes_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
  
    public function select($idAlmacen='')
    {
        if($idAlmacen == '')
        {
          $rsAlmacenes = $this->db->from("almacenes")
                                  ->where('alm_estado', ST_ACTIVO)
                                  ->get()
                                  ->result();
          return $rsAlmacenes;

        }else
        {
          $rsAlmacen = $this->db->from("almacenes")
                                ->where("alm_id", $idAlmacen)
                                ->get()
                                ->row();
          return $rsAlmacen;          
        }
                      
    }
    public function guardar(){
        if($_POST['id']!='')
    	{
    		$dataUpdate = [
    						'alm_nombre'	=> strtoupper($_POST['nombre']),
    						'alm_direccion'	=> strtoupper($_POST['direccion']),
    						'alm_encargado' => strtoupper($_POST['encargado']),
    						'alm_telefono'  => $_POST['telefono'],
    					  ];
	        $this->db->where('alm_id', $_POST['id']);
	        $this->db->update('almacenes', $dataUpdate);    					  
    	}else
    	{
    		$dataInsert = [
    						'alm_nombre'	=> strtoupper($_POST['nombre']),
    						'alm_direccion'	=> strtoupper($_POST['direccion']),
    						'alm_encargado' => strtoupper($_POST['encargado']),
    						'alm_telefono'  => $_POST['telefono'],
    						'alm_estado' 	=> ST_ACTIVO
    					  ];
    		$this->db->insert('almacenes', $dataInsert); 			  
    	}

    	return true;
    } 

    public function eliminar($idAlmacen)
    {                     
      $almacenUpdate = [
                          "alm_estado" => ST_ELIMINADO
                       ];

      $this->db->where("alm_id", $idAlmacen);
      $this->db->update("almacenes", $almacenUpdate);

    	return true; 
    } 	

    public function getMainList()
    {
        $select = $this->db->from("almacenes")
                           ->where("alm_estado", ST_ACTIVO);
        if($_POST['search'] != '')
        {
            $select->like("alm_nombre", $_POST['search']);
        }                   

        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();
        $rows = count($rsCount);
        
        $rsAlmacenes = $select->limit($_POST['pageSize'],$_POST['skip'])
                              ->order_by("alm_id", "desc")
                              ->get()
                              ->result();                                          

        foreach($rsAlmacenes as $almacen)
        {
        	$almacen->alm_editar = "<a class='btn btn-default btn-xs btn_modificar_almacen' data-id='{$almacen->alm_id}' data-toggle='modal' data-target='#myModal'>Modificar</a>";

          if($almacen->alm_principal!=1){
            $almacen->alm_eliminar = "<a class='btn btn-danger btn-xs btn_eliminar_almacen' data-id='{$almacen->alm_id}' data-msg='Desea eliminar almacen: {$almacen->alm_nombre}?'>Eliminar</a>";
          }else{
            $almacen->alm_eliminar = "";
          }
        	
        }

       	$datos = [
       				'data' => $rsAlmacenes,
       				'rows' => $rows
       			 ];
        return $datos;    	
    }
}