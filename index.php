<?php
//setting variables initial values to nothing
$error = "";
$userName = "";
$password = "";
$userType = "";
$logInReq = "";

session_start(); //starts the session on this page so that it can access session variables
//this if statements checks if the variable $_SESSION['unInput'] is defined/ is set to be holding anything
//if it is then it sets the following variables to their the values stored in session variables from loginProcess.php
if (isset($_SESSION['unInput'])){
  $password = $_SESSION['pswdInput'];
  $userName = $_SESSION['unInput'];
  $error = $_SESSION['error'];
  $userType = $_SESSION['userType'];
}
//this if statement checks if $_SESSION['logInReq'] is defined/ is set to be holding anything
//and if so then it is assigning $logInReq to its current value
if (isset($_SESSION['logInReq'])){
  $logInReq = $_SESSION['logInReq'];
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title> index </title>
<link href="artloans.css" rel="stylesheet" type="text/css">
<!--CSS taken and modified so not a modal and other adjustments from: https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_login_form_modal-->
<style>
input[type=text], select {
  width: 30%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}
input[type=password], select {
  width: 30%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 100%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

div {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
  text-align: center;
  height: 100%;
}
</style>
</head>
<body>




<div>
  <h4 style="color: red;"><?php echo $logInReq; ?></h4>
<img src="photos\logInImage.jpg" height="250px">
<br>
<br>
<br>
<form action="loginProcess.php" method="post" style="height: 100px">
  <label for="uname"><b>Account Type</b></label>
     <select name="userType" id="userType" required>
       <option value="student" <?php if ($userType == 'student') echo ' selected="selected"'; //this checks what was initially inputed and then sets that to the defult
       // so that it shows that preference when they are redirected back to the page?>>Student</option>
       <option value="teacher" <?php if ($userType == 'teacher') echo ' selected="selected"'; //this checks what was initially inputed and then sets that to the defult
       // so that it shows that preference when they are redirected back to the page ?>>Teacher</option>
     </select>
     <br>
     <br>
  <label for="uname"><b>Username &nbsp&nbsp&nbsp&nbsp</b></label>
     <input type="text" placeholder="Enter Username" name="uname" id="uname" value="<?php echo $userName; ?>" required>
     <br>
     <br>
     <label for="pswd"><b>Password &nbsp&nbsp&nbsp&nbsp</b></label>
     <input type="password" placeholder="Enter Password" name="pswd" id="pswd" value="<?php echo $password; ?>"required>
     <p style="color: red;"><?php echo $error; ?></p>
     <br>
     <button type="submit" class="button">Login</button>
  </form>
</div>

</body>
</html
