<?php

class SyncWithSystemThemeExtension extends Minz_Extension {

    public function install() {
        FreshRSS_Context::$user_conf->dark_theme = FreshRSS_Context::$user_conf->theme;
        FreshRSS_Context::$user_conf->light_theme = FreshRSS_Context::$user_conf->theme;
        FreshRSS_Context::$user_conf->save();
		return true;
	}

    public function init() {
        $this->registerTranslates();
        $this->registerHook('js_vars', array($this, 'providePreferredThemesInJs'));
        Minz_View::appendScript($this->getFileUrl('syncWithSystemTheme.js', 'js'));
    }

    public function handleConfigureAction() {
        if (Minz_Request::isPost()) {
            foreach (['dark_theme', 'light_theme', 'theme'] as $param) {
                if (Minz_Request::param($param)) {
                    FreshRSS_Context::$user_conf->_param($param, Minz_Request::param($param));
                }
            }
            FreshRSS_Context::$user_conf->save();
        }
    }

    public function providePreferredThemesInJs($vars) {
        $vars['SyncWithSystemTheme'] = array(
            'darkTheme' => FreshRSS_Context::$user_conf->dark_theme,
            'lightTheme' => FreshRSS_Context::$user_conf->light_theme,
            'postUrl' => _url('extension', 'configure', 'e', $this->getName()),
            'warning' => _t('ext.sync_with_system_theme.warning')
        );

        return $vars;
    }
}
