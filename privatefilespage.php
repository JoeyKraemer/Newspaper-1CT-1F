<?php
session_start();
if(isset($_SESSION['user'])){
    $user_id = intval($_SESSION['user']);
}
else{
    header('Location: login.php');
    exit;
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF8">
        <title> Gemorskos </title>
        <link rel= "stylesheet" href="css/privatefilespage.css">
        <link rel="stylesheet" href="css/headerStyle.css">
    </head>
    <?php
    //placeholders
    $permissions = true;
    $maxFileSize = 10*1024*1024; //10mb
    $filePath = "repository/";

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

    $users = scandir($filePath);
    foreach($users as $user){
        if(preg_match("/^{$user_id}_.*$/", $user)){
            $filePath = "repository/{$user}/";
            break;
        }
    }

    // for upload/delete operations
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $errorMsg = [];
        switch($_POST['requestType']){
            case 'upload':
                $file = $_FILES["file"];
                if ($file["error"] !== 0){
                    $errorMsg[] = "an error has occured";

                } else {
                    if ($file['size'] > $maxFileSize){
                        $errorMsg[] = "size can only be a maximum of". ConvertToReadableSize($maxFileSize);
                    }
                    if (file_exists($filePath . $file["name"])){
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
                        if (!move_uploaded_file($file["tmp_name"], "$filePath" . $file["name"])) {
                            echo "<script type='text/javascript'>alert('something went wrong while uploading.');</script>";
                        }
                    }

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
    //
    ?>

    <body>
        <header>
            <p> Gemorskos </p>
            <nav>
                <ul>
                    <li> <a href="privateFilesPage.php"> <img src="img/folder.svg" alt="filesbutton"/> </a> </li>
                    <li> <a href="calendar.php"> <img src="img/calendar.svg" alt="calendarbutton"/> </a> </li>
                    <li> <a href="profilePage.php"> <img src="img/person.svg" alt="profilebutton"/> </a> </li>
                </ul>
            </nav>
        </header>
        <div class='falseHeader'></div>
	    <main>
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
                    $i = 0;
                    foreach(scandir($filePath) as $file_name){
                        if($file_name == '.' OR $file_name == '..'){
                            continue;
                        }else{
                            $i++;
                            // filesize() and filectime() do NOT work on folders made by php

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


                ?>
            </table>
	    </main>
    </body>
    <footer>
        <form action="privatefilespage.php" method="post" id="form" enctype="multipart/form-data">
            <input type="file" name="file" id="fileInput">
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
