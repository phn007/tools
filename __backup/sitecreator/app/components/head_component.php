<?php
class HeadComponent
{
   public function __set( $name, $value ) {
      $this->{$name} = $value;
   }

   public function __get( $name ) {
      return $this->{$name};
   }

   function getHead() {
      if ( isset( $this->title ) )
         $head['title'] = '<title>' . $this->title . '</title>';

      if ( isset( $this->keywords ) )
         $head['keywords'] = '<meta name="keywords" content="' . $this->keywords . '" />';

      if ( isset( $this->description ) )
         $head['description'] = '<meta name="description" content="' . $this->description . '" />';

      if ( isset( $this->author ) )
         $head['author'] = '<meta name="author" content="' . $this->author . '" />';

      if ( isset( $this->robots ) )
         $head['robots'] = '<meta name="robots" content="' . $this->robots . '" />';

      if ( isset( $this->link ) )
         $head['link'] = '<link rel="canonical" href="' . $this->link . '" />';

      if ( isset( $this->property_locale ) )
         $head['property_locale'] = '<meta property="og:locale" content="' . $this->property_locale . '" />';

      if ( isset( $this->property_type ) )
         $head['property_type'] = '<meta property="og:type" content="' . $this->property_type . '" />';

      if ( isset( $this->property_title ) )
         $head['property_title'] = '<meta property="og:title" content="' . $this->property_title . '" />';

      if ( isset( $this->property_description ) )
         $head['property_description'] = '<meta property="og:description" content="' . $this->property_description . '" />';

      if ( isset( $this->property_url ) )
         $head['property_url'] = '<meta property="og:url" content="' . $this->property_url . '" />';

      if ( isset( $this->property_site_name ) )
         $head['property_site_name'] = '<meta property="og:site_name" content="' . $this->property_site_name . '" />';

      if ( isset( $this->property_article_tag1 ) )
         $head['property_article_tag1'] = '<meta property="article:tag" content="' . $this->property_article_tag1 . '" />';

      if ( isset( $this->property_article_tag2 ) )
         $head['property_article_tag2'] = '<meta property="article:tag" content="' . $this->property_article_tag2 . '" />';

      if ( isset( $this->property_article_tag3 ) )
         $head['property_article_tag3'] = '<meta property="article:tag" content="' . $this->property_article_tag3 . '" />';

      if ( isset( $this->property_article_section1 ) )
         $head['property_article_section1'] = '<meta property="article:section" content="' . $this->property_article_section1 . '" />';

      if ( isset( $this->property_article_section2 ) )
         $head['property_article_section2'] = '<meta property="article:section" content="' . $this->property_article_section2 . '" />';

      return $head;
   }
}
