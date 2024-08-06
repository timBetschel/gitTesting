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


//sets the local varaible $firstName = to the session varaible $_SESSION['userFirstName']
$firstName = $_SESSION['userFirstName'];

//this query defined by $studentQuery selects the firstName and surname from the userdata table
//aswell as the item name from the items table and the dueDate, returned, loanDate fields from the loanhistory table
//it does this by using an inner join liking the items table and userdata table by their primary keys (itemID and userID)
//it fetches only the results where firstName is like the value stored in $firstName and where the feild pending is = no
$studentQuery = mysqli_query($dblink, "SELECT userdata.firstName, userdata.surname, items.itemName, loanhistory.dueDate, loanhistory.returned,  loanhistory.loanDate FROM ((loanhistory
INNER JOIN items ON loanhistory.itemID = items.itemID)
INNER JOIN userdata ON loanhistory.userID = userdata.userID) WHERE firstName LIKE '$firstName' AND pending ='no' ORDER BY dueDate ASC");

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title> Searched Student </title>
<link href="artloans.css" rel="stylesheet" type="text/css">
<script></script>
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
   <td class="TopMenuText" style="cursor:pointer" onclick="location.href='items.php'">Items</td>
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
  &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<a href="students.php"><button type="button" class="button"> Back </button> </a>

  <br>
  <br>
  <br>


  <?php
  //this if statement Checks if $studentQuery returns with any results that
  //fit the criteria of the query. If it does, it will display the table, otherwise
  //The table will not display and the message "This student is yet to loan an item" displays instead.
if(!mysqli_num_rows($studentQuery)==0){

print <<<EOT
  <table width="90%" border="1" cellspacing="0" cellpadding="0"  align="center" style="text-align: center; border: #2f5989;">
  <tbody>
  <tr>
  <td><strong>Student Name</strong></td>
  <td><strong>Item Name </strong></td>
  <td><strong>Borrow Date </strong></td>
  <td><strong>Due Date</strong></td>
  <td><strong>Returned?</strong></td>
  </tr>

EOT;

} else{
print <<<EOT
    <p style="text-align: center; font-size:24px;">This student is yet to loan an item.</p>
EOT;
  }
// this while loop uses $row to cycle through values stored in an array which hold the results of the query $studentQuery
//while $row cycles through each result is prints $row[0-5] (5 fields) arranged in table collums
while ($row = mysqli_fetch_array($studentQuery)) {
print <<<EOT
      <tr>
          <td>$row[0] $row[1]</td>
          <td>$row[2]</td>
          <td>$row[5]</td>
          <td>$row[3]</td>
          <td>$row[4]</td>
      </tr>

EOT;
}

?>

  </tbody>
  </table>



  </div>
</div>

<footer class="footer"><br>Prototype by Tim Betschel 2020 for DCC arts borrowing software.</footer>

</body>
</html>
