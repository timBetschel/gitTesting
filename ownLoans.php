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


$userID = $_SESSION['userID']; //sets $userID equal to the session varaible $_SESSION['userID']
// MySQL Connection-----
//connect to the database, store this command into a variable $dblink
$dblink = mysqli_connect("localhost", "root", "") or die(mysqli_error());

//choose a database - in this case, artloans

mysqli_select_db($dblink, "artloans") or die(mysqli_error());

//this Query stored in $myLoansQuery selects itemName and itemDescription from the items table and dueDate, loanDate and retured fields from the loanhistory table
//it does this with an inner join linking the loanhistory table and the items table by using the itemID.
//the query searches where userID = the user id stored in $userID and where pending in = no.
//it sorts the results by dueDate in Ascending order.
$myLoansQuery =  mysqli_query($dblink, "SELECT items.itemName, items.itemDescription, loanhistory.dueDate, loanhistory.loanDate, loanhistory.returned  FROM (loanhistory
INNER JOIN items ON loanhistory.itemID = items.itemID) WHERE userID = $userID AND pending = 'no' ORDER BY dueDate ASC");



?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title> Own Loans </title>
<link href="artloans.css" rel="stylesheet" type="text/css">
</head>

<body>
  <div class="menu">
    <table width="100%" height="100px" bordercolor="black" cellspacing="0" border="0">
      <colgroup>
        <col span="1" style="width: 1%;">
        <col span="1" style="width: 1%;">
        <col span="1" style="width: 65%;">
        <col span="1" style="width: 1%;">
        <col span="1" style="width: 8%;">
        <col span="1" style="width: 6%;">
        <col span="1" style="width: 3%;">
        <col span="1" style="width: 4%;">


   </colgroup>

      <tbody>
        <tr>
   <td cellpadding="0">&nbsp</td>
   <td cellpadding="0"><img src="photos\dcc_logo.png" width="200px"></td>
   <td cellpadding="0" style="font-size:42px; color: white; font-family: 'Salsa', cursive; ">Arts Borrowing System</td>
   <td>&nbsp</td>
   <td class="TopMenuText" style="cursor:pointer" onclick="location.href='viewItems.php'"> Items</td>
   <td cellpadding="0">&nbsp</td>
   <td class="TopMenuText" style="cursor:pointer" onclick="location.href='logOut.php'">Log Out</td>
   <td cellpadding="0">&nbsp</td>
        </tr>
      </tbody>
    </table>
  </div>


<div class="content">
    <div class="content-inside">



<?php
//this if statement Checks if $myLoansQuery returns with any results that
//fit the criteria of the query. If it does, it will display the table, otherwise
//The table will not display and the message "You have no loans!" displays instead.
if(!mysqli_num_rows($myLoansQuery)==0){
print <<<EOT
<h2> Your Loans: </h2>
<table width="90%" border="1" cellspacing="0" cellpadding="0"  align="center" style="text-align: center;">
<tbody>
<tr style="background: #2f5989; color: white;">
<td><strong>Item Name</strong></td>
<td><strong>Item Description</strong></td>
<td><strong>Loan Date</strong></td>
<td><strong>Due Date</strong></td>
<td><strong>Returned?</strong></td>
</tr>

EOT;
}
else{
print <<<EOT
  <p style="text-align: center; font-size:24px;">You have no loans!</p>
EOT;
}


//this while loop uses the varaible $row to cycle through values stored in an array which holds the results of the query $myLoansQuery
//it then prints the values of felids 0-4 into table collums
while ($row = mysqli_fetch_array($myLoansQuery)) {
print <<<EOT
    <tr class="stripedTable">
        <td>$row[0]</td>
        <td>$row[1]</td>
        <td>$row[3]</td>
        <td>$row[2]</td>
        <td>$row[4]</td>
    </tr>

EOT;
}
?>

</tbody>
</table>


  </div>
</div>

<footer class="footer"> <br>Prototype by Tim Betschel 2020 for DCC arts borrowing software.</footer>

</body>
</html>
