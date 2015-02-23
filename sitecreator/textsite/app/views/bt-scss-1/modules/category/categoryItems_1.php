<?php
class CategoryItems_1 {
	function createHtml( $categoryItems ) {
		$catName = key( $categoryItems );
		$items = $categoryItems[$catName];

		echo "<h1>" . $catName . "</h1>";
		$this->displayItems( $items );

	}

	function displayItems( $items ) {
		foreach ( $items as $item ) {
			$this->items( $item );
		}
	}

	function items( $item ) {
		extract( $item );
		$image_url = Helper::image_size( $image_url, "125x125" );
		$keyword = ucfirst( strtolower( $keyword ) );
		$permalink = $item['permalink'];
		$blank = IMG_PATH . 'blank.png';
	?>
		<div class="col-xs-12 col-sm-6 col-md-4 pdl125-1__item">
			<div class="col-xs-12 col-sm-6 col-md-5 pdl125-1__item__img">
				<a href="<?php echo $permalink?>" title="<?php echo $keyword?>">
					<img src="<?php echo $blank?>" data-echo="<?php echo $image_url?>" alt="<?php echo $keyword?>">
				</a>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-7 pdl125-1__item__list">
				<h3><a href="<?php echo $permalink?>" title="<?php echo $keyword?>"><?php echo $keyword ?></a></h3>
				<div><?php echo $brand?></div>
				<div class="pdl125-1__item__list__price">$<?php echo $price?></div>
				<div class="pdl125-1__item__list__btn">
					<a href="<?php echo $permalink?>" title="<?php echo $keyword?>">More Detail</a>
				</div>
			</div>
		</div>
	<?php
	}
}