<?php
class Header2 {
	function createHtml() {?>
		<nav role="navigation" class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="index.html"><img src="<?php echo IMG_PATH?>logo.png" alt="Bootstrappin'" width="120"></a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li class="active"><a href="#welcome">Top Search</a></li>
						<li><a href="#features">Top Merchants</a></li>
						<li><a href="#impact">Impact</a></li>
						<li><a href="#signup">Sign Up</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div><!--/.container -->
		</nav>
	<?php
	}
}