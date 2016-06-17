<?php

extract($displayData);

/**
 * Layout variables
 * ---------------------
 *  $id               : (string) Field ID
 *  $name             : (string) Field name
 * 	$value            : (string) Filename
 *  $filetypes        : (integer)  The file Type
 *  $alttext          : (string) alttext for the image
 */

?>

<div id="progress<?php echo $id;?>" class="progress progress-striped">
    <div class="bar bar-success"></div>
</div>