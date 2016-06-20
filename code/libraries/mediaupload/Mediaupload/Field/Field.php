<?php
/**
 * Mediaupload
 *
 * @package    Mediaupload
 * @author     Robert Deutz <rdeutz@googlemail.com>
 *
 * @copyright  Robert Deutz
 * @license    GNU General Public License version 2 or later
 **/

namespace Mediaupload\Field;

/**
 * Class Field
 *
 * @package  Mediaupload\Field
 */
abstract class Field extends \JFormField
{
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
		$text = $this->translateLabel ? \JText::_($text) : $text;

		// Build the class for the label.
		$class = !empty($this->description) ? 'hasTooltip' : '';
		$class = $this->required == true ? $class . ' required' : $class;
		$class = !empty($this->labelclass) ? $class . ' ' . $this->labelclass : $class;

		$title = '';

		// If a description is specified, use it to build a tooltip.
		if (!empty($this->description))
		{
			// Don't translate discription if specified in the field xml.
			$description = $this->translateDescription ? \JText::_($this->description) : $this->description;
			\JHtml::_('bootstrap.tooltip');
			$title = \JHtml::tooltipText(trim($text, ':'), $description, 0);
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
}