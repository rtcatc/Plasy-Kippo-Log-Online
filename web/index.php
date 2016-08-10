<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
    <title>Kippo</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="imagetoolbar" content="no"/>
    <link rel="stylesheet" href="../scripts/playlog.css" type="text/css">
    <script type="text/javascript" src="../scripts/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="../scripts/BinFileReader.js"></script>
    <script type="text/javascript" src="../scripts/jquery.getUrlParam.js"></script>
</head>
<body id="top">
<div>
    <div>
        <div>
            <!-- ####################################################################################################### -->
            <h2>Play Kippo Log Online</h2>
            <?php
            #Author: ikoniaris, CCoffie
            define('DB_HOST', 'localhost');
            define('DB_USER', 'root');
            define('DB_PASS', 'root');
            define('DB_NAME', 'kippo');
            define('DB_PORT', '3306');

function xss_clean($data)
{
    // Fix &entity\n;
    $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

    do {
        // Remove really unwanted tags
        $old_data = $data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    } while ($old_data !== $data);

    // we are done...
    return $data;
}

            $db_conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT); //host, username, password, database, port

            if (mysqli_connect_errno()) {
                echo 'Error connecting to the database: ' . mysqli_connect_error();
                exit();
            }

            $id = preg_replace('/[^-a-zA-Z0-9_]/', '', xss_clean($_GET['id']));

            $db_query = "SELECT ttylog, id FROM ttylog "
                . "WHERE id=" . "\"" . $id . "\"";

            $result = $db_conn->query($db_query);

            while ($row = $result->fetch_array(MYSQLI_BOTH)) {
                $log = base64_encode($row['ttylog']);
            }
			
            $db_conn->close();
            ?>
            <!-- Pass PHP variables to javascript - Please ignore the below section -->
            <script type="text/javascript">
                var log = "<?php echo $log; ?>";
            </script>
            <script type="text/javascript" src="../scripts/jspl.js"></script>

            <noscript>Please enable Javascript for log playback.<br /><br /></noscript>
            <div id="description">Error loading specified log.</div>
            <br />
            <div id="playlog"></div>
            <!-- ####################################################################################################### -->
        </div>
    </div>
</div>
</body>
</html>
