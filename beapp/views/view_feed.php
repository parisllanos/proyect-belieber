<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Belieber.be</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="<?php echo base_url();?>bootstrap/css/bootstrap-select.css" rel="stylesheet" media="screen">
	<link href="<?php echo base_url();?>bootstrap/css/bootstrap-scroll-modal.css" rel="stylesheet" media="screen">
	<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url();?>css/user/style_feed.css">
	<meta property="og:title" content="Belieber"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="http://www.belieber.be"/>
    <meta property="og:image" content="http://www.belieber.be/images/logo_tag_facebook.png"/>
    <meta property="og:site_name" content="Belieber"/>
    <meta property="fb:admins" content="100002189287259"/>
    <!-- <meta property="og:description" content="<?php echo lang('facebook_description');?>"/>
	<meta name="keywords" content="belieber, Belieber, beliebers, Beliebers, justin bieber, Justin Bieber" /> 
	<meta name="description" content="<?php echo lang('home_description');?>" />  -->
</head>
<body>
	<!--MODAL PUBLISH-->
				<div class="modal hide fade" id="modal-publish">
				  <div class="modal-header modal-header-publish">
				  	 <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-2x"></i></button>
				    <img src="" class="img-rounded" alt="" id="modal-publish-img-user">
				    <p class="modal-publish-name-user"><a href="" id="modal-publish-a-name-user"></a></p>
				  </div>
				  <div class="modal-body">
					  <div class="modal-publish-container-img">
					  		<img src="" alt="" id="modal-publish-photo">
					  </div>
					  <div class="modal-publish-container-data-count">
					  	<p class="modal-publish-count-heart pull-right" id="modal-publish-count-icon-heart">
							<i class="icon-heart"></i>
							<span id="modal-publish-count-heart"></span>
						</p>
						<p class="modal-publish-count-comments pull-right" id="modal-publish-count-icon-comments">
							<i class="icon-comments"></i>
							<span id="modal-publish-count-comments"></span>
						</p>
					  </div>
					  <p class="modal-publish-text-user" id="modal-publish-text-user">
					  </p>
					  <hr style="margin:20px 0 10px 0;" />

					<div class="modal-publish-container-comments-big">
					  	<div class="ca">
						  	<div class="modal-publish-container-comment">
						  		<p class="alert alert-danger" id="modal-publish-alert">You need to write something.</p>
						  		<img src="/images/user/<?php echo $me['photo'];?>" alt="" class="img-rounded modal-publish-img-user-comment">
						  		<textarea class="modal-publish-textarea" id="modal-publish-textarea" placeholder="Write something"></textarea>
						  		<div style="height: 20px;padding:0 30px;clear:both">
						  			<button class="btn-small btn btn-info pull-right" id="modal-publish-bt-publish">Publicar</button>
						  		</div>
						  		<form action="">
						  			<input type="hidden" id="modal-publish-comment-url" value="">
						  			<input type="hidden" id="modal-publish-like-url" value="">
						  			<input type="hidden" id="modal-publish-like" value="">
						  		</form>
						  	</div>		
					  		<div id="aux"></div>
					  	</div>
					  	<hr style="margin:10px 0 10px 0;" />
					  	<div id="modal-publish-container-comments">
						</div>
					</div>
				  </div>
				</div>
	<!---->
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
					  	<li><a href="<?php echo site_url('logout');?>">Sign out</a></li>
		  			</ul>
				</div>
				<ul class="nav pull-right">
				  <li class="active">
				    <a href="<?php echo site_url('feed');?>">Home</a>
				  </li>
				  <li><a href="<?php echo site_url('profile/'.$_SESSION['user']['id'].'');?>">Profile</a></li>
				  <li><a href="<?php echo site_url('group');?>">Groups</a></li>
				</ul>
	    	</div>
	  	</div>
	</div>
	<!--end nav-->
	<div class="container container-page">
		<div class="row r" id="container-masonry">
			<div class="span3 item-masonry">
				<!--SP USER-->
				<div class="sp" id="sp-user">
					<div id="header-sp-user">	
					</div>
					<div id="container-sp-pic-user">
						<img src="/images/user/<?php echo $me['photo'];?>" alt="" class="img-polaroid" id="img-sp-pic-user">
					</div>
					<p id="p-sp-name-user"><a href="<?php echo site_url('profile/'.$me['id'].'');?>"><?php echo $me['name'];?></a></p>
					<?php if(!empty($member_groups)){?>
					<form action="#" method="POST" enctype="multipart/form-data">
					<ul class="nav nav-list">
						<li class="disabled">
							<a href="">Publicar a: </a>
						</li>
							<select class="selectpicker show-menu-arrow" data-style="btn-info" name="publish_group">
								<?php foreach($member_groups as $group){?>
								<option value="<?php echo $group['id_group'];?>"><?php echo htmlentities(utf8_decode($group['group_name']));?></option>
								<?php } ?>
  							</select>
  					</ul>
					<div id="container_user_message">
						<?php echo form_error('publish_text', '<p class="alert alert-danger" id="publish_error">', '</p>'); ?>
						<?php echo $this->upload->display_errors('<p class="alert alert-danger" id="publish_error">', '</p>');?>
						<textarea id="user_message" name="publish_text"></textarea>
						<div id="container-btns">
							<input type="file" class="filestyle" name="publish_photo" data-classButton="btn" data-input="true" data-classInput="input-small" data-classIcon="icon-camera" data-buttonText="">
							<input type="submit" value="Publish" class="btn btn-info pull-right" id="bt-sd">
						</div>
					</div>
					<input type="hidden" name="publish_tk" value="<?php echo $tk;?>">
					</form>
					<?php } ?>
				</div>
				<!---->
			</div>
			<!--PUBLICATIONS-->
			<?php if(empty($publications)){?>
			<div class="span6 well item-masonry">
				<p style="text-align:center;font-size:13px" class="droid"><i class="icon-exclamation-sign icon-3x" style="color:#15b8d5"></i><br><strong>Hey!</strong> You can follow some groups <a href="<?php echo site_url('group');?>">here</a> :')</p>
			</div>
			<?php } ?>
			<?php for($i=0;$i<count($publications);$i++){?>
			<div class="span3 item-masonry">
				<div class="sp" id="sp-<?php echo $i;?>">
					<div class="header-sp">
						<a href="<?php echo site_url('group/'.$publications[$i]['id_group'].'');?>" class="span-tooptip pull-right" data-animation="hide" data-toggle="tooltip" title="<?php echo htmlentities(utf8_decode($publications[$i]['group_name']));?>"><i class="icon-th-large img-sp-group a-group"></i></a>
						<img src="/images/user/<?php echo $publications[$i]['user_photo'];?>" class="img-rounded img-sp-user" alt="" id="img-sp-user-<?php echo $i;?>">
						<p class="name-sp-user" id="name-sp-user-<?php echo $i;?>"><a href="<?php echo site_url('profile/'.$publications[$i]['id_user'].'');?>" id="a-name-sp-user-<?php echo $i;?>"><?php echo $publications[$i]['user_name'];?></a></p>
					</div>
					<div class="cl" id="cl-<?php echo $i;?>">
						<?php if(!empty($publications[$i]['message_photo'])){ ?>
						<div class="container-sp-photo">
						<img src="/images/sp/<?php echo $publications[$i]['message_photo'];?>" alt="" class="sp-photo" id="sp-photo-<?php echo $i;?>">
						</div>
						<input type="hidden" value="1" id="sp-photo-indicator-<?php echo $i;?>">
						<?php }else{ ?>
						<input type="hidden" value="0" id="sp-photo-indicator-<?php echo $i;?>">
						<?php } ?>
						<div class="sp-container-counts">
						<p id="container-count-heart-<?php echo $i;?>" class="container-count-heart pull-right <?php echo $publications[$i]['like']=='1'?'heart-like':'';?>">
							<i class="icon-heart"></i>
							<span id="span-count-heart-<?php echo $i;?>"><?php echo $publications[$i]['likes'];?></span>
						</p>
						<p id="container-count-comments-<?php echo $i;?>" class="container-count-comments pull-right">
							<i class="icon-comments"></i>
							<span id="span-count-comments-<?php echo $i;?>"><?php echo $publications[$i]['comments'];?></span>
						</p>
						</div>
						<p class="sp-text" id="sp-text-<?php echo $i;?>"><?php echo htmlentities(utf8_decode($publications[$i]['message']));?></p>
					</div>
				</div>
				<form action="">
					<input type="hidden" id="url-comments-<?php echo $i;?>" value="<?php echo site_url('feed/comments/'.$publications[$i]['id'].'/'.$tk.'');?>">
					<input type="hidden" id="url-likes-<?php echo $i;?>" value="<?php echo site_url('feed/likes/'.$publications[$i]['id'].'/'.$tk.'');?>">
					<input type="hidden" id="like-<?php echo $i;?>" value="<?php echo $publications[$i]['like']=='1'?'1':'0';?>">
				</form>
			</div>
			<?php } ?>
			<!-- <div class="span3 item-masonry"> aca esta todo papa ! </div> -->
		</div>
		<div class="span4 offset4" id="container-load">
			<input type="hidden" value="<?php echo site_url('feed/publications/feed//'.$tk.'') ?>" id="url-publications">
			<input type="hidden" value="2" id="num-load">
			<div id="load" class="load"></div>
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
    <script src="<?php echo base_url();?>bootstrap/js/bootstrap-scroll-modal.js"></script>
    <script src="<?php echo base_url();?>js/masonry.js"></script>
    <script src="<?php echo base_url();?>js/inview.js"></script>
    <script>
   	var $container = $('#container-masonry');
   	$container.masonry({
	  columnWidth: 240, 
	  itemSelector: '.item-masonry'
	});
	msnry = $container.data('masonry'); // get instance
    </script>
    <script>
    $(":file").filestyle();
    $('.selectpicker').selectpicker({
      width: '190px'
 	});
    $('.span-tooptip').tooltip('hide');
    
    </script>
    <script>
    num=0;
    getItems=true;
    $(document).on('ready',function()
    {
    	//set click view modal publish
    	$('#container-masonry').on('click','div.cl',view); // Init view modal
    	//set create comment 
    	$('#modal-publish-bt-publish').on('click',create_comment);
    	// set scroll load get
    	getScroll();
    });
    function getScroll()
    {
    	// 40 max more sp-user = 41
    	var num = $('.item-masonry').length;
    	if((num-1)<40)
    	{
    		$('#load').html('<p class="alert alert-info" style="text-align:center;"><i class="icon-minus-sign"></i> <small>No more publications</small></p>');
    	}else{
	    		// load img 
		    	$('#load').html('<p style="margin:0px auto;text-align:center;padding:10px;background-color:white"><img src="/images/ajax_loader.gif"></p>');

	    		var url_publications_feed = $('#url-publications').val();

	    		$('#load').bind('inview', function (event, visible) {
				 if (visible == true && getItems==true) {
				    var arr = url_publications_feed.split('/');
					arr[7]=$('#num-load').val();
	    	 		url_publications_feed=arr.join('/');

	    	 		$.get(url_publications_feed).done(function(data){
	    	 			if(data==0)
	    	 			{
	    	 				$('#load').html('<p class="alert alert-info" style="text-align:center;"><i class="icon-minus-sign"></i> <small>No more publications</small></p>');
	    	 				getItems=false; // Don't get items
	    	 				
	    	 			}else{

	    	 				// obtengo nuevamente el container
				    		var $container = $('#container-masonry'); // get container
				    		$container.data('masonry', null); // clear data
				    		$(data).hide().appendTo($container).fadeIn("slow"); // insert element
				    		$container.masonry({ // set options again
							  columnWidth: 240, 
							  itemSelector: '.item-masonry'
							});
		
	    	 				$('.span-tooptip').tooltip('hide'); // set tooltip again
	    	 				$('#num-load').val(parseInt($('#num-load').val())+1);
	    	 			}
	    	 		});
				  }
				});
    	}
    }
    function view()
    {
    	$("#modal-publish").modal({dynamic:true});
    	$('#modal-publish').modal({show:true});
    	num = (this.id.split('-'))[1];
    	modal_publish_load();
    }
    function modal_publish_load()
    {
    	view_num = num; //(comments)
    	// set alert
    	$('#modal-publish-alert').css('display','none');
    	// get
    	var img_sp_user = $('#img-sp-user-'+num).attr('src');
    	var a_name_sp_user=Object();
    	a_name_sp_user.href = $('#a-name-sp-user-'+num).attr('href');
    	a_name_sp_user.html = $('#a-name-sp-user-'+num).html();
    	var span_count_heart = $('#span-count-heart-'+num).html();
    	var span_count_comments = $('#span-count-comments-'+num).html();
    	var sp_text = $('#sp-text-'+num).html();
    	var url_comments_feed = $('#url-comments-'+num).val();
    	var url_likes_feed = $('#url-likes-'+num).val();
    	var sp_like = $('#like-'+num).val();

    	// put 
    	if($('#sp-photo-indicator-'+num).val()==1)
    	{
    		var sp_photo = $('#sp-photo-'+num).attr('src');
    		$('#modal-publish-photo').css('display','inline');
    		$('#modal-publish-photo').attr('src',sp_photo);
    	}else{
    		$('#modal-publish-photo').css('display','none');
    		$('#modal-publish-photo').attr('src','');	
    	}


    	$('#modal-publish-img-user').attr('src',img_sp_user);
    	$('#modal-publish-a-name-user').attr('href',a_name_sp_user.href);
    	$('#modal-publish-a-name-user').html(a_name_sp_user.html);
    	$('#modal-publish-count-heart').html(span_count_heart);
    	$('#modal-publish-count-comments').html(span_count_comments);
    	$('#modal-publish-text-user').html(sp_text);
    	$('#modal-publish-comment-url').val(url_comments_feed);
    	$('#modal-publish-like').val(sp_like);
    	
    	// set color heart like
    	if($('#modal-publish-like').val()=='1')
    	{
    		$('#modal-publish-count-icon-heart').attr('class','modal-publish-count-heart pull-right heart-like');
    	}else{
    		$('#modal-publish-count-icon-heart').attr('class','modal-publish-count-heart pull-right');
    	}

    	// set LIKE
    	if($('#modal-publish-like').val()=='0')
    	{
    		$('#modal-publish-count-icon-heart').unbind('click');
    		$('#modal-publish-count-icon-heart').on('click',function()
    		{
    		 	$.post(url_likes_feed).done(function(data)
    		 	{
    		 		var resp = $.parseJSON(data);
    		 		if(resp.error=='1')
    		 		{
    		 			alert('Lo sentimos, intentalo nuevamente');	
    		 		}else{
	    		 		var count_heart = parseInt($('#modal-publish-count-heart').html());
	    		 		$('#modal-publish-count-icon-heart').attr('class','modal-publish-count-heart pull-right heart-like');
	    		 		$('#container-count-heart-'+num).attr('class','container-count-heart pull-right heart-like');
	    		 		$('#span-count-heart-'+num).html(parseInt(count_heart)+1);
	    		 		$('#modal-publish-count-heart').html(parseInt(count_heart)+1);
	    		 		$('#modal-publish-like').val('1');
	    		 		$('#like-'+num).val('1');
	    		 		$('#modal-publish-count-icon-heart').unbind('click');
    		 		}
    		 	});  
    		});
    	}
    	$.get(url_comments_feed).done(function(data){
    		$('#modal-publish-container-comments').html(data);
    	});
    }
    function create_comment()
    {
    	var comment = $('#modal-publish-textarea').val();
    	if(comment=='' || comment==0)
    	{
    		$('#modal-publish-alert').css('display','block');

    	}else{
    		$('#modal-publish-alert').css('display','none');
    		var url_comment_feed = $('#modal-publish-comment-url').val();

    		var data = {'comment':comment};
    		$.post(url_comment_feed,data).done(function(data)
    			{

    				var resp = $.parseJSON(data);
    				if(resp.error=='1')
    				{
    				 	alert('Lo sentimos, vuelve a intentarlo.');
    				}else{

    					$('#modal-publish-container-comments').prepend(resp.data.html);
    					$('#modal-publish-textarea').val('');

    					$('#span-count-comments-'+num).html(parseInt($('#span-count-comments-'+num).html())+1);
	    		 		$('#modal-publish-count-comments').html(parseInt($('#modal-publish-count-comments').html())+1);
    				}
    			});
    	}
    }
    </script>
</body>
</html>