<?php

class AnnouncementsControllerExtension extends Extension
{
	/**
	* Insert our CSS/Js before the module class is initialised
	* @return Null
	*/
	public function onBeforeInit()
	{
		Requirements::css(SITE_ANNOUNCEMENTS_DIR . '/css/styles.css');
		Requirements::javascript(SITE_ANNOUNCEMENTS_DIR . '/javascript/scripts.min.js');
	}

	/**
	* Get and return our list of filtered messages
	* @return array
	*/
	public function getSiteAnnouncements()
	{
		$dateNow = date('Y-m-d H:i');
		$rawMessages = SiteAnnouncement::get()
			->filter([
				'Starts:LessThan' => $dateNow,
				'Expires:GreaterThan' => $dateNow
			]);

		$customisedMessages = $this->owner->customise([
			'PAMessages' => $rawMessages
		]);

		return $customisedMessages->renderWith("SiteAnnouncements");

	}

}
