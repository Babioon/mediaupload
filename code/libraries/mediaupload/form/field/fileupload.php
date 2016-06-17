<?php

defined('JPATH_PLATFORM') or die;

/**
 * Form Field class for the Joomla CMS.
 * Provides a upload mechanism,
 * needs some more magic on component side
 */
class MediauploadFormFieldFileupload extends Mediaupload\Field\Field
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'Fileupload';

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
	 * @var string
	 */
	protected $alttext = '';

	/**
	 * @var string
	 */
	protected $directory = '';

	/**
	 * @var string
	 */
	protected $uploadendpoint = '';

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
		case 'uploadendpoint':
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
		case 'uploadendpoint':
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
			$fields = ['uploadbuttontext', 'filetype', 'alttext', 'directory', 'url', 'uploadendpoint' ];

			foreach ($fields AS $field)
			{
				if (isset($this->element[$field]))
				{
					$this->{$field} = (string) $this->element[$field];
				}
			}

			// Default value for debug
			$this->debug = false;

			$fields = ['placeholder'];

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

		$script = "
			jQuery( document ).ready(function($) {
				$('#file{$this->id}').fileupload({
					url: '{$this->uploadendpoint}',
					dataType: 'json',
					done: function (e, data) {
						if(data.result.files[0].error)
						{
							alert(data.result.files[0].error);
							$('#progress{$this->id} .bar').css('width', 0);
							
							return;
						}

						$('#input{$this->id}').val(JSON.stringify(data.result.files));
						$('#progress{$this->id}').fadeOut();
						$('#donemessage{$this->id}').show();
					},
					progressall: function (e, data) {
						$('#progress{$this->id}').show();
						var progress = parseInt(data.loaded / data.total * 100, 10);
						$('#progress{$this->id} .bar').css(
							'width',
							progress + '%'
						);
					},
					maxChunkSize: 10000000,
					formData: []
				}).prop('disabled', !$.support.fileInput)
					.parent().addClass($.support.fileInput ? undefined : 'disabled');
			  });
		";

		JFactory::getDocument()->addScriptDeclaration($script);

		$html = [];

		$data =  array(
			'filetypes'     => 2
		);

		$html[] = $this->getRenderer('mediaupload.area-open')->render($data);

		$data =  array(
			'id'            => $this->id,
			'name'          => $this->name,
			'value'         => $this->value,
			'alttext'       => $this->alttext,
			'required'      => $this->required
		);

		$html[] = $this->getRenderer('mediaupload.upload-file')->render($data);

		$html[] = $this->getRenderer('mediaupload.progress-bar')->render($data);

		$html[] = $this->getRenderer('mediaupload.done-message')->render($data);

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

		$doc->addStyleSheet($basedir . '/media/mediaupload/css/main.css');
		$doc->addScript($basedir . '/media/mediaupload/js/mediaupload.js');

		self::$initialised = true;

		return true;
	}
}