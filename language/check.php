#!/usr/bin/env php
<?php
if ($argc <= 1 || $argc > 3) {
	echo "usage: 	$argv[0] src dst\n";
	echo "example:  $argv[0] en_us it_it\n";
	exit(0);
}
$src = $argv[1];
$dst = $argv[2];

$src_files = scandir($src);
$dst_files = scandir($dst);

foreach ($src_files as $file) {
    if ($file == "." || $file == ".." || ! preg_match("/\.php$/",$file)) {
        continue;
    }
    echo "=== $file ===\n";
    $lang_src = include_once("$src/$file");
    $lang_dst = include_once("$dst/$file");
    $diff = array_diff_key($lang_src , $lang_dst);
    foreach ($diff as $key => $value) {
        echo "\t'$key' => '".str_replace("'","\'",$value)."',\n";

    }
}
