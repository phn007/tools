<?php
class ProductList {
	function createHtml( $products ) {
	?>
		<h2>Product List</h2>
	<?php
		$productItems = $products['category-group'];
		foreach ( $productItems as $productFile => $items ) {
			$key = key( $items );
			$catName = $items[$key]['category'];
			echo '<h3>' . $catName . '</h3>';
			foreach ( $items as $productKey => $item ) {
				echo '<a href="' . $item['permalink'] . '">' . $item['keyword'] . '</a>';
				echo "<br>";
			}
		}
		echo "<hr>";
	}
}