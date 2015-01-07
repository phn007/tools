<?php
class ErrorModel extends AppComponent
{
   function getMeta()
   {
      $cpn = $this->component( 'head' );

      //Main
      $cpn->robots = 'noindex, follow';
      $cpn->author = AUTHOR;
      $cpn->title = 'Welcome|' . SITE_NAME;
      $cpn->link = HOME_URL . '404.html';
      $cpn->property_locale = 'en_US';
      $cpn->property_type = 'article';
      $cpn->property_title = '404|' . SITE_NAME;
      $cpn->property_url = HOME_URL . '404.html';
      $cpn->property_site_name = SITE_NAME;
      $meta = $cpn->getHead();

      $data = null;
      foreach ( $meta as $met )
      {
         $data .= $met . PHP_EOL;
      }
      return $data;
   }
}
