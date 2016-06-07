<?php

extract($displayData);

/**
 * Layout variables
 * ---------------------
 *  $id               : (string) Field ID
 * 	$title            : (string) title
 *  $class            : (string) CSS class
 *  $text             : (string) label text
 *  $required         : (boolean) required or not
 */
?>
<label id="<?php echo $id;?>-lbl" for="file<?php echo $id;?>" class="<?php echo $class;?>"<?php echo $title == '' ? '' : 'title="' . $title . '"';?>
	<?php echo $text;?>
	<?php if($required) :?>
		<span class="star">&#160;*</span>
	<?php endif; ?>
</label>
