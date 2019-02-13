<?php
/* include("inc.php"); */

$link = @mysql_connect($db_settings['host'], $db_settings['user'], $db_settings['password']) or die("<p>Could not connect</p>");
mysql_select_db($db_settings['database']) or die("<li>Could not select database</li>");
mysql_query('SET NAMES utf8');
# every category except Mods/Admins, forum internals, not public
$query = "SELECT time, zeit, subject, cattext, id, tid, name, ip FROM mlf_entrycats WHERE category != 15 AND category != 18 AND category != 14 and category != 24 GROUP BY id ORDER BY zeit desc, id desc LIMIT 0, 20";
$result = mysql_query($query, $link) or die("Query failed: ".mysql_error());
while($row = mysql_fetch_array( $result )) {
  $post = date('Y-m-d H:i', $row["zeit"]);
  $tz = date(' T', $row["zeit"]);
  if($row['id']==$row['tid']){ /* Top level post of a thread*/
    echo "<li class='top'><a class='post' href=\"mix_entry.php?id=".htmlspecialchars($row["id"])."\" title='Top level post: ".$post.$tz."'>";
  } else {                    /* Reply within thread */
    echo "<li class='reply'><a class='post' href=\"mix_entry.php?id=".htmlspecialchars($row["tid"])."#top".htmlspecialchars($row["id"])."\" title='Reply: ".$post.$tz."'>";
  }
  echo ucfirst(htmlspecialchars($row['subject']));
  echo "</a>";
#  echo "<small><span class='cat'> in categ. “".htmlspecialchars($row['cattext'])."” by </span> ";
  echo "<small><span class='cat'> [".htmlspecialchars($row['cattext'])."]: </span> ";
  echo "<span class='name'>".htmlspecialchars($row['name'])."</span>";
  $diff = time() - strtotime($post); /* seconds */
  $days_ago = floor($diff / 86400);  /* not correct for days of DLT changes! */
  $hours_ago = floor(($diff / 3600)-($days_ago*24));
  $minutes_ago = floor(($diff / 60)-($hours_ago*60+$days_ago*1440));
  $hrsmins = sprintf("%02d:%02d", $hours_ago, $minutes_ago);
  if ($days_ago >= 1) {
    if ($days_ago > 1) {
      $days = ' days ';
    } else {
      $days = ' day ';
    }
  } else {
    $days_ago = '';
    $days = '';
  }
  echo " ".$days_ago.$days.$hrsmins." ago</small>"."\n     ";
  }
/* Free resultset */
mysql_free_result($result);
/* Closing connection */
mysql_close($link);
?>
