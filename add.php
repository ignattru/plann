<?php include __DIR__.'/config.php'; ?>
<html>
    <head>
        <title>Новая задача</title>

        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css" />
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/18.0.0/classic/ckeditor.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    </head>
<?php if(AUTH) { //Если мы авторизированы ?> 
<body>
    
<?php

#Скрипт с настройками соединения с базой
    require_once 'connection.php'; 
#Скрипты для отправки почты
    require 'phpmailer/PHPMailer.php';
    require 'phpmailer/SMTP.php';
    require 'phpmailer/Exception.php';

    if(isset($_POST['title']) && isset($_POST['body']) && isset($_POST['dcreate']) && isset($_POST['dplan']) && isset($_POST['important']) && isset($_POST['maker']) ){
    // подключаемся к серверу
        $link = mysqli_connect($host, $user, $password, $database) 
        or die("Ошибка " . mysqli_error($link)); 
	    $link->set_charset("utf8");
     
    // экранированиt символов для mysql
        $title = htmlentities(mysqli_real_escape_string($link, $_POST['title']));
        $body = $_POST['body'];
        $dcreate = htmlentities(mysqli_real_escape_string($link, $_POST['dcreate']));
        $dplan = htmlentities(mysqli_real_escape_string($link, $_POST['dplan'])); 
        $important = htmlentities(mysqli_real_escape_string($link, $_POST['important'])); 
        $maker = htmlentities(mysqli_real_escape_string($link, $_POST['maker'])); 
     
    // создание строки запроса
        $query ="INSERT INTO TASK_LIST VALUES(NULL, '$title','$body', '$dcreate', '$dplan', NULL, NULL, '$important', '$maker', '5' )";
        $queryId ="SELECT MAX(OUID) FROM TASK_LIST"; 
  
    // выполняем запрос
    $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link)); 

    $result_id = mysqli_query($link, $queryId) or die("Ошибка " . mysqli_error($link)); 
    while ($row = mysqli_fetch_row($result_id)) {
        $id = $row[0];
    }

    if($important == 1){
        $isimp = "Важно";
    } else {$isimp = "Не важно";}

    if($result)
    {
        echo "<div class='alert alert-success' role='alert'>Задача успешно создана</div>";
        
    // Сделаем табличку адресов почты для отправки:
        if ($maker == 1) { $addr = "a.v.sauckiv.ru"; }
        elseif ($maker == 2) { $addr = "i.b.hv.ru"; }
        elseif ($maker == 3) { $addr = "r.g.du.khv.ru";}
        elseif ($maker == 4) { $addr = "e.m.levshv.ru";}
        elseif ($maker == 5) { $addr = "i.v.levitdm.khv.ru";}
        elseif ($maker == 6) { $addr = "e.v.geram.khv.ru";}
        elseif ($maker == 7) { $addr = "evigndm.khv.ru";}
        elseif ($maker == 8) { echo "<div class='alert alert-danger' role='alert'>У бэтмена нет почты!</div>";}

    // Отправка на почту
       
        $mail = new PHPMailer\PHPMailer\PHPMailer();
            try {
        $msg = "<div class='alert alert-success' role='alert'>Письмо направлено на адрес: $addr</div>";
        $mail->isSMTP();   
        $mail->CharSet = "UTF-8";                                          
        $mail->SMTPAuth   = true;
    // Настройки вашей почты
        $mail->Host       = 'smtp.yandex.ru'; // SMTP сервер
        $mail->Username   = 'pup.vasan'; // Логин на почте
        $mail->Password   = 'Revenge34'; // Пароль на почте
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        $mail->setFrom('pup.vasan@yandex.ru', 'Трекер Задач'); // Адрес самой почты и имя отправителя, ответ будет на эту почту
    // Получатель письма
        $mail->addAddress($addr);  

        // ----------------------- 
        // Само письмо
        // -----------------------
        $mail->isHTML(true);
    
        $mail->Subject = 'В трекере создана новая задача';
        $mail->Body    = "
        <small>Таск Менеджер / задача №$id</small>
        <h1 style='margin-top: 1px;'><a href='https://tm.mszn27.ru/task.php?id=$id'>$title</a></h1>
        <b>Создан:</b> $dcreate <br>
        <b>Срок исполнения:</b> $dplan <br>
        <b>Приоритет:</b> $isimp
        <hr>
        <p>$body</p>
        
        <p>*Вы получили это письмо так как являетесь исполнителем задачи</p>
        ";

// gроверяем отравленность сообщения
if ($mail->send()) {
    echo "$msg";
} else {
echo "Сообщение не было отправлено. Неверно указаны настройки вашей почты";
}
} catch (Exception $e) {
    echo "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
}


    }
    // закрываем подключение
    mysqli_close($link);
}
?>

 <div class="container" style="background-color:#f0ad4e";>
    <a href="index.php" class="btn btn-default navbar-btn" role="button">Закрыть</a>
 </div>

 <div class="container" style="background-color:#fbfbfb";>
    <h1 style="text-align: center">
        <small>Добавить задачу</small>
    </h1>

    <form method="POST">
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="inputAddress">Заголовок:</label>
                <input type="text" class="form-control" id="inputAddress" placeholder="Заголовок задачи" name="title">
            </div>
        </div>
  
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="exampleFormControlTextarea1">Описание:</label>
                <textarea class="form-control" name="body" id="editor" placeholder="Описание задачи длинной не более 700 символов" rows="3"></textarea>
            </div>
        </div>
  
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="datetimepickerStart">Старт задачи:</label>
                <div class="input-group date" id="datetimepickerStart">
                    <span class="input-group-addon datepickerbutton"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" class="form-control" name="dcreate"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove2"></span></span>
                </div>
            </div>

            <div class="form-group col-md-6">
                <label for="datetimepickerEnd">Дата исполнения:</label>
                <div class="input-group date" id="datetimepickerEnd">
                    <span class="input-group-addon datepickerbutton"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="text" class="form-control" name="dplan"/>
                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove2" ></span></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="form-group col-md-6">
                <label for="inputState">Исполнитель:</label>
                <select id="inputState" class="form-control" name="maker">
                    <option value = "1" selected>user1</option>
                    <option value = "2">user2</option>
                    <option value = "3">user3</option>
                    <option value = "4">user4</option>
                </select>
            </div>
        </div>
  
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputState">Важно?</label>
                <select id="inputState" class="form-control" name="important">
                    <option value = "1" selected>Да</option>
                    <option value = "2">Нет</option>
                </select>
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
      $('#datetimepickerStart').datetimepicker({
	    locale: 'ru',
		stepping:10,
		format: 'YYYY-MM-DD',
		defaultDate: moment(),
		daysOfWeekDisabled:[0,6]
	});
        
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