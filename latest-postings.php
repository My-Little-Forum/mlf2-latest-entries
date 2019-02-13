<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!doctype html>
<html lang="en">
 <head>
  <meta charset="utf-8">
  <meta name="referrer" content="origin">
  <meta name="referrer" content="same-origin">
  <meta http-equiv="refresh" content="300"><!-- every five minutes -->
  <meta name="date" content="<?php echo date ('c', getlastmod()); ?>">
  <meta name="modified" content="<?php echo date('c'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Helmut Schütz">
  <meta name="robots" content="noindex, follow">             -->
  <link rel="shortcut icon" href="https://static.bebac.at/Forum_favicon.ico">
  <link rel="icon" href="favicon.ico" type="image/ico">
  <link rel="icon" href="195x195.png" type="image/png"><!-- Opera speed dial icon -->
  <link rel="stylesheet" type="text/css" href="latest.css">
  <title>BEBA Forum • Latest 20 posts</title>
 </head>
 <body>
  <div id="Seite"><!-- sic! -->
   <div id="Jump"><!-- directly to content -->
    <p><a href="#Inhalt" title="jump!">directly to content</a> (skip navigation)
    <hr>
   </div><!-- end 'Jump' div -->
   <div id="navcontainer"><!-- horizontal navigation -->
   </div><!-- end of navigation -->
   <p class="p.clr">
   <div id="Inhalt"><!-- content div -->
    <!-- <a href="http://blog.slepp.ca/mouldspellbound.php?mod=3">shortlived</a> -->
    <h1>Latest twenty posts</h1>
    <ul class="posts">
     <?php include('LatestPosts.inc.php'); ?>
</ul>
    <p class="small">Data are current with <?php echo date('l, j F o H:i T (\E\u\r\o\p\e\/\V\i\e\n\n\a; \U\T\C P)'); ?>.<br>
    <?php include('OnlineCounter.inc.php'); ?><!-- visitors (registered, guests, bots) -->
   </div><!-- end of content div -->
   <p id="foot">The <strong>BIOEQUIVALENCE / BIOAVAILABILITY FORUM</strong> is hosted by<br>
    <object data="https://static.bebac.at/img/bebac.svg" type="image/svg+xml" width="75" height="50">
     <param name="src" value="https://static.bebac.at/img/bebac.svg">
     <img src="https://static.bebac.at/img/bebac-small.gif" width="75" alt="BEBAC" title="">
    </object>
    <strong> <span lang="de">Ing. Helmut Schütz</span></strong><br>
    Established 2004-07-30 | last update 2018-10-20 | document <?php echo date ('Y-m-d', getlastmod()); ?> | GeoLite data created by <a href="https://dev.maxmind.com/geoip/legacy/geolite/" title="GeoLite Country">MaxMind</a>.  <a href="rss.php"><img src="https://static.bebac.at/img/rss.png" width="14" height="14" alt="RSS Feed" title="recent additions in RSS 2.0"></a>
   </p>
  </div><!-- end of page div -->
 </body>
</html>
