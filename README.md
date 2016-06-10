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
		$path           = JFactory::getApplication()->getUserState('mycomponent.uploaddir');
		$realbasefolder = JPATH_BASE . '/' . $path;
		$imagefolder    = $path;

		$input = JFactory::getApplication()->input;
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

To get the right path you need to implement a plugin or overwrite the preprocessForm function within your model

```
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		parent::preprocessForm($form, $data, $group);

		$conf = $this->getComponentConfiguration();
		$path = 'images/' . $conf->get('uploadfolder') . '/';
		$form->setFieldAttribute('imagefile', 'directory', $path);
		
		JFactory::getApplication()->setUserState('mycomponent.uploaddir', $path);
	}

```

Your config.xml should have 

```
		<field
			name="uploadfolder"
			type="folderlist"
			class="form-control"
			labelClass="control-label"
			default=""
			label="Uploadfolder"
			directory="images"
			hide_none="true"
		/>


```
