<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8">
  <title>BEBA Forum • Latest 20 posts</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="referrer" content="origin">
  <meta name="referrer" content="same-origin">
  <meta http-equiv="refresh" content="300"><!-- every five minutes -->
  <meta name="date" content="<?php echo date ('c', getlastmod()); ?>">
  <meta name="modified" content="<?php echo date('c'); ?>">
  <meta name="robots" content="noindex, follow">
  <link rel="shortcut icon" href="favicon.ico">
  <link rel="icon" href="favicon.ico" type="image/ico">
  <link rel="icon" href="195x195.png" type="image/png"><!-- Opera speed dial icon -->
  <link rel="stylesheet" type="text/css" href="latest.css">
 </head>
 <body>
  <p><a href="#Inhalt" title="jump!">directly to content</a> (skip navigation)</p>
  <header>
   <h1>Latest twenty posts</h1>
  </header>
  <nav><!-- put in your navigation -->
  </nav><!-- end of navigation -->
  <main role="main">
   <ul class="posts">
    <?php include('LatestPosts.inc.php'); ?>
   </ul>
  </main>
  <footer><!-- put in your page footer -->
  </footer><!-- end of page footer -->
 </body>
</html>
