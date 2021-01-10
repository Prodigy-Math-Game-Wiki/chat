<?php
session_start();

include("mods.php");

$mutedUsers = explode("\n", file_get_contents("./muted.txt"));

function startsWith($haystack, $needle) {
    $length = strlen($needle);
    return substr($haystack, 0, $length) === $needle;
}

function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if (!$length) return true;
    return substr($haystack, -$length) === $needle;
}

if (isset($_SESSION['name'])) {
    $text = $_POST['text'];

    if (in_array($_SESSION['name'], $mutedUsers)) {
        header("HTTP/2.0 403");
        die();
    }

    if (!(strlen($text) > 0)) {
        header("HTTP/2.0 403");
        die();
    }

    $badge = '';

    $text = stripslashes(htmlspecialchars($text));

    if((strpos($text, "[i]") and strpos($text, "[/i]")) !== false) {
        $text = str_replace("[/i]", "</i>", str_replace("[i]", "<i>", $text));
    }

    if((strpos($text, "[b]") and strpos($text, "[/b]")) !== false) {
        $text = str_replace("[/b]", "</b>", str_replace("[b]", "<b>", $text));
    }

    if(in_array($_SESSION['name'], $sysadmins)) {
        $badge = $badge . '<span title="Chat System Administrator"><img src="sysadmin.png"></span>';
    }

    if(in_array($_SESSION['name'], $mods)) {
        $badge = $badge . '<span title="Chat/Discussions Moderator" style="width: 32px!important; height: 32px!important;"><img src="./Badge-DiscussionsModerator.png" width="32px" height="32px"></span>';
    }

    if(in_array($_SESSION['name'], $admins)) {
        $badge = $badge . '<span title="Chat Moderator" style="width: 32px!important; height: 32px!important;"><img src="./Badge-Admin.png" width="32px" height="32px"></span>';
    }

    $fp = fopen("log.html", 'a');
    fwrite($fp, "<li><div class='msgln' data-user='" . $_SESSION['name'] . "'>(" . date("g:i A") . ") <b>" . $_SESSION['name'] . $badge . "</b>: <br/>" . $text . "<br></div></li>");

    if(filesize("log.html") > 35000) {
        file_put_contents("log.html", "");
        fwrite($fp, "<li><div class='msgln' data-user='SYSTEM'>(" . date("g:i A") . ") <b>SYSTEM</b>: <br/>Chat was cleared automatically due to a large amount of messages.<br/></div></li>");
    }

    fclose($fp);
}
?>
