<?php
class CategoryLinkList {
	function createHtml( $list ) { 
	?>
		<h2>CategoryLinks</h2>
	<?php
		foreach ( $list[0] as $catName => $url ) {
			echo '<a href="' . $url . FORMAT . '">' . $catName . '</a>';
			echo "<br>";
		}
		echo "<hr>";

		echo "<h2>BrandLinks</h2>";
		foreach ( $list[1] as $catName => $url ) {
			echo '<a href="' . $url . FORMAT . '">' . $catName . '</a>';
			echo "<br>";
		}
		echo "<hr>";

	}
}


