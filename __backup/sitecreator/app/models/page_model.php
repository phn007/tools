<?php
class pageModel extends AppComponent
{
   function getContent( $name )
   {
      $path = APP_PATH . 'static-pages/' . $name . '.html';

      if ( file_exists( $path ) )
      {
         //$domain = str_replace( 'http://', '', HOME_URL );
         $domain = SITE_NAME;
         $email = 'contact@' . str_replace( 'http://', '', HOME_URL );
         $content = file_get_contents( $path );

         $find = array( '{domain}', '{url}', '{email}');
         $replace = array( $domain, HOME_URL . 'index.html', $email );
         $content = str_replace( $find, $replace, $content );
         return $content;
      }
      else
      {
         $msg = '<span style="color:red">File Not Found';
         $msg .= '<pre>' . $path . '</pre></span>';

         die( $msg );
      }

   }

   function getMeta( $title, $page_url )
   {
      $cpn = $this->component( 'head' );

      //Main
      $cpn->robots = 'noindex, follow';
      $cpn->author = AUTHOR;
      $cpn->title = $title;
      $cpn->link = $page_url;
      $cpn->property_locale = 'en_US';
      $cpn->property_type = 'article';
      $cpn->property_title = $title;
      $cpn->property_url = $page_url;
      $cpn->property_site_name = $title . '|' . SITE_NAME;
      $meta = $cpn->getHead();

      $data = null;
      foreach ( $meta as $met )
      {
         $data .= $met . PHP_EOL;
      }
      return $data;
   }
}
