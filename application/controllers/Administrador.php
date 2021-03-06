<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Administrador extends Ci_Controller{

		public function __construct(){
			parent:: __construct();
			session_start();
			$this->load->helper('url');
    		$this->load->model('Mod_usuarios');
    		$this->load->library('grocery_CRUD');
    		error_reporting(E_ERROR | E_PARSE);
		}

		public function salida_datos($output= null){

			$this->load->view('head');
      		$this->load->view('headers/header_administrador');
			$this->load->view('administrador/admini_profesores.php',$output);
		}

		public function index(){

			$this->load->view('head_noticias');
			$this->load->view('headers/header_administrador');
			$this->load->view('administrador/noticias');
		}
		//ADMINISTRA A LOS PROFESORES
		public function admin_planificaciones_com(){

			
			$cod_asig = $_GET['cod_asig'];
			$crud = new grocery_CRUD();

       
	        $crud->set_language('spanish');
	        $crud->set_subject('planificacion');
	        //$crud->set_theme('datatables');
	        $crud->set_table('planificacion_historica');
	        
	      
	      //condiciones
	        $crud->display_as('codigo_asignatura','Asignatura');
	        $crud->columns('codigo_asignatura','rut_profesor','fecha_syllabus','syllabus');

	      $crud->set_field_upload('syllabus','assets/uploads/files');
	        //relaciones con la asignatura
	      $crud->set_relation('codigo_asignatura','asignatura','nombre');

	      //restricciones
	      $crud->unset_add();
	      $crud->unset_delete();
	      $crud->unset_edit();

	      $output = $crud->render();

			$this->salida_datos_planificaciones_com($output);
			

		}

		public function salida_datos_planificaciones_com($output= null){
			$this->load->view('head');
			$this->load->view('headers/header_administrador');
			$this->load->view('administrador/planificaciones_globales.php',$output);
		}


		//ADMINISTRA LAS PLANIFICACIONES Y ASIGNA LOS PROFESORES A LOS CURSOS
		public function admin_planificacion(){//se necesita programar a mano el agregado de planificacion....
			$cod=$_GET['cod_asig'];
			echo $cod;
			$consulta=$this->Mod_usuarios->revisar_asignacion($cod);
			 foreach ($consulta->result() as $atributo) {
         		 $codigo=$atributo->codigo_asignatura;
      		}  
			if($cod=$codigo)
			{
				redirect(base_url() .'index.php/administrador/malla_actual'); 
			}


			$this->salida_datos_plani($output);

		}

		public function malla_profesores(){//se necesita programar a mano el agregado de planificacion....
			
			$this->load->view('head');
			$this->load->view('headers/header_administrador');
			$this->load->view('administrador/malla_profesor');
		
		}

		public function salida_datos_plani($output= null){
			$this->load->view('head');
			$this->load->view('headers/header_administrador');
			$this->load->view('administrador/admini_planificaciones.php',$output);
		}

		//ADMINISTRA A LOS CURSOS
		public function admin_asignatura(){

			$crud = new grocery_CRUD();


			//$crud->set_theme('datatables');
			$crud->set_table('asignatura');
			$crud->set_language('spanish');
			$crud->set_subject('Asignatura');

			$crud->columns('codigo','nombre');



			$output = $crud->render();

			$this->salida_datos_cursos($output);
		}
		public function salida_datos_cursos($output= null){
			$this->load->view('head_noticias');
			$this->load->view('headers/header_administrador');
			$this->load->view('administrador/admini_cursos.php',$output);
		}
		//ADMINISTRA A LOS USUARIOS (TODOS)
		public function admin_usuarios(){

			$crud = new grocery_CRUD();

			//$crud->set_theme('datatables');
			$crud->set_table('usuarios');
			$crud->set_language('spanish');
			$crud->set_subject('usuario');
			$crud->columns('rut','nombre_1','apellido_1','apellido_2','departamento','correo','tipo');


			//reglas
			$crud->field_type('estado','enum',array('Activo','Inactivo'));
			$crud->field_type('tipo','enum',array('Profesor','Administrador'));
			$crud->field_type('departamento','enum',array('Facultad de Cs. de la Salud','Facultad de Cs. de la Educación','Facultad de Cs. de la Ingeniería','Facultad de Cs. Agrarias y Forestales','Facultad de Cs. Religiosas y Filosóficas','Facultad de Medicina','Facultad de Ciencias Básicas','Facultad de Ciencias Sociales y Económicas','Instituto de Estudios Generales'));
			
			$crud->callback_add_field('rut',function () {
				return '<input type="text" maxlength="50" value="" name="rut"> (Ej: 18.563.111-3 )';
			});


			$crud->display_as('nombre_1','Primer Nombre');
			$crud->display_as('nombre_2','Segundo Nombre');
			$crud->display_as('apellido_1','Apellido Paterno');
			$crud->display_as('apellido_2','Apellido Materno');
		
			$output = $crud->render();

			$this->salida_datos_usuarios($output);


		}

		public function salida_datos_usuarios($output= null){

			$this->load->view('head_noticias');
			$this->load->view('headers/header_administrador');
			$this->load->view('administrador/admini_usuarios.php',$output);
		}
		  public function salir() {
	   		session_destroy();
	    	redirect('welcome');
		}

		public function planificaciones_historicas_ad(){

     	 $crud = new grocery_CRUD();

       
        $crud->set_language('spanish');
        $crud->set_subject('Planificacion');
        //$crud->set_theme('datatables');
        $crud->set_table('planificacion');
        $crud->where('codigo_asignatura','cod_asig');
      
      //condiciones
        $crud->display_as('codigo_asignatura','Asignatura');
        $crud->columns('codigo_asignatura','rut_profesor','fecha_syllabus','syllabus');

      $crud->set_field_upload('syllabus','assets/uploads/files');
        //relaciones con la asignatura
      $crud->set_relation('codigo_asignatura','asignatura','nombre');

      //restricciones
      $crud->unset_add();
      $crud->unset_delete();
      $crud->unset_edit();

      $output = $crud->render();

      $this->salida_datos_historicas_ad($output);

  		}

  		public function salida_datos_historicas_ad($output= null){

			$this->load->view('head_noticias');
			$this->load->view('headers/header_administrador');
			$this->load->view('administrador/planificaciones_historicas_ad.php',$output);
		}


		public function agregar_noticia()
{  

			$this->load->view('head_noticias');
			$this->load->view('headers/header_administrador');
			$this->load->view('administrador/agregar_noticia');

  if (isset($_POST["agregar_noticia"]))
  {
     //Guardamos valores para que no tenga que reescribirlos
  	//$this->load->view('agregar_noticia')
  	$titulo = $_POST['titulo'];
    $cuerpo = $_POST['cuerpo'];
    date_default_timezone_set("America/Santiago");
    $fecha = date ("Y-m-d");
    $hora= date("G:i:s");
    $nombreImagen="";
    $nombreFichero="";
    // Subir fichero
    $copiarFichero = false;
    $copiarImagen = false;

//AGREGAR IMAGEN
   // Copiar fichero en directorio de ficheros subidos
   // Se renombra para evitar que sobreescriba un fichero existente
   // Para garantizar la unicidad del nombre se añade una marca de tiempo
   if (is_uploaded_file($_FILES['imagen'] ["tmp_name"]))
{

     $nombreDirectorio = "images_noticia/";
     $nombreImagen = $_FILES['imagen']['name'];
     $copiarImagen = true;

      // Si ya existe un fichero con el mismo nombre, renombrarlo
     $nombreCompleto = $nombreDirectorio . $nombreImagen;
     if (is_file($nombreCompleto))
     {
      $idUnico = time();
      $nombreImagen = $idUnico . "-" . $nombreImagen;
    }
  }
  if ($copiarImagen){
    move_uploaded_file ($_FILES['imagen']['tmp_name'],
      $nombreDirectorio . $nombreImagen);
  }
  //AGREGAR ARCHIVO
     // Copiar fichero en directorio de ficheros subidos
   // Se renombra para evitar que sobreescriba un fichero existente
   // Para garantizar la unicidad del nombre se añade una marca de tiempo
  if (is_uploaded_file ($_FILES['archivo']['tmp_name']))
  {
   $nombreDirectorio = "images_noticia/";
   $nombreFichero = $_FILES['archivo']['name'];
   $copiarFichero = true;

      // Si ya existe un fichero con el mismo nombre, renombrarlo
   $nombreCompleto = $nombreDirectorio . $nombreFichero;
   if (is_file($nombreCompleto))
   {
    $idUnico = time();
    $nombreFichero = $idUnico . "-" . $nombreFichero;
  }
}
if ($copiarFichero){
  move_uploaded_file ($_FILES['archivo']['tmp_name'],
    $nombreDirectorio . $nombreFichero);
}
//pasar todos los valores a un array
$datos=array(
  'titulo' =>$titulo,
  'descripcion' =>$cuerpo,
  'fecha' =>$fecha,  
  'hora' =>$hora,
  'imagen' => $nombreImagen,
  'archivo' => $nombreFichero  
  );
  //insertar en la base de datos la noticia
if (($this->Mod_usuarios->insertar_noticias($datos))) {
 $data['mensaje']=$result='<div class="alert alert-success"><h3>Noticia Ingresada Correctamente </h3></div>';

 

 
}else{

  
			$this->load->view('head_noticias');
			$this->load->view('headers/header_administrador');
  			$this->load->view('administrador/agregar_noticia');
}
}
}
		
		public function hist_plani(){
		    $cod_asig = $_GET['cod_asig'];
			$crud = new grocery_CRUD();

       
        $crud->set_language('spanish');
        $crud->set_subject('Planificacion');
        //$crud->set_theme('datatables');
        $crud->set_table('planificacion_historica');
        $crud->where('codigo_asignatura',$cod_asig);
      
      //condiciones
        $crud->display_as('codigo_asignatura','Asignatura');
        $crud->columns('codigo_asignatura','rut_profesor','fecha_syllabus','syllabus');

      $crud->set_field_upload('syllabus','assets/uploads/files');
        //relaciones con la asignatura
      $crud->set_relation('codigo_asignatura','asignatura','nombre');

      //restricciones
      $crud->unset_add();
      $crud->unset_delete();
      $crud->unset_edit();

      $output = $crud->render();

      $this->salida_datos_historicas_ad($output);

			
		}

		public function malla(){

			$this->load->view('head_noticias');
			$this->load->view('headers/header_administrador');
  			$this->load->view('administrador/malla');
		}

		public function malla_actual(){

			$this->load->view('head_noticias');
			$this->load->view('headers/header_administrador');
  			$this->load->view('administrador/malla_actual');
		}


		public function plantillas_ad(){

		$crud = new grocery_CRUD();

       
        $crud->set_language('spanish');
        $crud->set_subject('Plantilla');
        //$crud->set_theme('datatables');
        $crud->set_table('plantilla');

        $crud->columns('descripcion','plantilla');
        
        $crud->set_field_upload('plantilla','assets/uploads/files');

         $output = $crud->render();

      	$this->salida_datos_plantilla($output);

			}
  		
  		public function salida_datos_plantilla($output= null){

			$this->load->view('head_noticias');
			$this->load->view('headers/header_administrador');
			$this->load->view('administrador/plantillas.php',$output);
		}
		
		public function termino_semestre(){
  			$this->Mod_usuarios->transferir_tabla();
  			$this->Mod_usuarios->borrar_planificacion();		
  			redirect(base_url() .'index.php/administrador'); 		
  			
		}
		
		public function editar_planificaciones(){
			

			if ($_GET['cod_asig']!=''){
				$_COOKIE['cod_asig'] = $_GET['cod_asig'];
				$_GET['cod_asig']='';				
			}
			
			
			$crud = new grocery_CRUD();			
			$crud->set_language('spanish');
			$crud->set_subject('planificacion');			       		
       		
       	 //$crud->set_theme('datatables');
       		$crud->set_table('planificacion');
		    $crud->where('codigo_asignatura',$_COOKIE['cod_asig']);
		    $crud->columns('codigo_asignatura','syllabus','fecha_syllabus','semestre');	 
		    $crud->set_relation('codigo_asignatura','asignatura','nombre');
		    $crud->display_as('codigo_asignatura','Nombre asignatura');
		    $crud->display_as('fecha_syllabus','Año');
		    $crud->set_field_upload('syllabus','assets/uploads/files');
       
		    //restricciones
		    
		    $crud->unset_add();
		    $crud->unset_delete();
		    $crud->unset_read();
		    $crud->unset_edit();

		    $output = $crud->render();
		    $this->salida_datos_editar_planificaciones($output); 

		}

		public function editar_profe_asig(){
			

			
			if ($_GET['cod_asig']!=''){
				$_COOKIE['cod_asig'] = $_GET['cod_asig'];
				$_GET['cod_asig']='';				
			}
			
			$crud = new grocery_CRUD();			
			$crud->set_language('spanish');
			$crud->set_subject('Profesor/es');			       		
       		
       	 //$crud->set_theme('datatables');
       		$crud->set_table('profesor_planificacion');
       		$crud->where('codigo_asignatura',$_COOKIE['cod_asig']);
		    
		    $crud->columns('codigo_asignatura','rut_profesor','fecha','semestre');	 
		    $crud->set_relation('rut_profesor','usuarios','{nombre_1} {apellido_1} {apellido_2}');
		    $crud->set_relation('codigo_asignatura','asignatura','nombre');
		    $crud->display_as('codigo_asignatura','Nombre asignatura');
		    $crud->display_as('fecha','Año');
		    
		    //restricciones
		    
		    $crud->unset_add();
		    $crud->unset_delete();
		    $crud->unset_read();
		    $crud->unset_edit();

		    $output = $crud->render();
		    $this->salida_datos_profes($output);

		      


		}
	
			
		public function salida_datos_editar_planificaciones($output= null){

			$this->load->view('head_noticias');
			$this->load->view('headers/header_administrador');
			$this->load->view('administrador/editar_planificaciones.php',$output);
		}


		public function salida_datos_profes($output= null){

			$this->load->view('head_noticias');
			$this->load->view('headers/header_administrador');
			$this->load->view('administrador/editar_planificaciones.php',$output);
		}




		public function agregar_planificacion(){
			
			$año=date("Y");
			$ramo=$_POST['ramo'];
			$res=$this->Mod_usuarios->revisar_asignacion($ramo);
			foreach ($res->result() as $atributo) {
          		$veri=$atributo->codigo_asignatura;
      		}  
      		
      		if($ramo==$veri)
      		{
      			echo "hola";
      			redirect(base_url() .'index.php/administrador/malla_actual');
      		}

      		if($_POST['profesor']==$_POST['profesor_dos'])
      		{
      			print '<script language="JavaScript">'; 
				print 'alert("Error! Profesor 1 y Profesor 2 no pueden ser iguales");'; 
				print '</script>'; 
				$url='refresh:1;url='.base_url() .'index.php/administrador/admin_planificacion?cod_asig='.$_POST['ramo'];
				header($url);

      		}
			
			$array=array('codigo_asignatura' => $_POST['ramo'],				
				'fecha_syllabus'=>$año,
				'semestre'=>$_POST['semestre'],
				'codigo_asignatura2' => $_POST['ramo']);

			

			$array2=array('codigo_asignatura' => $_POST['ramo'],
				'rut_profesor'=> $_POST['profesor'],
				'fecha'=>$año,
				'semestre'=>$_POST['semestre']);			

			$this->Mod_usuarios->agregar_planificaciones($array);
			$this->Mod_usuarios->agregar_profesor($array2);

			if($_POST['profesor_dos']!='')
			{
				$array3=array('codigo_asignatura' => $_POST['ramo'],
				'rut_profesor'=> $_POST['profesor_dos'],
				'fecha'=>$año,
				'semestre'=>$_POST['semestre']);
				$this->Mod_usuarios->agregar_profesor($array3);
			}
			redirect(base_url() .'index.php/administrador/malla_actual'); 		
  			
  			
		}
	

  
		
	}
