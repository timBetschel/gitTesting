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
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title> Home </title>
<link href="artloans.css" rel="stylesheet" type="text/css">
</head>

<body>
  <div class="menu">
    <table width="100%" height="100" bordercolor="black" cellspacing="0" border="0">
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
   <td cellpadding="0"><a href="home.php"> <img src="photos\dcc_logo.png" width="200px"></a></td>
   <td cellpadding="0" style="font-size:42px; color: white; font-family: 'Salsa', cursive; ">Arts Borrowing System</td>
   <td class="TopMenuText" style="cursor:pointer" onclick="location.href='items.php'"> Items</td>
   <td class="TopMenuText" style="cursor:pointer" onclick="location.href='students.php'">Student</td>
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
<br>
<br>
<br>
<br>
<br>
<table width="100%" bordercolor="black" cellspacing="0" border=0 style="text-align: center;">

  <colgroup>
  <col span="1" style="width: 22%;">
  <col span="1" style="width: 15%;">
  <col span="1" style="width: 10%;">
  <col span="1" style="width: 15%;">
  <col span="1" style="width: 10%;">
  <col span="1" style="width: 1%;">
  <col span="1" style="width: 21%;">
    </colgroup>

<tbody>
<tr>
  <td> &nbsp</td>
  <td><a href="items.php"><img src="photos\itemsLogo.png"  height="220" style="opacity: 1"><strong style="font-size: 24px;"> Items</strong></a></td>
  <td> &nbsp</td>
  <td><a href="students.php"><img src="photos\studentLogo.png"  height="220" style="opacity: 1"><strong style="font-size: 24px;"> Students</strong></a></td>
  <td> &nbsp</td>
  <td style="background-color: ;"><a href="requests.php"><img src="photos\requestLogo.png"  height="220" style="opacity: 1"><strong style="font-size: 24px;"> Requests</strong></a></td>
  <td> &nbsp</td>
</tr>

 </tbody>
 </table>
</div>
</div>


<footer class="footer"> <br>Prototype by Tim Betschel 2020 for DCC arts borrowing software.</footer>

</body>
</html>
