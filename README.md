Mediaupload
==============

Media upload field for Joomla! based on https://github.com/blueimp/jQuery-File-Upload

You need to implement an endpoint in your component e.g. 

FILE: upload.raw.php

```
class MyComponentControllerUpload extends JControllerLegacy
{
	/**
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function save()
	{
		$componentConfiguration = JComponentHelper::getComponent('com_mycomponent')->params;
		$input = JFactory::getApplication()->input;
		$realbasefolder = JPATH_BASE . '/images/' . $componentConfiguration->get('uploadfolder') . '/';
		$imagefolder =  'images/' . $componentConfiguration->get('uploadfolder') . '/';
		
		$filestypes = $input->getUint('filetypes',1);
		
		switch($filestypes)
		{
			case 1:
				$accept_file_types = '/\.(gif|jpe?g|png)$/i';
				break;
			case 2:
				$accept_file_types = '/\.(pdf)$/i';
				break;
		}
		
		$options =  [
			'upload_dir' => $realbasefolder,
			'upload_url' => $imagefolder,
			'accept_file_types' => $accept_file_types
		];
		
		try {
			$upload_handler = new Mediaupload\Upload\Handler($options);

			return true;
		}
		catch (Exception $e)
		{
			return false;
		}


	}
}
```

