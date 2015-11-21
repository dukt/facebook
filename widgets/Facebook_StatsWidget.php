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

    /**
     * @inheritDoc IWidget::getIconPath()
     *
     * @return string
     */
    public function getIconPath()
    {
        return craft()->resources->getResourcePath('facebook/images/widgets/like.svg');
    }

    public function getBodyHtml()
    {
        $pluginSettings = craft()->plugins->getPlugin('facebook')->getSettings();

        $facebookAccountId = $pluginSettings['facebookAccountId'];

        $response = craft()->facebook_api->get('/me/accounts');
        $accounts = $response['data']['data'];

        $facebookAccount = null;
        $insight = [];

        foreach($accounts as $k => $account)
        {
            if($account['id'] == $facebookAccountId)
            {
                $facebookAccount = $account;
                $response = craft()->facebook_api->get('/'.$account['id'].'/insights/page_fans');
                $insight = $response['data']['data'][0];
            }
        }

        $variables['account'] = $facebookAccount;
        $variables['insight'] = $insight;

        return craft()->templates->render('facebook/_components/widgets/stats/body', $variables);
    }
}