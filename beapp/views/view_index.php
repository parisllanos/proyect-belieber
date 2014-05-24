<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Belieber.be</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo base_url();?>css/home/style_index.css">
	<meta property="og:title" content="Belieber"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="http://www.belieber.be"/>
    <meta property="og:image" content="http://www.belieber.be/images/logo_tag_facebook.png"/>
    <meta property="og:site_name" content="Belieber"/>
    <meta property="fb:admins" content="100002189287259"/>
    <meta property="og:description" content="<?php echo lang('facebook_description');?>"/>
	<meta name="keywords" content="belieber, Belieber, beliebers, Beliebers, justin bieber, Justin Bieber" /> 
	<meta name="description" content="<?php echo lang('home_description');?>" /> 
</head>
<body>
    <div class="logo"></div>
    <p class="intro droid">Discovers that it happens with all the beliebers of the world <br/> <a href="<?php echo site_url('loginfacebook');?>" class="btn btn-warning sg">Sign in</a></p>
	<div class="container">
		<div class="row">
			<span class="span12" >
				<img src="/images/map.png" alt="">
			</span>
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