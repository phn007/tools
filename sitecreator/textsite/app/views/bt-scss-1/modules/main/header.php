<?php
class Header {
	function createHtml() {
		$this->snav();
		$this->headerType1();
		$this->navbar();
	}

	function snav() {?>
		<div class="snav">
		<div class="container">
			<div class="row">
				<nav>Call us now : 0859779379-1111</nav>
			</div>
		</div>
	</div>
	<?php
	}

	function headerType1() {
	?>
	<div class="header-container">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-4"><span class="sitename">Prosperent Theme</span></div>
					<div class="col-xs-12 col-sm-6 col-md-6">Search</div>
					<div class="col-xs-12 col-md-2">Default Pages</div>
				</div>
			</div>
		</div>
	<?php
	}

	function navbar() {?>
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
	<?php
	}
}