<?php

/**
 * Display the latest entries of a My Little Forum instance
 * Copyright (C) <year>  H. Schütz, H. August
 *
 * The script is licensed under the terms of the General Public Licence 3.0
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author H. Schütz
 * @author H. August
 * @license https://opensource.org/licenses/GPL-3.0
 * @version 0.1
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);

# placeholder for the debug and the error output
$output['debug-and-errors'] = "";

$settings = parse_ini_file('data/config/lpp.ini', true, INI_SCANNER_TYPED);
#$output['debug'][] = print_r($settings, true);

include($settings['paths']['dbSettings']);
$template['main'] = file_get_contents($settings['paths']['mainTemplate']);
$template['list'] = file_get_contents($settings['paths']['itemTemplate']);

$link = mysqli_connect($db_settings['host'], $db_settings['user'], $db_settings['password'], $db_settings['database']);

if ($link === false) {
	$errors[] = "Connecting to database failed.";
} else {
	$output['debug'][] = "Database connection established.";
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
	WHERE t1.category IN(SELECT id FROM " . $db_settings['category_table'] . " WHERE accession IN(". implode(", ", $settings['general']['typeOfCategories']) .")) OR t1.category = 0
	ORDER BY t1.time DESC, t1.id DESC
	LIMIT 0, " . intval($settings['general']['numberOfEntries']);
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
			$template['item'] = str_replace('{%item-url%}', htmlspecialchars($settings['paths']['forumURL']) . "?id=" . htmlspecialchars($row['id']), $template['item']);
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
if ($settings['general']['debug'] === true) {
	$template['info'] = file_get_contents($settings['paths']['infoTemplate']);
	$template['info'] = str_replace('{%info-class%}', "debugging", $template['info']);
	$template['info'] = str_replace('{%info-header%}', "Debug informations", $template['info']);
	$template['info'] = str_replace('{%info-content%}', "<pre>". implode("\n\n", $output['debug']) . "</pre>\n", $template['info']);
	$output['debug-and-errors'] .= $template['info'];
}
if (!empty($errors)) {
	$template['error'] = file_get_contents($settings['paths']['infoTemplate']);
	$template['error'] = str_replace('{%info-class%}', "errors", $template['error']);
	$template['error'] = str_replace('{%info-header%}', "Error(s) occured", $template['error']);
	$template['error'] = str_replace('{%info-content%}', "<pre>" . print_r($errors, true) . "</pre>\n", $template['error']);
	$output['debug-and-errors'] .= $template['error'];
}

$settings['output']['pageTitle'] = str_replace('{%number-of-entries%}', $settings['general']['numberOfEntries'], $settings['output']['pageTitle']);
$template['main'] = str_replace('{%page-title%}', $settings['output']['pageTitle'], $template['main']);
$template['main'] = str_replace('{%reload-rhythm%}', $settings['output']['reloadRhythm'], $template['main']);
$template['main'] = str_replace('{%information-section%}', $output['debug-and-errors'], $template['main']);
$template['main'] = str_replace('{%list-of-latest-postings%}', implode("", $output['items']), $template['main']);

echo $template['main'];

?>
