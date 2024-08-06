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
//if the users profile type is not student then they are redirected back to the login
if ($_SESSION['type'] != "student"){
  header('Location: index.php');
}

// MySQL Connection-----
//connect to the database, store this command into a variable $dblink
$dblink = mysqli_connect("localhost", "root", "") or die(mysqli_error());

//choose a database - in this case, artloans

mysqli_select_db($dblink, "artloans") or die(mysqli_error());


$itemID = $_COOKIE["id"]; // gets the value of the cookie "id" and stores it in $itemID
$rDate = $_POST["rDate"]; // gets the value of "rDate" from the previous page via the POST method and assigns its value to the local varaible $rDate
$bDate = $_POST["bDate"]; // gets the value of "bDate" from the previous page via the POST method and assigns its value to the local varaible $bDate
$userID = $_SESSION['userID']; //assigns the local variable $userID to equal the session varaible $_SESSION['userID']
$check = 0; // sets the varaible $check to 0
$_SESSION['invalidDates'] = ""; // sets the session varaible $_SESSION['invalidDates'] = ""
$_SESSION['invalidDates2'] = ""; // sets the session varaible $_SESSION['invalidDates2'] = ""
$curDate=date("Y-m-d"); // $defines the varaible $curDate to equal the current date in the format years months than days with the date() function


//$checkRequest is a varaible which hold a query to select the itemID from the loanhistoryy table where userID is = to the userID stored in the varaible $userID
// and only where pending is = "yes"
 $checkRequest = mysqli_query($dblink, "SELECT itemID FROM loanhistory WHERE userID = $userID AND pending = 'yes'");

//$requestQuery is a varaible which holds the sql to insert a new record of the itemID, userID, loanDate, dueDate, returned, pending feilds into the loanhistory table
$requestQuery = "INSERT INTO loanhistory (itemID, userID, loanDate, dueDate, returned, pending)
VALUES ($itemID, $userID, '$bDate', '$rDate', 'no', 'yes')";

// this if statement checks if $rDate is less than $bDate
// if it is then it sets $check = 1
// and it sets a session varaible $_SESSION['invalidDates']  = to an error message
if($rDate < $bDate){
  $check = 1;
 $_SESSION['invalidDates'] = "* Your Borrow date cannot be after your Return date";
}

//this if statement checks if either $bDate or $rDate is less than the current date
//if so check = 1
//and the session varaible $_SESSION['invalidDates2'] is assigned a error message
if($bDate < $curDate || $rDate < $curDate){
  $check = 1;
  $_SESSION['invalidDates2'] = "* Your dates cannot be before the current day";
}

//this while loop uses $results to cycle through the results of the query $checkRequest held in an array
while ($result = mysqli_fetch_array($checkRequest)) {
 if ($result[0] == $itemID){ //if $result[0] (itemID) is equal to the value held in the varaible $itemID than check = 1 and $_SESSION['allreadyRequested'] = "yes"
   $check = 1;
   $_SESSION['allreadyRequested'] = "yes";
 }
}

//if the varaible $check holds the value 0 than the session varaible   $_SESSION['allreadyRequested'] is equal to "no"
//and a query using the sql code in $requestQuery is run (which insert the request into the loanhistory table)
//the session varaible $_SESSION['success'] is set to 1
if($check == 0){
  $_SESSION['allreadyRequested'] = "no";
  mysqli_query($dblink, $requestQuery);
  $_SESSION['success'] = 1;
}


header("Location:viewItems.php");


 ?>
