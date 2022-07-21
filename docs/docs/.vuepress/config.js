module.exports = {
    title: 'Facebook Documentation',
    description: 'Facebook Documentation',
    base: '/docs/facebook/',
    plugins: {
        '@vuepress/google-analytics': {
            'ga': 'UA-1547168-20'
        },
        'sitemap': {
            hostname: 'https://dukt.net/docs/facebook/'
        },
    },
    theme: 'default-prefers-color-scheme',
    themeConfig: {
        docsRepo: 'dukt/facebook',
        docsDir: 'docs',
        docsBranch: 'v2-docs',
        editLinks: true,
        editLinkText: 'Edit this page on GitHub',
        lastUpdated: 'Last Updated',
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
