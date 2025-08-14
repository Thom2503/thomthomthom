<?php

// simple check i dont know if this is logical to check if the default settings are set
$GLOBALS['check'] = false;

// If I want to accept images as uploads in pages and where to handle them
$GLOBALS['accept_imgs'] = false;
$GLOBALS['upload_dir'] = "/htdocs/THOMTHOMTHOM/uploads/";

// the database is a sqlite page
$GLOBALS['db'] = new PDO("sqlite:db.sqlite");

// https://en.wikipedia.org/wiki/IETF_language_tag
$GLOBALS['lang'] = "en";

// where the pages are uploaded
$GLOBALS['page_dir'] = 'pages';
$GLOBALS['archive_dir'] = 'archive';

// cookies are the simple way to restrict others
$GLOBALS['cookie_root'] = '/';

// where default links should redirect to
$GLOBALS['link_path'] = 'index.php?page=';

// the landing page
$GLOBALS['default_page_name'] = 'main';

// to keep track of logged in users
$GLOBALS['logins'] = [];
