#!/usr/bin/env php
<?php
/**
 * Copy relevant documentation from Ar.php to ArFluent.php and README.md
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('./vendor/autoload.php');

use FriendlyPixel\Ar\Ar;

$arString = file_get_contents('./src/Ar.php');
$matches = [];
$re = '/(\/\*\*(?:.|\n)*\*\/)\s+public static function ([a-zA-Z]+)\(/U';
preg_match_all($re, $arString, $matches);
// dump($matches);
$madeChanges = false;

/* Update ArFluent.php */
$arFluent = file_get_contents('./src/ArFluent.php');
foreach ($matches[1] as $i => $comment) {
    $funcName = $matches[2][$i];

    $re = '/(\/\*\*(\n|[^*]|\*[^\/])*+\*\/\s+)?public function ' . $funcName . '\(/';
    // dump($re);
    $changedComment = str_replace('@return mixed[]', '@return ArFluent', $comment);
    $arFluent = preg_replace($re, $changedComment . "\n    " . 'public function ' . $funcName . '(', $arFluent);
    if (preg_last_error()) {
        dump(array_flip(get_defined_constants(true)['pcre'])[preg_last_error()]);
    }
    // dump($arFluentString);
    // dump('---------------');
    // dump('');
}
if ($arFluent != file_get_contents('src/ArFluent.php')) {
    $madeChanges = true;
    file_put_contents('src/ArFluent.php', $arFluent);
}


/* Generate README.md */
$readme = file_get_contents('./README.template.md');

$rows = Ar::new($matches[2])
    ->map(function ($funcName, $i) use ($matches) {
        return [
            'funcName' => $funcName,
            'comment' => $matches[1][$i],
        ];
    })
    ->sort(function ($a, $b) {
        return strnatcmp($a['funcName'], $b['funcName']);
    });

$toc = Ar::new($rows)
    ->map(function ($row) {
        $funcName = $row['funcName'];
        return "- [$funcName()](#$funcName)";
    })
    ->implode("\n");
$readme = str_replace('<!-- METHOD_TOC_HERE -->', $toc, $readme);

$methodDocs = Ar::new($rows)
    ->map(function ($row) {
        $comment = $row['comment'];
        $comment = str_replace("     * @", "     * \n     * @", $comment);
        $comment = str_replace("/**\n", '', $comment);
        $comment = str_replace('     * ', '', $comment);
        $comment = str_replace("\n     */", '', $comment);

        $funcName = $row['funcName'];
        return "<a name=\"$funcName\"></a>\n### $funcName\n\n$comment\n\n\n";
    })
    ->implode("\n");
$readme = str_replace('<!-- METHODS_HERE -->', $methodDocs, $readme);
if ($readme != file_get_contents('./README.md')) {
    $madeChanges = true;
    file_put_contents('./README.md', $readme);
}

if ($madeChanges) {
    echo ('Updated docs' . PHP_EOL);
    exit(1);
}
