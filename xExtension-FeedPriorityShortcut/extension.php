<?php

class FeedPriorityShortcutExtension extends Minz_Extension {
    private const PRIORITY_MAP = [
        FreshRSS_Feed::PRIORITY_IMPORTANT => '📌',
        FreshRSS_Feed::PRIORITY_MAIN_STREAM => '🏠',
        FreshRSS_Feed::PRIORITY_CATEGORY => '📁',
        FreshRSS_Feed::PRIORITY_FEED => '📃',
        FreshRSS_Feed::PRIORITY_HIDDEN => '🔒'
    ];

    public function init() {
        if (Minz_Request::controllerName() === 'subscription' && Minz_Request::actionName() === 'index') {
            $this->registerHook('js_vars', [$this, 'provideFeedPrioritiesInJS']);
            Minz_View::appendScript($this->getFileUrl('main.js', 'js'));
            Minz_View::appendStyle($this->getFileUrl('main.css', 'css'));
        }
    }

    public function handleConfigureAction() {
        if (Minz_Request::isPost()) {
            $feedDAO = FreshRSS_Factory::createFeedDao();
            $emojiMap = array_flip(self::PRIORITY_MAP);
            $priorityEmoji = Minz_Request::param('priority');

            if (isset($emojiMap[$priorityEmoji])) {
                $feedDAO->updateFeed(Minz_Request::param('feed_id'), [
                    'priority' => $emojiMap[$priorityEmoji]
                ]);
            }
        }
    }

    public function provideFeedPrioritiesInJS($vars) {
        $feedDAO = FreshRSS_Factory::createFeedDao();
        $priorityEmojis = [];
        foreach ($feedDAO->listFeeds() as $feed) {
            $priorityEmojis[$feed->id()] = self::PRIORITY_MAP[$feed->priority()] ?? '🏠';
        }

        return array_merge($vars, ['FeedPriorityShortcut' => [
            'postUrl' => _url('extension', 'configure', 'e', $this->getName()),
            'priorityEmojis' => $priorityEmojis,
            'tooltips' => [
                'important' => _t('sub.feed.priority.important'),
                'main_stream' => _t('sub.feed.priority.main_stream'),
                'category' => _t('sub.feed.priority.category'),
                'feed' => _t('sub.feed.priority.feed'),
                'hidden' => _t('sub.feed.priority.hidden')
            ]
        ]]);
    }
}
