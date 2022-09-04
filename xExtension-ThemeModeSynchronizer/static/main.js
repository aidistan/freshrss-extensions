window.addEventListener('load', () => {

    (() => {
        const context = window.context.extensions.ThemeModeSynchronizer

        // Decode double-html-encoded postUrl
        const txt = document.createElement('textarea')
        txt.innerHTML = context.postUrl // once for js_vars
        txt.innerHTML = txt.value       // once for _url
        context.postUrl = txt.value
    })()

    function getCurrentTheme() {
        for (node of document.querySelectorAll('link[rel="stylesheet"]')) {
            const match = new RegExp('/themes/([^/]+)/').exec(node.attributes.href.value)
            if (match && match[1] !== 'base-theme') {
                return match[1]
            }
        }
    }

    function getSystemMode() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark'
        } else {
            return 'light'
        }
    }

    function syncWithSystemMode(callTiming) {
        const theme = getCurrentTheme()
        const preferredTheme = context.extensions.ThemeModeSynchronizer[getSystemMode() + 'Theme']

        if (preferredTheme && theme !== preferredTheme) {
            fetch(context.extensions.ThemeModeSynchronizer.postUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    _csrf: context.csrf,
                    theme: preferredTheme
                })
            })
            .then(() => {
                const duringReading = callTiming === 'onChange' &&
                    ['normal', 'global', 'reader'].includes(context.current_view)

                if (!duringReading) location.reload()
            })
        }
    }

    if (context.anonymous) {
        return console.log(context.extensions.ThemeModeSynchronizer.warning.anonymous)
    } else if (!window.matchMedia) {
        return console.log(context.extensions.ThemeModeSynchronizer.warning.unsupported)
    } else {
        syncWithSystemMode('onLoad')

        window.matchMedia('(prefers-color-scheme: dark)')
            .addEventListener('change', () => syncWithSystemMode('onChange'))
    }
})
