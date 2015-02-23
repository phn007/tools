<?php
class CategoryLinkList {
	function createHtml( $list ) { ?>
		<div class="categories"><?php echo $this->category( $list[0] ); ?></div>
		<div style="clear: both"></div>
		<div class="brands"><?php echo $this->brand( $list[1] ); ?></div>
	<?php
	}

	function category( $categoryList ) {
	?>
		<h3>Categories</h3>
		<?php
		foreach ( $categoryList as $catName => $catLink ):?>
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
				<a title="<?php echo $catName?>" href="<?php echo $catLink?>"><?php echo $catName?></a>
			</div>
		<?php 
		endforeach;
		?>
		<div style="clear: both"></div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<a href="#">All Categories...</a>
			</div>
	<?php
	}

	function brand( $brandList ) {
	?>
		<h3>Brands</h3>
		<?php
		foreach ( $brandList as $brandName => $brandLink ):?>
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
				<a title="<?php echo $brandName?>" href="<?php echo $brandLink?>"><?php echo $brandName?></a>
			</div>
		<?php 
		endforeach;
		?>
		<div style="clear: both"></div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<a href="#">All Brands...</a>
		</div>
		<?php
	}
}


