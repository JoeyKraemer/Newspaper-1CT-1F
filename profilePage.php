<?php

require_once("connection.php");

global $dbHandler;

$stmt = $dbHandler->prepare("SELECT photo FROM `Users`");
$stmt -> execute();
$photos = $stmt->setFetchMode(PDO ::FETCH_OBJ); 

foreach($stmt-> fetchall() as $photo){
	$image = $photo-> photo;
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile Page</title>
    <link rel="stylesheet" href="profile.css">
  </head>
  <body>
		<div id="gridContainer">
			<main>
				<div id="content">
					
						<img src="upload/<?php echo $image ?>" alt="" id="circle">
					
						<?php

							$stmt = $dbHandler->prepare("SELECT first_name, last_name FROM `Users`");
							$stmt -> execute();
							$table = $stmt->setFetchMode(PDO::FETCH_OBJ); 


								foreach($stmt->fetchall() as $tables){

									echo '<h1>' . $tables->first_name .' '. $tables->last_name. '</h1>';

								}
						?>
						<div id="accountDetails">
						<h2> Your Role: </h2>
						<h2> Jobs: </h2>
						<div class="jobBox">
							<p>1 december-Wedding photographer in London</p> 
						</div>
						<div class="jobBox">
							<p>1 september- Doing something</p>
						</div>
						 <a href="calendar.php"> Calendar/schedule </a>
						 <a href="recover.php"> Forgot your password? </a>
					</div>
				</div>
			</main>
		</div>
  </body>
</html>

