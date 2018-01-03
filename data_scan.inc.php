<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include "const.php";
include "fun.inc.php";
// var_dump(LEVEL);
extract($_GET);
$name = trim($name);
$contents = [];
if ($subjectName) {
	$contents[$subjectName] = getContents($subjectName);
}else {
	foreach ($SUBJECT as $k => $v) {
		$contents[$k] = getContents($k);
	}
}

if ($name) {
	foreach ($contents as $subjectId => $v) {
		foreach ($v as $levelId => $value) {
			foreach ($value as $schoolInfo) {
				if (strpos($schoolInfo, $name)) {
					// var_dump($schoolInfo);
					$tmp[$subjectId][$levelId] = [$schoolInfo];
					break;
				}
			}

		}
	}
// var_dump($tmp);
echo "************\n";
	$contents = $tmp;
	// var_dump($contents);
}

// var_dump($contents);

include template('data_scan:index');
