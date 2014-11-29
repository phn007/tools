<?php
class SocialShare
{
   public static function display( $keyword, $url )
   {
      $title = rawurlencode( $keyword );
      $img_path = HOME_URL . 'images/social-share/';

      #Share on facebook
      $facebook = 'http://www.facebook.com/share.php?u=' . $url;
      //$fb_img = 'http://freecode.siamfocus.com/social_share/facebook.png';
      $fb_img = $img_path . 'facebook.png';
      #Share on twitter
      $twitter = 'http://twitter.com/share?text=' . $url;
      //$twt_img = 'http://freecode.siamfocus.com/social_share/twitter.png';
      $twt_img = $img_path . 'twitter.png';

      #Email a friend
      $email = 'http://www.freetellafriend.com/tell/?heading=Share+This+Article&bg=1&option=email&url=' . $url;
      $email_img = $img_path . 'email.png';

      #Share on Blogger
      $blogger = 'http://www.blogger.com/blog_this.pyra?t&u=' . $url . '&n=' . $title . '&pli=1';
      $bg_img = $img_path . 'blogger.png';

      #Share on Delicious
      $delicious = 'http://del.icio.us/post?url='. $url .'&title=' . $title;
      $del_img = $img_path . 'delicious.png';

      #Share on Digg
      $digg = 'http://digg.com/submit?url=' . $url . '&title=' . $title;
      $digg_img = $img_path . 'digg.png';

      #Share on Google
      $google = 'http://www.google.com/bookmarks/mark?op=add&bkmk=' . $url . '&title=' . $title;
      $gg_img = $img_path . 'google.png';

      #Share on Myspace
      $myspace = 'http://www.myspace.com/Modules/PostTo/Pages/?u=' . $url . '&t=' . $title . '&c=' . $url;
      $mp_img = $img_path . 'myspace.png';

      #Share on StumbleUpon
      $stumble = 'http://www.stumbleupon.com/submit?url=' . $url . '&title=' . $title;
      $stb_img = $img_path . 'stumbleupon.png';

      #Share on Reddit
      $reddit = 'http://reddit.com/submit?url=' . $url . '&title=' . $title;
      $red_img = $img_path . 'reddit.png';

     $list = null;
      //$list = '<ul>';
      $list .= '<li><a rel="nofollow" title="Share on facebook" href="' . $facebook . '"><img src="'  . $fb_img . '" alt="Share on facebook"></a></li>';
      $list .= '<li><a rel="nofollow" title="Share on twitter"  href="' . $twitter . '"><img src="'   . $twt_img . '" alt="Share on twitter"></a></li>';
      $list .= '<li><a rel="nofollow" title="Email"             href="' . $email . '"><img src="'     . $email_img . '" alt="Email"></a></li>';
      $list .= '<li><a rel="nofollow" title="Share on blogger"  href="' . $blogger . '"><img src="'   . $bg_img . '" alt="Share on blogger"></a></li>';
      $list .= '<li><a rel="nofollow" title="Share on delicious" href="' . $delicious . '"><img src="' . $del_img . '" alt="Share on delicious"></a></li>';
      $list .= '<li><a rel="nofollow" title="Share on digg" href="' . $digg . '"><img src="'      . $digg_img . '" alt="Share on digg"></a></li>';
      $list .= '<li><a rel="nofollow" title="Share on google" href="' . $google . '"><img src="'    . $gg_img . '" alt="Share on google"></a></li>';
      $list .= '<li><a rel="nofollow" title="Share on myspace" href="' . $myspace . '"><img src="'   . $mp_img . '" alt="Share on myspace"></a></li>';
      $list .= '<li><a rel="nofollow" title="Share on stumble" href="' . $stumble . '"><img src="'   . $stb_img . '" alt="Share on stumble"></a></li>';
      $list .= '<li><a rel="nofollow" title="Share on reddit" href="' . $reddit . '"><img src="'    . $red_img . '" alt="Share on reddit"></a></li>';
      //$list .='</ul>';

      echo $list;
   }

}
