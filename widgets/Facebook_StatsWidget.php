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

        $token = craft()->facebook_oauth->getToken();

        if($token)
        {
            $response = craft()->facebook_api->get('/me/accounts');
            $accounts = $response['data']['data'];

            $facebookAccount = null;
            $insight = [];
            $weekTotal = 0;

            foreach($accounts as $k => $account)
            {
                if($account['id'] == $facebookAccountId)
                {
                    $facebookAccount = $account;
                    $response = craft()->facebook_api->get('/'.$account['id'].'/insights/page_fans', array(
                        'since' => date('Y-m-d', strtotime('-6 day')),
                        'until' => date('Y-m-d', strtotime('+1 day')),
                    ));

                    $insight = $response['data']['data'][0];

                    $weekTotalStart = $insight['values'][0]['value'];
                    $weekTotalEnd = end($insight['values'])['value'];

                    $weekTotal = $weekTotalEnd - $weekTotalStart;
                }
            }

            $variables['account'] = $facebookAccount;
            $variables['insight'] = $insight;
            $variables['weekTotal'] = $weekTotal;

            craft()->templates->includeCssResource('facebook/css/stats-widget.css');

            return craft()->templates->render('facebook/_components/widgets/stats/body', $variables);
        }
        else
        {
            return craft()->templates->render('facebook/_components/widgets/stats/disabled');
        }
    }
}