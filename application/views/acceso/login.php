<h1 align="center"></h1>
<br>
<p class="bg-info" align="center">
<?PHP 
//$this->session->sess_destroy();
session_destroy();
echo $this->session->flashdata('mensaje');?>
</p>

<div class="container">
    <br><br>
    <div class="row" id="div_atras_multiempresa">
        <!--<div class="col-md-2"><a id="enlace_atras"><img width="50px" id="img_atras"></a></div>-->
    </div>
    <div class="row"> 
        <div class="col-md-1">
        </div>
        
        <div class="col-md-11" style="text-align: center;">
             <?php  echo "<img  src='".base_url()."images/sistemasunat7.0.jpg' width='60%'>";?>
        </div>
        
        <div class="col-md-4" >
        </div>
        <div class="col-md-5" >
            <form class="form-signin" role="form" method="post" action="<?PHP echo base_url(); ?>index.php/acceso/login">
                <h2 class="form-signin-heading" style="text-align: center;"> Iniciar Sesión </h2>
                <h2><?php echo $this->session->flashdata('respuesta_login'); ?></h2>
                Correo: <input class="form-control" autofocus="" required="" placeholder="Correo" name="email_1" id="email_1"><br>
                Contraseña: <input class="form-control" type="password" autofocus="" required="" placeholder="Contraseña" name="contrasena" id="contrasena"><br>
                <br>
                <input type="submit" class="btn btn-lg btn-primary btn-block" value="Ingresar" style="border:0;"/>
            </form>
        </div>
        <div class="col-md-3" >
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-xs-12" align="center">
            <br>
            <br>
            <span align="center" id="span_empresa" style="color: #006400; font-size: 30px"></span>
        </div>        
    </div>
</div>
<script type="text/javascript">
    //para el caso de multiempresas
    var empresa = "";
    //$("#div_atras_multiempresa").hide();    
    
    var dominio = 'http://facturacionintegral.com/customers/';
    var base_url = dominio + 'jo_20609317729_kronox/';
    $("#img_atras").attr("src", base_url + "images/atras.png");
    $("#enlace_atras").attr("href",  dominio + "monstruo_multiempresa");
    $("#span_empresa").text(empresa);
</script>