<!doctype html>
<html>
    <head>
        <meta charset="UTF8">
        <title> Gemorskos </title>
        <link rel= "stylesheet" href="css/privatefilespage.css">
    </head>
    <body>
        <header>

        </header>
        <main>

    <?php
    //placeholders
    $permissions = true;
    $user_id = 2;
    $maxFileSize = 10*1024*1024; //10mb
    $baseFilePath = "repository/";
    $filePath = "";

    $dbname = "gemorskos";

    if(!$permissions){
        //redirect
    } else {
        try{
            $handler = new PDO("mysql:host={$_ENV["DB_SERVER"]}; dbname=$dbname; charset=utf8", $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
        }
        catch(Exception $ex){
            print $ex;
        }
    }

    // looks to match user id with folder in baseFilePath (followed by _). i.e. Repository/1_jordan_cook
    $users = scandir($baseFilePath);
    foreach($users as $user){
        if(preg_match("/^{$user_id}_.*$/", $user)){
            $filePath = "$baseFilePath{$user}/";
            break;
        }
    }


    // normal execution when the file path isn't empty
    if ($filePath){
        // for upload/delete operations
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            
            $errorMsg = [];
            switch($_POST['requestType']){
                case 'upload':
                    $i = 0;
                    while ($i < count($_FILES["file"]["name"])){
                        $file = $_FILES["file"];
                        if ($file["error"][$i] !== 0){
                            $errorMsg[] = "an error has occured";

                        } else {
                            if ($file['size'][$i] > $maxFileSize){
                                $errorMsg[] = "size can only be a maximum of". ConvertToReadableSize($maxFileSize);
                            }
                            if (file_exists($filePath . $file["name"][$i])){
                                $errorMsg[] = "that filename already exists";
                            }

                            if(!count($errorMsg) == 0){
                                $fullMsg = "";
                                foreach($errorMsg as $item){
                                    $fullMsg .= "$item. ";
                                }

                                echo "<script type='text/javascript'>alert('$fullMsg');</script>";
                            } else {
                                // uploads file
                                if (!move_uploaded_file($file["tmp_name"][$i], "$filePath" . $file["name"][$i])) {
                                    echo "<script type='text/javascript'>alert('something went wrong while uploading.');</script>";
                                }
                            }

                        }
                        $i++;
                    }
                    break;

                case 'delete':
                    $fileName = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_SPECIAL_CHARS);
                    if (file_exists($filePath . $fileName)){
                        if(!unlink($filePath . $fileName)){
                            echo "<script type='text/javascript'>alert('something went wrong while deleting the file');</script>";
                        }
                    }
                    else {
                        echo "<script type='text/javascript'>alert('the file does not exist');</script>";
                    }
            }
        }
        ?>
            <div id="myfile">
                <div class="myfiletext">
                    <h2>My Files</h2>
                </div>
                <div class="uploadicons">
                    <ul>
                        <!-- <li> <a class="icons" href="#"> <img src="img/trash.png" alt="trashbutton"> </a> </li> obsolete -->
                        <li> <a class="icons" href="#" onclick="uploadFile()"> <img src="img/upload.png" alt="uploadbutton"> </a> </li>
                        <!-- <li> <a class="icons" href="#"> <img src="img/caret_up.png" alt="caretbutton"> </a> </li> obsolete -->
                    </ul>
                </div>
            </div>
            <table id='filesTable'>
                <?php   
                    // for viewing the files
                    $i = 0;
                    foreach(scandir($filePath) as $file_name){
                       if($file_name == '.' OR $file_name == '..'){
                            continue;
                        }else{
                            $i++;

                            $file_size = convertToReadableSize(filesize("{$filePath}{$file_name}"));
                            $file_ctime = filectime("{$filePath}{$file_name}");


                            echo "
                            <tr id='$i'>
                                <td class='file_name'>$file_name</td>
                                <td class='file_size'>$file_size</td>
                                <td class='file_ctime'>uploaded on: ". date('d/m/Y',$file_ctime) ."</td>
                                <td class='options' onclick=\"openContext($i, '{$filePath}{$file_name}')\"><img src='img/menu.png' alt='context menu'/></td>
                            </tr>
                            ";
                        }
                    }
                    if($i == 0){
                        echo('<p>*empty*</p>');
                    }


                ?>
                </table>
            </main>
    <?php
    // if when the file path could not be established (no such user)
    } else {
        echo "<p>There is no such folder with that ID number</p>
            <p>Please seek help from an IT member</p>
            <a href='index.php'>Home</a>
        ";
    }
    ?>
    </body>
    
    <footer>
        <form action="privatefilespage.php" method="post" id="form" enctype="multipart/form-data">
            <input type="file" name="file[]" id="fileInput" multiple>
            <input type="text" name="text" id="textInput">
            <input type="hidden" name="requestType" id="requestType">
            <input type="submit" id="submitInput">
        </form>
        <script type="text/javascript" src="scripts/privatefilespage.js"></script>
    </footer>
<?php
function convertToReadableSize($size){
    $base = log($size) / log(1024);
    $suffix = array(" bytes", "kb", "mg", "gb", "tb");
    $f_base = floor($base);
    return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
}

?>
</html>
