<?php
/**
 * Runs top to bottom ( Most important should be at the top )
 * Map::get( '/', 'controller#action' );
*/

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