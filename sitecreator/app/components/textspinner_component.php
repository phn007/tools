<?php
include APP_PATH . 'extensions/textspinner.class.php';

class textspinnerComponent extends TextSpinner
{
   public function __set( $name, $value )
   {
      $this->{$name} = $value;
   }

   public function __get( $name )
   {
      return $this->{$name};
   }


   function runSpinContent()
   {
      //อ่านชื่อไฟล์ของ text ads
      $files = glob( $this->text_path );
      sort( $files );

      if ( ! isset( $files ) )
         die( "runSpinContent: file not found!!!" );

      $exclude = array( 'spam' );
      foreach ( $files as $path )
      {

         //กำหนดชื่อไฟล์
         $arr = explode( '/', $path );
         $filename = str_replace( '.txt', '', end( $arr ) );

         if ( !in_array( $filename, $exclude ) )
         {
            //Spin
            $text[$filename] = trim( $this->spinText( $this->wordlib_path, $path ) );
         }
      }
      return $text;
   }
   

   function addHtmlTag( $ads )
   {
      $arr = array(
         '<strong>#keyword#</strong>',
         '<em>#keyword#</em>',
         '<u>#keyword#</u>',
      );
      shuffle( $arr );
      return preg_replace('/#keyword#/', $arr[0], $ads, 1 );
   }



   function spamKeyword( $path )
   {
      $text = file( $path );
      shuffle( $text );

      foreach ( $text as $line )
      {
         $data = $this->readWordLibrary( $this->wordlib_path );
         $data = $this->createSpinFormatType( $data );
         $result[] = trim( $this->replaceTextSpin( $data, $line ) );
      }
      return strtolower( implode( ',', $result ) );
   }




}//class
