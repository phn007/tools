<?php
class CategoryItemList {
	function createHtml( $categoryItems ) {
		$catName = key( $categoryItems );
		$items = $categoryItems[$catName];

		echo "<h1>" . $catName . "</h1>";
		$this->displayItems( $items );
		echo "<hr>";
	}

	function displayItems( $items ) {
		foreach ( $items as $item ) {
			$this->items( $item );
		}
	}

	function items( $item ) {
		extract( $item );
		$keyword = ucfirst( strtolower( $keyword ) );
		$permalink = $item['permalink'];
	?>
		<div>
			<div>
				<a href="<?php echo $permalink?>" title="<?php echo $keyword?>">
					<?php echo Helper::showImage( $image_url, '125x125', $keyword )?>
				</a>
			</div>
			<div>
				<h3><a href="<?php echo $permalink?>" title="<?php echo $keyword?>"><?php echo $keyword ?></a></h3>
				<div><?php echo $brand?></div>
				<div>$<?php echo $price?></div>
				<div>
					<a href="<?php echo $permalink?>" title="<?php echo $keyword?>">More Detail</a>
				</div>
			</div>
		</div>
	<?php
	}
}