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
            'hint'  => 'Get your account from <a href="http://funcaptcha.co">FunCaptcha</a>',
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
        $funcaptcha->setProxy($options['funcaptcha_proxy']);
        $funcaptcha->setTheme($options['funcaptcha_theme']);
        $funcaptcha->setNoJSFallback($options['funcaptcha_javascript']);

        $output = "<div class=\"blockrow\"><input type=hidden value='1' id='humanverify' name='humanverify' /><div class=\"group\"><li>";
        $output = $output . "<span class='fc-ctn'>";
        $output = $output . "<div id='funcaptcha' data-pkey='{$options['funcaptcha_public']}'></div>";
        $output = $output . "</span></li></div></div>";
        $output = $output . "<script src='https://funcaptcha.com/fc/api/' async defer></script>\n";
        $output = $output . "        <script>\n";
        $output = $output . "        if (typeof fc_query_created == 'undefined') {\n";
        $output = $output . "            var fc_pkey = '".$options["funcaptcha_public"]."';\n";
        $output = $output . "            var fc_query_created = true;\n";
        $output = $output . "            jQuery(\".footerLinks .OverlayTrigger\").click(function() {\n";
        $output = $output . "                jQuery(\".xenOverlay\").children(\"form\").each(function(){\n";
        $output = $output . "                    if ($(this).attr(\"action\").indexOf(\"contact\") >= 0) {\n";
        $output = $output . "                        $(this).find(\".fc-ctn\").html(\"<div id='funcaptcha' data-pkey='\"+fc_pkey+\"'></div>\");\n";
        $output = $output . "                    } else if ($(this).attr(\"action\").indexOf(\"lost\") >= 0) {\n";
        $output = $output . "                        $(this).find(\".fc-ctn\").html(\"\");\n";
        $output = $output . "                    }\n";
        $output = $output . "                });\n";
        $output = $output . "                try {\n";
        $output = $output . "                    FunCaptcha();\n";
        $output = $output . "                } catch (e) {\n";
        $output = $output . "                    \n";
        $output = $output . "                }\n";
        $output = $output . "            });\n";
        $output = $output . "            jQuery(\".lostPassword .OverlayTrigger\").click(function() {\n";
        $output = $output . "                jQuery(\".xenOverlay\").children(\"form\").each(function(){\n";
        $output = $output . "                    if ($(this).attr(\"action\").indexOf(\"contact\") >= 0) {\n";
        $output = $output . "                        $(this).find(\".fc-ctn\").html(\"\");\n";
        $output = $output . "                    } else if ($(this).attr(\"action\").indexOf(\"lost\") >= 0) {\n";
        $output = $output . "                        $(this).find(\".fc-ctn\").html(\"<div id='funcaptcha' data-pkey='\"+fc_pkey+\"'></div>\");\n";
        $output = $output . "                    }\n";
        $output = $output . "                });\n";
        $output = $output . "                try {\n";
        $output = $output . "                    FunCaptcha();\n";
        $output = $output . "                } catch (e) {\n";
        $output = $output . "                    \n";
        $output = $output . "                }\n";
        $output = $output . "            });\n";
        $output = $output . "        }\n";
        $output = $output . "        </script>";    
        return $output;
        
    }
    public static function optionCaptchaRender(array &$extraChoices, XenForo_View $view, array $preparedOption) {
        $extraChoices['FunCaptcha'] = array(
            'label' => 'FunCaptcha',
            'hint'  => 'Get Free account from <a href="http://funcaptcha.com">FunCaptcha</a>',
            'value' => 'FunCaptcha_FunCaptcha',
        );
    }   
}