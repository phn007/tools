<?php
class UrlsList
{
   public static function homeCategoryList( $category_list )
   {
      foreach ( $category_list as $cat ) :
         $cat_name = $cat['cat_name'];
         $cat_url  = $cat['cat_url'];
      ?>
         <div class="col-md-3"><a title="<?php echo $cat_name?>" href="<?php echo $cat_url ?>"><?php echo $cat_name ?></a></div>
      <?php
      endforeach;
   }

   public static function homeBrandList( $brand_list )
   {
      foreach ( $brand_list as $cat) :
         $brand_name = $cat['cat_name'];
         $brand_url  = $cat['cat_url'];
      ?>
      <div class="col-md-3"><a title="<?php echo $brand_name?>" href="<?php echo $brand_url ?>"><?php echo $brand_name ?></a></div>
      <?php
      endforeach;
   }
}
