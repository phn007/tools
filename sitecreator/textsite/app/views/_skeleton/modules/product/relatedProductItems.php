<?php
class RelatedProductItems {
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
		<?php echo $this->image( $image_url, $keyword, $goto )?>
		<?php echo $this->title( $keyword, $goto )?>
		<?php echo $this->price( $price )?>
		<?php //echo $this->button( $goto, $keyword )?>
	<?php
	}

	function image( $image_url, $keyword, $goto ) {
	?>
		<div class="image">
			<a title="<?php echo $keyword?>" href="<?php echo $goto?>">
				<?php echo Helper::showImage( $image_url, '125x125', $keyword )?>
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