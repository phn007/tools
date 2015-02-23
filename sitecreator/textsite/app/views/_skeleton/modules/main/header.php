<?php
class Header {
	function createHtml() {
	?>
	<h2><a href="<?php echo HOME_URL?>"><?php echo SITE_NAME?></a></h2>
	<ul>
		<li><a href="<?php echo HOME_URL . 'about' . FORMAT?>">About</a></li>
		<li><a href="<?php echo HOME_URL . 'contact' . FORMAT?>">Contact</a></li>
		<li><a href="<?php echo HOME_URL . 'privacy-policy' . FORMAT?>">Privacy-Policy</a></li>
	</ul>
	<ul>
		<li><a href="<?php echo HOME_URL . 'categories' . FORMAT?>">Categories</a></li>
		<li><a href="<?php echo HOME_URL . 'brands' . FORMAT?>">Brands</a></li>
	</ul>
	<hr>
	<?php
	}
}