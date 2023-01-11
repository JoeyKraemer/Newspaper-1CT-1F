<!DOCTYPE html>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Resgistration</title>
</head>
<body>
	<form action="demoregistrationaction.php" method="POST" enctype="multipart/form-data">
		<label>Enter your Details</label><br><br>
		<input type="text" name="fname" placeholder="First name"><br><br>
		<input type="text" name="lname" placeholder="Last name"><br><br>
		<input type="email" name="email" placeholder="Enter Mail"><br><br>
		<input type="password" name="password" placeholder="Password"><br><br>
		<input type="password" name="repassword" placeholder="Repeat Password"><br><br>
		<input type="submit" name="Register" value="Register">
	</form>
</body>
</html>