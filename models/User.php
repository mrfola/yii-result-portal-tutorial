<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $created_at
 * @property string|null $updated_at
 *

 */
class User extends ActiveRecord implements IdentityInterface
{
    public $rememberMe = true;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password', ], 'required'],
            ['email', 'email'],
            ['email', 'unique'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'email', 'password'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($auth_key)
    {
        return $this->auth_key === $auth_key;
    }

    public static function findByEmail($email)
    {
        return self::findOne(["email" => $email]);
    }

    public function validatePassword($passwordHash)
    {
        return Yii::$app->getSecurity()->validatePassword($this->password, $passwordHash);
    }

    public function login()
    {
        $user = $this->findByEmail($this->email);
        if ($user && $this->validatePassword($user->password))
        {
            return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
        }else
        {
            $this->addError('password', 'Incorrect username or password.');
        }

    }

}
