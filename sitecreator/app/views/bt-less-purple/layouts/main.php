<?php
/*
   ตัวแปรต่างๆที่ต้องใช้
   ---------------
   $meta กำหนดค่าต่างๆสำหรับ seo ในส่วนของ head
   CSS_PATH path ของไฟล์ css
   JS_PATH path ของไฟล์ javascript
   SITE_DESC description หลักของเว็บไซต์
   HOME_URL url หลักของเว็บไซต์
   SITE_NAME ชื่อเว็บไซต์

   //สถานะ active ของ navmenu ( class="active" )
   $home_menu_state
   $cat_menu_state
   $brand_menu_state

   ตัวแปรของ page product
   ---------------------
   $product_page กำหนดสถานะของ page product
   $keyword
   $permalink
*/

?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <?php echo $meta?>
      <link href="<?php echo CSS_PATH ?>bootstrap-less.css" rel="stylesheet">
      <link href="<?php echo CSS_PATH ?>custom.css" rel="stylesheet">
   </head>
   <body>
      <!--Top Menu -->
      <div class="nav navbar-default top-nav">
         <div class="container">
            <div class="col-md-12">
            <?php
               //ถ้าเป็นหน้าของ page product ให้แสดงข้อความที่เป็น keyword ของสินค้า
               if ( isset( $product_page ) ): ?>
               <a title="<?php echo $keyword?>" href="<?php echo $permalink?>"><?php echo $keyword ?></a>
            <?php
               //ถ้าเป็นหน้าของเพจอื่นๆให้แสดง description หลักของเว็บไซต์
               else: echo SITE_DESC ?>
            <?php endif ?>

               <!-- Top right Menu -->
               <ul class="nav navbar-nav navbar-right top-left-nav">
                  <li><a href="<?php echo HOME_URL?>about.html">About</a></li>
                  <li><a href="<?php echo HOME_URL?>contact.html">Contact</a></li>
                  <li><a href="<?php echo HOME_URL?>privacy-policy.html">Privacy Policy</a></li>
               </ul>
            </div>
         </div>
      </div><!-- Top Menu -->

      <!-- Main Menu -->
      <div class="navbar navbar-inverse" role="navigation">
         <div class="container">
            <div class="col-md-12">
               <!-- Logo -->
               <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                     <span class="sr-only">Toggle navigation</span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                  </button>
                  <a title="<?php echo SITE_NAME?>" class="navbar-brand" href="<?php echo HOME_URL?>">
                     <img src="<?php echo HOME_URL?>images/logo/logo.png" alt="<?php echo SITE_NAME?>" />
                  </a>
               </div>
               <!-- Nav Menu-->
               <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <!-- Nav menu left -->
                  <ul class="nav navbar-nav">
                     <li <?php if ( isset( $home_menu_state) ) echo $home_menu_state ?>>
                        <a href="<?php echo HOME_URL?>">Home</a>
                     </li>
                     <li <?php if ( isset( $cat_menu_state ) ) echo $cat_menu_state ?>>
                        <a href="<?php echo HOME_URL?>categories.html">Categories</a>
                     </li>
                     <li <?php if ( isset( $brand_menu_state ) ) echo $brand_menu_state ?>>
                        <a href="<?php echo HOME_URL?>brands.html">Brands</a>
                     </li>
                     <li <?php if ( isset( $allproducts_menu_state ) ) echo $allproducts_menu_state ?>>
                        <a href="<?php echo HOME_URL?>allproducts/1-1.html">All Products</a>
                     </li>
                  </ul>
                  <!-- Navbar Right Search -->
                  <ul class="nav navbar-nav navbar-right">
                  </ul>
               </div><!-- Nav Menu -->
            </div><!-- col-md-12 -->
         </div><!-- container -->
      </div><!-- Main Menu -->

      <!-- Content -->
      [%LAYOUT_CONTENT%]
      <!-- EndContent -->

      <!-- Footer -->
      <div class="footer">
         <div class="container">
            <div class="col-md-12 text-center">
               <?php
               new Widget( 'bt-less/footer' );
               if ( isset( $product_page ) ) Footer::link( $keyword, $permalink, $textsearch );
               else Footer::link();
               ?>
            </div>
            <div class="col-md-12 text-center">
               <?php
               Footer::copyright();
               ?>
            </div><!-- col-md-12 -->
         </div><!-- container -->
      </div><!-- Footer -->
      <script src="<?php echo JS_PATH ?>jquery.js"></script>
      <script src="<?php echo JS_PATH ?>bootstrap.min.js"></script>

	   <!-- Start of StatCounter -->
    <script type="text/javascript">
      var sc_project=<?php echo SC_PROJECT ?>;
      var sc_invisible=1;
      var sc_security="<?php echo SC_SECURITY ?>";
      var scJsHost = (("https:" == document.location.protocol) ? "https://secure." : "http://www.");
      document.write("<sc"+"ript type='text/javascript' src='" + scJsHost + "statcounter.com/counter/counter.js'></"+"script>");
    </script>
    <noscript>
     <div class="statcounter">
       <a title="websitestatistics" href="http://statcounter.com/free-web-stats/"
        target="_blank"><img class="statcounter"
        src="http://c.statcounter.com/<?php echo SC_PROJECT ?>/0/<?php echo SC_SECURITY ?>/1/"
        alt="website statistics">
        </a>
      </div>
    </noscript>
    <!-- End of StatCounter -->

   </body>
</html>
