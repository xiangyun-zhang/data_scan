<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$filePath = __DIR__ . "/files/evaluation_results.dat";
$file = fopen($filePath, "r, ccs=UTF-8");
// $contents = fread($file, filesize($filePath));
if ($file) {
	fclose($filePath);
}

include template('data_scan:index');
