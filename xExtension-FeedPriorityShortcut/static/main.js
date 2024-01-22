window.addEventListener('load', () => {

    (() => {
        const context = window.context.extensions.FeedPriorityShortcut

        // Decode double-html-encoded postUrl
        const txt = document.createElement('textarea')
        txt.innerHTML = context.postUrl // once for js_vars
        txt.innerHTML = txt.value       // once for _url
        context.postUrl = txt.value
    })()

    // Add dropdowns
    document.querySelectorAll('.feed.item').forEach((li) => {
        if (!li.dataset.feedId) { return }

        const i = li.dataset.feedId
        const p = context.extensions.FeedPriorityShortcut.priority[i]
        const t = context.extensions.FeedPriorityShortcut.tooltips
        const d = document.createElement('div')

        d.classList.add('feed-priority-shortcut', 'dropdown')
        d.innerHTML =
            `<div id="dropdown-feed-${i}" class="dropdown-target"></div>
            <a class="dropdown-toggle" href="#dropdown-feed-${i}">${p}</a>
            <ul class="dropdown-menu">
                <li class="item" title="${t.important}">📌</li>
                <li class="item" title="${t.main_stream}">🏠</li>
                <li class="item" title="${t.category}">📁</li>
                <li class="item" title="${t.archived}">🔒</li>
            </ul>
            <a class="dropdown-close" href="#close">❌</a>`

        li.appendChild(d)
    })

    // Add listeners
    document.querySelectorAll('.feed-priority-shortcut .item').forEach((li) => {
        li.addEventListener('click', (e) => {
            fetch(context.extensions.FeedPriorityShortcut.postUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    _csrf: context.csrf,
                    feed_id: e.target.closest('.feed.item').dataset.feedId,
                    priority: e.target.textContent
                })
            })
            .then(() => location.reload())
        })
    })
})
