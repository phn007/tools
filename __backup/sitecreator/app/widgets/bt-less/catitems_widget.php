<?php
class CatItems
{

   public static function showItems( $list )
   {
   ?>
   <div id="show_cat" class="col-md-3">
      <div class="panel panel-default">
           <div class="panel-heading">
            <?php echo $list['title']?>
            <a title="<?php echo $list['cat_title'] ?>" href="<?php echo $list['cat_url']?>"><?php echo $list['cat_title']?></a>
         </div>
              <div class="panel-body">
                <a title="<?php echo $list['keyword']?>" href="<?php echo $list['url']?>">
                   <img src="<?php echo $list['image_url'] ?>" alt="<?php echo $list['keyword'] ?>" width="100">
                </a>
                <div><span class="badge"><?php echo $list['price']?></span></div>

               <div id="title">
                   <a title="<?php echo $list['keyword']?>" href="<?php echo $list['url']?>">
                      <?php echo $list['keyword'];?>
                   </a>
               </div>

             </div>
           </div>
      </div>
   <?php
   }
}
