module.exports = {
    title: 'Facebook 2 Documentation',
    description: 'Facebook 2 Documentation',
    base: '/docs/facebook/v2/',
    ga: 'UA-1547168-34',
    themeConfig: {
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
                    title: 'Getting Started',
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