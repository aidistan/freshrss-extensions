if (window.matchMedia) {

    // Get current theme
    function getCurrentTheme() {
        for (node of document.querySelectorAll('link[rel="stylesheet"]')) {
            const match = new RegExp("/themes/([^/]+)/").exec(node.attributes.href.value)
            if (match && match[1] !== "base-theme") {
                return match[1]
            }
        }
    }

    // Get prefered color scheme from browser
    function getPreferredColorScheme() {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return 'dark'
        } else {
            return 'light'
        }
    }

    // Check and synchronize with system theme
    function syncWithSystemTheme() {
        const theme = getCurrentTheme()
        const scheme = getPreferredColorScheme()
        const context = window.context.extensions.SyncWithSystemTheme
        const preferredTheme = context[scheme + "Theme"]

        if (preferredTheme && theme !== preferredTheme) {
            const form = document.createElement("form")
            form.action = './?c=configure&a=display'
            form.method = "POST"
            document.body.appendChild(form)

            let input = document.createElement("input")
            input.setAttribute("type", "radio")
            input.setAttribute("name", "theme")
            input.setAttribute("value", preferredTheme)
            input.setAttribute("checked", true)
            form.appendChild(input)

            input = document.createElement("input")
            input.setAttribute("type", "hidden")
            input.setAttribute("name", "_csrf")
            input.setAttribute("value", context.csrfToken)
            form.appendChild(input)

            form.submit()
        }
    }

    window.addEventListener('load', () => {
        syncWithSystemTheme()
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', syncWithSystemTheme)
    })
}
