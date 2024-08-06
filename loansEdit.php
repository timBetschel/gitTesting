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


//gets the variables POSTed from the previous pages form and stores them in local variables
$loanHistID = $_POST["loanHistID"];
$itemID = $_POST["itemID"];


//$returnedQuery holds the sql to update the table loanhistory setting returned to yes
//wherever loanHistID matches the one gathered from the prevoius page and held in $loanHistID
$returnedQuery = "UPDATE loanhistory SET returned = 'yes' WHERE loanHistID=$loanHistID";

//$quantQuer holds the sql to update the items table changing the quantity of avaliable and on loan feilds of the item that was returned
$quantQuer = "UPDATE items SET loaned = loaned -1, available = available + 1 WHERE itemID = $itemID";

//this function uses the database link stored in $dblink and the sql in $returnedQuery to run the query.
function returnLoan($loanHistID, $dblink, $returnedQuery){
  mysqli_query($dblink, $returnedQuery);
}
//this function uses the database link stored in $dblink and the sql in $quantQuer to run the query.
function adjustAval($itemID, $dblink, $quantQuer){
  mysqli_query($dblink, $quantQuer);
}

adjustAval($itemID, $dblink, $quantQuer); // this runs the function adjustAval()
returnLoan($loanHistID, $dblink, $returnedQuery); // this runs the function returnLoan()
header("Location:loanedItems.php");
 ?>
