<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Controller_home extends CI_Controller {

	public function index()
	{
		// Validar si el usuario ya esta logeado
		if(isset($_SESSION['user']['id']) and !empty($_SESSION['user']['id']))
		{
			redirect('loginfacebook');
		}

		// Cargar textos
		$this->lang->load('home');
		$this->lang->load('socialnetwork');
		$this->load->view('view_index.php');
	}
	public function soon()
	{
		if(!isset($_SESSION['user']['id']) or empty($_SESSION['user']['id']))
		{
			redirect('home');
		}

		// Obtener al usuario segun su SESSION
		$this->db->where('id',$_SESSION['user']['id']);
		$q = $this->db->get('user');
		$users = $q->result_array();

		// cargamos textos
		$this->lang->load('soon');
		
		$data = array('user_name'=>$users[0]['name'],'user_photo'=>$users[0]['photo']);

		$this->load->view('view_soon.php',$data);
	}
	public function email()
	{
		// Validamos si es un usuario valido
		if(!isset($_SESSION['user']['id']) or empty($_SESSION['user']['id']))
		{
			redirect('home');
		}

		// Obtenemos el id del usuario
		$user_id = $_SESSION['user']['id'];
		// STEP 1
		// Validar si el usuario tiene un email (Estos datos tambien son para la vista)
		$this->db->where('id',$user_id);
		$q=$this->db->get('user');
		$users=$q->result_array();
		if(!empty($users[0]['email']))
		{
			// Si tiene un email
			redirect('feed');
		}
		//------------------------------------------------------
		// STEP 2
		// El usuario no tiene un email, por lo tanto le mostraremos un formulario y lo reciviremos
		// Cargar idioma
		$this->lang->load('email');

		// Validamos si estamos reciviendo el email desde el formulario
		if($this->input->post())
		{	
			$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|callback_email_check');
			$this->form_validation->set_message('required',lang('email_error_required'));
			$this->form_validation->set_message('valid_email', lang('email_error_validemail'));
			$this->form_validation->set_message('email_check', lang('email_error_emailcheck'));

			if($this->form_validation->run()!=FALSE)
			{
				//Guardamos el email
				$data=array('email'=>$this->input->post('email'));
				$this->db->where('id',$user_id);
				$this->db->update('user',$data);
				redirect('feed');
			}
		}
		// La vista del formulario
		$data=array('user_name'=>$users[0]['name'],'user_photo'=>$users[0]['photo']);
		$this->load->view('view_email',$data);
	}
	public function email_check($email)
	{
		 $this->db->where('email',$email);
		 $q = $this->db->get('user');
		if($q->num_rows>0)
		{
			return FALSE;
		}else{
			return TRUE;
		}
	}
	public function justin()
	{
			require_once(APPPATH.'libraries/facebook/facebook.php');
			define('_APPID','397789973654566');
			define('_SECRET','e397f511bd8c5a3d74cebb7a6f1cc93d');
			define('_COOKIE',true);

			$_PERMISSIONS_APP_FACEBOOK = 'email, publish_stream, user_photos'; // Permisos de facebook

			 $facebook = new Facebook(array('appId'=>_APPID,'secret'=>_SECRET,'cookie'=>_COOKIE));
			 if(!$uid=$facebook->getUser())
			 {
			 	$url_login = $facebook->getLoginUrl(array('scope'=>$_PERMISSIONS_APP_FACEBOOK));
			 	redirect($url_login);
			 }


			 $token_url =    "https://graph.facebook.com/oauth/access_token?" .
                "client_id=" . _APPID .
                "&client_secret=" . _SECRET .
                "&grant_type=client_credentials";
			$resp = file_get_contents($token_url);
			$arr = explode('=', $resp);
			$app_access_token = $arr[1];
			echo $app_access_token;

			try {
			 	
				 $response = $facebook->api( '/100006510391274/notifications/', 'POST', array(

            	'template' => 'You have received a new message.',

            	'href' => 'www.belieber.be/es/justin',

            	'access_token' => $app_access_token
        	) );  

			var_dump($response);
			 	
			 } catch (Exception $e){
			     echo $e;
			 }
			 die('lol');

		//////////////////////////////////////////////////
		// $img = './images/base_justin_say.png';
		
		// $im = imagecreatefrompng($img);
		// $purpura = imagecolorallocate($im, 123, 32, 171);
		// $fuente = './css/fonts/segoepr.ttf';


		// $texto1 = 'Jose';
		// $texto2 = 'Nunca digas nuncaaaaa!';

		// $cajaTexto1 = imagettfbbox(21, 0, $fuente, $texto1);
		// $xCentrado1 = (imagesx($im) - $cajaTexto1[2]) / 2;
		// $yCentrado1 = (imagesy($im) - $cajaTexto1[3]) / 2;

		// $cajaTexto2 = imagettfbbox(16, 0, $fuente, $texto2);
		// $xCentrado2 = (imagesx($im) - $cajaTexto2[2]) / 2;
		// $yCentrado2 = (imagesy($im) - $cajaTexto2[3]) / 2;


		// imagettftext($im, 21, 0, $xCentrado1, 360, $purpura, $fuente, $texto1);
		// imagettftext($im, 16, 0, $xCentrado2, 400, $purpura, $fuente, $texto2);

		// header('Content-Type: image/png');
		// imagepng($im);
		// imagedestroy($im);
	}
	public function album()
	{
		die('wait');
	// 	require_once(APPPATH.'libraries/facebook/facebook.php');
	// 	define('_APPID','397789973654566');
	// 	define('_SECRET','e397f511bd8c5a3d74cebb7a6f1cc93d');
	// 	define('_COOKIE',true);

		
	// 	$_PERMISSIONS_APP_FACEBOOK = 'email, publish_stream, user_photos'; // Permisos de facebook

	// 	// USUARIO LOGIN FACEBOOK //
	// 	/*Si el usuario no ha aceptado la aplicacion, y no ha iniciado session*/
	// 	 $facebook = new Facebook(array('appId'=>_APPID,'secret'=>_SECRET,'cookie'=>_COOKIE));
	// 	 if(!$uid=$facebook->getUser())
	// 	 {
	// 	 	$url_login = $facebook->getLoginUrl(array('scope'=>$_PERMISSIONS_APP_FACEBOOK));
	// 	 	redirect($url_login);
	// 	 }

	// 					// Vemos si tenemos permisos de fotos
	// 	 				$sql = 'select user_photos from permissions where uid = '.$uid.'';
	// 					$params = array(
	// 					'method'=>'fql.query',
	// 					'query'=>$sql
	// 					);
	// 					$data = $facebook->api($params);

	// 					// Si no tiene los permisos, lo llevaremos a que los acepte.
	// 					if($data[0]['user_photos']==0)
	// 					{
	// 						$url_login = $facebook->getLoginUrl(array('scope'=>$_PERMISSIONS_APP_FACEBOOK));
	// 						redirect($url_login);
							
	// 					}

	// 					// Obtenemos los albunes de fotos de el.
	// 					$albums = $facebook->api('me/albums');
	// 					$exist_album = FALSE;
	// 				    //Vemos si ya existe un album belieber.be para obtener el id 
	// 					foreach($albums['data'] as $album)
	// 					{ // Recoreremos todos los albunes
	// 						if($album['name']=='Belieber.be')
	// 						{
	// 							$exist_album = TRUE;
	// 							$id_album_be=$album['id'];
	// 						}
	// 					}
	// 					// Si el album no existe lo creamos :)
	// 					if($exist_album==FALSE)
	// 					{
	// 						$data = $facebook->api('me/albums','POST',array(
	// 																'name'=>'Belieber.be',
	// 																'message'=>'www.belieber.be'));
	// 						$id_album_be  = $data['id'];

	// 					}
	// 					// Le avisamos a facebook que subiremos una foto 
	// 					$facebook->setFileUploadSupport(true);

	// 					$photo_details = array(
	// 			            'message'=> 'Photo message'
	// 			        );
	// 			        $file = './images/base_justin_say.png';
	// 			        $photo_details['image'] = '@' . realpath($file);

 // 						//Luego de esto enviamos a $facebook los datos de nuestra imagen
 //       					$upload_photo = $facebook->api('/'.$id_album_be.'/photos', 'post', $photo_details);
 //       					var_dump($upload_photo);
	// 					// Datos de la foto
	//
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */