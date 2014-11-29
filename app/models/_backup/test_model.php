<?php

class TestModel extends webtools\AppComponent
{

   function getSiteDescription( $site_category )
   {
      $cpn = $this->component( 'textspinner' );
      echo $cpn->spinSiteDescription( $site_category );

   }

}
