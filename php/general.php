<?php
function conversionInVar($inVar) {
	$input_text	= trim($inVar);
	$input_text	= strip_tags($input_text);
	$input_text	= htmlspecialchars($input_text);
	while (strpos($input_text," ")!=false) {
		$input_text = str_replace(" ", "_", $input_text);
		}
	return $input_text;
	}
?>
