<?php


defined('JPATH_PLATFORM') or die;

/**
 * Form Field class for the Joomla CMS.
 * Provides a upload mechanism,
 * needs some more magic on component side
 */
class MediauploadFormFieldMediaupload extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'Mediaupload';

	/**
	 * The initialised state of the document object.
	 *
	 * @var    boolean
	 */
	protected static $initialised = false;

	/**
	 * @var    string
	 */
	protected $filetype = '';

	/**
	 * @var bool
	 */
	protected $placeholder = false;

	/**
	 * @var string
	 */
	protected $uploadbuttontext = '';

	/**
	 * @var bool
	 */
	protected $showalttext = false;

	/**
	 * @var string
	 */
	protected $alttext = '';

	/**
	 * @var string
	 */
	protected $directory = '';

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'filetype':
			case 'placeholder':
			case 'uploadbuttontext':
			case 'edit':
			case 'alttext':
			case 'directory':
			return $this->$name;
		}

		return parent::__get($name);
	}

	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string  $name   The property name for which to the the value.
	 * @param   mixed   $value  The value of the property.
	 *
	 * @return  void
	 */
	public function __set($name, $value)
	{
		switch ($name)
		{
			case 'filetype':
			case 'uploadbuttontext':
			case 'alttext':
				$this->$name = (string) $value;
				break;

			case 'placeholder':
			case 'directory':
			case 'url':
			case 'debug':
			default:
				parent::__set($name, $value);
		}
	}

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see 	JFormField::setup()
	 */
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$result = parent::setup($element, $value, $group);

		if ($result == true)
		{
			$fields = ['uploadbuttontext', 'filetype', 'alttext', 'directory', 'url' ];

			foreach ($fields AS $field)
			{
				if (isset($this->element[$field]))
				{
					$this->{$field} = (string) $this->element[$field];
				}
			}

			// Default value for debug
			$this->debug = false;

			$fields = ['placeholder', 'showalttext'];

			foreach ($fields AS $field)
			{
				if (isset($this->element[$field]))
				{
					$this->{$field} = in_array(strtolower((string) $this->element[$field]), ['yes', '1',  'true']);
				}
			}
		}

		return $result;
	}


	/**
	 * Method to get the field input markup for a upload field.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$this->init();

		$basedir = JUri::base(true);

		$baseUploadDir   = $this->getBaseUploadDirectory();
		$thumbnailfolder = $baseUploadDir . '/thumbnail';

		$filetypes = $this->mapFiletypeToUrlParameterValue();

		$script = "
			jQuery( document ).ready(function( ) {
				Mediaupload.Upload.add('{$this->id}','{$filetypes}');
			  });
		";
		JFactory::getDocument()->addScriptDeclaration($script);

		$html = [];
		
		$data =  array(
			'filetypes'     => $filetypes
		);

		$html[] = $this->getRenderer('mediaupload.area-open')->render($data);

		if ($this->placeholder)
		{
			$data =  array(
							'id' => $this->id,
							'value' => $this->value,
							'thumbnailfolder' => $thumbnailfolder,
							'placeholderimage' => $basedir . '/media/mediaupload/images/placeholder.png'
						);

			$html[] = $this->getRenderer('mediaupload.placeholder')->render($data);
		}
		
		$data =  array(
			'id'            => $this->id,
			'value'         => $this->value,
			'showalttext'   => $this->showalttext,
			'alttext'       => $this->alttext,
			'filetypes'     => $filetypes
		);
		
		$html[] = $this->getRenderer('mediaupload.upload-simple')->render($data);
		
		$html[] = $this->getRenderer('mediaupload.area-close')->render(array());

		return implode("\n", $html);
	}

	protected function init()
	{
		if (self::$initialised)
		{
			return true;
		}

		$doc           = JFactory::getDocument();
		$basedir       = JUri::base(true);

		$baseUploadDir = $this->getBaseUploadDirectory();

		$doc->addStyleSheet($basedir . '/media/mediaupload/css/main.css');
		$doc->addScript($basedir . '/media/mediaupload/js/mediaupload.js');

		$script = "
				jQuery( document ).ready(function( ) {
						Mediaupload.Upload.basedir     = '{$baseUploadDir}';
						Mediaupload.Upload.placeholder = '{$basedir}/media/mediaupload/images/placeholder.png';
						Mediaupload.Upload.spinner     = '{$basedir}/media/mediaupload/images/loading_small.gif';
						Mediaupload.Upload.url         = 'index.php?option=com_mycomponent&task=upload.save&format=raw';
				});
		";

		JFactory::getDocument()->addScriptDeclaration($script);

		self::$initialised = true;

		return true;
	}

	/**
	 * maps a filetype string to an integer
	 *
	 * @return  int  filetype
	 */
	protected function mapFiletypeToUrlParameterValue()
	{
		switch ($this->filetype)
		{
			default:
			case 'image':
				$filetypes = 1;
				break;

			case 'pdf':
				$filetypes = 2;
				break;
		}

		return $filetypes;
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 */
	protected function getLabel()
	{
		if ($this->hidden)
		{
			return '';
		}

		// Get the label text from the XML element, defaulting to the element name.
		$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
		$text = $this->translateLabel ? JText::_($text) : $text;

		// Build the class for the label.
		$class = !empty($this->description) ? 'hasTooltip' : '';
		$class = $this->required == true ? $class . ' required' : $class;
		$class = !empty($this->labelclass) ? $class . ' ' . $this->labelclass : $class;

		$title = '';
		
		// If a description is specified, use it to build a tooltip.
		if (!empty($this->description))
		{
			// Don't translate discription if specified in the field xml.
			$description = $this->translateDescription ? JText::_($this->description) : $this->description;
			JHtml::_('bootstrap.tooltip');
			$title = JHtml::tooltipText(trim($text, ':'), $description, 0);
		}
		
		$data =  array(
			'id'            => $this->id,
			'class'         => $class,
			'title'         => $title,
			'text'          => $text,
			'required'      => $this->required
		);

		return $this->getRenderer('mediaupload.label')->render($data);
	}

	/**
	 * returns the base directory for file uploads
	 *
	 * @return string
	 */
	private function getBaseUploadDirectory()
	{
		$basedir       = JUri::base(true);
		$baseUploadDir = $basedir . '/images';

		if (!empty($this->directory))
		{
			$baseUploadDir .= "/" . $this->directory;

			return $baseUploadDir;
		}

		return $baseUploadDir;
	}
}
