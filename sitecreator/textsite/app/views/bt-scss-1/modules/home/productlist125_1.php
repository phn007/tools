<?php
class ProductList125_1 {
	use CategoryLink;

	function createHtml( $products ) {
		$productItems = $products['category-group'];
		foreach ( $productItems as $productFile => $items ) {
			$catName = $this->getCatName( $items );
			$this->displayProductList( $catName, $items );
		}
	}

	function getCatName( $items ) {
		$keys = array_keys( $items ) ;
		return $items[$keys[0]]['category'];
	}

	function displayProductList( $catName, $items ) {
		$this->headTitle( $catName );
		$this->displayItems( $items );
	}

	function displayItems( $items ) {
		$i = 1;
		foreach ( $items as $item ) {
			$this->items( $item );
			if ( $i == 6 ) break;
			$i++;
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

	function headTitle( $catName) {
		$catLink = $this->getCategoryLink( 'category', urlencode( $catName ) );
	?>
		<div class="col-xs-12 col-sm-6 col-md-10 pdl125-1__headtitle">
			<h2 class="pdl125-1__headtitle--linetext"><span><?php echo strtoupper( $catName )?></span></h2>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-2 pdl125-1__headtitle--shop-all"><a href="<?php echo $catLink?>">Shop All</a></div>
	<?php
	}
}