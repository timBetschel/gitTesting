<?php
session_start();// starts the session on this page so that it can access session varaibles
// MySQL Connection-----
//connect to the database, store this command into a variable $dblink
$dblink = mysqli_connect("localhost", "root", "") or die(mysqli_error());

//choose a database - in this case, artloans
mysqli_select_db($dblink, "artloans") or die(mysqli_error());

//this gets the submitted login information from the previous page using the POST method and stores them in local varaibles
$userType = $_POST["userType"];
$userName = $_POST["uname"];
$password = $_POST["pswd"];


//this SQL selects everything in a row from the table userdata where the fields userName, password and userType line up with that of what was inputed.
$sql = "SELECT * FROM userdata WHERE userName = '$userName' and password = '$password' and userType = '$userType'";
//$query takes the sql and the database link and does the query.
$query = mysqli_query($dblink, $sql);
//$count hold the number of rows returned as a result of the query $query
$count =  mysqli_num_rows($query);
//$row fetches an array of the results from $query so that we can access the values.
$row = mysqli_fetch_array($query);


// the following big switch case statement checkes whether the user selected teacher or student then runs checks if $count returned any rows
//if $count is == 1 this means that the inputed information was matched in the database
//and then session varaible according to the account type is created and they are redirected their respective pages
//if $count != 1 then the user is redirected to the login page and their login information is stored in session variables and the error is defined
//in any case  unset($_SESSION['logInReq']); is run because this is defined if someone tries to put the url of a page without logging in
//and it should be unset when the user tries to log in.
switch ($userType) {
  case 'teacher':
    if ($count == 1){ //checks if $count returned one result
    $_SESSION['userID'] = $row[0];// sets the users userID into a session variable
    $_SESSION['loggedIn'] = true; //sets the users loggin status to true with the session varaible $_SESSION['loggedIn']
    $_SESSION['type'] = "teacher"; //sets the users profile type with a session variable
    unset($_SESSION['logInReq']); //removes any value assigned to $_SESSION['logInReq'] (unsets the variable)
    header("location: home.php");//redirect
  }else{
    $_SESSION['unInput'] = $userName;
    $_SESSION['pswdInput'] = $password;
    $_SESSION['userType'] = $userType;
    $_SESSION['error'] = "Login information was incorrect! Please try again.";
    unset($_SESSION['logInReq']); //removes any value assigned to $_SESSION['logInReq'] (unsets the variable)
    header("location: index.php"); //redirect
  }
    break;
  case 'student';
    if ($count == 1){
    $_SESSION['userID'] = $row[0];
    $_SESSION['loggedIn'] = true;
    $_SESSION['type'] = "student";
    unset($_SESSION['logInReq']); //removes any value assigned to $_SESSION['logInReq'] (unsets the variable)
    header("location: ownLoans.php"); //redirect
  }else{
    $_SESSION['unInput'] = $userName;
    $_SESSION['pswdInput'] = $password;
    $_SESSION['userType'] = $userType;
    $_SESSION['error'] = "Login information was incorrect! Please try again.";
    unset($_SESSION['logInReq']); //removes any value assigned to $_SESSION['logInReq'] (unsets the variable)
    header("location:index.php"); //redirect
  }
    break;
}
 ?>
