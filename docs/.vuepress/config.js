module.exports = {
    title: 'Facebook 2 Documentation',
    description: 'Facebook 2 Documentation',
    base: '/facebook/v2/',
    ga: 'UA-1547168-34',
    themeConfig: {
        nav: [
            { text: 'Analytics', link: 'https://docs.dukt.net/analytics/v4/'},
            { text: 'Facebook', link: '/'},
            { text: 'Social', link: 'https://docs.dukt.net/social/v2/'},
            { text: 'Twitter', link: 'https://docs.dukt.net/twitter/v2/'},
            { text: 'Videos', link: 'https://docs.dukt.net/videos/v2/'},
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