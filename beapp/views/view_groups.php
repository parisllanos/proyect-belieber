<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Belieber.be</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="<?php echo base_url();?>bootstrap/css/bootstrap-select.css" rel="stylesheet" media="screen">
	<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url();?>css/group/style_groups.css">
	<meta property="og:title" content="Belieber"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="http://www.belieber.be"/>
    <meta property="og:image" content="http://www.belieber.be/images/logo_tag_facebook.png"/>
    <meta property="og:site_name" content="Belieber"/>
    <meta property="fb:admins" content="100002189287259"/>
   <!--  <meta property="og:description" content="<?php echo lang('facebook_description');?>"/>
	<meta name="keywords" content="belieber, Belieber, beliebers, Beliebers, justin bieber, Justin Bieber" /> 
	<meta name="description" content="<?php echo lang('home_description');?>" />  -->
</head>
<body>
	<!--nav-->
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner ">
	    	<div class="container bg-logo">
	     		<div class="btn-group pull-right">
		  			<a class="btn btn-info dropdown-toggle" data-toggle="dropdown" href="#">
		    			<i class="icon-user icon-white icon-large"></i>
		   				<span class="caret"></span>
		  			</a>
		  			<ul class="dropdown-menu">
		  				<li class="disabled"><a href="#"><i class="icon-envelope"></i> <?php echo $me['email'];?></a></li>
					  	<li class="divider"></li>
					  	<li><a href="<?php echo site_url('logout');?>">Sign out</a></li>
		  			</ul>
				</div>
				<ul class="nav pull-right">
				  <li>
				    <a href="<?php echo site_url('feed');?>">Home</a>
				  </li>
				  <li><a href="<?php echo site_url('profile/'.$_SESSION['user']['id'].'');?>">Profile</a></li>
				  <li class="active"><a href="<?php echo site_url('group');?>">Groups</a></li>
				</ul>
	    	</div>
	  	</div>
	</div>
	<!--end nav-->
	<div class="container container-page">
		<!--MODAL CREATE GROUP-->
		<?php if($group_owner!=1){?>
		<div class="modal hide fade" id="modal-create-group">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3><i class="icon-th-large"></i> Create group</h3>
			</div>
			<div class="modal-body">
				<div class="alert alert-success">
				  <button type="button" class="close" data-dismiss="alert">&times;</button>
				  <strong>Hey!</strong> You only can create one group.
				</div>
				<form class="form-horizontal" method="POST" enctype="multipart/form-data" action="<?php echo site_url('group/create'); ?>">
					<div class="modal-create-container-input">
						<p>Name Group</p>
						<?php echo form_error('group_name', '<p class=" alert alert-danger modal-create-alert-form">', '</p>'); ?>
						<input type="text" name="group_name" placeholder="Write name">
					</div>
					<div class="modal-create-container-input">
						<p>Description Group</p>
						<?php echo form_error('group_description', '<p class=" alert alert-danger modal-create-alert-form">', '</p>'); ?>
						<input type="text" name="group_description" placeholder="Write description">
					</div>
					<div class="modal-create-container-input">
						<p>Select photo of the group <br> <small>(min 100 pixels of height and width)</small></p>
						<?php echo $this->upload->display_errors('<p class="alert alert-danger modal-create-alert-form">', '</p>');?>
						<input type="file" class="filestyle" name="group_photo" data-classButton="btn" data-input="true" data-classInput="input-small" data-classIcon="icon-camera" data-buttonText="">
					</div>
					<div class="modal-create-container-input btn-submit-create" >
					<button class="btn btn-success btn-large"><i class="icon-ok icon-2x"></i></button>
					</div>
					<input type="hidden" name="tk" value="<?php echo $tk;?>">
				</form>
			</div>
		</div>
		<!---->
		<?php } ?>
		<div class="row">
			<div class="span10 offset1">
				<div class="sp">
					<div class="header-sps">
						<p><i class="icon-th-large"></i> Groups administration</p>
					</div>
					<?php if($error_create){ ?>
					<div class="alert alert-block alert-danger" style="text-align:center;">
					  <button type="button" class="close" data-dismiss="alert">&times;</button>
					  <h4>Hey!</h4>
					  We couldn't create your group :'(. Watch your errors and try it again. 
					</div>
					<?php } ?>
					<?php if($error_delete){ ?>
					<div class="alert alert-block alert-danger" style="text-align:center;">
					  <button type="button" class="close" data-dismiss="alert">&times;</button>
					  <h4>Hey!</h4>
					  We couldn't delete your group :'(. Please try it again. 
					</div>
					<?php } ?>
					<?php if($error_save){ ?>
					<div class="alert alert-block alert-danger" style="text-align:center;">
					  <button type="button" class="close" data-dismiss="alert">&times;</button>
					  <h4>Hey!</h4>
					  We couldn't save the changes of your group :'(. Please try it again. 
					</div>
					<?php } ?>
					<?php if($error_delete_member){ ?>
					<div class="alert alert-block alert-danger" style="text-align:center;">
					  <button type="button" class="close" data-dismiss="alert">&times;</button>
					  <h4>Hey!</h4>
					  We couldn't remove the member of your group :'(. Please try it again. 
					</div>
					<?php } ?>
					<?php echo form_error('email_user', '<p class="alert alert-danger droid" style="text-align:center;">', '</p>'); ?>
					<?php  if(!empty($member_groups)){ ?>
					<?php  foreach($member_groups as $me_group){ ?>
					<?php // verificamos si soy administrador del grupo  ?>
					<?php $adm = $me_group['adm']?true:false;?>
					<?php if($adm){?>
						<!--MODAL DELETE GROUP-->
						<div class="modal fade hide" id="modal-group-<?php echo $me_group['id_group'];?>">
						  <div class="modal-header">
						  	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						    <h3><i class="icon-th-large"></i> Delete group</h3>
						  </div>
						  <div class="modal-body">
						  <p class="p-message-delete">Do you want to delete this group (<?php echo htmlentities(utf8_decode($me_group['group_name']));?>) ?</p>
						  </div>
						   <div class="modal-footer">
						   	<form action="<?php echo site_url('group/delete');?>" method="post">
							    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
							    <button class="btn btn-success"><i class="icon-ok"></i></button>
							    <input type="hidden" value="<?php echo $me_group['id_group'];?>" name="groupid">
							    <input type="hidden" value="<?php echo $tk;?>" name="tk">
						    </form>
						  </div>
						</div>
						<!---->
					<?php } ?>

					<div class="container-groups">
						<div class="container-group-head">
							<img src="/images/group/<?php echo $me_group['group_photo'];?>" alt="" class="img-rounded container-group-head-img">
							<div class="container-data-group">
								<div>
								<p class="name-group"><a href="<?php echo site_url('group/'.$me_group['id_group'].'')?>"><strong><?php echo htmlentities(utf8_decode($me_group['group_name']));?></strong></a></p>
								<?php  if($adm){ ?>
								<a href="#modal-group-<?php echo $me_group['id_group'];?>" data-toggle="modal"  class="btn btn-mini btn-danger">Delete group</a>
								<button class="btn btn-mini btn-success submit-save" id="submit-<?php echo $me_group['id_group'];?>">Save changes</button>
								<button class="btn btn-mini bt-display-invite" id="bt-display-invite-<?php echo $me_group['id_group'];?>">New member</button>
								<?php  } ?>
								</div>
								<p class="desc-group"><?php echo htmlentities(utf8_decode($me_group['group_desc']));?></p> 
							</div>
						</div>
						<div class="row row-aux">
							<form action="<?php echo site_url('group/delete_member');?>" method="post" id="form-delete-member-<?php echo $me_group['id_group'];?>">
								<input type="hidden" value="<?php echo $tk;?>" name="tk">
								<input type="hidden" value="<?php echo $me_group['id_group'];?>" name="groupid">
								<input type="hidden" value="" name="uid" id="uid-delete-<?php echo $me_group['id_group'];?>">
							</form>
							<form action="<?php echo site_url('group/save');?>" method="post" id="form-save-<?php echo $me_group['id_group'];?>">
								<input type="hidden" value="<?php echo $me_group['id_group'];?>" name="groupid">
								<input type="hidden" value="<?php echo $tk;?>" name="tk">
							<?php  foreach($me_group['members'] as $members => $member){ ?>
								<input type="hidden" value="<?php echo $member['id'];?>" name="uid[]">
							<div class="span2">
								<?php
									$disabled = false;
									if(!$adm)
									{// Disabled si no soy admin
										$disabled = true;
									
									}elseif($adm && ($member['id']==$me_group['id_user']))
									{// Disabled si soy admin y es mi perfil 
										$disabled = true;
									}	
								?>
								<div class="container-img-member">
								<?php if($adm){ ?>
								<button  <?php echo $disabled?'disabled':'';?> type="button" class="btn btn-mini bt-delete-member" id="bt-delete-member-<?php echo $me_group['id_group'].'-'.$member['id'];?>"><i class="icon-remove"></i></button>
								<?php } ?>
								<img src="/images/user/<?php echo $member['user_photo'];?>" alt="" class="img-rounded img-member-group" style="display:inline">
								</div>
								<p class="name-member-group">
									<?php echo $member['user_name'];?>
								</p>
								<div class="container-permissions">
									<select name="appointment[]" class="selectpicker show-menu-arrow" <?php echo $disabled?'disabled':'';?> >
										<?php  if($member['adm']==1){ ?>
											<option value="adm">Administrator</option>
											<option value="cdc">Content creator</option>
										<?php  }else{ ?>
											 <option value="cdc">Content creator</option>
											<option value="adm">Administrator</option>
										<?php } ?>
		  							</select>
								</div>
							</div>
							<?php } ?>
		  					</form>
						</div>
						<?php if($adm){ ?>
						<form action="<?php echo site_url('group/invite');?>" method="post">
						<input type="hidden" value="<?php echo $me_group['id_group'];?>" name="groupid">
						<input type="hidden" value="<?php echo $tk;?>" name="tk">
						<div class="container-invitation-member" id="container-invitation-member-<?php echo $me_group['id_group'];?>">
							<hr style="margin:5px 0px;">
							<p class="p-invitation-member droid"><i class="icon-user icon-large"></i> Add to a new member. <br> <small>( Your friend can view the email on the top blue button <a href="#" class="btn btn-mini btn-info"><i class="icon-user"></i></a> )</small></p>
							<div class="input-prepend">
							  <span class="add-on"><i class="icon-envelope"></i></span>
							  <input class="span3" id="prependedInput" type="text" name="email_user" placeholder="Email address">
							</div>
							<input type="submit" class="btn btn-small droid" value="Add now" style="display:block">
						</div>
						</form>
						<hr style="margin:5px 0px;">
						<?php } ?>
					</div>
					<?php  } ?>
					<?php  } ?>
					<div id="container-create-group">
					<p class="alert alert-info alert-create"><?php echo $group_owner==1?'Sorry, You only can to create one group.':'Create your first group and invite all your friends.';?></p>
					<a href="#modal-create-group" data-toggle="modal" class="btn btn-info <?php echo $group_owner==1?'disabled':'';?> ">Create group</a>
					</div>
				</div>
			</div>
		</div>
		<!--ALL GROUPS-->
		<div class="row m">
			<div class="span10 offset1">
				<div class="sp">
					<div class="header-sps">
						<p><i class="icon-th-large"></i> All groups</p>
					</div>	
					<div class="container-all-groups">
						<?php foreach($groups as $group){ ?>
						<div class="container-all-group">
							<img src="/images/group/<?php echo $group['group_photo'];?>" alt="" class="img-rounded img-all-group">
							<div class="data-all-group">
								<p class="name-all-group data-all"><strong><a href="<?php echo site_url('group/'.$group['id_group'].'');?>"><?php echo htmlentities(utf8_decode($group['group_name']));?></a></strong></p>
								<p class="desc-all-group data-all"><?php echo htmlentities(utf8_decode($group['group_desc']));?></p>
								<p class="follow-member-all-group data-all"><?php echo $group['follows'];?> followers - <?php echo $group['members'];?> members</p>
							</div>
						</div>
						<?php } ?>
						<div class="aux"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-39149255-4', 'belieber.be');
  ga('send', 'pageview');

</script>
	<script src="http://code.jquery.com/jquery.js"></script>
    <script src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>bootstrap/js/bootstrap-select.js"></script>
    <script src="<?php echo base_url();?>bootstrap/js/bootstrap-filestyle.js"></script>
    <script>
    $(":file").filestyle();
     $('.selectpicker').selectpicker({
      width: '140px',
 	 });
    </script>
    <script>
    $(document).on('ready',function(){
    	$('.submit-save').on('click',save_group);
    	$('.bt-display-invite').on('click',display_invite);
    	$('.bt-delete-member').on('click',delete_member);

    });
    function delete_member()
    {
    	var id_group = (this.id.split('-'))[3];
    	var id_user = (this.id.split('-'))[4];
    	$('#uid-delete-'+id_group).val(id_user);
    	$('#form-delete-member-'+id_group).submit();
    }
    function save_group()
    {	
    	var num = (this.id.split('-'))[1];
    	$('.selectpicker').removeAttr('disabled');
    	$('#form-save-'+num).submit();
    }
    function display_invite()
    {
    	var num = (this.id.split('-'))[3];
    	$('#container-invitation-member-'+num).show('fast');
    }
    </script>
</body>
</html>