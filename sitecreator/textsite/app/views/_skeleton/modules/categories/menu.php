<?php
class Menu {
	function createHtml( $menu ) {
		extract( $menu['url'] );
		extract( $menu['status'] );
		$this->createLink( $firstStatus, $firstUrl, 'First' );
		$this->createLink( $prevStatus, $prevUrl, 'Prev' );
		$this->createLink( $nextStatus, $nextUrl, 'Next' );
		$this->createLink( $lastStatus, $lastUrl, 'Last' );
		echo "<hr>";
	}

	function createLink( $status, $url, $label ) {
		$class = null;
		if ( ! $status ) {
			$url = 'javascript:;';
			$class = 'class="disabled"';
		}
		echo '<a ' . $class . ' href="' . $url . '">' . $label . '</a>';
	}
}