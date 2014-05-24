<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Controller_feed extends CI_Controller {
	
	public function comments()
	{
		
		// Validamos si el usuario esta logeado
		if(!isset($_SESSION['user']['id']) or empty($_SESSION['user']['id']))
		{
			die("error session :'( , try again :)");	
		}

		$tk =  $this->uri->segment(5);
		if(!isset($_SESSION['tk']) or empty($tk) or !array_key_exists($tk, $_SESSION['tk']))
		{
			die("error tk :'( , try again :)");	
		}
		// POST COMMENT AJAX
		if($this->input->post())
		{
			// obtenemos los datos
			 $id_publication = $this->uri->segment(4);
			 $comment = $this->input->post('comment');
			 $id_user = $_SESSION['user']['id'];
			 $now = date("Y-m-d H:i:s");

			 // creamos regla de validacion
			 $this->form_validation->set_rules('comment', 'comment', 'trim|required');

			 // validamos validacion
			 if ($this->form_validation->run() == FALSE)
			 {
			 	$resp = array('error'=>'1');
			 }else{
			 
			 // creamos el array con info para la data base
			  $data = array(
				'id_publication'=>$id_publication,
				'id_user'=>$id_user,
				'message'=>$comment,
				'date'=>$now
			  );
			 // insertamos los datos
			 $this->db->insert('comment',$data);
			 // obtener los datos del que los inserto
			 $q = $this->db->query('select u.id as id_user,u.name as user_name,u.photo as user_photo,c.message as comment from comment c 
			inner join user u
			on u.id = c.id_user
			where c.id = '.$this->db->insert_id());
			 $users=$q->result_array();
			 $user=$users[0];
			 $url_profile = site_url('profile/'.$id_user.'');
			 $user_photo = $user['user_photo'];
			 $user_name = $user['user_name'];
			 $comment = $user['comment'];
			 // preparamos anti xss del commment
			 $html = '<div class="modal-publish-container-comment">
					  	<img src="/images/user/'.$user_photo.'" alt="" class="img-rounded modal-publish-img-user-comment">
						<p class="modal-publish-comment-user"><span><a href="'.$url_profile.'">'.$user_name.'</a></span><br> '.htmlentities(utf8_decode($comment)).'</p>
						<div id="aux"></div>
					  </div>
					  <hr style="margin:10px 0 10px 0;" />';

			 $data = array('html'=>$html);
			 $resp = array('error'=>'0','data'=>$data);

			}
			echo json_encode($resp);
			die();
			
		}
		// GET COMMENT AJAX
		$id_publication = $this->uri->segment(4);

		$q=$this->db->query('select c.id_user as id_user,u.name as user_name,u.photo as user_photo,c.message as comment
		from comment c
		inner join user u
		on c.id_user = u.id
		where c.id_publication ='.$id_publication.' order by c.id desc');
		$comments = array();
		$comments = $q->result_array();

		if(!empty($comments))
		{
			foreach($comments as $comment)
			{
		?>
					<div class="modal-publish-container-comment">
						<img src="/images/user/<?php echo $comment['user_photo']?>" alt="" class="img-rounded modal-publish-img-user-comment">
						<p class="modal-publish-comment-user"><span><a href="<?php echo site_url('profile/'.$comment['id_user'].'');?>"><?php echo $comment['user_name']?></a></span><br> <?php echo htmlentities(utf8_decode($comment['comment']));?></p>
						<div id="aux"></div>
					</div>
					<hr style="margin:10px 0 10px 0;" />
		<?php
			}
		}
	}
	public function likes()
	{
		// Validamos si el usuario esta logeado
		if(!isset($_SESSION['user']['id']) or empty($_SESSION['user']['id']))
		{
			die("error session :'( , try again :)");	
		}

		$tk =  $this->uri->segment(5);
		if(!isset($_SESSION['tk']) or empty($tk) or !array_key_exists($tk, $_SESSION['tk']))
		{
			die("error tk :'( , try again :)");	
		}
		// obtenemos los datos
		$id_publication = $this->uri->segment(4);
		$id_user = $_SESSION['user']['id'];
		$now = date("Y-m-d H:i:s");
		
		$data = array(
				'id_publication'=>$id_publication,
				'id_user'=>$id_user,
				'date'=>$now
		);
		// insertamos los datos
		if($this->db->insert('likee',$data))
		{
			$resp = array('error'=>'0');
		}else{
			$resp = array('error'=>'1');
		}

		echo json_encode($resp);
		die();
		
	}
	public function publications_feed()
	{
		// Validamos si el usuario esta logeado
		if(!isset($_SESSION['user']['id']) or empty($_SESSION['user']['id']))
		{
			die("error session :'( , try again :)");	
		}

		$tk =  $this->uri->segment(6);

		if(!isset($_SESSION['tk']) or empty($tk) or !array_key_exists($tk, $_SESSION['tk']))
		{
			die("error tk :'( , try again :)");	
		}

		// SET LIMIT
		$num_get=40; // num row get
		$num_to_load =  $this->uri->segment(5);
		$limit_a = ($num_to_load-1)*$num_get;
		$limit_b = $num_to_load*$num_get;

		// creamos un token
		$tk = md5(time().'belieber');
		$_SESSION['tk'][$tk]=$tk;

		// Obtenemos mis datos
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

		// Obtenemos las publicaciones con LIMIT
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
		where p.id in (select id from publication where id_group in(select id_group from follow where id_user='.$me['id'].')) order by p.id desc limit '.$limit_a.','.$limit_b.'');
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

		?>
			<?php if(empty($publications)){?>
			0
			<?php } ?>

			<?php for($i=0;$i<count($publications);$i++){?>
			<?php $x = ((($num_to_load-1)*$num_get)+$i); // para identificar los sp al crear los modales?>
			<div class="span3 item-masonry">
				<div class="sp" id="sp-<?php echo $x;?>">
					<div class="header-sp">
						<a href="<?php echo site_url('group/'.$publications[$i]['id_group'].'');?>" class="span-tooptip pull-right" data-animation="hide" data-toggle="tooltip" title="<?php echo htmlentities(utf8_decode($publications[$i]['group_name']));?>"><i class="icon-th-large img-sp-group a-group"></i></a>
						<img src="/images/user/<?php echo $publications[$i]['user_photo'];?>" class="img-rounded img-sp-user" alt="" id="img-sp-user-<?php echo $x;?>">
						<p class="name-sp-user" id="name-sp-user-<?php echo $x;?>"><a href="<?php echo site_url('profile/'.$publications[$i]['id_user'].'');?>" id="a-name-sp-user-<?php echo $x;?>"><?php echo $publications[$i]['user_name'];?></a></p>
					</div>
					<div class="cl" id="cl-<?php echo $x;?>">
						<?php if(!empty($publications[$i]['message_photo'])){ ?>
						<div class="container-sp-photo">
						<img src="/images/sp/<?php echo $publications[$i]['message_photo'];?>" alt="" class="sp-photo" id="sp-photo-<?php echo $x;?>">
						</div>
						<input type="hidden" value="1" id="sp-photo-indicator-<?php echo $x;?>">
						<?php }else{ ?>
						<input type="hidden" value="0" id="sp-photo-indicator-<?php echo $x;?>">
						<?php } ?>
						<div class="sp-container-counts">
						<p id="container-count-heart-<?php echo $x;?>" class="container-count-heart pull-right <?php echo $publications[$i]['like']=='1'?'heart-like':'';?>">
							<i class="icon-heart"></i>
							<span id="span-count-heart-<?php echo $x;?>"><?php echo $publications[$i]['likes'];?></span>
						</p>
						<p id="container-count-comments-<?php echo $x;?>" class="container-count-comments pull-right">
							<i class="icon-comments"></i>
							<span id="span-count-comments-<?php echo $x;?>"><?php echo $publications[$i]['comments'];?></span>
						</p>
						</div>
						<p class="sp-text" id="sp-text-<?php echo $x;?>"><?php echo htmlentities(utf8_decode($publications[$i]['message']));?></p>
					</div>
				</div>
				<form action="">
					<input type="hidden" id="url-comments-<?php echo $x;?>" value="<?php echo site_url('feed/comments/'.$publications[$i]['id'].'/'.$tk.'');?>">
					<input type="hidden" id="url-likes-<?php echo $x;?>" value="<?php echo site_url('feed/likes/'.$publications[$i]['id'].'/'.$tk.'');?>">
					<input type="hidden" id="like-<?php echo $x;?>" value="<?php echo $publications[$i]['like']=='1'?'1':'0';?>">
				</form>
			</div>
			<?php } ?>
		<?php
	}
	public function publications_profile()
	{
		// Validamos si el usuario esta logeado
		if(!isset($_SESSION['user']['id']) or empty($_SESSION['user']['id']))
		{
			die("error session :'( , try again :)");	
		}

		$tk =  $this->uri->segment(7);

		if(!isset($_SESSION['tk']) or empty($tk) or !array_key_exists($tk, $_SESSION['tk']))
		{
			die("error tk :'( , try again :)");	
		}

		// SET LIMIT
		$num_get=40; // num row get
		$num_to_load =  $this->uri->segment(6);
		$limit_a = ($num_to_load-1)*$num_get;
		$limit_b = $num_to_load*$num_get;


		// creamos un token
		$tk = md5(time().'belieber');
		$_SESSION['tk'][$tk]=$tk;

		// Obtenemos mis datos para la foto al publicar
		$this->db->where('id',$_SESSION['user']['id']);
		$q = $this->db->get('user');
		$users = $q->result_array();
		$me = $users[0];

		// Obtenemos el id del usuario buscado
		$id_profile = $this->uri->segment(5);

		// Verificamos si el usuario existe y obtenemos los datos

		$this->db->where('id',$id_profile);
		$q = $this->db->get('user');
		if($q->num_rows()<1)
		{
			?>
			0
			<?php
			// retorna un 0 int
			die();
		}
		$users = $q->result_array();
		$me = $users[0];
		
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
		where p.id_user='.$me['id'].' order by id desc limit '.$limit_a.','.$limit_b.'');
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

		?>
		<?php if(empty($publications)){?>
				0
		<?php } ?>
		<?php for($i=0;$i<count($publications);$i++){?>
		<?php $x = ((($num_to_load-1)*$num_get)+$i); // para identificar los sp al crear los modales?>
			<div class="span3 item-masonry">
				<div class="sp">
					<div class="header-sp">
						<a href="<?php echo site_url('group/'.$publications[$i]['id_group'].'');?>" class="span-tooptip pull-right" data-animation="hide" data-toggle="tooltip" title="<?php echo htmlentities(utf8_decode($publications[$i]['group_name']));?>"><i class="icon-th-large img-sp-group"></i></a>
						<img src="/images/user/<?php echo $publications[$i]['user_photo'];?>" class="img-rounded img-sp-user" alt="" id="img-sp-user-<?php echo $x;?>">
						<p id="name-sp-user-<?php echo $x;?>" class="name-sp-user"><?php echo $publications[$i]['user_name'];?></p>
					</div>
					<div class="cl" id="cl-<?php echo $x;?>">
						<?php if(!empty($publications[$i]['message_photo'])){ ?>
						<div class="container-sp-photo">
						<img src="/images/sp/<?php echo $publications[$i]['message_photo'];?>" alt="" class="sp-photo" id="sp-photo-<?php echo $x;?>">
						</div>
						<input type="hidden" value="1" id="sp-photo-indicator-<?php echo $x;?>">
						<?php }else{ ?>
						<input type="hidden" value="0" id="sp-photo-indicator-<?php echo $x;?>">
						<?php } ?>
						<div class="sp-container-counts">
							<p id="container-count-heart-<?php echo $x;?>" class="container-count-heart pull-right <?php echo $publications[$i]['like']=='1'?'heart-like':'';?>">
								<i class="icon-heart"></i>
							 	<span id="span-count-heart-<?php echo $x;?>"><?php echo $publications[$i]['likes'];?></span>
							</p>
							<p id="container-count-comments-<?php echo $x;?>" class="container-count-comments pull-right">
								<i class="icon-comments"></i>
								<span id="span-count-comments-<?php echo $x;?>"><?php echo $publications[$i]['comments'];?></span>
							</p>
						</div>
						<p class="sp-text" id="sp-text-<?php echo $x;?>"><?php echo htmlentities(utf8_decode($publications[$i]['message']));?></p>
					</div>
				</div>
				<form action="">
					<input type="hidden" id="url-comments-<?php echo $x;?>" value="<?php echo site_url('feed/comments/'.$publications[$i]['id'].'/'.$tk.'');?>">
					<input type="hidden" id="url-likes-<?php echo $x;?>" value="<?php echo site_url('feed/likes/'.$publications[$i]['id'].'/'.$tk.'');?>">
					<input type="hidden" id="like-<?php echo $x;?>" value="<?php echo $publications[$i]['like']=='1'?'1':'0';?>">
				</form>
			</div>
			<?php } ?>
		<?php
	}
	public function publications_group()
	{
		// Validamos si el usuario esta logeado
		if(!isset($_SESSION['user']['id']) or empty($_SESSION['user']['id']))
		{
			die("error session :'( , try again :)");	
		}

		$id_user = $_SESSION['user']['id'];
		$tk =  $this->uri->segment(7);

		if(!isset($_SESSION['tk']) or empty($tk) or !array_key_exists($tk, $_SESSION['tk']))
		{
			die("error tk :'( , try again :)");	
		}

		// SET LIMIT
		$num_get=40; // num row get
		$num_to_load =  $this->uri->segment(6);
		$limit_a = ($num_to_load-1)*$num_get;
		$limit_b = $num_to_load*$num_get;

		// creamos un token
		$tk = md5(time().'belieber');
		$_SESSION['tk'][$tk]=$tk;

		// Obtenemos mis datos (para poner mi foto al publicar)
		$this->db->where('id',$id_user);
		$q=$this->db->get('user');
		$users=$q->result_array();
		$yo=$users[0];

		// Obtenemos el id del grupo buscado
		$id_group = $this->uri->segment(5);

		// Verificamos si el grupo existe y Obtenemos los datos del grupo en vista
		$this->db->where('id',$id_group);
		$q = $this->db->get('groupp');
		if($q->num_rows()<1)
		{
			?>
			0
			<?php
			die();
		}

		$groups = $q->result_array();
		$me = $groups[0];
	    
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
		where p.id_group='.$id_group.' order by id desc limit '.$limit_a.','.$limit_b.'');
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
		?>
		<?php if(empty($publications)){?>
			0
		<?php } ?>
		<?php for($i=0;$i<count($publications);$i++){?>
		<?php $x = ((($num_to_load-1)*$num_get)+$i); // para identificar los sp al crear los modales?>
			<div class="span3 item-masonry">
				<div class="sp">
					<div class="header-sp">
						<img id="img-sp-user-<?php echo $x;?>" src="/images/user/<?php echo $publications[$i]['user_photo'];?>" class="img-rounded img-sp-user" alt="">
						<p class="name-sp-user"><a id="a-name-sp-user-<?php echo $x;?>" href="<?php echo site_url('profile/'.$publications[$i]['id_user'].'');?>"><?php echo $publications[$i]['user_name'];?></a></p>
					</div>
					<div class="cl" id="cl-<?php echo $x;?>">
						<?php if(!empty($publications[$i]['message_photo'])){ ?>
						<div class="container-sp-photo">
						<img src="/images/sp/<?php echo $publications[$i]['message_photo'];?>" alt="" class="sp-photo" id="sp-photo-<?php echo $x;?>">
						</div>
						<input type="hidden" value="1" id="sp-photo-indicator-<?php echo $x;?>">
						<?php }else{ ?>
						<input type="hidden" value="0" id="sp-photo-indicator-<?php echo $x;?>">
						<?php } ?>
						<div class="sp-container-counts">
						<p class="container-count-heart pull-right" id="container-count-heart-<?php echo $x;?>">
							<i class="icon-heart"></i> 
							<span id="span-count-heart-<?php echo $x;?>" class="count-heart"><?php echo $publications[$i]['likes'];?></span>
						</p>
						<p class="container-count-comments pull-right" id="container-count-comments-<?php echo $x;?>">
							<i class="icon-comments"></i>
							<span id="span-count-comments-<?php echo $x;?>" class="count-heart"><?php echo $publications[$i]['comments'];?></span>
						</p>
						</div>
						<p class="sp-text" id="sp-text-<?php echo $x;?>"><?php echo htmlentities(utf8_decode($publications[$i]['message']));?></p>
					</div>
				<form action="">
					<input type="hidden" id="url-comments-<?php echo $x;?>" value="<?php echo site_url('feed/comments/'.$publications[$i]['id'].'/'.$tk.'');?>">
					<input type="hidden" id="url-likes-<?php echo $x;?>" value="<?php echo site_url('feed/likes/'.$publications[$i]['id'].'/'.$tk.'');?>">
					<input type="hidden" id="like-<?php  echo $x;?>" value="<?php  echo $publications[$i]['like']=='1'?'1':'0';?>"> 
				</form>
				</div>
			</div>
			<?php } ?>
		<?php
	}
	public function syncf()
	{
		// Validamos si el usuario esta logeado
		if(!isset($_SESSION['user']['id']) or empty($_SESSION['user']['id']))
		{
			die("error session :'( , try again :)");	
		}

		$id_user = $_SESSION['user']['id'];
		$tk =  $this->uri->segment(5);

		// if(!isset($_SESSION['tk']) or empty($tk) or !array_key_exists($tk, $_SESSION['tk']))
		// {
		// 	die("error tk :'( , try again :)");	
		// }

		$syncf =  $this->uri->segment(4);

		if($syncf=='0'){

			$this->db->where('id',$id_user);
			$data = array('syncf'=>1);
			$this->db->update('user',$data);
			
			$resp = array('error'=>'0');
			echo json_encode($resp);
			die();

		}elseif($syncf=='1'){

			$this->db->where('id',$id_user);
			$data = array('syncf'=>0);
			$this->db->update('user',$data);
			
			$resp = array('error'=>'0');
			echo json_encode($resp);
			die();

		}
		// value no 1 or 0
		$resp = array('error'=>'1');
		echo json_encode($resp);
		die();		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */