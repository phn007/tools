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
	<link rel="stylesheet" href="<?php echo CSS_PATH?>superfish.css">
</head>
<body>
	<header role="banner">
		<div class="snav">
			<div class="container">
				<div class="row">
					<nav>Call us now : 0859779379</nav>
				</div>
			</div>
		</div>
		
		<div class="header-container">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-4"><span class="sitename">Prosperent Theme</span></div>
					<div class="col-xs-12 col-sm-6 col-md-6">Search</div>
					<div class="col-xs-12 col-md-2">Default Pages</div>
				</div>
			</div>
		</div>
		
		<div class="container">
			<div class="row">
				<nav role="navigation" class="navbar navbar-custom">
					<div class="container">
						<div class="navbar-header">
							<button 
								type="button" 
								class="navbar-toggle" 
								data-toggle="collapse" 
								data-target=".navbar-collapse">
								
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<div class="navbar-collapse collapse">
							<ul class="nav navbar-nav ">
								<li class="home active"><a href="#"><span class="icon fa fa-home"></span></a></li>
								<li class="dropdown">
									<a href="#welcome" data-toggle="dropdown" class="dropdown-toggle">
										Categories
										<b class="caret"></b>
									</a>
									<ul class="dropdown-menu" role="menu">
										<li><a href="#">Submenu1</a></li>
										<li><a href="#">Submenu2</a></li>
										<li><a href="#">Submenu3</a></li>
										<li class="divider"></li>
										<li><a href="#">Trash</a></li>
									</ul>
								</li>
								<li><a href="#features">Brands</a></li>
								<li><a href="#impact">Top Search</a></li>
								<li><a href="#signup">Store</a></li>
							</ul>
						</div><!--/.nav-collapse -->
					</div><!--/.container -->
				</nav>
		
			</div>
		</div>
	</header>
	
	<main role="main">
		<div class="container">
			<div class="row">
				[%LAYOUT_CONTENT%]
			</div>
		</div>
	</main>
	
	<footer role="contentinfo">
		<?php if ( isset( $footerHtml ) ) echo $footerHtml; ?>
	</footer>
	<script src="<?php echo JS_PATH?>jquery.js"></script>
	<script src="<?php echo JS_PATH?>plugins.js"></script>
	<script src="<?php echo JS_PATH?>main.js"></script>
</body>
</html>