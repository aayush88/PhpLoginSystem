<?php

session_start(); // to use session variable

if(isset($_POST['submit'])){
    include 'dbh.inc.php';
    
   
    $uid=mysqli_real_escape_string($conn, $_POST['uid']);
    $pwd=mysqli_real_escape_string($conn, $_POST['pwd']);  
     /*
    $uid=$_POST['uid'];
    $pwd=$_POST['pwd']; */
    
    //Error handlers
    //Check if inputs are empty
    
    if(empty($uid) || empty($pwd)){
        header("Location: ../index.php?login=empty");
        exist();
        
    } else{ // checking if username exists in database
        $sql="SELECT * FROM users WHERE user_uid='$uid' OR user_email='$uid'";
        // now we run this sql in database as
        $result=mysqli_query($conn, $sql);
        $resultCheck= mysqli_num_rows($result); // this returns the parameter showing how many rows were matched
        if($resultCheck < 1){ // <1 means we have no result in database ie there is no such username that exists. so now we display error message
            header("Location: ../index.php?login=error");
            exist();               
        } else {
            if($row=mysqli_fetch_assoc($result)){ // ie if username matched with the names in the databse
                // now we also have to check if the password is correct. First we have to dehatch the password
                
                //De-hashing the password
                $hashedPwdCheck=password_verify($pwd, $row['user_pwd']); // checks the entered password(ie $pwd) with database password ie (user_pwd)
                if($hashedPwdCheck == false){
                    header("Location: ../index.php?login=errorPassword");
                    exist();
                } elseif($hashedPwdCheck == true){ // dont user else here coz we are checking between two things here it true or false
                    // login the user here. for that we use global defined variable named session
                    $_SESSION['u_id'] = $row['user_id'];
                    $_SESSION['u_first'] = $row['user_first'];
                    $_SESSION['u_last'] = $row['user_last'];
                    $_SESSION['u_email'] = $row['user_email'];
                    $_SESSION['u_uid'] = $row['user_uid'];
                    header("Location: ../index.php?login=sucess");
                    exist();
                }
            }
        }
        
        
    }
} else {
    header("Location: ../index.php?login=error");
    exist();
}