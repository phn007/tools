<?php
class TopCarousel {
	use DataFormatting;

	function createHtml( $productItems ) {
		$productNumber = count( $productItems );
	?>
	
	<div id="top-carousel" class="carousel slide" data-ride="carousel">
		<!-- Indicators -->
		<!-- <ol class="carousel-indicators"> -->
			<?php //$this->carouselIndicators( $productNumber )?>
		<!-- </ol> -->

		<!-- Contents -->
		<div class="carousel-inner">
			<?php $this->carouselContents( $productItems )?>
		</div>

		<!-- Controls -->
		<a class="left carousel-control" href="#top-carousel" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#top-carousel" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
	<?php
	}

	function carouselIndicators( $productNumber ) {
		for ( $i = 0; $i < $productNumber; $i++ ) {
			if ( $i == 0 ) echo '<li data-target="#top-carousel" data-slide-to="' . $i . '" class="active"></li>';
			else echo '<li data-target="#top-carousel" data-slide-to="' . $i . '"></li>';
		}
	}

	function carouselContents( $productItems ) {
		$i = 0;
		foreach ( $productItems as $item ) {
			if ( $i == 0 ) $this->dataFormatting( $item, true );
			else $this->dataFormatting( $item );
			$i++;
		}
	}
}

trait DataFormatting {
	function dataFormatting( $item, $active = false ) {
		extract( $item );
	?>
		<div class="item <?php echo $this->setActive( $active )?>">
			<div class="product-content">
				<div class="col-xs-12 col-sm-6 col-md-3">
					<?php $this->displayImages( $image_url, $keyword, $permalink )?>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-8">
					<?php 
						$this->displayTitle( $keyword, $permalink );
						$this->displayBrand( $brand );
						echo "<hr>";
						$this->displayPrices( $price );
						echo "<hr>";
						$this->displayDescription( $description );
						$this->displayButton( $keyword, $permalink );
					?>
				</div>
			</div>
		</div>
	<?php
	}

	function setActive( $active ) {
		if ( $active ) return "active";
	}

	function displayImages( $image_url, $keyword, $permalink ) { ?>	
		<a title="<?php echo $keyword?>" href="<?php echo $permalink ?>">
			<img src="<?php echo $image_url?>" alt="<?php echo $keyword?>" />
		</a><?php
	}
	
	function displayBrand( $brand ) {
		$brandUrl = HOME_URL . 'brand/' . Helper::clean_string( $brand ) . '/page-1.html';
	?>
		<div>BRAND: <a title="<?php echo $brand ?>" href="<?php echo $brandUrl ?>"> <?php echo strtoupper( $brand )?></a></div>
	<?php
	}
	
	function displayTitle( $keyword, $permalink ) { ?>
		<h1><a title="<?php echo $keyword?>" href="<?php echo $permalink?>"><?php echo strtoupper( $keyword ) ?></a></h1><?php
	}
	
	function displayPrices( $price ) { ?>
		<span id="price">$<?php echo $price ?></span><?php 
	}
	
	function displayDescription( $description ) { ?>

	<?php 
		$description = html_entity_decode( $description );
	?>
		<p><?php echo helper::limit_words( $description, 30 )?> ...</p><?php
	}
	
	function displayButton( $keyword, $permalink ) { ?>
		<a class="button" title="<?php echo $keyword ?>" href="<?php echo $permalink ?>">More Info</a><?php
	}	
}