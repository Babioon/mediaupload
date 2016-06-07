<?php

extract($displayData);

/**
 * Layout variables
 * ---------------------
 * 	$filetype     : (integer)  The file Type
 */

$fileclass = $filetypes == 2 ? ' pdf' : ' image';




?>
<div class="areapicture">
	
	if ($this->value != "")
	{
	$html[] = '    <img id="thumbnail' . $this->id . '" src="' . $thumbnailfolder . '/' . $this->value . '" />';
	}
	else
	{
	$html[] = '    <img id="thumbnail' . $this->id . '" src="' . $basedir . '/media/mediaupload/images/placeholder.png" />';
	}
	
	$html[] = ' </div>';
