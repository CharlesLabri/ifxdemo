<?php

//Set up a switch statement to catch the POST requests from JS.
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $uri = $_SERVER['REQUEST_URI'];
    $query_uri = explode('/',$uri);
    if (isset($query_uri[1]) && !empty($query_uri[1])) {
        switch ($query_uri[1]) {
            case 'queryLocalMatch':
                queryLocalMatch();
                die('queryLocalMatch POST request');
                break;
            case 'loadText':
                queryIfixit();
                die('queryIfixit POST request');
                break;
            case 'insertDB':
                saveDisplayedContent();
                die('insertDB POST request');
                break;
            default:
                die("no match");
                break;
        }
    }
}

// Check Local Content before checking with iFixit
function queryLocalMatch() {
    require(ROOT_PATH . "inc/db.php");
    // **todo** PHP manual says its not exceptionally trusted method of finding the right data http://php.net/manual/en/reserved.variables.server.php
    $referer = $_SERVER['HTTP_REFERER'];
    $query_ref = explode('/',$referer);
    $namespace = strtoupper($query_ref[3]);
    $title = $query_ref[4];
    $sql = "SELECT namespace,title FROM scribe WHERE namespace='$namespace' AND title='$title'";
    $sql_cr = "SELECT contents_rendered FROM scribe WHERE namespace='$namespace' AND title='$title'";
    try {
        $db_query = $db->prepare($sql);
        $db_query->execute();
        $rowCount = $db_query->rowCount();
        if($rowCount == 0) {
            queryIfixit();
        } else {
            $db_query = $db->prepare($sql_cr);
            $db_query->execute();
            $return = $db_query->fetch(PDO::FETCH_ASSOC);
            $results = json_encode($return);
            ob_flush();
            header('Content-Type: application/json');
            die($results);
        }
    } catch(exception$e) {
        echo 'failure';
        exit;
    }
}

// iFixit API Call for 'fresh' data
function queryIfixit() {
    $referer = $_SERVER['HTTP_REFERER'];
    $query_ref = explode('/',$referer);
    $namespace = strtoupper($query_ref[3]);
    $url = "https://www.ifixit.com/api/2.0/wikis/$namespace/$query_ref[4]?pretty";
    $data = file_get_contents($url);
    $json = json_decode($data, true);
    $validNamespaces = array('CATEGORY','ITEM','TEAM','USER','INFO','WIKI');
    $namespace = $json["namespace"];
        if(in_array("$namespace",$validNamespaces)) {
        } else {
            die('failure');
            break;
        }
    $title = $json["title"];
    $contents_rendered = $json["contents_rendered"];
    $array = array("namespace" => $namespace, "title" => $title, "contents_rendered" => $contents_rendered);
    $results = json_encode($array);
    ob_flush();
    header('Content-Type: application/json');
    die($results);
}

// Local Database Calls
function saveDisplayedContent() {
    require(ROOT_PATH . "inc/db.php");
    $contents_rendered = addslashes($_POST['contents_rendered']);
    $referer = $_SERVER['HTTP_REFERER'];
    $query_ref = explode('/',$referer);
    $namespace = $query_ref[3];
    $title = $query_ref[4];
    // sql statement to insert into db using above values and updating when contents_rendered is matched as changed
    $sql = "INSERT INTO scribe (namespace,title,contents_rendered)
            VALUES ('$namespace','$title','$contents_rendered')
            ON DUPLICATE KEY UPDATE contents_rendered = '$contents_rendered'";
    try {
        $db_insert = $db->prepare($sql);
        $db_insert->execute();
        echo 'The insert was successful.';
    } catch(exception$e) {
        echo 'failure';
        exit;
    }
    exit;
}
