<?php
/**
 * @link      https://dukt.net/facebook/
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/facebook/blob/master/LICENSE.md
 */

namespace dukt\facebook\services;

use Craft;
use dukt\facebook\errors\InvalidAccountException;
use dukt\facebook\models\Account;
use dukt\facebook\records\Account as AccountRecord;
use yii\base\Component;
use Exception;

/**
 * OAuth Service
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class Accounts extends Component
{
    /**
     * Gets an account
     *
     * @return Account
     */
    public function getAccount()
    {
        $result = AccountRecord::find()->one();

        if ($result) {
            return new Account($result->toArray([
                'id',
                'token',
            ]));
        }

        return new Account();
    }

    /**
     * Saves an account
     *
     * @param Account $account
     * @param bool $runValidation
     * @return bool
     * @throws InvalidAccountException
     * @throws \yii\db\Exception
     */
    public function saveAccount(Account $account, bool $runValidation = true): bool
    {
        $isNewAccount = !$account->id;

        if ($runValidation && !$account->validate()) {
            Craft::info('Account not saved due to validation error.', __METHOD__);
            return false;
        }

        if ($account->id) {
            $accountRecord = AccountRecord::find()
                ->where(['id' => $account->id])
                ->one();

            if (!$accountRecord) {
                throw new InvalidAccountException("No account exists with the ID '{$account->id}'");
            }
        } else {
            $accountRecord = new AccountRecord();
        }

        $accountRecord->token = $account->token;

        $transaction = Craft::$app->getDb()->beginTransaction();

        try {
            // Is the event giving us the go-ahead?
            $accountRecord->save(false);

            // Now that we have an account ID, save it on the model
            if ($isNewAccount) {
                $account->id = $accountRecord->id;
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();

            throw $e;
        }

        return true;
    }

    /**
     * Deletes an account
     *
     * @param Account $account
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteAccount(Account $account): bool
    {
        if (!$account->id) {
            return true;
        }

        $accountRecord = AccountRecord::findOne($account->id);

        if (!$accountRecord) {
            return true;
        }

        $accountRecord->delete();

        return true;
    }
}