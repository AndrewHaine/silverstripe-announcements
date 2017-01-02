<?php

class SiteAnnouncement extends DataObject
{

	private static $singular_name = 'Announcement';

	private static $plural_name = 'Announcements';

	private static $default_sort = 'Expires ASC';

	private static $summary_fields =
	[
		'Title',
		'TimeUntilExpiry',
		'ColorPreview'
	];

	private static $field_labels =
	[
		'TimeUntilExpiry' => 'Expires in',
		'ColorPreview' => 'Background Color'
	];

	private static $db =
	[
		'Title' => 'Varchar(130)',
		'Starts' => 'SS_Datetime',
		'Expires' => 'SS_Datetime',
		'LinkTo' => 'Text',
		'Content' => 'HTMLText',
		'CanClose' => 'Boolean',
		'StickyPos' => 'Boolean',
		'PagePos' => 'Enum("Top Left,Top Full,Top Right,Bottom Left,Bottom Full,Bottom Right,Left Side,Right Side", "Top Full")',
		'TakesSpace' => 'Boolean',
		'HasCTA' => 'Boolean',
		'CTAText' => 'Varchar(20)',
		'BackgroundColor' => 'Color',
		'BackgroundTransparency' => 'Boolean',
		'TextColor' => 'Color',
		'CTAColor' => 'Color',
		'CTATextColor' => 'Color'
	];

	private static $defaults = [
		'Title' => 'New Site Announcement',
		'CanClose' => '1',
		'PagePos' => 'Top Full',
		'TakesSpace' => '1'
	];

	/**
	 * CMS Fields
	 * @return FieldList
	 */
	public function getCMSFields()
	{
		$this->beforeUpdateCMSFields(function($fields) {

			/** Setup for all CMS hints and datetime formatting */
			$startTime = DatetimeField::create('Starts');
			$expiryTime = DatetimeField::create('Expires');

			$dateFields = [$startTime, $expiryTime];

			foreach ($dateFields as $field) {
				$field->getDateField()
					->setConfig('dateformat', 'dd/MM/yyyy')
					->setConfig('showcalendar', true)
					->setAttribute('placeholder', 'Date')
					->setDescription('Date format: d/m/Y');

				$field->getTimeField()
					->setAttribute('placeholder', 'Time')
					->setDescription('Time format: hh:mm');
			}

			/** Implement a simplified version of TinyMCE */
			HtmlEditorConfig::set_active('silverstripe-announcements');

			$fields->addFieldsToTab(
				"Root.Main",
				[
				TextField::create('Title'),
				$startTime,
				$expiryTime,
				TextField::create('LinkTo', 'Link To')
					->setDescription('Optional field for linking the message to a url.'),
				HTMLEditorField::create('Content')
				]
			);

			/** Functionality fields, closability etc */
			$fields->addFieldsToTab(
				"Root.Functionality",
				[
					CheckboxSetField::create('CanClose', 'Show close button?', ['1' => ' ']),
					CheckboxSetField::create('StickyPos', 'Sticky message', ['1' => 'Check this box to pin the message to the page']),
					CheckboxSetField::create('HasCTA', 'Show button', ['1' => 'If checked the message will contain a "Call to action" button']),
					TextField::create('CTAText', 'Button text')
						->setDescription('This text will appear inside the message button'),
					DropdownField::create(
						'PagePos',
						'Position',
						singleton('SiteAnnouncement')->dbObject('PagePos')->enumValues()
					)->setDescription('Select where on the page you would like the message to appear'),
					CheckboxSetField::create('TakesSpace', 'Takes space', ['1' => 'If checked the message will push the page content down'])
						->setDescription('This option is only available if the position is "Top Full"')
				]
			);

			/** Design fields */
			$fields->addFieldsToTab(
				"Root.Design",
				[
					ColorField::create('BackgroundColor', 'Background Color'),
					CheckboxSetField::create('BackgroundTransparency', 'Background transparency', ['1' => 'Add transparency to your message background']),
					ColorField::create('TextColor', 'Text Color'),
					ColorField::create('CTAColor', 'Button color'),
					ColorField::create('CTATextColor', 'Button text color')
				]
			);


		});
		return parent::getCMSFields();
	}

	/**
	* Color preview in GridField
	* @return String
	*/
	public function ColorPreview()
	{
		$bgCol = $this->BackgroundColor;
		if($bgCol) {
			$prevBox = HTMLText::create();
			$prevBox->setValue("<div class='ss_announcements__color-preview-block' style='background-color:#" . $bgCol  . "'> #" . $bgCol . "</div>");
			return $prevBox;
		} else {
		 	return "No Color Selected";
		}
	}

	/**
	* Calculate time until expiry for preview
	* @return String
	*/
	public function TimeUntilExpiry()
	{
		$expirySrc = $this->Expires;

		if($expirySrc) {
			$expires = strtotime($expirySrc);
			$dateNow = strtotime("now");

			if(($expires - $dateNow) < 0) {
				return 'Expired';
			} else {
				$daysBetween = floor(abs($expires - $dateNow) / 86400);
				$hoursBetween = ceil(abs($expires - $dateNow) / 3600) - (floor(abs($expires - $dateNow) / 86400) * 24);
				return $daysBetween . ' Days,   ' . $hoursBetween . " Hours";
			}

		} else {
			return 'No date set';
		}
	}

	/**
	 * Event handler called before writing to the database.
	 */
	public function onBeforeWrite()
	{
		parent::onBeforeWrite();

		/* Set our default date to now */
		if(!$this->Starts) {
			$this->Starts = date('d/m/Y');
		}

		/** Space taking is only available on the top full position */
		if($this->PagePos !== 'Top Full') {
			$this->TakesSpace = 0;
		}

		/** Don't rely on checkboxes being correctly checked */
		if($this->CTAText) {
			$this->HasCTA = 1;
		}

		/** Don't allow transparency if there is no content behind */
		if($this->TakesSpace == 1) {
			$this->BackgroundTransparency = 0;
		}
	}
}
