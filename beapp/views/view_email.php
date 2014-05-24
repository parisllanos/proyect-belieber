<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Belieber.be - Email</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" href="<?php echo base_url();?>css/home/style_email.css">
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
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="span6 offset3 text">
				<p id="fromnow" class="segoe color-celestial"><?php echo lang('email_ultimo');?></p>
				<form action="<?php echo site_url('email'); ?>" method="post">
				<p class="droid color-celestial" id="need"><?php echo lang('email_escribe');?><br/><span class="droid" id="soon"><?php echo lang('email_te');?></span></p>

				<?php echo form_error('email', '<div class="alert alert-info"><a class="close" data-dismiss="alert" href="#">×</a>', '</div>'); ?>
				<div class="input-prepend">
					<span class="add-on">
						<i class="icon-envelope"></i>
					</span>
			        <input type="text" id="email" name="email" placeholder="your@email.com">
			    </div>
				<br />
				<input type="submit" value="<?php echo lang('email_listo');?>" class="btn btn-info" />
				</form>
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