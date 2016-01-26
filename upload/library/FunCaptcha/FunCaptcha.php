<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class FunCaptcha_FunCaptcha extends XenForo_Captcha_Abstract
{

    public static function CaptchaHook(array &$extraChoices, XenForo_View $view, array $preparedOption)
    {
        $extraChoices['FunCaptcha'] = array(
            'label' => 'FunCaptcha',
            'hint'  => 'Get Free account from <a href="http://funcaptcha.co">FunCaptcha</a>',
            'value' => 'FunCaptcha_FunCaptcha',
        );
    }

    public static function Addon_Install()
    {
        // set FunCaptcha as default captcha:
        $dw = XenForo_DataWriter::create('XenForo_DataWriter_Option');
        $dw->setExistingData('captcha', true);
        $dw->set('option_value', 'FunCaptcha_FunCaptcha');
        $dw->save();
    }

    public function isValid(array $input)
	{
		$options = XenForo_Application::get('options')->getOptions();
        if (empty($options['funcaptcha_public']) || empty($options['funcaptcha_private'])) {
            return true;
        }		

        require_once('funcaptcha-lib.php');

        $funcaptcha =  new FUNCAPTCHA();
        $funcaptcha->setProxy($options['funcaptcha_proxy']);
        $funcaptcha->setTheme($options['funcaptcha_theme']);
        $funcaptcha->setNoJSFallback($options['funcaptcha_javascript']);
		$score =  $funcaptcha->checkResult($options['funcaptcha_private']);
		
		if ($score) {
			return true;
        } else {
            $this->error = 'funcaptcha_unverfied';
            return false;
        }

    }

	public function renderInternal(XenForo_View $view)
	{
		$options = XenForo_Application::get('options')->getOptions();
        if (empty($options['funcaptcha_public']) || empty($options['funcaptcha_private'])) {
            return '';
        }		
        
        require_once('funcaptcha-lib.php');
        
        $funcaptcha =  new FUNCAPTCHA();
        $funcaptcha->setSecurityLevel($options['funcaptcha_security']);
        $funcaptcha->setLightboxMode($options['funcaptcha_lightbox']);
        $funcaptcha->setProxy($options['funcaptcha_proxy']);
        $funcaptcha->setTheme($options['funcaptcha_theme']);
        $funcaptcha->setNoJSFallback($options['funcaptcha_javascript']);

        //only show HTML/label if not lightbox mode.
        if ($options['funcaptcha_lightbox']) {
                $output = $funcaptcha->getFunCaptcha($options['funcaptcha_public']);
        } else {
                $output = "<div class=\"blockrow\"><input type=hidden value='1' id='humanverify' name='humanverify' /><div class=\"group\"><li>";
                $output = $output . $funcaptcha->getFunCaptcha($options['funcaptcha_public']);
                $output = $output . "</li></div></div>";
        }

        return $output;
		
	}
	public static function optionCaptchaRender(array &$extraChoices, XenForo_View $view, array $preparedOption)	{
		$extraChoices['FunCaptcha'] = array(
			'label'	=> 'FunCaptcha',
			'hint'	=> 'Get Free account from <a href="http://funcaptcha.com">FunCaptcha</a>',
			'value' => 'FunCaptcha_FunCaptcha',
		);
	}	
}