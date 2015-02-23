<?php
class Footer {
	function createHtml() {
	?>
		<a href="<?php echo HOME_URL?>">@<?php echo $this->getYear()?> <?php echo $this->getDomain()?></a>
	<?php
	}

	function getDomain() {
		$arr = explode( '/', rtrim( HOME_URL, '/' ) );
		return end( $arr );
	}

	function getYear() {
		date_default_timezone_set("Asia/Bangkok");
		return date("Y");
	}
}