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
 *  $required         : (boolean) required
 */

?>

<span class="btn btn-success fileinput-button">
	<i class="glyphicon glyphicon-plus"></i>
	<span><?php echo JTExt::_('LIB_MEDIAUPLOAD_SELECT_FILES'); ?></span>
	<!-- The file input field used as target for the file upload widget -->
	<input id="file<?php echo $id;?>" type="file" name="files[]" multiple>
</span>
<input id="input<?php echo $id; ?>" type="hidden" name="<?php echo $name; ?>" <?php if($required) {echo "required";} ?> />
