<html>
<head>
<title>Таск менеджер</title>
<meta charset="utf-8">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">

<style>
    .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
    color: #495057;
    background-color: #f7f7f7;
    border-color: #dee2e6 #dee2e6 #f7f7f7;
}

.tab-content>.active {
    display: block;
    background-color: #f7f7f7;
    border-color: #dee2e6 #dee2e6 #dee2e6;w 
    border-left: 1px solid #dee2e6;
    border-right: 1px solid #dee2e6;
    border-bottom: 1px solid #dee2e6;
}

.semiCircle {
  position: relative;
  background: #f0ad4e;
  height: 6vh;
}

.semiCircle::before {
  position: absolute;
  content: '';
  left: 50%;
  z-index: 10;
  width: 66px;
  height: 75px;
  border-radius: 50%;
  background: inherit;
  transform: translateX(-50%) translateY(42%);
  bottom: 0px;
}

.table td, .table th {
    vertical-align: middle;
}
</style>

 <?php
 
 require_once 'connection.php'; // подключаем скрипт соединения с базой
 
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) 
    or die("Ошибка " . mysqli_error($link));
$link->set_charset("utf8");
    
$query ="
SELECT
 usr.OUID 
,usr.A_NAME 
,post.A_NAME
,otdel.A_NAME
,IFNULL(usr.EMAIL, 'Не заполнено')
,usr.EMAIL
 
FROM USERS usr
   LEFT JOIN POST post ON post.OUID = usr.T_POST
   LEFT JOIN OTDEL otdel ON otdel.OUID = usr.T_OTDEL
WHERE usr.OUID != 999
ORDER BY usr.A_NAME

";
?>

</head>
<body>
	<section> <!-- контейнер с треугольником -->
  <div class="semiCircle">

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12" style="padding-top: 70px;">
            <nav class="navbar navbar-expand-lg fixed-top navbar-dark" style="background-color: #f0ad4e;">
    <a class="navbar-brand" href="index.php">
    <img src="img/work.svg" width="32" height="32" class="d-inline-block align-top" alt="">
    <b>Таск менеджер</b>
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
      <li class="nav-item active">
        <a class="nav-link" href="">Отдел информатизации</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-3" id="myInput" type="search" placeholder="Поиск по штатке" aria-label="Search">
    </form>
 </div>
    </section>

  </div>
</nav>  

<div class="container">

&nbsp;
&nbsp;
<div class="page-header">
        <h1>
            <small>Настройки штатки</small>
        </h1>
</div>
&nbsp;
<section class="tabs">
<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true" >Справочник сотрудников</a>
  </li>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

    &nbsp;
    <div class="container-fluid">
   <div class="table-responsive">
    <table class="table table-hover table-md" id="myTable">
    <thead class="thead-light">
    <tr>
        <th> </th>
        <th>ФИО</th>
        <th>Должность</th>
        <th>Отдел</th>
        <th>Эл. Почта</th>
    </tr>
    </thead>
    <tbody>
    
<?php
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));     
if($result)
{   
    while ($row = mysqli_fetch_row($result)) {
     echo "<tr>";
        echo "<td> <a href='shtat.php?id=",$row[0],"'>","<img src='img/edit.svg' width='18' height='18' class='d-inline-block align-top'>","</a></td>";
        echo "<td>",$row[1],"</td>";
        echo "<td>",$row[2],"</td>";
        echo "<td>",$row[3],"</td>";
        echo "<td width='150'>",$row[4],"</td>";
     echo "</tr>";
       }
}

?>
</tbody>
</table>
</div>
</div>

</div>

<!-- Горячий поиск по задачам -->
<script>
    $(document).ready(function(){
     $("#myInput").on("keyup", function() {
     var value = $(this).val().toLowerCase();
     $("#myTable tr").filter(function() {
         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    });
</script>
</div>
</div>

</div>
</section>
</div>
        </div>
    </div>
</div>
</div>
<?php
mysqli_free_result($result);
// закрываем подключение
mysqli_close($link);
?>

</body>
</html>