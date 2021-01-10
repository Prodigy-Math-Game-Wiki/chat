<?php
$sysadmins = array(
    'NameIsA'
);

$admins = array(
    'Glaciersong',
    'Bush of Electricity'
);

$mods = array(
    'FoxxshadowChan',
    'NameIsA',
    'Voldemort0714',
    'Magisukiyo'
);

function isUserStaff($username) {
    return (in_array($username, $sysadmin) || in_array($username, $mods) || in_array($username, $admins));
}
?>
