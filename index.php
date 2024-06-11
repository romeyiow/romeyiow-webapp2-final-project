<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1Welcome,
    sign up to continue.0" />
  <title>Login Page</title>
  <link rel="stylesheet" href="assets/css/styles.css" />
</head>

<body>
  <?php
  require 'assets/php/config.php';
  // print_r($_SESSION);
  $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
  $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

  
  try {
    $pdo = new PDO($dsn, $user, $password, $options);

    if ( "POST" === $_SERVER['REQUEST_METHOD'] ) {
      $input_username = $_POST['username'];
      $input_password = $_POST['password'];

      $query = "SELECT * FROM users WHERE username = :username";
      $statement = $pdo->prepare($query);
      $statement->execute([':username' => $input_username]);

      $user = $statement->fetch(PDO::FETCH_ASSOC);

      if ($user) {
        if ('secret123' ===  $input_password ) {
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['username'] = $user['username'];

          header("Location: assets/php/posts.php?userId=" . $user['id']);
          exit;
        } else {
          echo "Incorrect password!";
        }
      } else {
        echo "User not found!";
      }
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
  ?>


  <section class="pseudo-screen">
    <div class="screen-info">
      <div class="left-infos">
        <img src="assets/images/vec-time.png" alt="" />
        <img src="assets/images/vec-4g.png" alt="" />
      </div>
      <div class="right-infos">
        <img src="assets/images/vec-signal.png" alt="" />
        <img src="assets/images/vec-wifi.png" alt="" />
        <img src="assets/images/vec-battery.png" alt="" />
      </div>
    </div>
    <div class="greetings flex-col-center-center">
      <img src="assets/images/illust-login.png" alt="" />
      <h1>Welcome Back!</h1>
      <p>Please log in to your existing account</p>
    </div>
    <div class="form-wrapper flex-col-center-center">
      <form id="loginForm" class="flex-col-center-center" method="POST"
        action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <input type="text" id="username" placeholder="Your username" name="username" required />
        <input type="password" id="password" placeholder="Your password" name="password" required />
        <button id="submit">Log In</button>
      </form>
    </div>
  </section>
</body>

</html>