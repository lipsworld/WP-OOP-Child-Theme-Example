<?php

// include files
require_once __DIR__ . '/includes/includes.php';

// define constants
define('CM_PHP_VERSION', '5.4');
define('CM_WP_VERSION', '4.0');
define('CM_TEXT_DOMAIN', 'codeline-movies');

// singleton 
global $codeline_movies;
$codeline_movies = Codeline\CodelineMovies::getInstance();
$codeline_movies::init();
