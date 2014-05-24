<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Controller_user extends CI_Controller {

	public function logintwitter()
	{
		die("Sorry twitter :'( ");
		// En este caso que el usuario no puede haceer logout.. NO LO HAREMOS
		// Validamo si esta previamente logeado
		// if(isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id']))
		// {
		// 	redirect('soon');
		// }
		// Lo registraremos y logearemos
		define('CONSUMER_KEY', '2WIHSysoGl6GdKMn1Vb23Q');
		define('CONSUMER_SECRET', 'k2WGCikCC6qK1zLZSGk2gACM5kcaXAtXrQO3jo6Fzp4');
		require_once(APPPATH.'libraries/twitteroauth/twitteroauth.php');
		$_CALLBACK = current_url();

		// USUARIO LOGIN TWITTER
		/*Si el usuario no ha aceptado la aplicacion, y no ha iniciado session*/
					if(!isset($_REQUEST['oauth_token']) || $_SESSION['oauth_token'] !== $_REQUEST['oauth_token'])
					{
						/* Build TwitterOAuth object with client credentials. */
						$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET); 
						/* Get temporary credentials. */
						$request_token = $connection->getRequestToken($_CALLBACK);
						/* Save temporary credentials to session. */
						$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
						$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
						
						if($connection->http_code!=200)
						{
							show_error('connection http_code fail twiiter');
						}
						
						$url = $connection->getAuthorizeURL($token);
						header('location: '.$url); 
						exit;	
					}
		// EL USUARIO ACEPTO LA APLICACION.
					/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
					$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
					
					/* Request access tokens from twitter */
					$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
					
					/* Save the access tokens. Normally these would be saved in a database for future use. */
					$_SESSION['access_token'] = $access_token;
					
					/* Remove no longer needed request tokens */
					unset($_SESSION['oauth_token']);
					unset($_SESSION['oauth_token_secret']);
					
					if($connection->http_code!=200)
					{
						show_error('connection callback http_code fail twiiter');
					}	
	    // Creamos el ultimo objeto con el acces_token para requerir los datos de el user
					$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
					
					// Obtenemos la informacion de el usuario // OJO QUE LO OBTENDREMOS COMO UN OBJETO ! 
					$info1 = $connection->get('account/verify_credentials');
					
					$cod2_tw = $info1->id;
					$name_tw = $info1->name;
					$url_pic = $info1->profile_image_url_https; // 48x48
					$email_tw = ''; // FUCK TWIITER!

		// Verificaremos si el usuario esta registrado.
					$this->db->where('id2',$cod2_tw);
					$query = $this->db->get('user');
					$users = $query->result_array();

					if($query->num_rows==0)
					{
						// USER NO REGISTER.
						// Ya obtuvimos los datos.
						//Subimos la pic de el usuario de facebook al servidor de nomkey (images/user/)

						$arr = explode('.',$url_pic);
						$name_file = md5(time()).'.'.array_pop($arr);
						
						if(!$get = file_get_contents($url_pic))
						{
							show_error('error get pic user');
						}
						if(!file_put_contents('./images/user/'.$name_file,$get))
						{
							show_error('error put puc user');	
						}
						// Registramos al usuario con los datos de twitter
						$now = date("Y-m-d H:i:s");
						$data = array('id2'=>$cod2_tw,'name'=>$name_tw,'email'=>$email_tw,'photo'=>$name_file,'register'=>'twitter','registered'=>$now);
						$this->db->insert('user',$data);
						// Cargamos el mensaje de bienvenida dependiendo de el pais - idioma
						// SET LANGUAGE
						$this->lang->load('socialnetwork');
						$message_welcome = lang('twitter_welcome');
						
						// Le mandamos el mensaje de bienvenida a su estado de twiiter
						$connection->post('statuses/update',array('status' => $message_welcome));

						// OBTENEMOS LOS DATOS PARA EL LOGEO
						$this->db->where('id2',$cod2_tw);
						$query = $this->db->get('user');
						$users = $query->result_array();


					} // Terminamos con el registro del usuario

					// LOGIN 
					$_SESSION['user']['id'] = $users[0]['id'];
					$_SESSION['user']['name'] = $users[0]['name'];

					// Redireccionamos a soon
					redirect('email');
	}
	public function loginfacebook()
	{
		// En este caso que el usuario no puede haceer logout.. NO LO HAREMOS
		// Validamo si esta previamente logeado
		// if(isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id']))
		// {
		// 	redirect('soon');
		// }
		require_once(APPPATH.'libraries/facebook/facebook.php');
		define('_APPID','397789973654566');
		define('_SECRET','e397f511bd8c5a3d74cebb7a6f1cc93d');
		define('_COOKIE',true);

		$_PERMISSIONS_APP_FACEBOOK = 'email, publish_stream, user_photos'; // Permisos de facebook

		// USUARIO LOGIN FACEBOOK //
					/*Si el usuario no ha aceptado la aplicacion, y no ha iniciado session*/
					 $facebook = new Facebook(array('appId'=>_APPID,'secret'=>_SECRET,'cookie'=>_COOKIE));
					 if(!$uid=$facebook->getUser())
					 {
					 	$url_login = $facebook->getLoginUrl(array('scope'=>$_PERMISSIONS_APP_FACEBOOK));
					 	redirect($url_login);
					 	
					 }
					 // Desde ahora el usuario inicio session en facebook y acepto app
					 // Verificar si el usuario esta registrado
					
					 $this->db->where('id2',$uid);
					 $query = $this->db->get('user');
					 $users = $query->result_array();

					 if($query->num_rows==0)
					 {
					 	// Usuario no esta registrado
					 	// Obtenemos los datos del usuario en facebook por API
					 	$sql = 'select uid,name,pic_big ,email from user where uid = '.$uid.'';
					 	$params = array(
							'method'=>'fql.query',
							'query'=>$sql
							);
					 	$data = $facebook->api($params);


					 	$cod2_fb = (string) $data[0]['uid'];
						$name_fb = $data[0]['name'];
						$url_pic = $data[0]['pic_big']; // max 50x50
						$email_fb = $data[0]['email'];
						
						//Subimos la pic de el usuario de facebook al servidor de nomkey (images/user/)
					 	$arr = explode('.',$url_pic);
						$name_file = md5(time()).'.'.array_pop($arr);
						
						if(!$get = file_get_contents($url_pic))
						{
							show_error('error get pic user facebook');
						}
						if(!file_put_contents('./images/user/'.$name_file,$get))
						{
							show_error('error put puc user facebook');	
						}

						// Registramos al usuario con los datos de facebook
						$now = date("Y-m-d H:i:s");
						$data = array('id2'=>$cod2_fb,'name'=>$name_fb,'photo'=>$name_file,'email'=>$email_fb,'register'=>'facebook','registered'=>$now);
						$this->db->insert('user',$data);
						// obtenemos los permisos para ver si podemos postear en su muro

						$sql = 'select publish_stream from permissions where uid = '.$uid.'';
						$params = array(
						'method'=>'fql.query',
						'query'=>$sql
						);
						$data = $facebook->api($params);
						if($data[0]['publish_stream']==1)
						{// Tenemos los permisos para postear en el muro de el usuario

							$this->lang->load('socialnetwork');
							$message_welcome = lang('facebook_welcome');
							try{
								$facebook->api('/me/feed', 'POST',
					                                    array(
														  'link'=>'http://www.belieber.be',
					                                      'message' => $message_welcome
					                                 ));
							}catch(FacebookApiException $e)
							{
							  show_error('Problem post facebook user welcome');
							}
						}

						//Obtenemos los datos para el logeo de usurio
						 $this->db->where('id2',$uid);
						 $query = $this->db->get('user');
						 $users = $query->result_array();

					 }// Termino del registro de usuario
					// LOGIN 
					$_SESSION['user']['id'] = $users[0]['id'];
					$_SESSION['user']['name'] = $users[0]['name'];

					// Redireccionamos a soon
					redirect('email');
	}
	public function feed()
	{
		// Validamos si el usuario esta logeado
		if(!isset($_SESSION['user']['id']) or empty($_SESSION['user']['id']))
		{
			redirect('home');
		}

		// creando una publicaciones
		if($this->input->post())
		{
			$this->_create_publication();
		}
		// creamos un token
		$tk = md5(time().'belieber');
		$_SESSION['tk'][$tk]=$tk;
		// Obtenemos mis datos para la foto al publicar
		$this->db->where('id',$_SESSION['user']['id']);
		$q = $this->db->get('user');
		$users = $q->result_array();
		$me = $users[0];
		// Obtenemos a todos los grupos que sigo
		$q=$this->db->query('select g.id as id_group,g.name as group_name ,f.adm as adm,f.cdc as cdc
		from follow f
		inner join groupp g
		on f.id_group = g.id
		where f.id_user = '.$me['id'].'');
		$groups = array(); // array para guardar a todos los grupos
		$groups = $q->result_array();
		// verificaremos si soy admin o cdc de algunos de esos grupos
		$member_groups = array(); // array para guardar a todos los grupos que sigo
		if(!empty($groups)) // Si sigo a algun grupo entonces verificaremos
		{
			foreach ($groups as $group)
			{
				if($group['adm']==1 or $group['cdc']==1)
				{
					$member_groups[]=$group;
				}
			}
		}
		// Obtenemos las publicaciones
		$q=$this->db->query('select u.id as id_user,u.name as user_name,u.photo as user_photo,p.id as id,p.message as message,p.photo as message_photo, IFNULL(lk.likes,0) as likes, IFNULL(cm.comments,0) as comments,g.id as id_group,g.name as group_name
		from publication p
		left join 
		(select id_publication, count(*) as likes from likee group by id_publication) lk 
		on p.id = lk.id_publication
		left join 
		(select id_publication, count(*) as comments from comment group by id_publication) cm
		on p.id = cm.id_publication
		inner join user u
		on p.id_user=u.id
		inner join groupp g
		on p.id_group = g.id
		where p.id in (select id from publication where id_group in(select id_group from follow where id_user='.$me['id'].')) order by id desc limit 0,40');
		$publications=array();
		$publications = $q->result_array();
		// OBTENEMOS TODOS LOS me gusta que he hecho
		$this->db->where('id_user',$me['id']);
		$q=$this->db->get('likee');
		$likes=array();
		$likes = $q->result_array();

		//VERIFICAREMOS SI HE HECHO ME GUSTA EN LAS PUBLICACIONES
		if(!empty($publications)) // Si hay publicaciiones, verificamos
		{
			for($i=0;$i<count($publications);$i++) // ciclo de cada publicaciones
			{
				if(!empty($likes)) // si hice algun like alguna vez
				{
					for($x=0;$x<count($likes);$x++)
					{
						if($publications[$i]['id']==$likes[$x]['id_publication'])
						{
							$publications[$i]['like']='1';
							break;
						}else{
							$publications[$i]['like']='0';
						}
					}

				}else{ // si nunca hice un like en la vida
					$publications[$i]['like']='0';
				}
			}
		}
		// Guardamos los datos para la vista 
		$data=array(
			'me'=>$me,
			'groups' => $groups,
			'member_groups'=>$member_groups,
			'publications'=>$publications,
			'tk'=>$tk
		);

		$this->load->view('view_feed',$data);
	}
	private function _create_publication()
	{
		/*validar tk*/
		$tk = $this->input->post('publish_tk');
		if(!isset($_SESSION['tk']) or empty($tk) or !array_key_exists($tk, $_SESSION['tk']))
		{
			die("error tk :'( , try again :)");	
		}
		$text = $this->input->post('publish_text');
		$id_group = $this->input->post('publish_group');
		$now = date("Y-m-d H:i:s");

		$this->form_validation->set_rules('publish_text', 'texto', 'trim|required');
		$this->form_validation->set_message('required', "You need to write something :')");

		$validation_text = false;
		$post_photo = false;
		$validation_photo = false;

		// Validamos el texto
		if($this->form_validation->run() == TRUE)
		{
			$validation_text = true;
		}   
		
		//chequeamos si viene photo
		if(!empty($_FILES['publish_photo']['name']) && $validation_text)
		{
				$post_photo=true;
				// seteamos la subida de foto
				$config['upload_path'] = './images/sp/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']	= '1024';
				$config['max_width'] = '1024';
				$config['max_height'] = '1024';
			    $ext = strtolower(end(explode('.',$_FILES['publish_photo']['name'])));
				$config['file_name'] = time().'.'.$ext;
				$this->upload->initialize($config);

				// validamos la foto
				if($this->upload->do_upload('publish_photo')==TRUE)
				{
					$validation_photo=true;
				}
		}



		if($validation_text && (!$post_photo or ($post_photo && $validation_photo)))
		{
			$id_user = $_SESSION['user']['id'];

			$data = array(
				'id_user'=>$id_user,
				'id_group'=>$id_group,
				'message'=>$text,
				'date'=>$now
			);
			if($post_photo && $validation_photo)
			{
				$data['photo']=$config['file_name'];	
			}
			
			$this->db->insert('publication',$data);

			// POST FACEBOOK IF SYNC
			$this->_facebook_publication($this->db->insert_id());


			if(!empty($_SERVER['HTTP_REFERER']))
			{
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
	}
	public function profile()
	{
		// Validamos si el usuario esta logeado
		if(!isset($_SESSION['user']['id']) or empty($_SESSION['user']['id']))
		{
			redirect('home');
		}

		// publicacion nueva
		if($this->input->post())
		{
			$this->_create_publication();
		}
		// creamos un token
		$tk = md5(time().'belieber');
		$_SESSION['tk'][$tk]=$tk;
		// Obtenemos mis datos (para poner mi foto al publicar)
		$this->db->where('id',$_SESSION['user']['id']);
		$q=$this->db->get('user');
		$users=$q->result_array();
		$yo=$users[0];
		// Obtenemos el id del usuario buscado
		$id_profile = $this->uri->segment(3);
		// Verificamos si el usuario existe y obtenemos los datos
		$this->db->where('id',$id_profile);
		$q = $this->db->get('user');
		if($q->num_rows()<1)
		{
			redirect('home');
		}
		$users = $q->result_array();
		$me = $users[0];
		// Verificamos si soy yo mismo
		if($me['id']==$_SESSION['user']['id'])
		{
			$myself = true;
		}else{
			$myself = false;
		}
		// Obtenemos a todos los grupos que el usuario sigue
		$q=$this->db->query('select g.id as id_group,g.name as group_name,g.photo as photo,g.desc as group_desc,f.adm as adm,f.cdc as cdc
		from follow f
		inner join groupp g
		on f.id_group = g.id
		where f.id_user = '.$me['id'].'');
		$groups = array(); // array para guardar a todos los grupos
		$groups = $q->result_array();
		// verificaremos si el usuario es admin o cdc de algunos de esos grupos
		$member_groups = array(); // array para guardar a todos los grupos que sigo
		if(!empty($groups)) // Si sigo a algun grupo entonces verificaremos
		{
			foreach ($groups as $group)
			{
				if($group['adm']==1 or $group['cdc']==1)
				{
					$member_groups[]=$group;
				}
			}
		}
		// Obtenemos las publicaciones del usuario
		$q=$this->db->query('select u.id as id_user,u.name as user_name,u.photo as user_photo,p.id as id,p.message as message,p.photo as message_photo, IFNULL(lk.likes,0) as likes, IFNULL(cm.comments,0) as comments,g.id as id_group,g.name as group_name
		from publication p
		left join 
		(select id_publication, count(*) as likes from likee group by id_publication) lk 
		on p.id = lk.id_publication
		left join 
		(select id_publication, count(*) as comments from comment group by id_publication) cm
		on p.id = cm.id_publication
		inner join user u
		on p.id_user=u.id
		inner join groupp g
		on p.id_group = g.id
		where p.id_user='.$me['id'].' order by id desc limit 0,40');
		$publications=array();
		$publications = $q->result_array();
		// OBTENEMOS TODOS LOS me gusta que YO he hecho
		$this->db->where('id_user',$_SESSION['user']['id']);
		$q=$this->db->get('likee');
		$likes=array();
		$likes = $q->result_array();
		//VERIFICAREMOS SI HE HECHO ME GUSTA EN SUS PUBLICACIONES
		if(!empty($publications)) // Si hay publicaciiones, verificamos
		{
			for($i=0;$i<count($publications);$i++) // ciclo de cada publicaciones
			{
				if(!empty($likes)) // si hice algun like alguna vez
				{
					for($x=0;$x<count($likes);$x++)
					{
						if($publications[$i]['id']==$likes[$x]['id_publication'])
						{
							$publications[$i]['like']='1';
							break;
						}else{
							$publications[$i]['like']='0';
						}
					}
				}else{ // si nunca hice un like en la vida
					$publications[$i]['like']='0';
				}
			}
		}
		// Guardamos los datos para la vista
		$data=array(
			'me'=>$me,
			'yo'=>$yo,
			'myself'=>$myself,
			'groups' => $groups,
			'member_groups'=>$member_groups,
			'publications'=>$publications,
			'tk'=>$tk
		);

		$this->load->view('view_profile',$data);
	}
	private function _facebook_publication($last_p)
	{
		// Obtenemos mis datos
		$id_user = $_SESSION['user']['id'];
		$this->db->where('id',$id_user);
		$q = $this->db->get('user');
		$users = $q->result_array();
		$me = $users[0];

		if(!empty($me['syncf']))
		{
			// Obtenemos la reciente publicacion
			$this->db->where('id',$last_p);
			$q=$this->db->get('publication');
			$publications = $q->result_array();
			$publication=$publications[0];

			// SET KEYS 
			require_once(APPPATH.'libraries/facebook/facebook.php');
			define('_APPID','397789973654566');
			define('_SECRET','e397f511bd8c5a3d74cebb7a6f1cc93d');
			define('_COOKIE',true);
			$_PERMISSIONS_APP_FACEBOOK = 'email, publish_stream, user_photos'; // Permisos de facebook

			$facebook = new Facebook(array('appId'=>_APPID,'secret'=>_SECRET,'cookie'=>_COOKIE));



			$sql = 'select user_photos,publish_stream from permissions where uid = '.$me['id2'].'';
			$params = array(
			'method'=>'fql.query',
			'query'=>$sql
			);
			$data = $facebook->api($params);


			// Si no tiene los permisos, lo llevaremos a que los acepte.
			if(!empty($publication['photo']) && $data[0]['user_photos']==0)
			{
				$url_login = $facebook->getLoginUrl(array('scope'=>$_PERMISSIONS_APP_FACEBOOK));
				redirect($url_login);	

			}elseif(!empty($publication['message']) && $data[0]['publish_stream']==0){

				$url_login = $facebook->getLoginUrl(array('scope'=>$_PERMISSIONS_APP_FACEBOOK));
				redirect($url_login);	
			}
			
			// Subio una fotoooo
			if(!empty($publication['photo']))
			{
				// Obtenemos los albunes de fotos de el.
				$albums = $facebook->api('me/albums');
				$exist_album = FALSE;

				//Vemos si ya existe un album belieber.be para obtener el id 
				foreach($albums['data'] as $album)
				{ // Recoreremos todos los albunes
					if($album['name']=='Belieber.be')
					{
						$exist_album = TRUE;
						$id_album_be=$album['id'];
					}
				}

				// Si el album no existe lo creamos :)
				if($exist_album==FALSE)
				{
					$data = $facebook->api('me/albums','POST',array(
															'name'=>'Belieber.be',
															'message'=>'www.belieber.be'));
					$id_album_be  = $data['id'];
				}

				//Le avisamos a facebook que subiremos una foto 
	 			$facebook->setFileUploadSupport(true);

	 			$photo_details = array(
				            'message'=> $publication['message']
		    	);

		    	$file = './images/sp/'.$publication['photo'].'';
	        	$photo_details['image'] = '@' . realpath($file);

	        	//Luego de esto enviamos a $facebook los datos de nuestra imagen
        		$upload_photo = $facebook->api('/'.$id_album_be.'/photos', 'post', $photo_details);
			}else{
				// es solo un mensaje sin foto
				try{
					$facebook->api('/me/feed', 'POST',
					                                  array(
					                                  'message' => $publication['message']
					                                  ));
				}catch(FacebookApiException $e)
				{
				  show_error('Problem post facebook user publication');
				}
			}
			// se redirigira en el return ( create publication )
		}
	}
	public function logout()
	{
		$_SESSION = array();
		redirect('home');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */