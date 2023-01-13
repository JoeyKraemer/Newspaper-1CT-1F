
<?php

    try{
        $dbHandler = new PDO("mysql:host=mysql;dbname=gemorskos;charset=utf8", "root", "qwerty");
    }
    catch(Exception $ex){
        echo "The following exception has occurred $ex";
    }
    
?>