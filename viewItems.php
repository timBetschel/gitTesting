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




//$itemsQuery defines a query which select all fields and rows from the 'items' table in ascending order
$itemsQuery = mysqli_query($dblink, "SELECT * FROM items ORDER BY itemName ASC");


$dateError = ""; //sets $dateError to "" (nothing)
$dateError2 = ""; //sets $dateError2 to "" (nothing)


//if $_SESSION['allreadyRequested'] is set/ defined then set the local varaible $alrdyRequested to equal this session varaible
//else  set $alrdyRequested = "" and the session varaible $_SESSION['allreadyRequested'] also = ""
if(isset($_SESSION['allreadyRequested'])){
  $alrdyRequested = $_SESSION['allreadyRequested'];
}else{
  $alrdyRequested = "";
  $_SESSION['allreadyRequested'] = "";
}

//if $_SESSION['invalidDates'] is set than set $dateError to equal it (notice i dont need the else on this one because it is already defined above (just a different way of doing it))
if(isset($_SESSION['invalidDates'])){
  $dateError = $_SESSION['invalidDates'];
}
//if $_SESSION['invalidDates2'] is set than set $dateError2 to equal it (notice i dont need the else on this one because it is already defined above (just a different way of doing it))
if(isset($_SESSION['invalidDates2'])){
  $dateError2 = $_SESSION['invalidDates2'];
}
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title> View Items </title>
<link href="artloans.css" rel="stylesheet" type="text/css">
<!--https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_login_form_modal-->
<!--stuff for alert message: https://www.w3schools.com/howto/howto_js_alert.asp-->
<style>
body {font-family: Arial, Helvetica, sans-serif;}

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

/* Full-width input fields */
input[type=date] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

/* Set a style for all buttons */
button {
  background-color: #4CAF50;
  color: white;
  padding: 12px 32px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

button:hover {
  opacity: 0.8;
}

/* Extra styles for the cancel button */
.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

/* Center the image and position the close button */
.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
  position: relative;
}

img.avatar {
  width: 40%;
  border-radius: 50%;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  padding-top: 60px;
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button (x) */
.close {
  position: absolute;
  right: 25px;
  top: 0;
  color: #000;
  font-size: 35px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: red;
  cursor: pointer;
}

/* Add Zoom Animation */
.animate {
  -webkit-animation: animatezoom 0.6s;
  animation: animatezoom 0.6s
}

@-webkit-keyframes animatezoom {
  from {-webkit-transform: scale(0)}
  to {-webkit-transform: scale(1)}
}

@keyframes animatezoom {
  from {transform: scale(0)}
  to {transform: scale(1)}
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }
  .cancelbtn {
     width: 100%;
  }
}
</style>
</head>
<script>
// Get the modal
var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

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
   <td class="TopMenuText" style="cursor:pointer" onclick="location.href='ownLoans.php'"> Own Loans</td>
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
//this if statement checks if the varaible $alrdyRequested hold the value "yes" if it does than it prints an alert which reads
// "You have already submited a request for this item!"
//it also sets $_SESSION['allreadyRequested'] and $alrdyRequested = ""
if ($alrdyRequested == "yes"){
print <<<EOT
    <div class="alert">
    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
    <center style="color: red;">You have already submited a request for this item!</center>
    </div>
EOT;
    $_SESSION['allreadyRequested'] = "";
    $alrdyRequested = "";
}

//this if statement checks if the session varaible $_SESSION['success'] is set/ defined and if so prints and alert with the text
// "Your Request Has Been Submitted!"
//it also then unsets the session varaible $_SESSION['success']
if (isset($_SESSION['success'])){
print <<<EOT
<div class="alert">
  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
<center style="color: black;">Your Request Has Been Submitted!</center>
</div>
EOT;
unset($_SESSION['success']);
}

 //this if statement checks if the varaible $dateError holds the error text "* Your Borrow date cannot be after your Return date",
 // if it does than it prints the variable $dateError
 if($dateError == "* Your Borrow date cannot be after your Return date"){
 print <<<EOT
  <p style="color: red;">$dateError<p>
EOT;
}
//this if statement checks if the varaible $dateError2 holds the error text "* Your dates cannot be before the current day",
// if it does than it prints the variable $dateError2
if($dateError2 == "* Your dates cannot be before the current day"){
print <<<EOT
<p style="color: red;">$dateError2<p>
EOT;
}
 ?>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
  <table width="90%" border="1" cellspacing="1" cellpadding="0"  align="center" style="text-align: center; border: #2f5989;">
  <tbody>
    <tr>
      <td><strong>Item Name</strong></td>
      <td><strong>Item Description</strong></td>
      <td><strong>Quantity Available</strong></td>
      <td><strong>Quantity On Loan</strong></td>
    </tr>
<?php
//this while loop uses the varaible $row to access the results of the query $itemsQuery which is stored in an array.
while ($row = mysqli_fetch_array($itemsQuery)) {
  if ($row[4] > 0){ //only for results where Quantity Avaliable is greater than 0

    //the print <<<EOT code between lines 324 and 349 were adapted from: https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_login_form_modal
  //line number 326 contains id=$row[0] which makes a cookie containing the itemID of the item you click "request" on in order to pass to the next page
  //idea of cookie and help in execution by the one only: https://schoolbox.donvale.vic.edu.au/eportfolio/3310/profile
print <<<EOT
    	<tr>
          <td>$row[1]</td>
          <td>$row[2]</td>
          <td>$row[4]</td>
          <td>$row[3]</td>
          <td class="noBorder">

          <button onclick="document.getElementById('id01').style.display='block'; document.cookie = 'id=$row[0]'" style="width:auto;">Request</button>

          <div id="id01" class="modal">

            <form class="modal-content animate" action="requestingFunction.php" method="post">
              <div class="imgcontainer">
                <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
              </div>

              <div class="container">
                <label for="bDate"><b>Borrow Date</b></label>
                <input type="date" name="bDate" required>

                <label for="rDate"><b>Return Date</b></label>
                <input type="date" name="rDate" required>

                <button type="submit">Submit</button>
              </div>

              <div class="container" style="background-color:#f1f1f1">
                <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
              </div>
            </form>
          </div>
          </td>
      </tr>
EOT;

  }
  else { //if Quantity avalable ($row[4]) is 0
print <<<EOT
    	<tr>
          <td>$row[1]</td>
          <td>$row[2]</td>
          <td>$row[4]</td>
          <td>$row[3]</td>
          <td class="noBorder"><input type="submit" value="Not Avaliable" style="background-color: #A8A8A8;
      border: none;
      color: white;
      padding: 11px 12px;
      text-align: center;
      text-decoration: none;
      font-size: 15px;"></td>
      </tr>
EOT;
    }
}
?>
  </tbody>
</table>

  </div>
</div>


<footer class="footer"> <br>Prototype by Tim Betschel 2020 for DCC arts borrowing software.</footer>

</body>
</html>
