<?php
class customException extends Exception {
  public function handle() {
    $this->log();
    $this->redirect();
  }

  function redirect() {
    echo "\n";
    echo "Redirect";
  }

  function log() {
    echo $this->getMessage();
    echo "\nFile: " . $this->getFile();
    echo "\nLine: " . $this->getLine(); 
  }
}

$n = 0;

try {
  if ( $n < 1 )
    throw new customException( "Test Send Error Message" );

} catch (customException $e) {
  $e->handle();
}
