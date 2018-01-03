
<?php

function getContents($fileName){
    $filePath = __DIR__ . "/files/" . $fileName . ".dat";
    $file = fopen($filePath, "r, ccs=UTF-8");
    $content = fread($file, filesize($filePath));
    $content = explode(PHP_EOL, $content);

    foreach ($content as $value) {

        if (in_array($value, LEVEL)) {
            $tmp = $value;
            $contents[$tmp] = [];
            continue;
        }else {
            $contents[$tmp][] = $value;
        }
    }

    if ($file) {
        fclose($filePath);
    }

    return $contents;
}
