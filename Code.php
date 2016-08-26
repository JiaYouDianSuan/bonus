<?php
session_start ();
header ( 'P3P: CP=CAO PSA OUR' );

$xstr1 = chr ( rand ( 49, 57 ) );
$xstr2 = chr ( rand ( 49, 57 ) );
$xstr3 = chr ( rand ( 49, 57 ) );
$xstr4 = chr ( rand ( 49, 57 ) );
$_SESSION ['xstr1'] = $xstr1;
$_SESSION ['xstr2'] = $xstr2;
$_SESSION ['xstr3'] = $xstr3;
$_SESSION ['xstr4'] = $xstr4;
$_SESSION ['code'] = $xstr1 . $xstr2 . $xstr3 . $xstr4;
$cashx = 55;
$cashy = 20;
$im = imagecreate ( $cashx, $cashy ); // 建立画布
$white1 = ImageColorAllocate ( $im, 204, 204, 204 ); // 匹配颜色
$black = ImageColorAllocate ( $im, 0, 0, 0 ); // 匹配颜色
$red = ImageColorAllocate ( $im, 200, 140, 40 );
imagerectangle ( $im, 1, 1, 54, 19, $black );
srand ( ( double ) microtime () * 1000000 );

$size1 = rand ( 2, 4 );
$size2 = rand ( 2, 4 );
$size3 = rand ( 2, 4 );
$size4 = rand ( 2, 4 );
if ($size1 == 4)
	$size1 = 3;
if ($size2 == 3)
	$size2 = 4;
if ($size3 == 3)
	$size3 = 2;
if ($size4 == 2)
	$size4 = 3;
for($i = 0; $i < 50; $i ++) // 加入干扰象素
{
	imagesetpixel ( $im, rand ( 1, 79 ), rand ( 1, 19 ), $black );
}
imagestring ( $im, $size1, 5, 3, $_SESSION ['xstr1'], $black );
imagestring ( $im, $size2, 15, 3, $_SESSION ['xstr2'], $black );
imagestring ( $im, $size3, 25, 3, $_SESSION ['xstr3'], $black );
imagestring ( $im, $size4, 40, 3, $_SESSION ['xstr4'], $black );
ImagePng ( $im ); // 建立 PNG 图型。
ImageDestroy ( $im ); // 结束图形.
?>
