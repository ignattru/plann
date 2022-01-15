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
    <?php if(AUTH) { //Если мы авторизированы ?>
<body>

<?php
  $id = (int) $_GET[ "id" ];
  require_once 'connection.php';
 
  // подключаемся к серверу
  $link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
	$link->set_charset("utf8");
	
$query ="
  SELECT
 /*row[0] OUID таска */
  tList.OUID AS idTask  
 /*row[1] заголовок*/
  ,tList.T_TITLE AS title 
 /* row[2] описание*/
  ,IFNULL(tList.T_BODY, 'Нет описания')
 /* row[3] дата создания*/
  ,tList.T_CREATEDATE AS dCreate
 /* row[4] дата дедлайна*/
  ,tList.T_PLANDATE AS dPlan 
 /* row[5] дата фактического исполнения*/
  ,tList.T_FACTDATE AS dFact 
 /* row[6] текст отчета*/
  ,report.A_NAME AS report   
 /* row[7] важно?*/
  ,imp.A_NAME AS isImportant 
 /* row[8] имя исполнителя*/
  ,users.A_NAME AS users      
 /* row[9] статус задачи*/
  ,tStatus.A_NAME AS statusTask  
  /* row[10] OUID (важно)*/
  ,imp.OUID AS isimpid
 /* row[11] OUID статуса задачи */
  ,tStatus.OUID AS statId 
 /* row[12] текущая дата*/
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
<?php } else { //Если мы не авторизированы  ?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css" />
<style>
.form-width {max-width: 25rem;}
.has-float-label {
 position: relative; }
 .has-float-label label {
 position: absolute;
 left: 0;
 top: 0;
 cursor: text;
 font-size: 75%;
 opacity: 1;
 -webkit-transition: all .2s;
 transition: all .2s;
 top: -.5em;
 left: 0.75rem;
 z-index: 3;
 line-height: 1;
 padding: 0 1px; }
 .has-float-label label::after {
 content: " ";
 display: block;
 position: absolute;
 background: white;
 height: 2px;
 top: 50%;
 left: -.2em;
 right: -.2em;
 z-index: -1; }
 .has-float-label .form-control::-webkit-input-placeholder {
 opacity: 1;
 -webkit-transition: all .2s;
 transition: all .2s; }
 .has-float-label .form-control:placeholder-shown:not(:focus)::-webkit-input-placeholder {
 opacity: 0; }
 .has-float-label .form-control:placeholder-shown:not(:focus) + label {
 font-size: 150%;
 opacity: .5;
 top: .3em; }

.input-group .has-float-label {
 display: table-cell; }
 .input-group .has-float-label .form-control {
 border-radius: 0.25rem; }
 .input-group .has-float-label:not(:last-child) .form-control {
 border-bottom-right-radius: 0;
 border-top-right-radius: 0; }
 .input-group .has-float-label:not(:first-child) .form-control {
 border-bottom-left-radius: 0;
 border-top-left-radius: 0;
 margin-left: -1px; }
 body {
    background: linear-gradient(to bottom, #ff9900 0%, #ffcc00 100%);
 }
</style>

<div class="p-x-1 p-y-3">
    <form class="card card-block m-x-auto bg-faded form-width" action="login.php" method="post">
    <legend class="m-b-1 text-xs-center">Войти</legend>
    <div class="form-group input-group">
    <span class="has-float-label">
    <input class="form-control" id="first" type="text" name="login" placeholder="Логин"/>
    <label for="first">Логин</label>
    </span>
    </div>
    <div class="form-group has-float-label">
    <input class="form-control" id="password" type="password" name="password" placeholder="••••••••"/>
    <label for="password">Пароль</label>
    </div>
    <div class="form-group">
     <label class="custom-control custom-checkbox">
    <input class="custom-control-input" type="checkbox" name="remember"/>
    <span class="custom-control-indicator"></span>
    <span class="custom-control-description">Запомнить меня</span>
    </label>
    <?php if(!empty($message)) { ?>
    <p><?php echo $message; ?></p>
    <?php } ?>
    </div>
    <div class="text-xs-center">
    <button class="btn btn-block btn-warning" type="submit">Вход</button>
    </div>
    </form>
</div>
<?php } ?>
</body>
</html>