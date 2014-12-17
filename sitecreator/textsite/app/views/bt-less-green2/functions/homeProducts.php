<?php

$crs = new Carousels( $productItems );
$carouselHtml = $crs->getHtml();

$pdl = new ProductList( $productItems );
$productListHtml = $pdl->getHtml();

/**
 * Home Products
 * -----------------------------------------------
*/
class HomeProducts {
	private $productItems = array();
	protected $carouselItems;
	protected $productList;
	
	function __construct( $productItems ) {
		$this->productItems = $productItems;
		$this->separateItems();
	}
	
	function separateItems() {
		$splice = array_splice( $this->productItems, 0, 3 );
		$this->carouselItems = $splice;
		$this->productList = $this->productItems;
		
		$this->productItems = null;
		unset( $this->productItems );
	}
}


/**
 * Carousels Html
 * -----------------------------------------------
*/
class Carousels extends HomeProducts {
	function __construct( $productItems ) {
		parent::__construct( $productItems );
	}
	
	function getHtml() {
		ob_start();
		$this->setHtml();
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function setHtml()
	{
		?>
<ol class="carousel-indicators">
	<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
	<li data-target="#myCarousel" data-slide-to="1"></li>
	<li data-target="#myCarousel" data-slide-to="2"></li>
</ol>
<div class="carousel-inner">
	<?php $i = 0;
    foreach ( $this->carouselItems as $key => $product ) :
    	extract( $product ); $price = '$' . $price;
    ?>
    <div class="<?php echo $this->setItemActiveClass( $i )?>">
		<div class="container">
			<div class="slide-content">
				<div class="col-md-4"><!-- Image -->
					<?php $this->displayImages( $image_url, $keyword, $permalink ); ?>
				</div>
				<div class="col-md-7"><!-- Content -->
				<?php 
					$this->displayTitle( $keyword, $permalink );
					$this->displayBrand( $brand );
					$this->displayPrices( $price );
					$this->displayDescription( $description );
					$this->displayButton( $keyword, $permalink );
				?>
				</div>
			</div>
		</div>
	</div>
    <?php $i++; ?>
    <?php endforeach; ?>
</div><!-- Carousel-inner -->
<?php
	}

	function setItemActiveClass( $i ) {
		if ( $i == 0 )
			return 'item active';
        else
			return 'item';
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
		<span><?php echo $price ?></span><?php 
	}
	
	function displayDescription( $description ) { ?>
		<p><?php echo helper::limit_words( $description, 30 )?> ...</p><?php
	}
	
	function displayButton( $keyword, $permalink ) { ?>
		<a title="<?php echo $keyword ?>" href="<?php echo $permalink ?>" class="btn btn-default">More Info</a><?php
	}
}


/**
 * Product List Html
 * ---------------------------------------------------
*/
class ProductList extends HomeProducts {
	function __construct( $productItems ) {
		parent::__construct( $productItems );
	}
	
	function getHtml() {
		ob_start();
		$this->setHtml();
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	function setHtml() {
?>
<div class="col-md-12">
	<div style="background-color: #EBEBEB; text-align:center; padding: 1px; margin-bottom: 20px">
		<h3>Lastest Products</h3>
	</div>
</div>
<?php
foreach ( $this->productList as $key => $val ):
	extract( $val );
	$image_url = Helper::image_size( $image_url, '125x125' );
	$price = '$'. $price;
?>	
<div class="col-md-3">
	<div class="items">	
 		<?php
		$this->displayImage( $image_url, $keyword, $permalink );
		$this->displayTitle( $keyword, $permalink );
		$this->displayButton( $keyword, $permalink, $price );
		?>
	</div>
</div>
 
<?php endforeach; 	
	}
	
	function displayImage( $image_url, $keyword, $permalink ) { ?>
		<a title="<?php echo $keyword?>" href="<?php echo $permalink ?>">
			<img src="<?php echo $image_url ?>" alt="<?php echo $keyword?>" />
		</a> <?php
	}
	
	function displayTitle( $keyword, $permalink ) { ?>
		<h4>
			<a title="<?php echo $keyword?>" href="<?php echo $permalink?>"><?php echo $keyword?></a>
		</h4><?php
	}
	
	function displayButton( $keyword, $permalink, $price ) { ?>
		<a title="<?php echo $keyword?>" class="btn btn-danger" href="<?php echo $permalink ?>">
			<i class="glyphicon glyphicon-shopping-cart"></i>
			<?php echo $price ?>
		</a><?php
	}
}


