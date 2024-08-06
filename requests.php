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



//this query stored in $requestQuery selects the firstName and surname from the userdata table
//aswell as the itemName and itemID from the items table and the loanDate, dueDate, loanHistID fields from the loanhistory table
//it does this by using an inner join liking the items table and userdata table by their primary keys (itemID and userID)
//it fetches only the results where pending is = yes
$requestedQuery = mysqli_query($dblink, "SELECT userdata.firstName, userdata.surname, items.itemName, loanhistory.loanDate, loanhistory.dueDate, loanhistory.loanHistID, items.itemID
FROM ((loanhistory
INNER JOIN items ON loanhistory.itemID = items.itemID)
INNER JOIN userdata ON loanhistory.userID = userdata.userID) WHERE pending = 'yes'");

//this if statement checks if the session varaible $_SESSION['accepted'] is set.
//if so then the local variable $accepted is set to = that session varaible
//if not then both the local varaible and session varaible are set to equal "" (nothing)
if(isset($_SESSION['accepted'])){
  $accepted = $_SESSION['accepted'];
}else{
  $accepted = "";
  $_SESSION['$accepted'] = "";
}


?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title> Request </title>
<link href="artloans.css" rel="stylesheet" type="text/css">
<!--css from: https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_alert-->
<style>
.alert {
  padding: 10px;
  background-color: #f2f2f2;
  color: white;
}

.closebtn {
  margin-left: 15px;
  color: black;
  font-weight: bold;
  float: right;
  font-size: 22px;
  cursor: pointer;
  transition: 0.3s;
}

.closebtn:hover {
  color: black;
}
</style>
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
   <td class="TopMenuText" style="cursor:pointer" onclick="location.href='items.php'">Items</td>
   <td class="TopMenuText" style="cursor:pointer" onclick="location.href='logOut.php'">Log Out</td>
   <td cellpadding="0">&nbsp</td>
        </tr>
      </tbody>
    </table>
  </div>


<div class="content">
    <div class="content-inside">
<?php
//<div class="alert" stuff was sourced from: https://www.w3schools.com/howto/tryit.asp?filename=tryhow_js_alert

//if the varaible $accepted holds the value yes then print an alert with the text "Loan has successfully been added!"
//also set both $accepted and $_SESSION['accepted'] to "" (nothing)
// else if the varaible $accepted holds the value no then print an alert with the text "Request has been denied!"
// and then also set both varaible to ""
if ($accepted == "yes"){
print <<<EOT
    <div class="alert">
    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
    <center style="color: green;">Loan has successfully been added!</center>
    </div>
EOT;
    $_SESSION['accepted'] = "";
    $accepted = "";
}elseif($accepted == "no"){
print <<<EOT
      <div class="alert">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
      <center style="color: black;">Request has been denied!</center>
      </div>
EOT;
      $_SESSION['accepted'] = "";
      $accepted = "";
}
?>
<br>
<br>
<?php

//this if statement Checks if $requestedQuery returns with any results that
//fit the criteria of the query. If it does, it will display the table, otherwise
//The table will not display and the message "There are no requested items at this time" displays instead.
if(!mysqli_num_rows($requestedQuery)==0){
print <<<EOT
      <table width="80%" border="1" cellspacing="0" cellpadding="0"  align="center" style="text-align: center; border: #2f5989;">
      <tbody>
      <tr>
      <td><strong>First Name</strong></td>
      <td><strong>Surname</strong></td>
      <td><strong>Item Name</strong></td>
      <td><strong>Borrow Date</strong></td>
      <td><strong>Return Date</strong></td>
      </tr>

EOT;
      }
      else{
print <<<EOT
        <p style="text-align: center; font-size:24px;">There are no requested items at this time.</p>
EOT;
      }

//this while loop uses the varaible $row to cycle through values stored in an array which holds the results of the query $requestedQuery
//it then prints the values of felids 0-4 into table collums and stores required values for further querys in the two buttons confirm and decline
while ($row = mysqli_fetch_array($requestedQuery)) {
print <<<EOT
          <tr>
              <td>$row[0]</td>
              <td>$row[1]</td>
              <td>$row[2]</td>
              <td>$row[3]</td>
              <td>$row[4]</td>
                <form method="POST" action="requestResponse.php">
                <input type="hidden" name="loanHistID" value="$row[5]"/>
                <input type="hidden" name="decline" value="no"/>
                <input type="hidden" name="itemID" value="$row[6]"/>
              <td class="noBorder"><button class="button" style="background-color: #4CAF50;">Confirm</button></td>
                </form>

                <form method="POST" action="requestResponse.php">
                <input type="hidden" name="decline" value="yes"/>
                <input type="hidden" name="loanHistID" value="$row[5]"/>
                <input type="hidden" name="itemID" value=""/>
              <td class="noBorder"><button class="button" style="background-color: red;">Decline</button></td>
                </form>
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
