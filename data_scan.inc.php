<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include "const.php";

foreach ($SUBJECT as $k => $v) {
	$filePath = __DIR__ . "/files/" . $k . ".dat";
	$file = fopen($filePath, "r, ccs=UTF-8");
	$content = fread($file, filesize($filePath));
	$content = explode(PHP_EOL, $content);
	foreach ($content as $value) {
		if (in_array($value, $LEVEL)) {
			$tmp = $value;
			$contents[$k][$tmp] = [];
			continue;
		}else {
			$contents[$k][$tmp][] .= $value;
		}
	}

	if ($file) {
		fclose($filePath);
	}
}
// var_dump($contents);

include template('data_scan:index');
