<?php

//check if user got the this page by the right way
if (isset($_POST["reset-password-submit"])) {
    
    $password = $_POST["pwd"];
    $passwordRepeat = $_POST["pwd-repeat"];
    
    //check if the user left the pass empty or if passes are not the same
    if (empty($password) || empty($passwordRepeat)) {
        header("Location:".$url."?newpwd=pwdempty"); //reset page with an message
        exit();
    } else if ($password != $passwordRepeat) {
        header("Location: ".$url."?newpwd=pwdnotsame"); //reset page with an update message (won't work cause the tokens arent included to the url
        exit();
    }
    
    //check for the tokens
    $currentDate = date("U");
    
    //connect to database
    try {
    $dbhandler = new PDO("mysql:host={$_ENV["DB_SERVER"]}; dbname=$dbname; charset=utf8", $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
    } catch (Exception $ex){
        print $ex;
    }

    $stmt = $dbhandler->prepare("SELECT * FROM pwdReset WHERE pwdResetSelector=? AND pwdResetExpires >= ?");
    if (!$stmt) {
        echo "There was an error!";
        exit();
    } else {
        $stmt->bindParam("ss", $selector, $currentDate); //checking if the database is picking the correct token using the selector
        $stmt->execute();
        
        $result = $stmt->get_result();
        if (!$result) {
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
                
                $stmt = $dbhandler->prepare("SELECT * FROM users WHERE email_address=?");
                if (!$stmt) {
                    echo "There was an error!";
                    exit();
                } else {
                    $stmt->bindParam('s', $tokenEmail);
                    $stmt->execute();
                    
                    $result = $stmt->get_result();
                    if (!$result) {
                        echo "You need to re-submit your reset request!";
                        exit();
                    } else {
                        
                        $stmt = $dbhandler->prepare("UPDATE users SET user_password=? WHERE email_address=?");
                        if (!$stmt) {
                            echo "There was an error!";
                            exit();
                        } else {
                            $newPwdHash = password_hash($password, PASSWORD_DEFAULT);
                            $stmt->bindParam('ss', $newPwdHash, $tokenEmail);
                            $stmt->execute();
                            
                            //delete the token when user gets to the page
                            $stmt = $dbhandler->prepare("DELETE FROM pwdReset WHERE pwdResetEmail=?");
                            if (!$stmt) {
                                echo "There was an error!";
                                exit();
                            } else {
                                $stmt->bindParam('s', "$tokenEmail");
                                $stmt->execute();
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