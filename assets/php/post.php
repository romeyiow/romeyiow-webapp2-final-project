<?php
require 'config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../../index.php");
  exit;
}

$postId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($postId == 0) {
  echo "Invalid Post ID.";
  exit;
}

$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
  $pdo = new PDO($dsn, $user, $password, $options);

  
  $query = "SELECT p.*, u.name, u.email, dp.thumbnail_url AS DPUrl, vp.url AS VisualUrl
              FROM posts p
              JOIN users u ON p.user_id = u.id
              LEFT JOIN photos dp ON dp.id = p.id
              LEFT JOIN photos vp ON vp.id = (p.id + 2)
              WHERE p.id = :postId";
  $statement = $pdo->prepare($query);
  $statement->execute([':postId' => $postId]);
  $post = $statement->fetch(PDO::FETCH_ASSOC);

  if (!$post) {
    echo "Post not found.";
    exit;
  }

  $name = htmlspecialchars($post['name']);
  $username = htmlspecialchars(explode("@", $post['email'])[1]);
  $title = htmlspecialchars($post['title']);
  $body = ucfirst(htmlspecialchars($post['body']));
  $DPUrl = htmlspecialchars($post['DPUrl'] ?? 'https://via.placeholder.com/150/c96cad');
  $VisualUrl = htmlspecialchars($post['VisualUrl'] ?? 'https://via.placeholder.com/600/35185e');

} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Post Page</title>
  <link rel="stylesheet" href="../css/base.css" />
  <link rel="stylesheet" href="../css/post-styles.css" />
  <script src="assets\js\back-button-func.js"></script>
</head>

<body>
  <section class="pseudo-screen">
    <div class="screen-info">
      <div class="left-infos">
        <img src="../images/vec-time.png" alt="" />
        <img src="../images/vec-4g.png" alt="" />
      </div>
      <div class="right-infos">
        <img src="../images/vec-signal.png" alt="" />
        <img src="../images/vec-wifi.png" alt="" />
        <img src="../images/vec-battery.png" alt="" />
      </div>
    </div>
    <div class="page-header flex-row-center-between">
      <div id="back-btn" class="btn-back-wrapper flex-col-center-center">
        <img src="../images/icon-left-arrow.png" alt="" />
      </div>
      <h1>Post</h1>
      <div class="profile-pic-wrapper" style="visibility: hidden"></div>
    </div>

    <div class="post-card" id="post-card">
      <div class="card-poser-info">
        <div class="profile-picture" style="background-image:url('<?= $DPUrl ?>');"></div>
        <div class="profile-names">
          <h3><?= $name ?></h3>
          <span>@<?= $username ?></span>
        </div>
      </div>
      <div class="title-post">
        <h1><?= $title ?></h1>
      </div>
      <div class="visuals-post" style="background-image:url('<?= $VisualUrl ?>');"></div>
      <div class="content-post">
        <p>
          <?= $body ?>.
        </p>
      </div>
    </div>
  </section>

  <script>
    const backBtn = document.getElementById("back-btn");
    backBtn.addEventListener("click", () => {
      window.location.href = `posts.php`;
    });
  </script>
</body>

</html>
