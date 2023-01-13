<?php



if($_SERVER['REQUEST_METHOD'] == 'POST'){

    require_once("connection.php");

    global $dbHandler;

    if ($_FILES["file"]["error"]>0)
    {
        echo "error resend file";
    }
    else
    {
        $accepted_types=array("image/gif","image/jpeg","image/jpg","image/png");
        $fileInfo=finfo_open(FILEINFO_MIME_TYPE);
        $uploadedFileType=finfo_file($fileInfo,$_FILES["file"]["tmp_name"]);
        echo $uploadedFileType;
        if (in_array($uploadedFileType,$accepted_types))
        {
            echo "file received";
            if (!file_exists("upload/".$_FILES["file"]["name"]))
            {
                echo "file can be uploaded <br>";
                if (move_uploaded_file($_FILES["file"]["tmp_name"],"upload/".$_FILES["file"]["name"]))
                {
                    echo "upload successful";
                }
                else
                {
                    echo "upload unsuccessful";
                }
            }
        }
    }

    $stmt = $dbHandler->prepare("UPDATE Users
                                SET photo = :photo");

    $stmt -> bindParam('photo', $_FILES["file"]["name"], PDO::PARAM_STR);
    $stmt -> execute();

    echo "<script>window.location ='profilePage.php'</script>";
}

?>
