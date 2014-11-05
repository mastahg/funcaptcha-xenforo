<?php

/**
 * Listener to extend options
 */
class FunCaptcha_Listener_Options
{
	
	/**
	 * Extend Captcha options.
	 *
	 * @param array &$extraChoices The array you should push your extra choices in to. This will be used in a <xen:options /> tag.
	 * @param XenForo_View $view The current view object.
	 * @param array $preparedOption The prepared option's data.
	 */
	public static function captcha(array &$extraChoices, XenForo_View $view, array $preparedOption)
	{
		$extraChoices['FunCaptcha_Captcha'] = array(
			"label" => new XenForo_Phrase('funcaptcha_use_funcaptcha'),
			"hint" => new XenForo_Phrase('funcaptcha_set_keys_first'),
			"value" => "FunCaptcha_Captcha_FunCaptcha"
		);
	}

}
