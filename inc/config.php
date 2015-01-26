<?php
    // create root relative paths for use in application
    define("BASE_URL","/");
    define("ROOT_PATH",$_SERVER["DOCUMENT_ROOT"] . "/");

    // DB constants for reuse
    define("DB_HOST", "CHANGEME");
    define("DB_NAME", "CHANGEME");
    define("DB_PORT", "3306");
    define("DB_USER", "CHANGEME");
    define("DB_PASS", "CHANGEME");

    // show errors on page
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
