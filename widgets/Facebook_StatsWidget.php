<?php
namespace Craft;

class Facebook_StatsWidget extends BaseWidget
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritDoc IComponentType::getName()
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t('Facebook Stats');
    }

    public function getBodyHtml()
    {
        $response = craft()->facebook_api->get('/me/accounts');
        $accounts = $response['data']['data'];

        $insights = [];

        foreach($accounts as $k => $account)
        {
            $response = craft()->facebook_api->get('/'.$account['id'].'/insights/page_fans');
            $insights[$account['id']] = $response['data']['data'][0];
        }

        $variables['accounts'] = $accounts;
        $variables['insights'] = $insights;


        return craft()->templates->render('facebook/_components/widgets/stats/body', $variables);
    }
}