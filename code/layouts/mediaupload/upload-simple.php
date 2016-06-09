<?php

extract($displayData);

/**
 * Layout variables
 * ---------------------
 *  $id               : (string) Field ID
 *  $name             : (string) Field name
 * 	$value            : (string) Filename
 *  $filetypes        : (integer)  The file Type
 *  $showalttext      : (boolean) show alttext or not
 *  $alttext          : (string) alttext for the image
 */

?>
<?php if($showalttext) :?>
	<div class="row fields">
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<input id="<?php echo $id;?>alttext" type="text" class="form-control" name="<?php echo $id;?>alttext" value="<?php echo $alttext; ?>">
		</div>
		<div class="col-xs-3 col-sm-4 col-md-4 col-lg-4">
			<input id="<?php echo $id;?>" type="text" name="<?php echo $name;?>" value="<?php echo $value;?>" class="form-control" readonly="readonly">
		</div>
		<div class="col-xs-3 col-sm-2 col-md-2 col-lg-2">
<?php else : ?>
		<input id="<?php echo $id;?>" type="hidden" name="<?php echo $name;?>" value="<?php echo $value;?>">
<?php endif; ?>
			
			<div class="actions<?php echo $filetypes == 2 ? ' pdf' : ' image';?>">
				<div class="btn btn-primary fileinput-button">
				    <i class="glyphicon glyphicon-plus"><span>+</span></i>
				    <input id="file<?php echo $id;?>" type="file" name="files[]" >
				</div>
				<button class="btn btn-danger" onclick="return Mediaupload.Upload.remove('<?php echo $id;?>')">
	                <i class="glyphicon glyphicon-remove"><span>-</span></i>
				</button>
			</div> <!-- actions -->
		
<?php if($showalttext) :?>
		</div>
	</div> <!-- row fields -->
<?php endif;?>		