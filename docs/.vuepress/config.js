module.exports = {
    title: 'Facebook Documentation',
    description: 'Facebook Documentation',
    base: '/docs/facebook/v2/',
    plugins: {
        '@vuepress/google-analytics': {
            'ga': 'UA-1547168-20'
        },
        'sitemap': {
            hostname: 'https://dukt.net/docs/facebook/v2/'
        },
    },
    themeConfig: {
        docsRepo: 'dukt/facebook',
        docsDir: 'docs',
        docsBranch: 'v2-docs',
        editLinks: true,
        editLinkText: 'Edit this page on GitHub',
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
