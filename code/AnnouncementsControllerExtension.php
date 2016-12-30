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
	* Get and return our list of messages
	* @return array
	*/

	public function getSiteAnnouncements()
	{
		$rawMessages = SiteAnnouncement::get();

		$customisedMessages = $this->owner->customise([
			'PAMessages' => $rawMessages
		]);

		return $customisedMessages->renderWith("SiteAnnouncements");

	}
}
