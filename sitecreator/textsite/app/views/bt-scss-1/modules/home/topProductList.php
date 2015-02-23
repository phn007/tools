<?php
class TopProductList {
	use BestSellerProducts;
	use RecommendedProducts;

	function createHtml( $productItems ) {?>
		<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 bestsell">
			<?php echo $this->createBestSellerHtml( $productItems['group-one'] ); ?>
		</div>
		<div class="col-xs-12 col-sm-8 col-md-9 col-lg-9 recomm">
			<?php echo $this->createRecommendedProducts( $productItems['group-two'] ); ?>
		</div>
	<?php
	}
}

trait BestSellerProducts {
	function createBestSellerHtml( $productItems ) {
		$productFile = key( $productItems );

		echo '<div><h2>Latest Products</h2></div>';
		$i = 0;
		foreach ( $productItems[$productFile] as $item ):
			if ( ++$i > 7 ) break;
		?>
		<div class="row bestsell__item">
			<div class="col-xs-4 col-sm-6 col-md-4 col-lg-4 bestsell__item--img">
				<?php $this->displayImage( $item );?>
			</div>
			<div class="col-xs-8 col-sm-6 col-md-8 col-lg-8 bestsell__item--content">
				<?php $this->displayContent( $item );?>
			</div>
		</div>
		<?php endforeach; 
	}

	function displayImage( $item ) {
		$image_url = Helper::image_size( $item['image_url'], '75x75' );
		$blank = IMG_PATH . 'blank.png';
		$keyword = $item['keyword'];
		$permalink = $item['permalink'];
		$img = '<a title="' . $keyword . '" href="' . $permalink . '">';
		$img .= '<img src="' . $blank . '" data-echo="' . $image_url . '" alt="' . $keyword . '">';
		$img .= '</a>';
		echo $img;
	}

	function displayContent( $item ) {
		extract( $item );
	?>
		<div>
			<h3>
				<a href="<?php echo $permalink?>" title="<?php echo $keyword?>">
				<?php echo $keyword ?>
				</a>
			</h3>
		</div>
		<div class="bestsell__item--content--price">$<?php echo $price?></div>
		<hr>
		<a class="bestsell__item--content--btn" href="<?php echo $permalink?>" title="<?php echo $keyword ?>">More Detail</a>
	<?php
	}
}

trait RecommendedProducts {
	function createRecommendedProducts( $productItems ) {
		$productFile = key( $productItems );
		echo '<div><h2>Recommended Products</h2></div>';
		$i = 1;
		foreach ( $productItems[$productFile] as $item ) {
			extract( $item );
			$image_url = Helper::image_size( $image_url, '250x250' );
			$blank = IMG_PATH . 'blank.png';
		?>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
			<div class="view effect">
				<img src="<?php echo $blank?>" data-echo="<?php echo $image_url?>" alt="">
		    	<div class="mask">
		    		<h2><?php echo $keyword?></h2>
		    		<p>$<?php echo $price?></p>
		    		<a title="<?php echo $keyword?>" href="<?php echo $permalink?>" class="info">Read More</a>
		    	</div>
			</div>
		</div>

		<?php
			if ( $i == 9 ) break;
			$i++;
		}
	}
}