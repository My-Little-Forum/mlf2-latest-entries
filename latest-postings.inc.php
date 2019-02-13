<?php

$db_settings_file = "";

include($db_settings_file);

$link = @mysqli_connect($db_settings['host'], $db_settings['user'], $db_settings['password'], $db_settings['database']);

if ($link === false) {
	$errors[] = "Connecting to database failed.";
}
if (empty($errors)) {
	mysqli_query($link, 'SET NAMES utf8');
	# every category except Mods/Admins, forum internals, not public
	$query = "SELECT
	time,
	zeit,
	subject,
	cattext,
	id,
	tid,
	name,
	ip
	FROM mlf_entrycats
	WHERE category NOT IN(15, 18, 14, 24)
	GROUP BY id
	ORDER BY zeit desc, id desc LIMIT 0, 20";
	$result = mysqli_query($link, $query);
	if ($result === false) {
		$errors[] = "Reading from the database failed.";
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

?>
