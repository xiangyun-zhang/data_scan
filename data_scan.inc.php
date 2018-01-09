 <?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include "const.php";
include "fun.inc.php";

extract($_GET);
$name = trim($name);

$contents = [];




if ($name) {


    foreach ($SUBJECT as $k => $v) {
        $contents[$k] = getContents($k);
    }

	foreach ($contents as $subjectId => $v) {
		foreach ($v as $levelId => $value) {
			foreach ($value as $schoolInfo) {
				if ($schoolInfo == $name) {
					$tmp[$subjectId] = SCORE[$levelId];
					break;
				}
			}

		}
	}
    arsort($tmp);
	$contents = $tmp;

    foreach ($contents as $key => $value) {
        $contents[$key] = array_search($value, SCORE);
    }

    $schoolScores = getScore();
    $sort = getSort($schoolScores, $name);

}

include template('data_scan:index');
