<?php

/** Include our custom controller extension */
Controller::add_extension('AnnouncementsControllerExtension');

/** Define variable for getting module directory */
define('SITE_ANNOUNCEMENTS_DIR', basename(dirname(__FILE__)));

/** Define a simplified TinyMCE interface */
HtmlEditorConfig::get('silverstripe-announcements')->removeButtons('tablecontrols', 'blockquote', 'hr');
HtmlEditorConfig::get('silverstripe-announcements')->enablePlugins(array(
	'ssbuttons' => sprintf('../../../%s/tinymce_ssbuttons/editor_plugin_src.js', THIRDPARTY_DIR)
));
HtmlEditorConfig::get('silverstripe-announcements')->insertButtonsAfter('charmap', 'sslink', 'unlink');
HtmlEditorConfig::get('silverstripe-announcements')->setOptions(
	[
		'theme_advanced_blockformats' => 'p, h2, h3',
		'paste_remove_spans' => 'true'
	]
);
