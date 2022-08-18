window.addEventListener('load', () => {

    function getCurrentTheme() {
        for (node of document.querySelectorAll('link[rel="stylesheet"]')) {
            const match = new RegExp('/themes/([^/]+)/').exec(node.attributes.href.value)
            if (match && match[1] !== 'base-theme') {
                return match[1]
            }
        }
    }

    function getPreferredColorScheme() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark'
        } else {
            return 'light'
        }
    }

    function syncWithSystemTheme() {
        const theme = getCurrentTheme()
        const scheme = getPreferredColorScheme()
        const preferredTheme = extContext[scheme + 'Theme']

        if (preferredTheme && theme !== preferredTheme) {
            fetch(extContext.postUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    _csrf: context.csrf,
                    theme: preferredTheme
                })
            })
            // Only fresh the page if not reading
            .then(() => !['normal', 'global', 'reader'].includes(context.current_view) && location.reload())
        }
    }

    const extContext = context.extensions.SyncWithSystemTheme

    // To decode html-encoded postUrl
    const txt = document.createElement('textarea')
    txt.innerHTML = extContext.postUrl // once for js_vars
    txt.innerHTML = txt.value          // once for _url
    extContext.postUrl = txt.value

    if (window.matchMedia) {
        syncWithSystemTheme()
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', syncWithSystemTheme)
    } else {
        console.log(extContext.warning)
    }
})
