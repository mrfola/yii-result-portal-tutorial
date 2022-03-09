<?php

namespace app\models;

use Yii;
use app\models\Result;
use yii\db\ActiveRecord;

class Subject extends ActiveRecord
{

    public static function tableName()
    {
        return 'subjects';
    }

    public function rules()
    {
        return [
            [['name', 'added_by'], 'required'],
            ['added_by', 'integer'],
            ['name', 'unique'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    public function getResults()
    {
        return $this->hasMany(Result::className(), ["id" => "result_id"]);
    }

}
