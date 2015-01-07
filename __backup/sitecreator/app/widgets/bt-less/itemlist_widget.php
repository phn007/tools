<?php
class ItemList
{
   public static function newarrivals( $product_list )
   {
   ?>
      <div class="col-md-12">
         <div style="background-color: #EBEBEB; text-align:center; padding: 1px; margin-bottom: 20px">
            <h3>Lastest Products</h3>
         </div>
      </div>

         <?php
         foreach ( $product_list as $key => $val ):
            extract( $val );
            /*
            affiliate_url, image_url, keyword, description, category
            price, merchant, brand, permalink
            */

            $image_url = Helper::image_size( $image_url, '125x125' );
            $price = '$'. $price;
         ?>

         <div class="col-md-3">
            <div class="items">
               <a title="<?php echo $keyword?>" href="<?php echo $permalink ?>">
                  <img src="<?php echo $image_url ?>" alt="<?php echo $keyword?>" />
               </a>
               <h4>
                  <a title="<?php echo $keyword?>" href="<?php echo $permalink?>"><?php echo $keyword?></a>
               </h4>
               <a title="<?php echo $keyword?>" class="btn btn-danger" href="<?php echo $permalink ?>">
                  <i class="glyphicon glyphicon-shopping-cart"></i>
                  <?php echo $price ?>
               </a>
            </div>
         </div>

         <?php
         endforeach;
         ?>
   <?php
   }
}
