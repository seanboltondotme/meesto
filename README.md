meesto
======

A social networking tool I made years ago, similar to Google Plus (before it was out), based on open source and non profit ideals.

This project could not have been accomplished without the wealth of information found on the internet. As a result, you will find parts of code borrowed from other developers. I thank everyone immensely.

The purpose of creating a github repository for Meesto is to share the code with many people who have asked to see it. I see this as my way of paying what I learned forward. I hope others will find it useful.


Basic File Structure
-------

### Base Files
Each main page the user will access is located in httpdocs/ All other files are loaded through AJAX calls or includes.

### External Files
Directories are organized according to base files and function. httpdocs/externalfiles contains most external files. externals/ contains a few top level external files, some of which contain access codes which are are designed to be hidden from the public directory.

### Uploads
PHP FTP functions are used to create upload directories for users and events. Each user and event has their own directory.


Installation
-------

### Database
See databases.txt for all of the database creation scripts. There should be 68 tables.

Edit externals/sessions/db_sessions.inc.php with your database access information.

### URL Paths
Edit these files to contain the correct path to your server. The URL should route to the base public directory.

* externals/general/includepaths.php
* externals/ftp/ftpconnect.php
* httpdocs/externalfiles/m.js
