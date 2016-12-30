<?php

class AnnouncementsAdmin extends ModelAdmin
{
	private static $menu_title = 'Announcements';

	private static $url_segment = 'pa_system';

	private static $managed_models = ['SiteAnnouncement'];

	private static $menu_icon = "silverstripe-announcements/images/icons/pa_icon.png";
}
