<?php

class FeedPriorityShortcutExtension extends Minz_Extension {

    public function init() {
        if (Minz_Request::controllerName() == 'subscription' && Minz_Request::actionName() == 'index') {
            $this->registerHook('js_vars', [$this, 'provideFeedPrioritiesInJS']);
            Minz_View::appendScript($this->getFileUrl('main.js', 'js'));
            Minz_View::appendStyle($this->getFileUrl('main.css', 'css'));
        }
    }

    public function handleConfigureAction() {
        if (Minz_Request::isPost()) {
            $feedDAO = FreshRSS_Factory::createFeedDao();
            $feedDAO->updateFeed(Minz_Request::param('feed_id'), [
                'priority' => [
                    '🏠' => FreshRSS_Feed::PRIORITY_MAIN_STREAM,
                    '📁' => FreshRSS_Feed::PRIORITY_NORMAL,
                    '🔒' => FreshRSS_Feed::PRIORITY_ARCHIVED
                ][Minz_Request::param('priority')]
            ]);
        }
    }

    public function provideFeedPrioritiesInJS($vars) {
        $feedDAO = FreshRSS_Factory::createFeedDao();

        return array_merge($vars, ['FeedPriorityShortcut' => [
            'postUrl' => _url('extension', 'configure', 'e', $this->getName()),
            'priority' => array_map(function($feed) {
                return [
                    FreshRSS_Feed::PRIORITY_MAIN_STREAM => '🏠',
                    FreshRSS_Feed::PRIORITY_NORMAL => '📁',
                    FreshRSS_Feed::PRIORITY_ARCHIVED => '🔒'
                ][$feed -> priority()];
            }, $feedDAO->listFeeds())
        ]]);
    }
}
