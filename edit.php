<?php include __DIR__.'/config.php'; ?>
<html>
  <head>
    <title>Редактировать задачу</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/18.0.0/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" charset="utf-8">
  </head>
  <?php if(AUTH) { //Если мы авторизированы ?>
<body>
  <style>
  .page-header {
     padding-bottom: 0px; 
     margin: 0px 0 0px;
   }
  </style>

 <?php
  $id = (int) $_GET[ "id" ];
  require_once 'connection.php';
 
  // подключаемся к серверу
  $link = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($link));
  $link->set_charset("utf8");
	
$query ="
SELECT
 /*row[0] */
 tList.OUID  
 /*row[1] */
 ,tList.T_TITLE
 /* row[2]  */
 ,tList.T_BODY  
 /* row[3] */
 ,tList.T_CREATEDATE
 /* row[4] */
 ,tList.T_PLANDATE
 /* row[5] */
 ,tList.T_FACTDATE
 /* row[6] */
 ,report.A_NAME   
 /* row[7] */
 ,imp.A_NAME
 /* row[8] */
 ,users.A_NAME    
 /* row[9] */
 ,tStatus.A_NAME
  /* row[10] */
 ,imp.OUID
 /* row[11] */
 ,tStatus.OUID 
 /* row[12] */
 ,(SELECT CURDATE())
 /* row[13] */
 ,users.OUID

FROM TASK_LIST tList
  LEFT JOIN IMPORTANT imp ON imp.OUID = tList.T_IMPORTANT
  LEFT JOIN REPORT report ON report.OUID = tList.T_REPORT
  LEFT JOIN STATUS_TASK tStatus ON tStatus.OUID = tList.T_STATUS_TASK
  LEFT JOIN USERS users ON users.OUID = tList.T_MAKER
WHERE tList.OUID = $id
";

$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 
$row = mysqli_fetch_row($result);


if(isset($_POST['dfact']) && isset($_POST['report']) && isset($_POST['statustask']) && isset($_POST['title']) && isset($_POST['body']) && isset($_POST['important']) && isset($_POST['maker'])){
    
    // экранирования символов
    $dfact = $_POST['dfact']; 
    $report = $_POST['report']; 
    $title = strip_tags($_POST['title']); 
    $body = $_POST['body']; 
    $statustask = $_POST['statustask'];
    $important = $_POST['important'];
    $maker = $_POST['maker'];
    
    // создание строки запроса 
    $sql_insert_report = "INSERT INTO REPORT VALUES(NULL, '$report')";
    
    $query2 ="UPDATE TASK_LIST SET T_FACTDATE = '$dfact', T_REPORT = (SELECT MAX(OUID) FROM REPORT), T_STATUS_TASK = '$statustask', T_TITLE = '$title', T_BODY = '$body', T_IMPORTANT = '$important', T_MAKER = '$maker' WHERE TASK_LIST.OUID = $id ";
    
     $resultinsert = mysqli_query($link, $sql_insert_report) or die("Ошибка " . mysqli_error($link)); 
    
    $result2 = mysqli_query($link, $query2) or die("Статус задачи не выбран!"); 
    if($result2)
    {
        echo "<div class='alert alert-success' role='alert'>Задача успешно обновлена</div>";
    }
   mysqli_free_result($result2);
   mysqli_free_result($resultinsert);
}
?>

  <div class="container" style="background-color:#f0ad4e";>
    <a href="index.php" class="btn btn-default navbar-btn" role="button">Закрыть</a>
    <b style="color:#fff;">&nbsp;&nbsp;Задача</b>
    <span class="label label-default"><?php echo $row[0]; ?></span>
  </div>

<div class="container" style="background-color:#fbfbfb";>
<div class="page-header">
        <h2>
            <b><?php echo $row[1]; ?></b>
        </h2>
        <p>
            <span class="label label-warning"><?php echo $row[9]; ?></span>
        </p>
</div>
  <div class="row">

  <form method="POST">

<div class="form-row">
    <div class="form-group col-md-12">
    <label for="inputAddress">Заголовок:</label>
    <input type="text" class="form-control" id="inputAddress" value="<?php echo $row[1]; ?>" name="title">
    </div>
  </div>

<div class="form-row">
    <div class="form-group col-md-12">
    <label for="exampleFormControlTextarea3">Описание</label>
    <textarea class="form-control" name="body" id="editor" placeholder="Описание задачи не более 700 символов" rows="3"><?php echo $row[2]; ?></textarea>
    </div>
  </div>


  <div class="form-group">
   <div class="form-group col-md-6">
    <label for="inputState">Сменить исполнителя:</label>
      <select id="inputState" class="form-control" name="maker">
  <?php 
  $sqlMaker = "SELECT usr.OUID, usr.A_NAME FROM USERS usr";
  $linkMaker = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($linkMaker));
  $linkMaker->set_charset("utf8"); 

    $resultMaker = mysqli_query($linkMaker, $sqlMaker) or die("Ошибка " . mysqli_error($linkMaker)); 
    if($resultMaker) {   
        while ($rowMaker = mysqli_fetch_row($resultMaker)) { 
            if ($rowMaker[0] == $row[13]) { $selected = "selected";
            } else {
              $selected = " ";
            }
            echo "<option value = '$rowMaker[0]' $selected>",$rowMaker[1],"</option>";
        }
    }
    mysqli_free_result($resultMaker);
   mysqli_close($linkMaker);
?>
      </select>
    </div>
  </div>
  
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputState">Важно?</label>
      <select id="inputState" class="form-control" name="important">
         <?php 
  $sqlIfImport = "SELECT imp.OUID, imp.A_NAME FROM IMPORTANT imp";
  $linkIfImport = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($linkIfImport));
  $linkIfImport->set_charset("utf8"); 

    $resultIfImport = mysqli_query($linkIfImport, $sqlIfImport) or die("Ошибка " . mysqli_error($linkIfImport)); 
    if($resultIfImport) {   
        while ($rowIfImport = mysqli_fetch_row($resultIfImport)) { 
            if ($rowIfImport[0] == $row[10]) { $selected = "selected";
            } else {
              $selected = " ";
            }
            echo "<option value = '$rowIfImport[0]' $selected>",$rowIfImport[1],"</option>";
        }
    }
    mysqli_free_result($resultIfImport);
   mysqli_close($linkIfImport);
?>
      </select>
    </div>
  
  <div class="form-row">
    <div class="form-group">
      <div class="form-group col-md-6">
        <label for="inputState">Статус:</label>
        <select id="inputState" class="form-control" name="statustask">
       <?php 
        $sqlStatus = "SELECT stat.OUID, stat.A_NAME FROM STATUS_TASK stat";
        $linkStatus = mysqli_connect($host, $user, $password, $database) or die("Ошибка " . mysqli_error($linkStatus));
        $linkStatus->set_charset("utf8"); 

        $resultStatus = mysqli_query($linkStatus, $sqlStatus) or die("Ошибка " . mysqli_error($linkStatus)); 
        if($resultStatus) {   
          while ($rowStatus = mysqli_fetch_row($resultStatus)) { 
            if ($rowStatus[0] == $row[11]) { $selected = "selected";
            } else {
                $selected = " ";
              }
            echo "<option value = '$rowStatus[0]' $selected>",$rowStatus[1],"</option>";
            }
        }
        mysqli_free_result($resultStatus);
        mysqli_close($linkStatus);
        ?>
        </select>
      </div>
  </div>
    <div class="form-group col-md-6">
      <label for="datetimepickerEnd">Исполнено:</label>
      <div class="input-group date" id="datetimepickerEnd">
        <span class="input-group-addon datepickerbutton">
          <span class="glyphicon glyphicon-calendar"></span>
        </span>
      <input type="text" class="form-control" name="dfact"/>
        <span class="input-group-addon">
          <span class="glyphicon glyphicon-remove2" ></span>
        </span>
      </div>
    </div>
  </div>
 
<div class="form-row">
  <div class="form-group col-md-12">
    <label for="exampleFormControlTextarea1">Отчет об исполнении:</label>
    <textarea class="form-control" name="report" id="editorReport" placeholder="Введите отчет об исполнении" rows="3"><?php echo $row[6]; ?></textarea>
  </div>
</div>

<div class="form-row">
  <div class="form-group col-md-6">
    <button type="submit" class="btn btn-warning">Сохранить</button>
  </div>
</div>
</form>
</div>

<script src="js/jquery-3.2.1.min.js"></script>
  <script src="js/moment-with-locales.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/bootstrap-datetimepicker.min.js"></script>
  <script type="text/javascript">
    $(function () {
        $('#datetimepickerEnd').datetimepicker({
	    locale: 'ru',
		stepping:10,
		format: 'YYYY-MM-DD',
		defaultDate: moment(),
		daysOfWeekDisabled:[0,6]
	    });
    });

    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } );

  ClassicEditor
        .create( document.querySelector( '#editorReport' ) )
        .catch( error => {
            console.error( error );
        } );
  </script>
<?php
 // закрываем подключение
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