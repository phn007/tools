<?php
//Shop Redirect to merchant
Map::get( '/', 'home#index' );

Map::get( '/categories' . FORMAT, 'categories#categories' );
Map::get( '/brands' . FORMAT, 'categories#brands' );
Map::get( '/categories/(.*)' . FORMAT, 'categories#categories' );
Map::get( '/brands/(.*)' . FORMAT, 'categories#brands' );

Map::get( '/category/(.*)' . FORMAT, 'category#category' );
Map::get( '/brand/(.*)' . FORMAT, 'category#brand' );

Map::get( '/about' . FORMAT, 'staticpage#about' );
Map::get( '/contact' . FORMAT, 'staticpage#contact' );
Map::get( '/privacy-policy' . FORMAT, 'staticpage#privacy' );

Map::get( '/html-sitemap' . FORMAT, 'htmlSitemap#index' );


Map::get( '/search/(.*)', 'search#index' );


Map::get( '/shop/(.*)', 'shop#index' );
Map::get( '/error', 'error#index' );
Map::get( '/(.*)', 'product#index' );
