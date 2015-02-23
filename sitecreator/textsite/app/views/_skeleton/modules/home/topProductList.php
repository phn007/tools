<?php
class TopProductList {
	function createHtml( $productItems ) {
	?>
		<h2>Top Product List</h2>
	<?php
		foreach( $productItems['group-one'] as $productFile => $items ) {
			$productFile;
			$key = key( $items );
			echo $catName = $items[$key]['category'];
			echo "<br>";

			foreach ( $items as $productKey => $item ) {
				echo '<a href="' . $item['permalink'] . '">' . $item['keyword'] . '</a>';
				echo "<br>";
			}
		}
		echo "<hr>";
	}
}

