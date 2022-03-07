<?php

namespace app\commands;

use yii\console\Controller;
use app\models\User;
use Yii;

class SeedController extends Controller
{

    public function actionAdmin()
    {
        $admin = new User();
        $admin->name = "admin";
        $admin->email = "admin@gmail.com";
        $admin->password = Yii::$app->getSecurity()->generatePasswordHash("password");
        $admin->user_type = "admin";

        if($admin->save())
        {
            echo "Admins successfully seeded";
        }else
        {
            foreach($admin->getErrors() as $error)
            {
                echo $error[0]." ";
            }
        }   
    }

 }
