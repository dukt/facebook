module.exports = {
    title: 'Facebook Documentation',
    description: 'Facebook Documentation',
    base: '/docs/facebook/v2/',
    ga: 'UA-1547168-34',
    themeConfig: {
        docsRepo: 'dukt/facebook',
        docsDir: 'docs',
        docsBranch: 'v2-docs',
        editLinks: true,
        editLinkText: 'Edit this page on GitHub',
        nav: [
            { text: 'Analytics', link: 'https://dukt.net/docs/analytics/v4/'},
            { text: 'Facebook', link: '/'},
            { text: 'Social', link: 'https://dukt.net/docs/social/v2/'},
            { text: 'Twitter', link: 'https://dukt.net/docs/twitter/v2/'},
            { text: 'Videos', link: 'https://dukt.net/docs/videos/v2/'},
        ],
        sidebar: {
            '/': [
                {
                    title: 'Facebook plugin for Craft CMS',
                    collapsable: false,
                    children: [
                        '',
                        'requirements',
                        'installation',
                        // 'updating',
                        'configuration',
                    ]
                },
                {
                    title: 'Widgets',
                    collapsable: false,
                    children: [
                        'insights-widget',
                    ]
                },
            ],
        }
    }
}
