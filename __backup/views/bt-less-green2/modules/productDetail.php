<?php 
$product = new ProductDetailTemplate( $productDetail, $permalink );
$producDetailHtml = $product->getHtml();

/**
* Product Detail template
*/
class ProductDetailTemplate {

	private $productDetail;

	function __construct( $productDetail, $permalink ) {
		$this->productDetail = $productDetail;
		$this->permalink = $permalink;
	}

	function getHtml() {
		ob_start();
		$this->setHtml();
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}

	function setHtml() {
		echo '<div class="row">';
		$this->displayImage();
		$this->displayDetails();
		echo '</div>';
	}

	function displayDetails() {
		echo '<div class="col-md-8" id="product-detail">';
		$this->title();
		$this->brand();
		$this->priceAndButton();
		$this->description();
		$this->socialShare();
		echo '</div>';
	}

	function socialShare() { ?>
		<div>
		<ul class="list-inline">
			<?php SocialShare::display( $this->productDetail['keyword'], $this->permalink ) ?>
		</ul>
		</div>
	<?php
	}

	function description() {
		$description = $this->productDetail['description'];
		$description = Helper::limit_words( $description, '100' );
	?>
		<p><?php echo $description ?></p><hr>
	<?php
	}

	function priceAndButton() {	
		$price = $this->productDetail['price'];
		$keyword = $this->productDetail['keyword'];
		$goto = $this->productDetail['goto'];?>
<div class="row">
	<div class="col-md-2" id="price">$<?php echo $price ?></div>
	<div class="col-md-10">
		<a rel="nofollow" title="<?php echo $keyword ?>" class="btn btn-warning" href="<?php echo $goto ?>">
		<i class="glyphicon glyphicon-shopping-cart"></i>Visit Store</a>
	</div>
</div>
<hr>
	<?php
	}

	function brand() {
		$brand = $this->productDetail['brand'];
		$brandLink = $this->productDetail['brandLink'];
		if ( !empty( $brand ) ) : ?>
<div>Brand: <a title="<?php echo $brand ?>" href="<?php echo $brandLink ?>"><?php echo $brand ?></a></div>
<hr><?php
		endif;
	}

	function title() { ?>
<h1><?php echo $this->productDetail['keyword'] ?></h1>	
	<?php
	}

	function displayImage() { 
		$keyword = $this->productDetail['keyword'];
		$imageUrl  = $this->productDetail['image_url'];
		$goto    = $this->productDetail['goto'];
	?>
<div class="col-md-4">
	<a rel="nofollow" title="<?php echo $keyword ?>" href="<?php echo $goto ?>">
		<img src="<?php echo $imageUrl?>" alt="<?php echo $keyword?>">
	</a>
</div><?php 
	}
}