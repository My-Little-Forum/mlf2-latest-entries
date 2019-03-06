# Extra page for latest postings

## Purpose of the script

Several operators of instances of the [My Little Forum script](https://mylittleforum.net/) asked for a script to show the latest postings of their forum outside of the forum itself in a different page of the website. After repeated questions, H. Schütz decided to hand over a script for that purpose, he developed for his own forum (an instance of My Little Forum 1.8 (never released version)).

## Licence

This script is licenced under the terms of the GPL 3.

## Configuration

With version 0.2 an INI file (`data/config/lpp.ini`) for storing of the settings got introduced. The settings stayed the same but changed their names.

- section `[general]`
    - `debug` [`boolean`]: additional output for development and/or debugging (`true` or `false`)
    - `numberOfEntries` [`integer`]: number of latest entries to display in the page
    - `typeOfCategories` [`array`]: an array to store the information, which type of categories should get displayed (`0`: public categories, `1`: categories, restricted to registered users, `2`: categories, restricted to moderators and administrators)
- section `[paths]`
    - `forumURL` [`string`]: the URL of the forum as you see it in the browsers address bar (i.e. "https://example.com/forum/")
    - `dbSettings` [`string`]: the *local path* of the webserver from the script to the file `config/db_settings.php` from within the forum
    - `mainTemplate` [`string`]: file name of the main HTML template
    - `itemTemplate` [`string`]: file name of the HTML template for list items
    - `infoTemplate` [`string`]: file name of the HTML template for error messages and/or debug messages
- section `[output]`
    - `pageTitle` [`string`]: string with placeholders for the page title
    - `reloadRhythm` [`integer`]: number of seconds until the page reloads the next time

`$output['debug-and-errors']` got removed *from the settings* but is a variable initialisation in the script anyway
