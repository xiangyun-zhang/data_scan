 <?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include "const.php";
include "fun.inc.php";

extract($_GET);
$name = trim($name);
$schoolScores = getScore();

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
				if ($schoolInfo == $name) {
					$tmp[$subjectId][$levelId] = [$schoolInfo];
					break;
				}
			}

		}
	}

	$contents = $tmp;
}

include template('data_scan:index');
