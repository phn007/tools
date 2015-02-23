<?php
class CategoryLinks {
	function createHtml( $categories ) {
		foreach ( $categories as $catName => $url ) {
			echo '<a href="' . $url . '">' . $catName . '</a>';
			echo "<br>";
		}
		echo "<hr>";
	}
}