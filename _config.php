<?php

/** Include our custom controller extension */
Controller::add_extension('AnnouncementsControllerExtension');

/** Define variable for getting module directory */
define('SITE_ANNOUNCEMENTS_DIR', basename(dirname(__FILE__)));
