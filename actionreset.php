<?php

//check if user got the this page by the right way
if (isset($_POST["reset-password-submit"])) {
    
    $selector = $_POST["selector"];
    $validator = $_POST["validator"];
    $password = $_POST["pwd"];
    $passwordRepeat = $_POST["pwd-repeat"];
    
    //check if the user left the pass empty or if passes are not the same
    if (empty($password) || empty($passwordRepeat)) {
        header("Location: reset.php?newpwd=pwdempty"); //reset page with an message
        exit();
    } else if ($password != $passwordRepeat) {
        header("Location: reset.php?newpwd=pwdnotsame"); //reset page with an update message (won't work cause the tokens arent included to the url
        exit();
    }
    
    //check for the tokens
    $currentDate = date("U");
    
    //include the database connection
    require 'dbh.inc.php';

    $sql = "SELECT * FROM pwdReset WHERE pwdResetSelector=? AND pwdResetExpires >= ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "There was an error!";
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $selector, $currentDate); //checking if the database is picking the correct token using the selector
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        if (!$row = mysqli_fetch_assoc($result)) {
            echo "You need to re-submit your reset request!";
            exit();
        } else {
            
            $tokenBin = hex2bin($validator);
            $tokenCheck = password_verify($tokenBin, $row["pwdResetToken"]);
            
            if ($tokenCheck === false) {
                echo "You need to re-submit your reset request!";
                exit();
            } elseif ($tokenCheck === true) {
                
                $tokenEmail = $row['pwdResetEmail'];
                
                $sql = "SELECT * FROM users WHEERE emailUsers=?;"; //change necessary: this needs to match the email saved in login database
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    echo "There was an error!";
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $tokenEmail);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    if (!$row = mysqli_fetch_assoc($result)) {
                        echo "There was an error!";
                        exit();
                    } else {
                        
                        $sql = "UPDATE users SET pwdUsers=? WHERE emailUsers=?"; //change necessary: this needs to match the email saved in login database
                        $stmt = mysqli_stmt_init($conn);
                        if (!mysqli_stmt_prepare($stmt, $sql)) {
                            echo "There was an error!";
                            exit();
                        } else {
                            $newPwdHash = password_hash($password, PASSWORD_DEFAULT);
                            mysqli_stmt_bind_param($stmt, "ss", $newPwdHash, $tokenEmail);
                            mysqli_stmt_execute($stmt);
                            
                            //delete the token when user gets to the page
                            $sql = "DELETE FROM pwdReset WHERE pwdResetEmail=?;";
                            $stmt = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                echo "There was an error!";
                                exit();
                            } else {
                                mysqli_stmt_bind_param($stmt, "s", "$tokenEmail");
                                mysqli_stmt_execute($stmt);
                                header("Location: reset.php?newpwd=passwordupdated"); //reset page with an update message
                            }
                        }
                    }
                }
                
            }
        }
    }
    
    
} else {
    header("Location: login.php")
}

?>