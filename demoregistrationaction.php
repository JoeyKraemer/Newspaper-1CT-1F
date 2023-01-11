<?php
	require_once "connection.php";
	if(isset($_POST['Register']))
	{
		$firstname= trim($_POST['fname']);
		$lastname= trim($_POST['lname']);
		$email= trim($_POST['email']);
		$password= trim($_POST['password']);
		$repassword= trim($_POST['repassword']);
		
	// password validation
   $uppercase = preg_match('@[A-Z]@', $password);
   $lowercase = preg_match('@[a-z]@', $password);
   $number    = preg_match('@[0-9]@', $password);
   $specialChars = preg_match('@[^\w]@', $password);
	
	if($firstname=="") {
?>
	<script>
	alert("please provide first name...!");
	history.back()
	</script>
<?php
	exit();
	}		
	
		else if($lastname=="") {
			?>
	<script>
	alert("please provide first name...!");
	history.back()
	</script>
<?php
	exit();
	}
	
		else if($email=="") {
?>
	<script>
	alert("please provide first name...!");
	history.back()
	</script>
<?php
	exit();
	}
	
			else if($password=="") {
?>
	<script>
	alert("please provide first name...!");
	history.back()
	</script>
<?php
	exit();
	}
	
			else if($repassword=="") {
?>
	<script>
	alert("please provide first name...!");
	history.back()
	</script>
<?php
	exit();
	}
	
	else if (strlen($password) < 8){
	?>
	<script>
	alert("password must be at least 6 characters!");
	history.back()
	</script>
	
	<?php
	exit();
	}
	
	// password validation
   
   
   
	else if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
    		 ?>
      <script>
	  alert(" Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character! ");
	  history.back()</script>
      <?php
	  exit();
   }
	
	$stmt = $DB_con->prepare("SELECT * FROM users WHERE email =:email");
	$stmt->execute(array( ':email'=>$email));
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	
	if($row){
	
			 ?>
      <script>
	  alert(" sorry email already taken by another user...!");
	  history.back()</script>
      <?php
	  exit();
	}
		
	
			//hash new password
			$new_password = password_hash ($password, PASSWORD_DEFAULT);
			$date=date('Y-m-d H:i:s');
			//$status=i;
			$stmt=$DB_con->prepare("INSERT INTO user(fname,lname,email,password,date)
														VALUES(:fname, :lname,:email,:new_password, :date)");
														
			$stmt->bindparam(":fname", $firstname);
			$stmt->bindparam(":lname", $lastname);
			$stmt->bindparam(":email", $email);
			$stmt->bindparam(":date", $date);
			$stmt->bindparam(":new_password", $new_password);
		if(	$stmt->execute()){
			?>
			<script>
	  alert(" Registration Succesful! ");
	  </script>
			
		<?php
		}
	}
	
	?>