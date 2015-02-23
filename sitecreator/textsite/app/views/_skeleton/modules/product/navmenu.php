<?php
class Navmenu {

	function createHtml( $navmenu ) {
		extract( $navmenu['url'] );
		extract( $navmenu['state'] );
		$this->createLink( $first, $firstUrl, 'First' );
		$this->createLink( $prev, $prevUrl, 'Prev' );
		$this->createLink( $next, $nextUrl, 'Next' );
		$this->createLink( $last, $lastUrl, 'Last' );
	}

	function createLink( $state, $url, $label ) {
		$class = null;
		if ( ! $state ) {
			$url = 'javascript:;';
			$class = 'class="disabled"';
		}
		echo '<a ' . $class . ' href="' . $url . '">' . $label . '</a>';
	}
}