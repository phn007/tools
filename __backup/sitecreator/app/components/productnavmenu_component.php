<?php
class ProductNavmenuComponent
{
   public function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   public function __get( $name )
   {
      return $this->{$name};
   }


   public function menu( $files )
   {
      #next page - product key
      $this->set_next( $files, $this->key );
      next( $files );
      $next = key( $files );

      #prev page - product key
      $this->set_prev( $files, $this->key );
      prev( $files );
      $prev = key( $files );

      #first - product key
      reset( $files );
      $first = key( $files );

      #last - product key
      end( $files );
      $last = key( $files );

      /*
       * create menu link
       * ----------------------------------------------------------------------
      */

      $first_url = $this->set_url( $files, $first );
      $prev_url  = $this->set_url( $files, $prev );
      $next_url  = $this->set_url( $files, $next );
      $last_url  = $this->set_url( $files, $last );

      $files = null;
      unset( $files );

      if ( ! empty( $prev ) )
      {
         $menu['link_first'] = '<a href="' . $first_url . '">' . $this->lbl_first . '</a>';
         $menu['link_prev']  = '<a href="' . $prev_url . '">' . $this->lbl_prev . '</a>';

      }
      else
      {
         $menu['link_first'] = '<a href="' . $first_url . '">' . $this->lbl_first . '</a>';
         $menu['link_prev']  = '<a href="' . $first_url . '">' . $this->lbl_prev . '</a>';
      }

      if ( ! empty( $next ) )
      {
         $menu['link_next']  = '<a href="' . $next_url . '">' . $this->lbl_next . '</a>';
         $menu['link_last']  = '<a href="' . $last_url . '">' . $this->lbl_last . '</a>';
      }
      else
      {
         $menu['link_next']  = '<a href="' . $last_url . '">' . $this->lbl_next . '</a>';
         $menu['link_last']  = '<a href="' . $last_url . '">' . $this->lbl_last . '</a>';
      }

      //Pager State Class of bootstrap
      $menu['first_state'] = ( empty( $prev ) ) ? 'class="disabled"' : NULL;
      $menu['prev_state']  = ( empty( $prev ) ) ? 'class="disabled"' : NULL;
      $menu['next_state']  = ( empty( $next ) ) ? 'class="disabled"' : NULL;
      $menu['last_state']  = ( empty( $next ) ) ? 'class="disabled"' : NULL;

      return $menu;
   }


   private function set_url( $files, $product_key )
   {
      //init var
      $product_data = null;

      $product_file  = $this->file;

      if ( isset( $files[ $product_key ] ) )
         $product_data  = $files[ $product_key ];

      $category_name = $product_data['category'];
      $cat_slug = Helper::clean_string( $category_name );

      $url = Helper::get_permalink( $product_file, $product_key, $cat_slug );

      return $url;
   }




   private function set_next ( &$array, $key )
   {
      reset( $array );
      while ( key( $array ) !== $key )
      {
         if ( next( $array ) === false )
            throw new Exception('Invalid key');
      }
   }




   private function set_prev ( &$array,$key )
   {
      end( $array );
      while ( key( $array ) !== $key )
      {
         if ( prev( $array ) === false )
            throw new Exception('Invalid key');
      }
   }
}
