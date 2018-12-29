<html>
<head>
<meta data-n-head="true" content="width=device-width,initial-scale=1user-scalable=no" name="viewport">
<?php wp_head(); ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-131155819-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-131155819-1');
</script>
</head>
<body>
<nav id="headnav" class="navbar" role="navigation" aria-label="main navigation">
  <div class="container" id=maincont>
  <a id=logo href=/><img src=/header-logo.png id=logo /></a>
  <div id=mainmenu>
  	<?php wp_nav_menu('main'); ?>
	<img src='/burger.png' id=burger />
	<img src='/close@2x.png' id=close />
  </div>
  </div>
</nav>
   <div id=home data-current=home>
