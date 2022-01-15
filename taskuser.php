<html>
    <head>
        <title>Задачи исполнителя</title>
        <meta charset="utf-8">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" >
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> 
    </head>
    <body>
 <?php
  $id = (int) $_GET[ "id" ];
  require_once 'connection.php'; 
 
// Подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) 
	or die("Ошибка " . mysqli_error($link));
$link->set_charset("utf8");
	
$query ="
SELECT
 tl.OUID AS idTask
 ,tl.T_TITLE AS title
 ,tl.T_BODY AS body
 ,tl.T_CREATEDATE AS dCreate
 ,tl.T_PLANDATE AS dPlan
 ,tl.T_FACTDATE AS dFact
 ,r.A_NAME AS report
 ,i.A_NAME AS isImportant
 ,users.A_NAME AS users
 ,tStatus.A_NAME AS statusTask 
 /*Характеристики тасков row[10 - n]*/
 ,i.OUID AS isimpid
 ,tStatus.OUID AS statId 
 ,(SELECT CURDATE()) AS timestmp
 ,tl.T_MAKER AS makert

FROM TASK_LIST tl
LEFT JOIN IMPORTANT i ON i.OUID = tl.T_IMPORTANT
LEFT JOIN REPORT r ON r.OUID = tl.T_REPORT
LEFT JOIN STATUS_TASK tStatus ON tStatus.OUID = tl.T_STATUS_TASK
LEFT JOIN USERS users ON users.OUID = tl.T_MAKER

WHERE tl.T_MAKER = $id

ORDER BY tl.T_CREATEDATE
";
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
$name = mysqli_fetch_row($result);
?>
 
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12" style="padding-top: 70px;">
            <nav class="navbar navbar-expand-lg fixed-top navbar-dark" style="background-color: #FF9933;">
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
         
        <a class="nav-link" href="https://wa.me/+79243015855?text=Привет%20я%20по%20поводу%20задачи%20">Написать исполнителю</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-3" id="myInput" type="search" placeholder="Поиск по задачам" aria-label="Search">
    </form>
 
  </div>
</nav>  

<div class="page-header">
        <h1>
            <small>Задачи исполнителя: <?php echo $name[8] ?></small>
        </h1>
</div>
&nbsp
<section class="tabs">
<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true" >НА ИСПОЛНЕНИИ</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">ГОРИТ</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">ВАЖНОЕ</a>
  </li>
    <li class="nav-item">
    <a class="nav-link" id="deyatel-tab" data-toggle="tab" href="#deyatel" role="tab" aria-controls="deyatel" aria-selected="false">АРХИВ</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

    &nbsp;
    
   <div class="table-responsive">
    <table class="table table-hover table-md" id="mytable">
    <thead class="thead-light">
    <tr>
        <th> </th>
        <th> </th>
        <th data-sortable="true">#</th>
        <th>Задача</th>
        <!--<th>Описание</th>-->
        <th data-sortable="true">Создано</th>
        <th data-sortable="true">Дедлайн</th>
        <!--<th data-sortable="true">Исполнено</th>-->
        <!--<th>Отчет</th>-->
        <th>Важно</th>
        <!--<th data-sortable="true">Исполнители</th>-->
        <!--<th>Статус</th>-->
    </tr>
    </thead>
    <tbody id="myTable">
    
<?php
   
  
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));     
if($result)
{   
	while ($row = mysqli_fetch_row($result)) {
	    if($row[11] === '5') {
	     if($row[4] <= $row[12]){echo "<tr style='background-color: #ff000029;'>";}
	    echo "<td> <a href='task.php?id=",$row[0],"'>", "<img src='img/lupa.svg' width='18' height='18' class='d-inline-block align-top'>","</a></td>";
	    echo "<td> <a href='edit.php?id=",$row[0],"'>", "<img src='img/edit.svg' width='18' height='18' class='d-inline-block align-top'>","</a></td>";
        echo "<td>",$row[0],"</td>";
        echo "<td> <a href='task.php?id=",$row[0],"'>",$row[1],"</a></td>";
        // echo "<td>",$row[2],"</td>";
        echo "<td>",$row[3],"</td>";
        echo "<td>",$row[4],"</td>";
        //echo "<td>",$row[5],"</td>";
        //echo "<td>",$row[6],"</td>";
        echo "<td>",$row[7],"</td>";
        //echo "<td>",$row[8],"</td>";
        //echo "<td>",$row[9],"</td>";
        echo "</tr>";
	    }
    }
}

?>
</tbody>
</table>

</div>
</div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    &nbsp;
    &nbsp;
    <div class="table-responsive">
        <table class="table table-hover table-md" id="mytable">
    <thead class="thead-light">
    <tr>
        <th> </th>
        <th> </th>
        <th>#</th>
        <th>Задача</th>
        <!--<th>Описание</th>-->
        <th>Создано</th>
        <th>Дедлайн</th>
        <!--<th>Исполнено</th>-->
        <!--<th>Отчет</th>-->
        <th>Важно</th>
        <!--<th>Исполнители</th>-->
        <!--<th>Статус</th>-->
    </tr>
    </thead>
    <tbody id="myTable">
    
<?php
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
if($result)
{   
	while ($row = mysqli_fetch_row($result)) {
	    if($row[4] >= $row[12] AND $row[11] === '5' ) {
	    echo "<tr>";
	    echo "<td> <a href='task.php?id=",$row[0],"'>", "<img src='img/lupa.svg' width='18' height='18' class='d-inline-block align-top'>","</a></td>";
	    echo "<td> <a href='edit.php?id=",$row[0],"'>", "<img src='img/edit.svg' width='18' height='18' class='d-inline-block align-top'>","</a></td>";
        echo "<td>",$row[0],"</td>";
        echo "<td> <a href='task.php?id=",$row[0],"'>",$row[1],"</a></td>";
        //echo "<td>",$row[2],"</td>";
        echo "<td>",$row[3],"</td>";
        echo "<td>",$row[4],"</td>";
        //echo "<td>",$row[5],"</td>";
        //echo "<td>",$row[6],"</td>";
        echo "<td>",$row[7],"</td>";
        //echo "<td>",$row[8],"</td>";
        //echo "<td>",$row[9],"</td>";
        echo "</tr>";
	    }
    }
}

?>
</tbody>
</table>
</div>
&nbsp;
</div>
  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
    &nbsp;
    &nbsp;
    <div class="table-responsive">
        <table class="table table-hover table-md" id="mytable">
    <thead class="thead-light">
    <tr>
        <th> </th>
        <th> </th>
        <th>#</th>
        <th>Задача</th>
        <!--<th>Описание</th>-->
        <th>Создано</th>
        <th>Дедлайн</th>
        <!--<th>Исполнено</th>-->
        <!--<th>Отчет</th>-->
        <!--<th>Важно</th>-->
        <!--<th>Исполнители</th>-->
        <!--<th>Статус</th>-->
    </tr>
    </thead>
    <tbody id="myTable">
    
<?php
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
if($result)
{   
	while ($row = mysqli_fetch_row($result)) {
	    if($row[10] === '1' and  $row[11] === '5' ) {
	   if($row[4] <= $row[12]){echo "<tr style='background-color: #ff000029;'>";}
	    echo "<td> <a href='task.php?id=",$row[0],"'>", "<img src='img/lupa.svg' width='18' height='18' class='d-inline-block align-top'>","</a></td>";
	    echo "<td> <a href='edit.php?id=",$row[0],"'>", "<img src='img/edit.svg' width='18' height='18' class='d-inline-block align-top'>","</a></td>";
        echo "<td>",$row[0],"</td>";
        echo "<td> <a href='task.php?id=",$row[0],"'>",$row[1],"</a></td>";
        //echo "<td>",$row[2],"</td>";
        echo "<td>",$row[3],"</td>";
        echo "<td>",$row[4],"</td>";
        //echo "<td>",$row[5],"</td>";
        //echo "<td>",$row[6],"</td>";
        //echo "<td>",$row[7],"</td>";
        //echo "<td>",$row[8],"</td>";
        //echo "<td>",$row[9],"</td>";
        echo "</tr>";
	    }
    }
}

?>
</tbody>
</table>
        </div>    
</div>
  <div class="tab-pane fade" id="deyatel" role="tabpanel" aria-labelledby="deyatel-tab">
&nbsp;
&nbsp;
    <div class="table-responsive">
        <table class="table table-hover table-md" id="mytable">
    <thead class="thead-light">
    <tr>
        <th> </th>
        <th> </th>
        <th>#</th>
        <th>Задача</th>
        <!--<th>Описание</th>-->
        <th>Создано</th>
        <th>Дедлайн</th>
        <th>Исполнено</th>
        <th>Отчет</th>
        <th>Важно</th>
        <!--<th>Исполнители</th>-->
        <th>Статус</th>
    </tr>
    </thead>
    <tbody id="myTable">
    
<?php
$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
if($result)
{   
	while ($row = mysqli_fetch_row($result)) {
	    if($row[11] === '3' or $row[11] === '1') {
	    echo "<tr>";
	    echo "<td> <a href='task.php?id=",$row[0],"'>", "<img src='img/lupa.svg' width='18' height='18' class='d-inline-block align-top'>","</a></td>";
	    echo "<td> <a href='edit.php?id=",$row[0],"'>", "<img src='img/edit.svg' width='18' height='18' class='d-inline-block align-top'>","</a></td>";
        echo "<td>",$row[0],"</td>";
        echo "<td> <a href='task.php?id=",$row[0],"'>",$row[1],"</a></td>";
       // echo "<td>",$row[2],"</td>";
        echo "<td>",$row[3],"</td>";
        echo "<td>",$row[4],"</td>";
        echo "<td>",$row[5],"</td>";
        echo "<td>",$row[6],"</td>";
        echo "<td>",$row[7],"</td>";
        //echo "<td>",$row[8],"</td>";
        echo "<td>",$row[9],"</td>";
        echo "</tr>";
	    }
    }
}

?>
</tbody>
</table>
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
<?php
mysqli_free_result($result);
 // закрываем подключение
mysqli_close($link);
?>

</body>
  
</html>