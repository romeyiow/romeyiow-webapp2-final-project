<?php
require 'config.php';
// print_r($_SESSION);
if (!isset($_SESSION['user_id'])) {
  header("Location: ../../index.php");
  exit;
}

$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
  $pdo = new PDO($dsn, $user, $password, $options);
  $query = "SELECT p.*, u.name, u.email, ph.thumbnail_url 
              FROM posts p 
              JOIN users u ON p.user_id = u.id 
              JOIN photos ph ON p.id = ph.id 
              LIMIT 100";
  $statement = $pdo->query($query);
  $posts = $statement->fetchAll(PDO::FETCH_ASSOC);
  // echo "<pre>";
  // print_r($posts);
  // echo "</pre>";
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Posts</title>
  <link rel="stylesheet" href="../css/base.css" />
  <link rel="stylesheet" href="../css/posts-styles.css" />
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
      <h1>Posts</h1>
      <div class="profile-pic-wrapper" id="dp-wrapper" style="visibility: hidden!important;"></div>
    </div>
    <div class="posts-container">
      <ul id="postLists">
        <?php foreach ($posts as $post): ?>
          <?php
          $DPUrl = $post['thumbnail_url'] ?? 'https://via.placeholder.com/150/c96cad';
          $name = $post['name'];
          $username = explode("@", $post['email'])[1];
          ?>
          <li data-id="<?= htmlspecialchars($post['id']) ?>">
            <div class="item-posts">
              <div class="post-header">
                <div class="title">
                  <h3><?= htmlspecialchars($post['title']) ?></h3>
                  <sub><?= htmlspecialchars($name) ?> <span>@<?= htmlspecialchars($username) ?></span></sub>
                </div>
                <div class="wrapper-poster-img" style="background-image:url('<?= htmlspecialchars($DPUrl) ?>');"></div>
              </div>
              <div class="btn-go-wrapper">
                <div class="btn-go">→</div>
              </div>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.querySelectorAll("li[data-id]").forEach(function (li) {
        li.addEventListener("click", function () {
          const id = this.getAttribute("data-id");
          window.location.href = `post.php?id=${id}`;
        });
      });
    });

    // Add back functionality
    const backBtn = document.getElementById("back-btn");
    backBtn.addEventListener("click", () => {
      alert('Logging you out (session terminated) ...');
      setTimeout(() => { window.location.href = `logout.php`; }, 200);
    });
  </script>
</body>

</html>