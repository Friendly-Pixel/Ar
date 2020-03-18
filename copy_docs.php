#!/usr/bin/env php
<?php
/**
 * Copy relevant documentation from Ar.php to ArFluent.php and README.md
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('./vendor/autoload.php');

use Frontwise\Ar\Ar;

$arString = file_get_contents('./src/Ar.php');
$matches = [];
$re = '/(\/\*\*(?:.|\n)*\*\/)\s+public static function ([a-zA-Z]+)\(/U';
preg_match_all($re, $arString, $matches);
// dump($matches);

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
file_put_contents('src/ArFluent.php', $arFluent);


/* Generate README.md */
$readme = file_get_contents('./README.template.md');
$funcNames = $matches[2];
sort($funcNames);
foreach ($matches[1] as $i => $comment) {

    $toc = Ar::new($funcNames)
        ->map(function ($funcName) {
            return "- [$funcName()](#$funcName)";
        })
        ->implode("\n");
    $readme = str_replace('<!-- METHOD_TOC_HERE -->', $toc, $readme);

    $methodDocs = Ar::new($funcNames)
        ->map(function ($funcName) use ($comment) {
            $comment = str_replace("     * @", "     * \n     * @", $comment);
            $comment = str_replace("/**\n", '', $comment);
            $comment = str_replace('     * ', '', $comment);
            $comment = str_replace("\n     */", '', $comment);

            return "<a name=\"$funcName\"></a>\n### $funcName\n\n$comment\n\n\n";
        })
        ->implode("\n");
    $readme = str_replace('<!-- METHODS_HERE -->', $methodDocs, $readme);
}
file_put_contents('./README.md', $readme);
