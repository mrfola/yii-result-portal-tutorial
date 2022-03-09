<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\models\Result;
use app\models\Subject;

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

    public static function findByUserEmail($email)
    {
        return self::findOne([
            "email" => $email,
            "user_type" => "user"
        ]);
    }

    public function validatePassword($passwordHash)
    {
        return Yii::$app->getSecurity()->validatePassword($this->password, $passwordHash);
    }

    public function login()
    {
        $user = $this->findByUserEmail($this->email);
        if ($user && $this->validatePassword($user->password))
        {
            return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
        }else
        {
            $this->addError('password', 'Incorrect username or password.');
        }
    }

    public static function findByAdminEmail($email)
    {
        return self::findOne([
            "email" => $email,
            "user_type" => "admin"
        ]);
    }

    public function adminLogin()
    {
        $user = $this->findByAdminEmail($this->email);
        if ($user && $this->validatePassword($user->password))
        {
            return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30 : 0);
        }else
        {
            $this->addError('password', 'Incorrect username or password.');
        }
    }

    public function getResults()
    {
        return $this->hasMany(Result::className(), ['user_id' => 'id']);
    }

    public function createDefaultUserResult($id)
    {   
        //get all subjects
        $subjects = Subject::find()->all();

         //save result for each subject
         foreach($subjects as $subject)
         {
            $result = new Result();
            $result->user_id = $id;
            $result->score = 0;
            $result->subject_id = $subject->id;
            $result->save();
         }
    }

    public static function getAllResults()
    {
        $students = self::find()->where(["user_type" => "user"])->all();

        $studentResults = [];
        foreach($students as $index => $student)
        {
            $studentResult["id"] = $student->id;
            $studentResult["name"] = $student->name;

            //get individual result and add it to new array
            foreach($student->results as $result)
            {
                $studentResult["subjects"][$result->subject->name] = 
                [
                    "subject_id" => $result->subject->id,
                    "score" => $result->score 
                ];

            }

            //add new student array to the larger array containing all students
            $studentResults[] = $studentResult;
        }

        return $studentResults;
    }


}
