<?php

/**
 * Display the latest entries of a My Little Forum instance
 *
 * @author: H. Schütz
 * @author: H. August
 *
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * configuration section
 */
# (de)activate the debug mode
# with debug mode = true the script outputs additional informations about the script run
# default is: $debug = false;
$debug = false;
# file with the database settings
# please set path to the db_settings.php of the installation of My Little Forum
$db_settings_file = "../config/db_settings.php";
# number of entries to display
# set the overall number of entries you want to display on this page
$numberOfEntries = 25;
# array of category types to display
# 0 = categories, visible to not registered users
# 1 = categories, visible to registered users (hide them in the list, if they are hidden in the forum)
# 2 = categories, visible to only moderators and administrators (normally hidden in the forum)
#
# show entries only from public categories or from a forum without categories
# $typeOfCategories = array(0);
# show entries only from public categories and from categories, that are accessible only by registered users
# $typeOfCategories = array(0, 1);
# show entries from categories, accessible to the public (0), and those that are restricted to registered users (1)) and to the forum team (admins and moderators) (2))
# $typeOfCategories = array(0, 1, 2);
$typeOfCategories = array(0);
# page totle to display
# shown in the title element (program title bar of the browser) and as main header in the page
$output['page-title'] = "The latest (max) ". $numberOfEntries ." entries of my forum";
# reload rhythm
# number of seconds to the next automatic page reload (i.e. 300 seconds = five minutes)
$output['reload-rhythm'] = 120;
# placeholder for the debug and the error output
$output['debug-and-errors'] = "";
# file path to the main template
$filename_main = "data/lp-template.html";
# file path to the item template
$filename_item = "data/lp-item.html";
# file path to the information block template
$filename_info = "data/lp-debug.html";

include($db_settings_file);
$template['main'] = file_get_contents($filename_main);
$template['list'] = file_get_contents($filename_item);

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
	WHERE t1.category IN(SELECT id FROM " . $db_settings['category_table'] . " WHERE accession IN(". implode(", ", $typeOfCategories) .")) OR t1.category = 0
	ORDER BY t1.time DESC, t1.id DESC
	LIMIT 0, " . intval($numberOfEntries);
	$output['debug'][] = print_r($query, true);
	$result = mysqli_query($link, $query);
	if ($result === false) {
		$errors[] = "Reading from the database failed.";
		$errors[] = mysqli_errno($link);
		$errors[] = mysqli_error($link);
	} else {
		while ($row = mysqli_fetch_assoc($result)) {
			$output['debug'][] = print_r($row, true);
			$template['item'] = $template['list'];
			if ($row['category'] !== NULL) {
				$template['item'] = str_replace('{%item-category%}', " <span>(". htmlspecialchars($row['category']) .")</span>", $template['item']);
			} else {
				$template['item'] = str_replace('{%item-category%}', "", $template['item']);
			}
			$template['item'] = str_replace('{%item-url%}', $forum_url . "?id=" . htmlspecialchars($row['id']), $template['item']);
			$template['item'] = str_replace('{%item-subject%}', htmlspecialchars($row['subject']), $template['item']);
			$template['item'] = str_replace('{%item-author%}', htmlspecialchars($row['name']), $template['item']);
			$template['item'] = str_replace('{%item-time%}', htmlspecialchars($row['time']), $template['item']);
			$output['items'][] = $template['item'];
		}
		/* Free resultset */
		mysqli_free_result($result);
	}
	/* Closing connection */
	mysqli_close($link);
}
if ($debug === true) {
	$template['info'] = file_get_contents($filename_info);
	$template['info'] = str_replace('{%info-class%}', "debugging", $template['info']);
	$template['info'] = str_replace('{%info-header%}', "Debug informations", $template['info']);
	$template['info'] = str_replace('{%info-content%}', "<pre>". implode("\n\n", $output['debug']) . "</pre>\n", $template['info']);
	$output['debug-and-errors'] .= $template['info'];
}
if (!empty($errors)) {
	echo "<pre>" . print_r($errors, true) . "</pre>\n";
}

?>
