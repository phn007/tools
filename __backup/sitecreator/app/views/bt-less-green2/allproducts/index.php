<div class="container" id="content-main">

   <div class="row">
      <div class="col-md-12">
         <div class="page-header">
            <h1>All Products</h1>
         </div>
      </div>
   </div>

   <?php foreach ( $products as $data ): ?>
   <div class="row all-products">
      <div class="col-md-2">
         <a title="<?php echo $data['keyword']?>" href="<?php echo $data['product_link']?>"><img src="<?php echo Helper::image_size( $data['image_url'], '75x75' )?>" ></a>
      </div>
      <div class="col-md-8">
         <a title="<?php echo $data['keyword']?>" href="<?php echo $data['product_link']?>"><h3><?php echo $data['keyword']; ?></h3></a>
         <p><?php echo Helper::limit_words( $data['description'], 30 ) ?>...</p>
         <div id="footer-content">
            Category: <a  href="<?php echo $data['cat_link']?>"><?php echo $data['category']?></a>,
            Brand: <a href="<?php echo $data['brand_link']?>"><?php echo $data['brand']?></a>
         </div>
      </div>
      <div class="col-md-2">
         <a class="btn btn-danger" title="<?php echo $data['keyword']?>" href="<?php echo $data['product_link'] ?>">
            <i class="glyphicon glyphicon-shopping-cart"></i>
            $<?php echo $data['price']?>
         </a>
      </div>
   </div>
   <hr>
   <?php endforeach; ?>

   <div class="row">
      <ul class="pager">
         <li <?php echo $menu['first_state'] ?>><?php echo $menu['first']?></li>
         <li <?php echo $menu['prev_state'] ?>><?php echo $menu['prev']?></li>
         <li <?php echo $menu['next_state'] ?>><?php echo $menu['next']?></li>
         <li <?php echo $menu['last_state'] ?>><?php echo $menu['last']?></li>
      </ul>
   </div>
</div>
