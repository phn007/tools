<?php
class Footer
{
   public static function link( $keyword=null, $permalink=null, $textsearch=null )
   {
      ?>
      <a href="<?php echo HOME_LINK ?>">Home</a>

      | <a href="<?php echo HOME_URL . 'about.html'?>">About</a>
      | <a href="<?php echo HOME_URL . 'contact.html'?>">Contact</a>
      | <a href="<?php echo HOME_URL . 'privacy-policy.html'?>">Privacy Policy</a>


      | <a href="<?php echo HOME_URL . 'categories.html'?>">All Categories</a>
      | <a href="<?php echo HOME_URL . 'brands.html'?>">All Brands</a>
      | <a href="<?php echo HOME_URL . 'allproducts/1-1.html'?>">All Products</a>
      <?php if ( isset( $permalink ) ): ?>
         | <a rel="nofollow" href="<?php echo $permalink?>"><?php echo $keyword?></a>
      <?php endif ?>
      | <a target="_blank" rel="nofollow" href="http://en.wikipedia.org/wiki/fashion">Fashion</a>
      <?php if ( isset( $permalink ) ): ?>
         | <a
            target="_blank" rel="nofollow"
            href="http://www.youtube.com/results?search_query=<?php echo urlencode( $textsearch )?>">
            Youtube
         </a>
      <?php endif ?>
      <?php
   }

   public static function copyright()
   {
      ?>
      <a href="<?php echo HOME_LINK ?>">© <?php echo SITE_NAME ?> <?php echo "2014" //echo date("Y"); ?> All right reserved </a>
      <?php
   }
}
