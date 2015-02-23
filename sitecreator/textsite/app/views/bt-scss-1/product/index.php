<div id="main-content" class="container">
	<div class="breadcrumb">
		<?php if ( isset( $breadcrumbHtml ) ) echo $breadcrumbHtml?>
	</div>
	<div class="product-detail">
		<?php if ( isset( $productDetailHtml ) ) echo $productDetailHtml ?>
	</div>
	<div class="related-products"><?php if ( isset( $relatedProductsHtml ) ) echo $relatedProductsHtml ?></div>
	<div class="navmenu"><?php if ( isset( $navmenuHtml ) ) echo $navmenuHtml ?></div>
</div>
