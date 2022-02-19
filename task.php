<?php include __DIR__.'/config.php'; ?>
<html>
    <head>
        <title>Просмотр задачи</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>
<body>

<?php
  $id = (int) $_GET[ "id" ];
  require_once 'connection.php';
 
  // подключаемся к серверу
  $link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
	$link->set_charset("utf8");
	
$query ="
  SELECT
  tList.OUID AS idTask  
  ,tList.T_TITLE AS title 
  ,IFNULL(tList.T_BODY, 'Нет описания')
  ,tList.T_CREATEDATE AS dCreate
  ,tList.T_PLANDATE AS dPlan 
  ,tList.T_FACTDATE AS dFact 
  ,report.A_NAME AS report   
  ,imp.A_NAME AS isImportant 
  ,users.A_NAME AS users      
  ,tStatus.A_NAME AS statusTask  
  ,imp.OUID AS isimpid
  ,tStatus.OUID AS statId 
  ,(SELECT CURDATE()) AS timestmp

  FROM TASK_LIST tList
    LEFT JOIN IMPORTANT imp ON imp.OUID = tList.T_IMPORTANT
    LEFT JOIN REPORT report ON report.OUID = tList.T_REPORT
    LEFT JOIN STATUS_TASK tStatus ON tStatus.OUID = tList.T_STATUS_TASK
    LEFT JOIN USERS users ON users.OUID = tList.T_MAKER
  WHERE tList.OUID = $id
";

$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
$row = mysqli_fetch_row($result);
?>

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
                        <a href="index.php" class="btn btn-light btn-md my-2 my-sm-0" role="button">Закрыть</a>
                </div>
            </nav>  
        </div>
    </div>
</div>
&nbsp;
<div class="container" > 
  <div class="page-header">
    <h4><b>Карточка задачи #<?php echo $row[0]; ?></b></h4>
    <span class="badge badge-warning"><?php echo $row[9]; ?></span>
  </div>
  &nbsp;
  <div class="row" > 
      <div class="col-9" style="background-color:#fbfbfb";>
        <h2><?php echo $row[1]; echo "<a href='edit.php?id=",$row[0],"'>", "<img src='img/edit.svg' width='18' height='18' class='d-inline-block align-top'>","</a>"; ?></h2>
        <?php echo $row[2]; ?>        
      </div>
      <div class="col-sm-3"> 
        <b>Исполнитель:</b>
        <p><?php echo $row[8]; ?></p>
        <b>Дата создания:</b>
        <p><?php echo $row[3]; ?></p>
        <b>Дедлайн:</b>
        <p><?php echo $row[4]; ?></p>
        <b>Исполнено:</b>
        <p><?php echo $row[5]; ?></p>
        <b>Важно?</b>
        <p><?php echo $row[7]; ?></p>
      </div> 
    <hr />
  </div> 
<hr />
<b>Отчет:</b>
<?php echo $row[6]; ?>
</div>
<?php
  mysqli_free_result($result);
  mysqli_close($link);
?>

</body>
</html>