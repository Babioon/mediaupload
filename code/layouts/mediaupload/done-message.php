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

<div class="alert alert-success" style="display: none"  id="donemessage<?php echo $id;?>">
    <?php echo JText::_('LIB_MEDIAUPLOAD_UPLOAD_SUCCESS'); ?>
</div>
