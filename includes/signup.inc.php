<?php
  
  if(isset($_POST['submit'])){  // coz of name= sumit. This checks whether we have clicked submit button or not
      include_once 'dbh.inc.php';
        
      $first=mysqli_real_escape_string($conn, $_POST['first']); // firstname in signup file is named as first
      $last=mysqli_real_escape_string($conn, $_POST['last']); 
      $email=mysqli_real_escape_string($conn, $_POST['email']); 
      $uid=mysqli_real_escape_string($conn, $_POST['uid']); 
      $pwd=mysqli_real_escape_string($conn, $_POST['pwd']); 
      
      
      
      // Error handlers
      // Check for empty fields
      
      if(empty($first) || empty($last) || empty($email) || empty($uid) || empty($pwd) ){
          header("Location: ../signup.php?signup=empty"); // here ? is used to add extra detail(ie sentence or message) in url
          exit(); // here exit is necessary coz to stop exceution of else statement
          
      } else{
          // check if input characters are valid
          if(!preg_match("/^[a-zA-Z]*$/", $first) || !preg_match("/^[a-zA-Z]*$/", $last) ){ // this preg function checks if there is unusal characters  that are not allowed as a input. inside preg we define which characters are allowed
              header("Location: ../signup.php?signup=invalid");
              exit();
          } else{
              // check if email is valid
              if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                  header("Location: ../signup.php?signup=InvalidEmail");
                  exit();
              } else{
              	// we cant let the user type same username which is already taken ie which already exists in database
              	$sql= "SELECT * FROM users WHERE user_uid= '$uid'";
              	$result=mysqli_query($conn, $sql);
                $resultCheck=mysqli_num_rows($result);
                  
                  if($resultCheck > 0){ // if > 0 then the user name already exists 
                     header("Location: ../signup.php?signup=usernameTaken");
                     exit(); 
                  } else {  
                      // hashing the password ie making non readable in databse
                      $hashedPwd= password_hash($pwd, PASSWORD_DEFAULT);
                      // Insert the user into the database
                      $sql= "INSERT INTO users (user_first, user_last, user_email, user_uid,user_pwd) VALUES ('$first', '$last', '$email', '$uid','$hashedPwd');";  // you can use the same variable name here ie sql as before coz only one else block will execute anyway 
                      // user_first, ... are the columns name we declared in database
                      
                      mysqli_query($conn, $sql);  // this puts the input data in database
                      header("Location: ../signup.php?signup=Sucess");
                      exit();
                  }

              }
          }
        
      }
      
  } else{
      header("Location: ../signup.php");
      exit(); // not necessary here coz there are no other statements to execute anyway
  }
