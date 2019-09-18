<?php
include_once('barcode.print.php');
 
 header("Content-type: " . $mime);
 header("Content-Length: " . $size);
 // NOTE: Possible header injection via $basename
 header("Content-Disposition: attachment; filename=" . $data.".".$format);
 header('Content-Transfer-Encoding: binary');
 header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
 ?>