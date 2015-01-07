<?php
class RelatedProduct
{
   public static function display( $related_products )
   {
      if ( !empty( $related_products ) )
      {
         foreach ( $related_products as $prod )
         {
            $price = '$'.$prod['price'];
         ?>
            <div class="col-md-3">
               <div class="items">
                  <a rel="nofollow" title="<?php echo $prod['keyword']?>" href="<?php echo $prod['goto'] ?>">
                     <img src="<?php echo $prod['image_url']?>">
                  </a>
                  <h4><?php echo $prod['keyword']?></h4>
                  <p><?php echo $prod['description']?> ... </p>
                  <a rel="nofollow" class="btn btn-danger" title="<?php echo $prod['keyword']?>" href="<?php echo $prod['goto'] ?>">
                     <i class="glyphicon glyphicon-shopping-cart"></i>
                     <?php echo $price?>
                  </a>
               </div>
            </div>

         <?php
         }
      }
   }
}
