<?php

namespace app\models;

use Yii;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $expired_at
 * @property string $token
 */
class Token extends \yii\db\ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'token';
    }

    public function generateToken($expire): void
    {
        $this->expired_at = $expire;
        $this->token = Yii::$app->security->generateRandomString(40);
    }


}