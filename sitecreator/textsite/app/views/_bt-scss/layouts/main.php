<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>MyWebsite</title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo CSS_PATH?>application.css">
</head>
<body>
	<header role="banner">
		<?php echo $headerHtml; ?>
	</header>
	[%LAYOUT_CONTENT%]
	<footer role="contentinfo">
		<?php echo $footerHtml; ?>
	</footer>
	<script src="<?php echo JS_PATH?>jquery.min.js"></script>
	<script src="<?php echo JS_PATH?>plugins.js"></script>
	<script src="<?php echo JS_PATH?>main.js"></script>
</body>
</html>