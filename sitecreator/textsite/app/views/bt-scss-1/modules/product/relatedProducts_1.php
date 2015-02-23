<?php
class RelatedProducts_1 {
	function createHtml( $relatedProducts ) {
		$this->header();
		foreach ( $relatedProducts as $products ) {
			$this->displayProducts( $products );
		}
	}

	function header() {
	?>
		<div class="header"><h2>RELATED PRODUCTS</h2></div>
	<?php
	}	

	function displayProducts( $products ) {
		extract( $products );
	?>
		<div class="item col-xs-12 col-sm-4 col-md-3 col-lg-3">
			<?php echo $this->image( $image_url, $keyword, $goto )?>
			<?php echo $this->title( $keyword, $goto )?>
			<?php echo $this->price( $price )?>
			<?php //echo $this->button( $goto, $keyword )?>
		</div>
	<?php
	}

	function image( $image_url, $keyword, $goto ) {
		$image_url = Helper::image_size( $image_url, '125x125' );
	?>
		<div class="image">
			<a title="<?php echo $keyword?>" href="<?php echo $goto?>">
				<img src="<?php echo $image_url ?>" alt="<?php echo $keyword?>">
			</a>
		</div>
	<?php
	}

	function title( $keyword, $goto ) {
	?>
		<div class="title">
			<h3>
				<a title="<?php echo $keyword?>" href="<?php echo $goto?>"><?php echo $keyword?></a>
			</h3>
		</div>
	<?php	
	}

	function price( $price ) {
	?>
		<div class="price">$<?php echo $price?></div>
	<?php
	}

	function button( $goto, $keyword ) {
	?>
		<div class="button"><a title="<?php echo $keyword?>" href="<?php echo $goto?>">Visit Store</a></div>
	<?php	
	}
}