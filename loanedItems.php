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



// $loansQuery: selects loanHistID, itemName, itemDescription, firstName, surname, dueDate, itemID from loanhistory
// its does this by join loanhistory with the items table aswell as the userdata table with us of an inner join on the itemID and userID.
//it gathers this information where the returned feild is no and pending is also no
$loansQuery = mysqli_query($dblink, "SELECT loanhistory.loanHistID, items.itemName, items.itemDescription, userdata.firstName, userdata.surname, loanhistory.dueDate, loanhistory.itemID FROM ((loanhistory
INNER JOIN items ON loanhistory.itemID = items.itemID)
INNER JOIN userdata ON loanhistory.userID = userdata.userID) WHERE returned LIKE '%no%' AND pending = 'no'");


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title> Loaned Items </title>
<link href="artloans.css" rel="stylesheet" type="text/css">
</head>

<body>
  <div class="menu">
    <table width="100%" height="100px" bordercolor="black" cellspacing="0" border="0">
      <colgroup>
      <col span="1" style="width: 3%;">
      <col span="1" style="width: 1%;">
      <col span="1" style="width: 20%;">
      <col span="1" style="width: 3%;">
      <col span="1" style="width: 3%;">
      <col span="1" style="width: 3%;">
      <col span="1" style="width: 3%;">
      <col span="1" style="width: 2%;">

   </colgroup>

      <tbody>
        <tr>
   <td cellpadding="0">&nbsp</td>
   <td cellpadding="0"><a href="home.php"> <img src="photos\dcc_logo.png" width="200"></a></td>
   <td cellpadding="0" style="font-size:42px; color: white; font-family: 'Salsa', cursive; ">Arts Borrowing System</td>
   <td class="TopMenuText" style="cursor:pointer" onclick="location.href='home.php'"> Home</td>
   <td class="TopMenuText" style="cursor:pointer" onclick="location.href='students.php'">Students</td>
   <td class="TopMenuText" style="cursor:pointer" onclick="location.href='requests.php'">Requests</td>
   <td class="TopMenuText" style="cursor:pointer" onclick="location.href='logOut.php'">Log Out</td>
   <td cellpadding="0">&nbsp</td>
        </tr>
      </tbody>
    </table>
  </div>


<div class="content">
    <div class="content-inside">
<br>
<br>

<?php
//this if statement Checks if $loansQuery returns with any results that
//fit the criteria of the query. If it does, it will display the table, otherwise
//The table will not display and the message "There are no loaned items at this time" displays instead.
if(!mysqli_num_rows($loansQuery)==0){
print <<<EOT
<table width="90%" border="1" cellspacing="0" cellpadding="0"  align="center" style="text-align: center; border: #2f5989;">
<tbody>
<tr>
<td><strong>Item Name</strong></td>
<td><strong>Item Description</strong></td>
<td><strong>Student Name</strong></td>
<td><strong>Due Date</strong></td>
</tr>

EOT;
}
else{
print <<<EOT
  <p style="text-align: center; font-size:24px;">There are no loaned items at this time.</p>
EOT;
}

//this while loop uses a variable $row to access an array of the query $loansQuery
//it then prints values held in the array for each result in a table
//one of the printed table collums is a button which submits values to the loansEdit.php page
while ($row = mysqli_fetch_array($loansQuery)) {
print <<<EOT
    <tr>
        <td>$row[1]</td>
        <td>$row[2]</td>
        <td>$row[3] $row[4]</td>
        <td>$row[5]</td>
        <form method="POST" action="loansEdit.php">
        <input type="hidden" name="loanHistID" value="$row[0]"/>
        <input type="hidden" name="itemID" value="$row[6]"/>
        <td class="noBorder"><input type="submit" value="Return" class="button" style="background-color: #4CAF50;"></td>
        </form>
    </tr>

EOT;
}
?>

</tbody>
</table>
<br>
<br>
<br>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<a href="items.php"><button type="button" class="button"> Back </button> </a>

  </div>
</div>

<footer class="footer"> <br>Prototype by Tim Betschel 2020 for DCC arts borrowing software.</footer>

</body>
</html>
