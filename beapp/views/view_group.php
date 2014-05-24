<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Belieber.be</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="<?php echo base_url();?>bootstrap/css/bootstrap-scroll-modal.css" rel="stylesheet" media="screen">
	<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url();?>css/group/style_group.css">
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
		  				<li class="disabled"><a href="#"><i class="icon-envelope"></i> <?php echo $yo['email'];?></a></li>
					  	<li class="divider"></li>
					  	<li><a href="<?php echo site_url('logout');?>">Sign out</a></li>
		  			</ul>
				</div>
				<ul class="nav pull-right">
				  <li>
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
		<!--MODAL MEMBERS-->
		<div class="modal fade hide" id="modal-members">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		    <h3><i class="icon-th-large"></i> Members</h3>
		  </div>
		  <div class="modal-body">
		    <div class="modal-members-container">
		    	<?php foreach($members_group as $member){?>
		    	<div class="modal-members-container-member">
		    		<img src="/images/user/<?php echo $member['photo'] ?>" alt="" class="modal-members-img img-rounded">
		    		<p class="modal-members-name-member"><a href="<?php echo site_url('profile/'.$member['id_user'].'');?>"><?php echo $member['user_name'];?></a></p>
		    	</div>
		    	<?php } ?>
		    </div>
		  </div>
		</div>
		<!---->
		<!--MODAL PUBLISH-->
		<div class="modal hide fade" id="modal-publish">
				  <div class="modal-header modal-header-publish">
				  	 <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove icon-2x"></i></button>
				    <img src="" class="img-rounded" alt="" id="modal-publish-img-user">
				    <p class="modal-publish-name-user"><a id="modal-publish-a-name-user" href=""></a></p>
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
					  		<img src="/images/user/<?php echo $yo['photo'];?>" alt="" class="img-rounded modal-publish-img-user-comment">
					  		<textarea class="modal-publish-textarea" id="modal-publish-textarea" placeholder="Comment here"></textarea>
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
		<div class="row r" id="container-masonry">
			<!--SP GROUP-->
			<div class="span3 item-masonry">
				<div class="sp" id="sp-group">
					<div id="header-sp-group">	
					</div>
					<div id="container-sp-pic-group">
						<img src="/images/group/<?php echo $me['photo'];?>" alt="" class="img-polaroid" id="img-sp-pic-group">
					</div>
					<p id="p-sp-name-group"><i class="icon-th-large"></i> <?php echo htmlentities(utf8_decode($me['name']));?></p>
					<hr class="hr-sp"/>
					<p class="p-sp-counts">Followers <br> <?php echo $follows_group;?></p>
					<hr class="hr-sp"/>
					<p class="p-sp-counts">Members <br> <?php echo count($members_group);?></p>
					<hr class="hr-sp"/>
					<div id="sp-buttons">
						<form action="#" method="post">
							<?php if(!empty($follow_group)){ ?>
							<?php if($follow_group['adm']=='1' or $follow_group['cdc']=='1'){$disabled=true;}else{$disabled=false;} ?>
							<button <?php echo $disabled?'disabled':'';?> class="btn btn-info pull-right bts"><i class="icon-heart-empty"></i> Unfollow</button>
							<?php }else{ ?>
							<button class="btn btn-info pull-right bts"><i class="icon-heart"></i> Follow</button>
							<?php } ?>
							<input type="hidden" name="tk" value="<?php echo $tk;?>">
							<input type="hidden" name="groupid" value="<?php echo $me['id'];?>">
						</form>
						<a href="#modal-members" class="btn pull-right bts" id="open-modal-members">Members</a>
					</div>
				</div>
			</div>
			<!--PUBLICATIONS-->
			<?php if(empty($publications)){?>
			<div class="span6 well item-masonry">
				<p style="text-align:center;font-size:13px" class="droid"><i class="icon-exclamation-sign icon-3x" style="color:#15b8d5"></i><br>This group doesn't have publications :'(</p>
			</div>
			<?php } ?>
			<?php for($i=0;$i<count($publications);$i++){?>
			<div class="span3 item-masonry">
				<div class="sp">
					<div class="header-sp">
						<img id="img-sp-user-<?php echo $i;?>" src="/images/user/<?php echo $publications[$i]['user_photo'];?>" class="img-rounded img-sp-user" alt="">
						<p class="name-sp-user"><a id="a-name-sp-user-<?php echo $i;?>" href="<?php echo site_url('profile/'.$publications[$i]['id_user'].'');?>"><?php echo $publications[$i]['user_name'];?></a></p>
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
						<p class="container-count-heart pull-right" id="container-count-heart-<?php echo $i;?>">
							<i class="icon-heart"></i> 
							<span id="span-count-heart-<?php echo $i;?>" class="count-heart"><?php echo $publications[$i]['likes'];?></span>
						</p>
						<p class="container-count-comments pull-right" id="container-count-comments-<?php echo $i;?>">
							<i class="icon-comments"></i>
							<span id="span-count-comments-<?php echo $i;?>" class="count-heart"><?php echo $publications[$i]['comments'];?></span>
						</p>
						</div>
						<p class="sp-text" id="sp-text-<?php echo $i;?>"><?php echo htmlentities(utf8_decode($publications[$i]['message']));?></p>
					</div>
				<form action="">
					<input type="hidden" id="url-comments-<?php echo $i;?>" value="<?php echo site_url('feed/comments/'.$publications[$i]['id'].'/'.$tk.'');?>">
					<input type="hidden" id="url-likes-<?php echo $i;?>" value="<?php echo site_url('feed/likes/'.$publications[$i]['id'].'/'.$tk.'');?>">
					<input type="hidden" id="like-<?php  echo $i;?>" value="<?php  echo $publications[$i]['like']=='1'?'1':'0';?>"> 
				</form>
				</div>
			</div>
			<?php } ?>
		</div>
		<div class="span4 offset4" id="container-load">
			<input type="hidden" value="<?php echo site_url('feed/publications/group/'.$me['id'].'//'.$tk.'') ?>" id="url-publications">
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
    <script src="<?php echo base_url();?>bootstrap/js/bootstrap-scroll-modal.js"></script>
    <script src="http://masonry.desandro.com/masonry.pkgd.min.js"></script>
    <script src="<?php echo base_url();?>js/inview.js"></script>
    <script>
   	var container = $('#container-masonry');
	// initialize
	container.masonry({
	  columnWidth: 240,
	  itemSelector: '.item-masonry'
	});
    </script>
    <script>
    num=0;
    getItems=true;
    $(document).on('ready',function()
    {
    	$('#open-modal-members').on('click',open_modal_members);
    	$('#container-masonry').on('click','div.cl',view); // Init view modal
    	//set create comment 
    	$('#modal-publish-bt-publish').on('click',create_comment);
    	getScroll();
    });
    function open_modal_members()
    {
    	$("#modal-members").modal({dynamic:true});
     	$('#modal-members').modal({show:true});
    }
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
    		 $('#load').bind('inview', function (event, visible){
    		 	if(visible == true && getItems==true)
		    	{
		    		var arr = url_publications_feed.split('/');
					arr[8]=$('#num-load').val();
	    	 		url_publications_feed=arr.join('/');

	    	 		$.get(url_publications_feed).done(function(data){
	    	 			if(data==0)
	    	 			{
	    	 				$('#load').html('<p class="alert alert-info" style="text-align:center;"><i class="icon-minus-sign"></i> <small>No more publications</small></p>');
	    	 				getItems=false; // Don't get items

	    	 			}else{

	    	 				//init mansorry again
				    		var container = $('#container-masonry');
				    		masonryOptions={columnWidth: 240,itemSelector: '.item-masonry'};
				    		container.data('masonry', null); // Clear data
				    		$(data).hide().appendTo(container).fadeIn("slow");
				    		container.masonry( masonryOptions ); // Set Options (Again) 
	    	 				$('#num-load').val(parseInt($('#num-load').val())+1);
	    	 			}
	    	 		});
		    	}
    		 });
    	}
    }
    function view()
    {
    	$("#modal-publish").modal({ dynamic: true });
    	$('#modal-publish').modal('show');
    	num = (this.id.split('-'))[1];
    	modal_publish_load();
    }
    function modal_publish_load()
    {
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