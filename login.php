<?php
include __DIR__.'/config.php';

if(!AUTH) {
  // Еще не авторизованы
  if(!empty($_POST['login']) && !empty($_POST['password']) && isset($users[$_POST['login']])) {
      // Передали данные для входа и логин существует
      if($users[$_POST['login']]['password'] == getPassword($_POST['password'])) {
          // Пароль совпадает
          $_SESSION['user'] = $_POST['login'];

          if(isset($_POST['remember'])) {
            // Стоит галка "запомнить меня"
            setcookie('login', $_POST['login'], time() + 60, '/');
            setcookie('password', getPassword($users[$_POST['login']]['password']), time() + 60, '/');
          }

      }
  }
  if(!isset($_SESSION['user']) || $_SESSION['user'] != $_POST['login']) {
  // Авторизация не прошла, сохраним ошибку
    $_SESSION['message'] = 'Неверный логин или пароль';
  }
  // Выход из системы
} else { 
    if(isset($_GET['logout'])) { 
        unset($_SESSION['user']);
        setcookie('login', '', time() - 60, '/');
        setcookie('password', '', time() - 60, '/');
    }
}

header('Location: index.php'); // Переходим на главную страницу после авторизации