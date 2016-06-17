<?php

extract($displayData);

/**
* Layout variables
* ---------------------
* 	$filetype     : (integer)  The file Type
*/

$fileclass = $filetypes == 2 ? ' file' : ' image';

?>
<div class="uploadarea<?php echo $fileclass;?>">
