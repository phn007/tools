<?php
use libs\html\map;
/**
 * Runs top to bottom ( Most important should be at the top )
 * Map::get( '/', 'controller#action' );
*/

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