<?php

extract($displayData);

/**
 * Layout variables
 * ---------------------
 *  $id               : (string) Field ID
 * 	$value            : (string) Filename
 *  $thumbnailfolder  : (string) Path to the thumbnailfolder
 *  $placeholderimage : (string) Full uri to placeholderimage
 */

$src = $placeholderimage;

if ($value != "")
{
	$src = $thumbnailfolder . '/' . $value;
}

?>
<div class="areapicture">
	<img id="thumbnail<?php echo $id;?>" src="<?php echo $src;?>" />
</div>
