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

//sets error variable to nothing
$nameExistenceErr = "";
$nameEmptyErr = "";
$nameCharErr = "";

//all three of the following if statements just check if a session varaible is set/ defined and if so than set the local error variable to equal the session varaible
if(isset($_SESSION['existingNameErr'])){
$nameExistenceErr = $_SESSION['existingNameErr'];
}

if(isset($_SESSION['emptyNameError'])){
$nameEmptyErr = $_SESSION['emptyNameError'];
}

if(isset($_SESSION['nameCharacterErr'])){
$nameCharErr = $_SESSION['nameCharacterErr'];
}



?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title> Search Students </title>
<link href="artloans.css" rel="stylesheet" type="text/css">
<!--css from: https://www.tutorialrepublic.com/php-tutorial/php-mysql-ajax-live-search.php -->
<style type="text/css">

    /* Formatting search box */
    .search-box{
        width: 87%;
        position: relative;
        display: inline-block;
        font-size: 14px;

    }

    .search-box input[type="text"]{
        height: 65px;
        padding: 5px 10px;
        border: 1px solid #CCCCCC;
        font-size: 24px;
    }
    .result{
        position: absolute;
        z-index: 999;
        top: 100%;
        left: 0;
    }
    .search-box input[type="text"], .result{
        width: 100%;
        box-sizing: border-box;
    }
    /* Formatting result items */
    .result p{
        margin: 0;
        padding: 7px 10px;
        border: 1px solid #CCCCCC;
        border-top: none;
        cursor: pointer;
    }
    .result p:hover{
        background: #f2f2f2;
    }
</style>
<!--script from: https://www.tutorialrepublic.com/php-tutorial/php-mysql-ajax-live-search.php -->
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.search-box input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");
        if(inputVal.length){
            $.get("backendStudentSearch.php", {term: inputVal}).done(function(data){
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });

    // Set search input value on click of result item
    $(document).on("click", ".result p", function(){
        $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
        $(this).parent(".result").empty();
    });
});
</script>
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
<?php
//this if statement checks if $nameExistenceErr is = the error message. if it is then the error must have been recognized and it then prints the error Message
//then it usets the session varaible $_SESSION['existingNameErr']
if($nameExistenceErr == "The first name you searched for doesn't exist try again"){
print <<<EOT
<p style="color: red;"> * $nameExistenceErr </p>
EOT;
unset($_SESSION['existingNameErr']);
}
//this if statement checks if $nameEmptyErr is = the required error message. if it is then the error must have been recognized and it then prints the error Message
//then it usets the session varaible $_SESSION['emptyNameError']
if($nameEmptyErr == "You have to enter a name!"){
print <<<EOT
<p style="color: red;"> * $nameEmptyErr </p>
EOT;
unset($_SESSION['emptyNameError']);
}
//this if statement checks if $nameCharErr is = the required error message. if it is then the error must have been recognized and it then prints the error Message
//then it usets the session varaible $_SESSION['nameCharacterErr']
if($nameCharErr == "You search must only contain letters"){
print <<<EOT
<p style="color: red;"> * $nameCharErr </p>
EOT;
unset($_SESSION['nameCharacterErr']);
}
 ?>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<form method="get" action="studentSearchedVal.php">
  <div class="search-box" style="margin: auto;">
      <input type="text" name="firstNameSearched" autocomplete="off" placeholder="Search Student..." style="text-align: center;"/>
      <div class="result"></div>
  </div>
  <input type="submit" style="background-color: #3d84cc;
  border: none;
  color: white;
  padding: 15px 30px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 20px;
  margin: 4px 24px;
  cursor: pointer;
  float: right;">
  </form>

  </div>
</div>

<footer class="footer"> <br>Prototype by Tim Betschel 2020 for DCC arts borrowing software.</footer>

</body>
</html>
