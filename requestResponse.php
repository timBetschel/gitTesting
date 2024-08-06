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

// MySQL Connection-----
//connect to the database, store this command into a variable $dblink
$dblink = mysqli_connect("localhost", "root", "") or die(mysqli_error());

//choose a database - in this case, artloans

mysqli_select_db($dblink, "artloans") or die(mysqli_error());

//the following three lines get the values submitted in the forms on the previous page using the POST methods and stores them into local Variables
$loanID = $_POST['loanHistID'];
$decline = $_POST['decline'];
$itemID = $_POST['itemID'];

//this if statement checks if $decline holds the value no
//if it does than it runs the query $confirmRequest which changes that loan request to no longer be pending
//it also runs the query $adjustQuantityAvalability which changes the Quantity of items available and on loan now that one has been officially loaned.
//lastly it makes the session variable $_SESSION['accepted'] = yes
//however if $decline doesnot = no but instead = yes. Then the query $removeRequest runs and deletes the request from the loanhistory table
//and makes the session varaible $_SESSION['accepted'] = no
if ($decline == "no"){
  $confirmRequest = mysqli_query($dblink, "UPDATE loanhistory SET pending = 'no' WHERE loanHistID=$loanID");
  $adjustQuantityAvalability = mysqli_query($dblink, "UPDATE items SET loaned = loaned + 1, available = available - 1 WHERE itemID = $itemID");
  $_SESSION['accepted'] = "yes";
}elseif($decline == "yes"){
  $removeRequest = mysqli_query($dblink, "DELETE FROM loanhistory WHERE loanHistID=$loanID ");
  $_SESSION['accepted'] = "no";
}

header("Location:requests.php");
?>
