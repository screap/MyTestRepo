<?php
$strPageRoot = '/stripes';
define('HTTP_ROOT','http://'.$_SERVER['HTTP_HOST'].$strPageRoot.'/');
$strDirRoot = $_SERVER['DOCUMENT_ROOT'];
if (substr($_SERVER['DOCUMENT_ROOT'],-1) == '/') {
	$strDirRoot .= substr($strPageRoot,1);
} else {
	$strDirRoot .= $strPageRoot;
}
define('DIR_ROOT',$strDirRoot.'/');
?>