<?php

namespace app\models;

use Yii;
use app\models\User;
use app\models\Subject;
use yii\db\ActiveRecord;

class Result extends ActiveRecord
{

    public static function tableName()
    {
        return 'results';
    }

    public function rules()
    {
        return [
            [['score', 'user_id', 'subject_id'], 'required'],
            [['score', 'user_id', 'subject_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ["id" => "user_id"]);
    }

    public function getSubject()
    {
        return $this->hasOne(Subject::className(), ["id" => "subject_id"]);
    }
}
