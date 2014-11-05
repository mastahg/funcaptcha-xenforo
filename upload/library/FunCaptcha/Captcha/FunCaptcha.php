<?php

class FunCaptcha_Captcha_FunCaptcha extends XenForo_Captcha_Abstract
{
	protected $_funCaptcha;
	protected $_config = array();

	/**
	 * Constructor
	 *
	 * Initializes the FUNCAPTCHA library
	 */
	public function __construct()
	{
		$options = XenForo_Application::getOptions();

		$pubKey = $options->funcaptcha_public_key;
		$privKey = $options->funcaptcha_private_key;

		if(!$pubKey || !$privKey)
		{
			$this->_config['public_key'] = false;
			return;
		}
		else
		{
			$this->_config['public_key'] = $pubKey;
			$this->_config['private_key'] =  $privKey;
		}

		require_once(__DIR__.'/../Library/funcaptcha.php');

		$this->_funCaptcha = new FUNCAPTCHA();

		if($theme = $options->funcaptcha_theme)
		{
			$this->_funCaptcha->setTheme($theme);
		}
	}

	/**
	 * Determines if CAPTCHA is valid (passed).
	 *
	 * @see XenForo_Captcha_Abstract::isValid()
	 */
	public function isValid(array $input)
	{
		if(!$this->_config['public_key'])
		{
			return true;
		}

		return $this->_funCaptcha->checkResult($this->_config['private_key']);
	}

	/**
	 * Renders the CAPTCHA template.
	 *
	 * @see XenForo_Captcha_Abstract::renderInternal()
	 */
	public function renderInternal(XenForo_View $view) {
		if(!$this->_config['public_key'])
		{
			return '';
		}

		return $this->_funCaptcha->getFunCaptcha($this->_config['public_key']);
	}

}
