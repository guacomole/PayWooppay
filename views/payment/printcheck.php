<?php
$base64 = $check->checkOnPrint->content;
$binary = $base64;//base64_decode($base64);
file_put_contents('my.pdf', $binary);
header("Content-type:application/pdf");
// It will be called downloaded.pdf
//header("Content-Disposition:" . $check->checkOnPrint->headers->get('Content-Disposition'));
header('Content-Disposition: attachment; filename="my.pdf"');
// The PDF source is in original.pdf
readfile("my.pdf");
//readfile(substr(explode("=", $check->checkOnPrint->headers->get('Content-Disposition'))[1], 1, -1));
?>