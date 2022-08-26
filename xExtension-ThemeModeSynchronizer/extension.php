<?php

class ThemeModeSynchronizerExtension extends Minz_Extension {

    public function install() {
        FreshRSS_Context::$user_conf->dark_theme = FreshRSS_Context::$user_conf->theme;
        FreshRSS_Context::$user_conf->light_theme = FreshRSS_Context::$user_conf->theme;
        FreshRSS_Context::$user_conf->save();
		return true;
	}

    public function init() {
        $this->registerTranslates();
        $this->registerHook('js_vars', [$this, 'providePreferredThemesInJs']);
        Minz_View::appendScript($this->getFileUrl('main.js', 'js'));
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
        return array_merge($vars, ['ThemeModeSynchronizer' => [
            'darkTheme' => FreshRSS_Context::$user_conf->dark_theme,
            'lightTheme' => FreshRSS_Context::$user_conf->light_theme,
            'postUrl' => _url('extension', 'configure', 'e', $this->getName()),
            'warning' => _t('ext.theme_mode_synchronizer.warning')
        ]]);
    }
}
