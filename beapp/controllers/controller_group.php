<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Controller_group extends CI_Controller {
	public function group()
	{
		// Validamos si el usuario esta logeado
		if(!isset($_SESSION['user']['id']) or empty($_SESSION['user']['id']))
		{
			redirect('home');
		}

		if($this->input->post())
		{
			// VALIDAR TK
			$this->_follow_unfollow();
		}

		$id_user = $_SESSION['user']['id'];
		// creamos un token
		$tk = md5(time().'belieber');
		$_SESSION['tk'][$tk]=$tk;
		// Obtenemos mis datos (para poner mi foto al publicar)
		$this->db->where('id',$id_user);
		$q=$this->db->get('user');
		$users=$q->result_array();
		$yo=$users[0];
		// Obtenemos el id del grupo buscado
		$id_group = $this->uri->segment(3);
		// Obtenemos los datos del grupo en vista
		$this->db->where('id',$id_group);
		$q = $this->db->get('groupp');
		// verificamos si existe
		if($q->num_rows()<1)
		{
			redirect('home');
		}
		$groups = $q->result_array();
		$me = $groups[0];
		// Vemos si yo soy seguidor (para el boton follow) y si soy admin (disabled)
		$this->db->where('id_user',$id_user);
		$this->db->where('id_group',$id_group);
		$q=$this->db->get('follow');
		$follow_group=array();
		if($q->num_rows()>0)
		{
			$follow_group=$q->result_array();
			$follow_group=$follow_group[0];
		}
		// Obtenemos a todos los usuarios que son miembro
		$q=$this->db->query('select u.id as id_user,u.name as user_name,u.photo as photo
		from follow f 
		inner join user u
		on f.id_user = u.id
		where f.id_group='.$id_group.' and (f.adm=1 or f.cdc=1)');
		$members_group = array();
		$members_group = $q->result_array();
		// Obtenemos el total de seguidores
		$this->db->where('id_group',$id_group);
		$follows_group=$this->db->count_all_results('follow');
		// Obtenemos las publicaciones del grupo
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
		where p.id_group='.$id_group.' order by id desc limit 0,40');
		$publications = array();
		$publications = $q->result_array();
		// OBTENEMOS TODOS LOS me gusta que YO he hecho
		$this->db->where('id_user',$id_user);
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
		// datos para la vista
		$data=array(
			'me'=>$me,
			'yo'=>$yo,
			'follow_group'=>$follow_group,
			'members_group'=>$members_group,
			'follows_group'=>$follows_group,
			'publications'=>$publications,
			'tk'=>$tk
		);
		// Cargar vista
		$this->load->view('view_group',$data);
	}
	private function _follow_unfollow()
	{
		$id_user = $_SESSION['user']['id'];
		$id_group=$this->input->post('groupid');

		// validar si el grupo existe
		$this->db->where('id',$id_group);
		$q=$this->db->get('groupp');
		if($q->num_rows()>0)
		{
			//obtener si lo sigo o no lo sigo
			$this->db->where('id_group',$id_group);
			$this->db->where('id_user',$id_user);
			$q=$this->db->get('follow');
			if($q->num_rows()>0)
			{
				// lo sigo y  lo dejare de seguir
				$this->db->where('id_group',$id_group);
				$this->db->where('id_user',$id_user);
				$this->db->delete('follow');
			}else{
				// no lo sigo y lo seguire
				$now = date("Y-m-d H:i:s");
				$data = array('id_user'=>$id_user,'id_group'=>$id_group,'adm'=>'0','cdc'=>'0','date'=>$now);
				$this->db->insert('follow',$data);
			}

		}
	}
	public function groups()
	{
		// Validamos si el usuario esta logeado
		if(!isset($_SESSION['user']['id']) or empty($_SESSION['user']['id']))
		{
			redirect('home');
		}
		// verificamos si se desea hacer una accion
		$error_create=false;
		$error_delete=false;
		$error_save=false;
		$error_delete_member=false;

		if($this->input->post())
		{
			$tk = $this->input->post('tk');
			if(!isset($_SESSION['tk']) or empty($tk) or !array_key_exists($tk, $_SESSION['tk']))
			{
				die("error tk :'( , try again :)");	
			}
			// VERIFICAR EL TOKEN $tk
			// aqui
			 $action = $this->uri->segment(3);
			 if($action=='create'){
			 	$error_create = $this->_create_group();
			 }elseif ($action == 'delete'){
			 	$error_delete= $this->_delete_group();
			 }elseif ($action == 'save'){
			 	$error_save = $this->_save_group();
			 }elseif($action == 'invite'){
			 	$error_invite = $this->_invite_member();
			 }elseif($action == 'delete_member'){
			 	$error_delete_member = $this->_delete_member();
			 }
		}
		// Creamos TK 
		$tk = md5(time().'belieber');
		$_SESSION['tk'][$tk]=$tk;
		//obtenemos mis datos
		$id_user=$_SESSION['user']['id'];
		$this->db->where('id',$id_user);
		$q=$this->db->get('user');
		$users=$q->result_array();
		$me=$users[0];
		// Obtener todos los grupos del sistema
		$q=$this->db->query('select g.id as id_group,g.name as group_name,g.desc as group_desc,g.photo as group_photo,f1.follows as follows, f2.members as members 
		from groupp g
		left join
		(select id_group,count(*) as follows from follow group by id_group) f1
		on g.id=f1.id_group
		left join
		(select id_group,count(*) as members from follow where adm=1 or cdc=1 group by id_group) f2
		on g.id=f2.id_group');
		$groups = array();
		$groups = $q->result_array();
		// Obtener los grupos los cuales soy cdc o adm
		$q=$this->db->query('select g.id as id_group,g.name as group_name,g.desc as group_desc,g.photo as group_photo,f.id_user as id_user,f.adm as adm, f.cdc as cdc
		from follow f
		inner join groupp g 
		on f.id_group = g.id
		where f.id_user = '.$id_user.' and (f.adm=1 or f.cdc=1);');
		$member_groups = array();
		$member_groups = $q->result_array();
		// Obtener a todos los miembros del grupo siempre y cuando sea miembro de un grupo
		if(!empty($member_groups))
		{
			for($i=0;$i<count($member_groups);$i++)
			{
				$q=$this->db->query('select u.id as id,u.name as user_name,u.photo as user_photo,f.adm as adm, f.cdc as cdc
				from follow f
				inner join user u
				on f.id_user = u.id
				where f.id_group='.$member_groups[$i]["id_group"].' 
				and (f.adm=1 or f.cdc=1)');
				$members = array();
				$members=$q->result_array();
				$member_groups[$i]['members']=$members;
			}
		}
		// Obtenemos los datos para ver si soy creador de algun grupo
		$this->db->where('id_user',$id_user);
		$group_owner=$this->db->count_all_results('groupp');
		
		// Data a pasar a la vista
		$data=array(
			'error_create'=>$error_create,
			'error_delete'=>$error_delete,
			'error_save'=>$error_save,
			'error_delete_member'=>$error_delete_member,
			'me'=>$me,
			'groups'=>$groups,
			'member_groups'=>$member_groups,
			'group_owner'=>$group_owner,
			'tk'=>$tk
		);
		$this->load->view('view_groups',$data);
	}
	private function _create_group()
	{
		$id_user = $_SESSION['user']['id'];
		$group_name = $this->input->post('group_name');
		$group_description = $this->input->post('group_description');
		$now = date("Y-m-d H:i:s");
		// set rules
		$this->form_validation->set_rules('group_name', 'Name', 'trim|required');
		$this->form_validation->set_rules('group_description', 'Description', 'trim|required');
		// set messages
		$this->form_validation->set_message('required', 'You must write the group name');
		$this->form_validation->set_message('required', 'You must write the description of the group');

		// Validamos el nombre y descripcion
		if($this->form_validation->run() == FALSE)
		{
			return $error_create=true;
		}else{

				// seteamos la subida de foto
				$config['upload_path'] = './images/group/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']	= '1024';
				$config['max_width'] = '1024';
				$config['max_height'] = '1024';
			    $ext = strtolower(end(explode('.',$_FILES['group_photo']['name'])));
				$config['file_name'] = time().'.'.$ext;
				$this->upload->initialize($config);
				// validamos la foto
				if($this->upload->do_upload('group_photo')==FALSE)
				{
					return $error_create=true;
				}else{

					$data=array(
						'id_user'=>$id_user,
						'name'=>$group_name,
						'desc'=>$group_description,
						'photo'=>$config['file_name'],
						'date'=>$now,
					);
					// creamos el grupo
					$this->db->insert('groupp',$data);
					// now you follow the group : )
					$data = array(
						'id_user'=>$id_user,
						'id_group'=>$this->db->insert_id(),
						'date'=>$now,
						'adm'=>'1',
						'cdc'=>0
					);
					$this->db->insert('follow',$data);
					redirect('group');
				}
		}
	}
	private function _delete_group()
	{
		$id_user = $_SESSION['user']['id'];
		$id_group = $this->input->post('groupid');
		// verificamos si el usuario que deasea eliminarlo es un administrador de este
		$this->db->where('id_group',$id_group);
		$this->db->where('id_user',$id_user);
		$this->db->where('adm','1');
		$q=$this->db->get('follow');
		if($q->num_rows()>0 and $this->db->delete('groupp',array('id'=>$id_group)))
		{
			redirect('group');
		}else{
			return $error_delete=true;
		}
	}

	private function _save_group()
	{
		$id_group = $this->input->post('groupid');
		$id_user = $_SESSION['user']['id'];
		$id_users = $this->input->post('uid');
		$appointment_users = $this->input->post('appointment');

		// verificamos si el usuario que deasea eliminarlo es un administrador de este
		$this->db->where('id_group',$id_group);
		$this->db->where('id_user',$id_user);
		$this->db->where('adm','1');
		$q=$this->db->get('follow');

		if($q->num_rows()>0)
		{
			for($i=0;$i<count($id_users);$i++)
			{
				$id_user =  $id_users[$i];
				$appointment = $appointment_users[$i];
				$this->db->where('id_user',$id_user);
				$this->db->where('id_group',$id_group);
				if($appointment=='adm')
				{
					$data = array('adm'=>'1','cdc'=>'0');
				}else{
					$data = array('adm'=>'0','cdc'=>'1');
				}
				$this->db->update('follow',$data);
			}
			redirect('group');
		}else{
			return $error_save=true;
		}
	}
	private function _invite_member()
	{
		$id_group = $this->input->post('groupid');
		$_SESSION['invite']['id_group']=$id_group; // Solo para verificar si el usuario sigue el grupo
		$id_user = $_SESSION['user']['id'];
		$email_user = $this->input->post('email_user');

		$this->form_validation->set_rules('email_user', 'Email address', 'trim|required|valid_email|callback__exist_email|callback__invite_me|callback__follow_group');
		$this->form_validation->set_message('required', 'You must write the email address');
		$this->form_validation->set_message('valid_email', 'The Email address must be valid');
		$this->form_validation->set_message('_exist_email',"The Email address doesn't exist, your friend can look it on the top blue button");
		$this->form_validation->set_message('_invite_me',"You can't invite yourself");
		$this->form_validation->set_message('_follow_group',"The user who you invited must be follower of your group");

		if($this->form_validation->run() == TRUE)
		{
			$this->db->where('email',$email_user);
			$q=$this->db->get('user');
			$users = $q->result_array();
			$user = $users[0]['id'];
			$this->db->where('id_user',$user);
			$this->db->where('id_group',$id_group);
			$data=array('cdc'=>'1','adm'=>'0');
			$this->db->update('follow',$data);
			redirect('group');
		}
	}
	public function _exist_email($email_user)
	{
		$this->db->where('email',$email_user);
		$q=$this->db->get('user');
		if($q->num_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	public function _invite_me($email_user)
	{
		$this->db->where('email',$email_user);
		$q=$this->db->get('user');
		$users = $q->result_array();

		if($users[0]['id']==$_SESSION['user']['id'])
		{
			return false;
		}else{
			return true;
		}
		
	}
	public function _follow_group($email_user)
	{
		$q=$this->db->query('select id from follow where id_group ='.$_SESSION['invite']['id_group'].' and id_user = (select id from user where email = "'.$email_user.'")');
		//unset($_SESSION['invite']['id_group']);
		if($q->num_rows()>0)
		{
			return true;
		}else{
			return false;
		}
	}
	private function _delete_member()
	{
		$id_member = $this->input->post('uid');
		$id_group = $this->input->post('groupid');
		$id_user = $_SESSION['user']['id'];

		// verificamos si el usuario que deasea eliminarlo es un administrador de este
		$this->db->where('id_group',$id_group);
		$this->db->where('id_user',$id_user);
		$this->db->where('adm','1');
		$q=$this->db->get('follow');


		if($q->num_rows()>0)
		{
			if($id_member!=$id_user)
			{
				$this->db->where('id_user',$id_member);
				$this->db->where('id_group',$id_group);
				$data=array('adm'=>'0','cdc'=>'0');
				if($this->db->update('follow',$data))
				{
					redirect('group');
				}
			}
		}
		return $error_delete=true;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */