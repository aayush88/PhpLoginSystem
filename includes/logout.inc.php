
<?php  // we simply destroy the session here
if(isset($_POST['submit'])){
   session_start(); // first start the session before destroying
    
   session_unset();
   session_destroy();
   header("Location: ../index.php");
    
}