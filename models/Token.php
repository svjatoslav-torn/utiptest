<?php

namespace app\models;

use Yii;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $expired_at
 * @property string $token
 * 
 */
class Token extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'token';
    }

    public function generateToken($expire)
    {
        $this->expired_at = $expire;
        $this->token = Yii::$app->security->generateRandomString();
    }


}
