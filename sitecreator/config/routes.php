<?php
//Runs top to bottom ( Most important should be at the top )
#Map::get( '/', 'controller#action' );

//Textsite or Htmlsite

if ( 'textsite' == SITE_TYPE || 'htmlsite' == SITE_TYPE )
{
   //Product Page
   #Map::get( '/' . PROD_ROUTE . '/(.*)', 'product#index' );

   //Shop Redirect to merchant
   Map::get( '/shop/(.*)', 'shop#index' );

   Map::get( '/', 'home#index' );
   Map::get( '/index', 'home#index' );

   Map::get( '/categories', 'category#categories' );
   Map::get( '/brands', 'category#brands' );

   Map::get( '/category/(.*)', 'category#category' );//
   Map::get( '/brand/(.*)', 'category#brand' );

   Map::get( '/allproducts/(.*)', 'allproducts#index' );

   Map::get( '/about.html', 'page#about' );
   Map::get( '/contact.html', 'page#contact' );
   Map::get( '/privacy-policy.html', 'page#privacy_policy' );

   Map::get( '/welcome', 'error#index' );

}

//Api Site
elseif ( 'apisite' == SITE_TYPE )
{
   Map::get( '/shop/(.*)', 'shop#index' );

   Map::get( '/', 'api-home#index' );
   Map::get( '/index', 'api-home#index' );

   Map::get( '/categories', 'api-category#categories' );
   Map::get( '/brands', 'api-category#brands' );

   Map::get( '/category/(.*)', 'api-category#category' );
   Map::get( '/brand/(.*)', 'api-category#brand' );

   Map::get( '/about.html', 'page#about' );
   Map::get( '/contact.html', 'page#contact' );
   Map::get( '/privacy-policy.html', 'page#privacy_policy' );

   Map::get( '/404', 'error#index' );
}
