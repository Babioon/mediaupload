<?php

extract($displayData);

/**
* Layout variables
* ---------------------
* 	$filetype     : (integer)  The file Type
*/

$fileclass = $filetypes == 2 ? ' pdf' : ' image';

?>
<div class="uploadarea<?php echo $fileclass;?>">
