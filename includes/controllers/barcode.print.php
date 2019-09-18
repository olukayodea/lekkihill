<?php
include_once('barcode.php');
$option = array();
$format = "png";
$symbology = "code-39-ascii";
$data = $_GET['d'];
$options = $option['w'] = "220";

$generator = new barcode_generator();

/* Output directly to standard output. */
$generator->output_image($format, $symbology, $data, $options);
?>