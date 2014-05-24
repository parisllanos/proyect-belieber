<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Belieber.be</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<!-- <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet"> -->
	<link rel="stylesheet" href="<?php echo base_url();?>css/home/style_soon.css">
</head>
<body>
	<div class="navbar navbar-fixed-top navbar-inverse">
	  <div class="navbar-inner">
	    <div class="container">
	    	<div class="logo-navbar"></div>
	    </div>	
	  </div>
	</div>
	<div class="aux-container bgc-celestial first">
		<div class="container">
			<div class="row">
				<div class="span4 offset4 sprofile">
					<img src="/images/user/<?php echo $user_photo;?>" alt="" class="img-polaroid">
					<p class="color-white" id="p-name"><?php echo $user_name; ?></p>
					<p class="color-white"><i class="icon-star icon-white"></i> Official Belieber!</p>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="span6 offset3 text">
				<p id="fromnow" class="segoe color-celestial"><?php echo lang('soon_desde');?> <?php echo $user_name;?> <?php echo lang('soon_eres');?> Belieber!</p>
				<p class="droid color-celestial" id="need"><?php echo lang('soon_necesitamos');?><br> <?php echo lang('soon_siguenos');?> <br/> <?php echo lang('soon_tenemos');?></p>
				<div class="row row-social">
					<div class="span6">
						<div id="pr">
						<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fbeliebers.be&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=true&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe>
						<a style="display:inline;"href="https://twitter.com/teambelieberbe" class="twitter-follow-button" data-show-count="false" data-size="" data-show-screen-name="false">Follow @teambelieberbe</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
						</div>
					</div>
				</div>
				<p class="droid color-celestial" id="soon"><?php echo lang('soon_cambiaremos');?></p>
				<div class="logo-footer"></div>
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
</body>
</html>