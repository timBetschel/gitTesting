<?php
session_start();// starts the session on this page so that it can access session varaibles

//this if statement checks if $_SESSION['loggedIn'] is set/ defined and holds a value and that that value is true
//if this if statement finds that these conditions are not met then it defines a session varaible ($_SESSION['logInReq']) for a log in required type error
//and redirects to the login page
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true){

}else {
  $_SESSION['logInReq'] = "Please log in first before accessing any pages";
  header('Location: index.php');
}
//if the users profile type is not teacher then they are redirected back to the login
if ($_SESSION['type'] != "teacher"){
  header('Location: index.php');
}

$dblink = mysqli_connect("localhost", "root", "") or die(mysqli_error());

//choose a database - in this case, artloans

mysqli_select_db($dblink, "artloans") or die(mysqli_error());

//gets the inputed firstname from the search bar on the previous page using the GET method and stores it in the local varaible $firstName
$firstName = $_GET["firstNameSearched"];
//sets $error = 1 (duh) (most of this seems like duh to me, like it shouldn't need to be explained. but this definetly doesn't need too, but i will anyway)
$error = 1;
//sets $otherErrCount = 0 (duh)
$otherErrCount = 0;

/* The following function attemps to stop malicious code. trim($data) will strip unnecessary characters
(extra space, tab, newline) from the user input data (with the PHP trim() function).
stripslashes($data) will remove backslashes () from the user input data (with the PHP stripslashes() function).
htmlspecialchars($data) converts special characters to HTML entities.
*/
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

test_input($firstName);// this runs the function test_input using $firstName as the data $data

//this if statement checks if the varaible $firstName is empty or not
//if it is empty then a session varaible $_SESSION['emptyNameError'] is set to hold an error message
//a variable called $otherErrCount is set to 1. And the user is redirected back to the students.php page
if(empty($firstName)){
$_SESSION['emptyNameError'] = "You have to enter a name!";
$otherErrCount = 1;
header("Location:students.php");
}

//this if statement checks if the varaible $firstName only contains letters or not and that it is not empty
// if the varaible $firstName is both only not only containing letter and isnt empty than a session varaible $_SESSION['nameCharacterErr'] is set to an error message
// and $otherErrCount is set to 1. and the user is redirected to students.php
if (!ctype_alpha($firstName) && !empty($firstName)){
$_SESSION['nameCharacterErr'] = "You search must only contain letters";
$otherErrCount = 1;
header("Location:students.php");
}
//$checkStudent holds a query for checking if the student exists in the database
//by selecting the firstName feild from the table userdata where the firstName is = to the inputed firstName by the user stored in $firstName
$checkStudent = mysqli_query($dblink, "SELECT firstName FROM userdata WHERE firstName = '$firstName'");

// this while loop uses $result to access an array of the query $checkStudent
//if the value of the firstName returned by the query (which is stored in $result[0]) is the same as the inputed firstName in $firstName
//(not taking capitals and lowercase into account, by using strcasecmp()) then $error is set to 0
while ($result = mysqli_fetch_array($checkStudent)) {
if (strcasecmp($result[0], $firstName) == 0 ){
$error = 0;
 }
}

//this double up if statement first checks if their were no other errors (empty or alpha character type errors) shown. If so then $otherErrCount with = 0.
//in this case the next if statement can run, and if $error == 1 meaning that the firstName did not match one in the database.
//than a session varaible $_SESSION['existingNameErr'] is defined with an error message and the user is redirected to students.php
if($otherErrCount == 0){
if ($error == 1){
$_SESSION['existingNameErr'] = "The first name you searched for doesn't exist try again";
header("Location:students.php");
}
}

//this if statement checks if their were no errors and if so this creates a session varaible to hold the inputted firstName and redirects them to studentSearched.php
if ($error == 0){
$_SESSION['userFirstName'] = $firstName;
header("Location:studentSearched.php");
}



?>
