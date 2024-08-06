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





//$itemsQuery: select all fields and rows from the 'items' table and order it by the feild itemName is ascending order

$itemsQuery = mysqli_query($dblink, "SELECT * from items ORDER BY itemName ASC;");
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title> items </title>
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
<br>
<br>
<br>
<br>
  <table width="90%" border="1" cellspacing="0" cellpadding="0"  align="center" style="text-align: center; border: #2f5989;">
  <tbody>
    <tr>
      <td><strong>Item Name</strong></td>
      <td><strong>Item Description</strong></td>
      <td><strong>Quantity Available</strong></td>
      <td><strong>Quantity On Loan</strong></td>
    </tr>
<?php
//cycles through a fetched array of the query ($itemsQuery) and prints out the data in positions 1-4 in the array in a table format
while ($row = mysqli_fetch_array($itemsQuery)) {
print <<<EOT
	<tr>
      <td>$row[1]</td>
      <td>$row[2]</td>
      <td>$row[4]</td>
      <td>$row[3]</td>
  </tr>
EOT;
}
?>
  </tbody>
</table>
<br>
<br>
<br>
<br>

&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<a href="loanedItems.php"><button type="button" class="button"> Items On Loan </button> </a>
  </div>
</div>


<footer class="footer"> <br>Prototype by Tim Betschel 2020 for DCC arts borrowing software.</footer>

</body>
</html>
