# Queue manager

An application for the turnkeys. They generate calls from their computer on a screen placed in the waiting room. The call message simply contains the ticket office name and ticket numbers.

It does the taff, but is quickly coded. No authentification.

## How it works.

The screen on the waiting shows, full-screen mode the page http(s):// your application /index.php?panneau

The turnky can make calls on the page http(s):// your application /index.php?guichet=ID

It support groups of ticket office.

## Install

* Needs php (not tested with versions under 7).
* Create a Mysql or postgree database.

* Execute the install.sql content to create the needed table.

* rename config/bdd.json.sample to config/bdd.json, and edit it with your creds.

* The Apache / nginx /other ... document root is ./html

## Contribute

You are welcome.

