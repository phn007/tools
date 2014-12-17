<div class="container" id="content-main">
	<div id="myCarousel" class="carousel slide"><?php echo $carouselHtml ?></div>
	<div class="row" ><?php echo $productListHtml ?></div>
</div>

<div style="background-color:#34373B; color:#fff">
   <div class="container">
      <div class="col-md-12"><h3>Categories/Brands</h3></div>
   </div>
</div>

<?php if ( isset( $home_page ) ): ?>
<div id="top-footer"><!-- top footer -->
	<div class="container">
		<div class="col-md-12">
			<h4>CATEGORIES</h4>
			<!-- Category List -->
			<div class="row">Category List</div><!-- row -->
			<div><a title="categories" href="<?php echo HOME_URL?>categories.html">More...</a></div>
			<hr>
			<h4>BRANDS</h4>
			<div class="row">Brand List</div><!-- row -->
			<div><a title="brands" href="<?php echo HOME_URL?>brands.html">More...</a></div>
		</div><!-- col-md-12 -->
	</div><!-- container -->
</div><!-- top footer -->
<?php endif ?>
