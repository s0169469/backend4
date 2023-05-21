<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Массив для временного хранения сообщений пользователю.
    $messages = array();

    // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
    // Выдаем сообщение об успешном сохранении.
    if (!empty($_COOKIE['save'])) {
      // Удаляем куку, указывая время устаревания в прошлом.
      setcookie('save', '', 100000);
      // Если есть параметр save, то выводим сообщение пользователю.
      $messages[] = 'Спасибо, результаты сохранены.';
    }

  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['fio'] = !empty($_COOKIE['fio_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['gender'] = !empty($_COOKIE['gender_error']);
  $errors['limbs'] = !empty($_COOKIE['limbs_error']);
  $errors['ability'] = !empty($_COOKIE['ability_error']);
  $errors['biography'] = !empty($_COOKIE['biography_error']);

  // Выдаем сообщения об ошибках.
  if ($errors['fio']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('fio_error', '', 100000);
    // Выводим сообщение.
    $messages[] = '<div class="error">Заполните имя. Допустимые символы: A-Z, a-z, А-Я, а-я, " " .</div>';
  }
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div class="error">Заполните email. Пример: "example@example.ex".</div>';
  }
  if ($errors['year']) {
    setcookie('year_error', '', 100000);
    $messages[] = '<div class="error">Заполните год. Выберете одно поле из списка.</div>';
  }
  if ($errors['gender']) {
    setcookie('gender_error', '', 100000);
    $messages[] = '<div class="error">Заполните пол. Выберете одно из допустимых значений: "ж","м".</div>';
  }
  if ($errors['limbs']) {
    setcookie('limbs_error', '', 100000);
    $messages[] = '<div class="error">Заполните количество конечностей. Выберете одно из допустимых значений: "1","2","3","4".</div>';
  }
  if ($errors['ability']) {
    setcookie('ability_error', '', 100000);
    $messages[] = '<div class="error">Заполните сверхспособности. Выберете одно или несколько полей из списка.</div>';
  }
  if ($errors['biography']) {
    setcookie('biography_error', '', 100000);
    $messages[] = '<div class="error">Заполните биографию. Допустимые значения: 0-9, A-Z, a-z, А-Я, а-я, " ", ".", пробельные символы.</div>';
  }
 

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
  $values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
  $values['limbs'] = empty($_COOKIE['limbs_value']) ? '' : $_COOKIE['limbs_value'];
  $values['ability'] = empty($_COOKIE['ability_value']) ? array() : json_decode($_COOKIE['ability_value']);
  $values['biography'] = empty($_COOKIE['biography_value']) ? '' : $_COOKIE['biography_value'];




  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('form.php');
  // Завершаем работу скрипта.
  exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else{
// Проверяем ошибки.
$errors = FALSE;
  if (empty($_POST['fio']) || !preg_match('/^([a-zA-Zа-яА-Я\s]{1,})$/', $_POST['fio'])) {
    // Выдаем куку на день с флажком об ошибке в поле fio.
    setcookie('fio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('fio_value', $_POST['fio'], time() + 30 * 24 * 60 * 60);
  }

if (empty($_POST['year']) || !is_numeric($_POST['year']) || !preg_match('/^\d+$/', $_POST['year'])) {
  setcookie('year_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}
else {
  setcookie('year_value', $_POST['year'], time() + 30 * 24 * 60 * 60);
}

if (empty($_POST['email']) || !preg_match('/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u',$_POST['email'])) {
  setcookie('email_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}
else {
  setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
}


if (empty($_POST['gender']) || ($_POST['gender']!='m' && $_POST['gender']!='w')) {
  setcookie('gender_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}
else {
  setcookie('gender_value', $_POST['gender'], time() + 30 * 24 * 60 * 60);
}

if (empty($_POST['limbs']) || ($_POST['limbs']!='1' && $_POST['limbs']!='2' && $_POST['limbs']!='3' && $_POST['limbs']!='4')) {
  setcookie('limbs_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}
else {
  setcookie('limbs_value', $_POST['limbs'], time() + 30 * 24 * 60 * 60);
}


if (empty($_POST['biography']) || !preg_match('/^([0-9a-zA-Zа-яА-Я\,\.\s]{1,})$/', $_POST['biography']) ){
  setcookie('biography_error', '1', time() + 24 * 60 * 60);
  $errors = TRUE;
}
else {
  setcookie('biography_value', $_POST['biography'], time() + 30 * 24 * 60 * 60);
}

foreach ($_POST['ability'] as $ability) {
  if($ability != '1' && $ability != '2' && $ability != '3' && $ability != '4'){
    setcookie('ability_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
    break;
  }
  
}
if (!empty($_POST['ability'])) {
  setcookie('ability_value', json_encode($_POST['ability']), time() + 24 * 60 * 60);
}
}



// *************
// TODO: тут необходимо проверить правильность заполнения всех остальных полей.
// Сохранить в Cookie признаки ошибок и значения полей.
// *************

if ($errors) {
  // При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
  header('Location: index.php');
  exit();
}
else {
  // Удаляем Cookies с признаками ошибок.
  setcookie('fio_error', '', 100000);
  // TODO: тут необходимо удалить остальные Cookies.
  setcookie('year_error', '', 100000);
  setcookie('email_error', '', 100000);
  setcookie('gender_error', '', 100000);
  setcookie('limbs_error', '', 100000);
  setcookie('biography_error', '', 100000);
  setcookie('ability_error', '', 100000);

}


// Сохранение в базу данных.

$user = 'u52996';
$pass = '6060818';
$db = new PDO('mysql:host=localhost;dbname=u52996', $user, $pass, [PDO::ATTR_PERSISTENT => true]);

// Подготовленный запрос. Не именованные метки.
try {
  $stmt = $db->prepare("INSERT INTO application SET name = ?, email=?, year=?, gender=?, biography=?, limbs=?");
  $stmt -> execute([$_POST['fio'], $_POST['email'],$_POST['year'],$_POST['gender'], $_POST['biography'],$_POST['limbs'] ]);
  $app_id = $db->lastInsertId();
  $stmt = $db->prepare("INSERT INTO ability_application SET ability_id= ?, application_id=?");
  foreach ($_POST['ability'] as $ability) {
    $stmt->execute([$ability, $app_id]);
  }
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}

//  stmt - это "дескриптор состояния".
 
//  Именованные метки.
//$stmt = $db->prepare("INSERT INTO test (label,color) VALUES (:label,:color)");
//$stmt -> execute(['label'=>'perfect', 'color'=>'green']);
 
//Еще вариант
/*$stmt = $db->prepare("INSERT INTO users (firstname, lastname, email) VALUES (:firstname, :lastname, :email)");
$stmt->bindParam(':firstname', $firstname);
$stmt->bindParam(':lastname', $lastname);
$stmt->bindParam(':email', $email);
$firstname = "John";
$lastname = "Smith";
$email = "john@test.com";
$stmt->execute();
*/

// Делаем перенаправление.
// Если запись не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
// Если ошибок при этом не видно, то необходимо настроить параметр display_errors для PHP.

// Сохраняем куку с признаком успешного сохранения.
setcookie('save', '1');
// Делаем перенаправление.
header('Location: index.php');
