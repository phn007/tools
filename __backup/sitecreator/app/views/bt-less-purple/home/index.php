<div class="container" id="content-main">

   <div id="myCarousel" class="carousel slide">
      <?php
         new Widget( 'bt-less/carousel' );
         Carousel::btLessRed( $slide_products );
      ?>
   </div>

   <!-- New Arrival -->
   <div class="row" >
      <?php
         new Widget( 'bt-less/itemlist' );
         ItemList::newarrivals( $product_list );
      ?>
   </div>

</div>
<div style="background-color:#34373B; color:#fff">
   <div class="container">
      <div class="col-md-12">
         <h3>Categories/Brands</h3>

      </div>
   </div>
</div>

<?php if ( isset( $home_page ) ): ?>
<div id="top-footer"><!-- top footer -->
   <div class="container">
      <div class="col-md-12">

         <h4>CATEGORIES</h4>
         <!-- Category List -->
         <div class="row">
         <?php
            new Widget( 'bt-less/urlslist' );
            UrlsList::homeCategoryList( $category_list );
         ?>
         </div><!-- row -->

         <div><a title="categories" href="<?php echo HOME_URL?>categories.html">More...</a></div>
         <hr>

         <h4>BRANDS</h4>
         <!-- Brand List -->
         <div class="row">
         <?php
            UrlsList::homeBrandList( $brand_list );
         ?>
         </div><!-- row -->
         <div><a title="brands" href="<?php echo HOME_URL?>brands.html">More...</a></div>


      </div><!-- col-md-12 -->
   </div><!-- container -->
</div><!-- top footer -->
<?php endif ?>
