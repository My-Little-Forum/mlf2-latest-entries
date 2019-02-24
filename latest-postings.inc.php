<?php

$db_settings_file = "";
/**
 * Display the latest entries of a My Little Forum instance
 *
 * @author: H. Schütz
 * @author: H. August
 *
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

include($db_settings_file);

$link = mysqli_connect($db_settings['host'], $db_settings['user'], $db_settings['password'], $db_settings['database']);

if ($link === false) {
	$errors[] = "Connecting to database failed.";
}
if (empty($errors)) {
	mysqli_query($link, 'SET NAMES utf8');
	# every category except Mods/Admins, forum internals, not public
	$query = "SELECT
	t1.id,
	t1.tid,
	t1.time,
	CASE
		WHEN t1.user_id > 0 THEN (SELECT t2.user_name FROM " . $db_settings['userdata_table'] . " AS t2 WHERE t2.user_id = t1.user_id)
		ELSE t1.name
	END AS name,
	t1.subject,
	CASE
		WHEN t1.category > 0 THEN (SELECT t3.category FROM " . $db_settings['category_table'] . " AS t3 WHERE t3.id = t1.category)
		ELSE NULL
	END AS category
	FROM " . $db_settings['forum_table'] . " AS t1
	WHERE t1.category IN(SELECT id FROM " . $db_settings['category_table'] . " WHERE accession NOT IN(1, 2)) OR t1.category = 0
	ORDER BY t1.time DESC, t1.id DESC
	LIMIT 0, 20";
	$result = mysqli_query($link, $query);
	if ($result === false) {
		$errors[] = "Reading from the database failed.";
		$errors[] = mysqli_errno($link);
		$errors[] = mysqli_error($link);
	} else {
		while ($row = mysqli_fetch_assoc($result)) {
			$post = date('Y-m-d H:i', $row["zeit"]);
			$tz = date(' T', $row["zeit"]);
			if ($row['id']==$row['tid']) { /* Top level post of a thread*/
				echo '<li class="top"><a class='"post" href="mix_entry.php?id=".htmlspecialchars($row["id"]).'" title="Top level post: '.$post.$tz.'">'."\n";
			} else {  /* Reply within thread */
				echo '<li class="reply"><a class="post" href="mix_entry.php?id='.htmlspecialchars($row["tid"]).'#top'.htmlspecialchars($row["id"]).'" title="Reply: '.$post.$tz.'">'."\n";
			}
			echo ucfirst(htmlspecialchars($row['subject']));
			echo "</a>";
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
			echo "</li>\n";
		}
		/* Free resultset */
		mysqli_free_result($result);
	}
	/* Closing connection */
	mysqli_close($link);
}
if (!empty($errors)) {
	echo "<pre>" . print_r($errors, true) . "</pre>\n";
}

?>
