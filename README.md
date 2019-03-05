# Extra page for latest postings

## Purpose of the script

Several operators of instances of the [My Little Forum script](https://mylittleforum.net/) asked for a script to show the latest postings of their forum outside of the forum itself in a different page of the website. After repeated questions, H. Schütz decided to hand over a script for that purpose, he developed for his own forum (an instance of My Little Forum 1.8 (never released version)).

## Licence

This script is licenced under the terms of the GPL 3.

## Configuration

- `$debug` [`true`, `false`]: additional output for development and/or debugging
- `$forum_url` [`string`]: the URL of the forum as you see it in the browsers address bar (i.e. "https://example.com/forum/")
- `$db_settings_file` [`string`]: the *local path* of the webserver from the script to the file `config/db_settings.php` from within the forum
- `$numberOfEntries` [`integer`]: number of latest entries to display in the page
- `$typeOfCategories` [`array`]: an array to store the information, which type of categories should get displayed (`0`: public categories, `1`: categories, restricted to registered users, `2`: categories, restricted to moderators and administrators)
- `$output['page-title']` [`string`]: string with placeholders for the page title
- `$output['reload-rhythm']` [`integer`]: number of seconds until the page reloads the next time
- `$output['debug-and-errors']` [`string`]: empty string that get complemented with strings if errors occured and/or the debug mode is activated
- `$filename_main` [`string`]: file name of the main HTML template
- `$filename_item` [`string`]: file name of the HTML template for list items
- `$filename_info` [`string`]: file name of the HTML template for error messages and/or debug messages
