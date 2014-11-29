<?php

class Carousel
{

   /**
    * สำหรับ Theme bt-less-red ( Bootstrap less red )
    * -------------------------------------------------------------------------
   */
   public static function btLessRed( $slide_products )
   {
?>


      <ol class="carousel-indicators">
         <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
         <li data-target="#myCarousel" data-slide-to="1"></li>
         <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>

         <div class="carousel-inner">

            <?php
            $i = 0;
            foreach ( $slide_products as $key => $prod ) :
               extract( $prod );
               $price = '$' . $price;
               /*
               affiliate_url, image_url, keyword, description, category
               price, merchant, brand, permalink
               */
            ?>

            <!-- Item -->
            <?php
               if ( $i == 0 ): echo '<div class="item active">';
               else : echo '<div class="item">';
               endif;
            ?>
               <div class="container">
                  <div class="slide-content">

                     <!-- Image -->
                     <div class="col-md-4">
                        <a title="<?php echo $keyword?>" href="<?php echo $permalink ?>">
                           <img src="<?php echo $image_url?>" alt="<?php echo $keyword?>" />
                        </a>
                     </div>

                     <!-- Content -->
                     <div class="col-md-7">
                        <h1>
                           <a title="<?php echo $keyword?>" href="<?php echo $permalink?>"><?php echo strtoupper( $keyword ) ?></a>
                        </h1>
                        <?php
                           $brand_url = HOME_URL . 'brand/' . Helper::clean_string( $brand ) . '/page-1.html';
                        ?>
                        <div>BRAND: <a title="<?php echo $brand ?>" href="<?php echo $brand_url ?>"> <?php echo strtoupper( $brand )?></a></div>
                        <span><?php echo $price ?></span>
                        <p><?php echo helper::limit_words( $description, 30 )?> ...</p>
                        <a title="<?php echo $keyword ?>" href="<?php echo $permalink ?>" class="btn btn-default">More Info</a>
                     </div>

                  </div>
                  <!-- slide-content -->
               </div>
               <!-- container -->
            </div>
            <!-- item -->

         <?php
            $i++;
            endforeach;
         ?>
      </div>
         <!-- Carousel-inner -->


         <!-- Control -->
         <a class="carousel-control left" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
         </a>

         <a class="carousel-control right" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
         </a>


<?php
   }
}
