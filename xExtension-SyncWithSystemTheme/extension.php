<?php

class SyncWithSystemThemeExtension extends Minz_Extension {

    public function init() {
        $this->registerHook('js_vars', array($this, 'providePreferredThemesInJs'));
        Minz_View::appendScript($this->getFileUrl('syncWithSystemTheme.js', 'js'));
    }

    public function handleConfigureAction() {
        $this->registerTranslates();

        if (Minz_Request::isPost()) {
            FreshRSS_Context::$user_conf->light_theme = Minz_Request::param('light_theme', null);
            FreshRSS_Context::$user_conf->dark_theme = Minz_Request::param('dark_theme', null);
            FreshRSS_Context::$user_conf->save();
        }
    }

    public function providePreferredThemesInJs($vars) {
        $vars["SyncWithSystemTheme"] = array(
            "lightTheme" => FreshRSS_Context::$user_conf->light_theme,
            "darkTheme" => FreshRSS_Context::$user_conf->dark_theme,
            "csrfToken" => FreshRSS_Auth::csrfToken()
        );

        return $vars;
    }
}
