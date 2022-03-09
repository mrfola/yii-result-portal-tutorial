<?php

namespace app\commands;

use yii\console\Controller;
use app\models\Result;
use app\models\Subject;
use app\models\User;
use Yii;

class SeedController extends Controller
{

    protected $subjects = ["Maths", "English", "History", "Mental Studies", "Negotiation Studies"];

    public function actionAll()
    {
        $this->actionAdmin(); //create default admins
        $this->actionSubjects(); //create default subjects based on protected $subjects
        $this->actionUser(); //create default users
    }

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

    public function actionSubjects()
    {
        //get admin
        if(!$admin_id = User::findOne(["user_type" => "admin"])->id)
        {
            $this->actionAdmin();//create test admin
            $admin_id = User::findOne(["user_type" => "admin"])->id;
        }

        foreach ($this->subjects as $subjectName)
        {
            $subject = new Subject();
            $subject->name = $subjectName;
            $subject->added_by = $admin_id;
            if($subject->save())
            {
                echo $subjectName." Subject successfully seeded. ";

            }else
            {
                foreach($subject->getErrors() as $error)
                {
                    echo $error[0]." ";
                }
            }
        }
    }


    public function actionUser()
    {
        $user = new User;
        $user->name = "Test";
        $user->email = "test@gmail.com";
        $user->password =  Yii::$app->getSecurity()->generatePasswordHash("password");
        if($user->save())
        {
            echo "User successfully seeded";        

            //get all subjects
            $subjects = Subject::find()->all();

            //create user result for each subject
            $this->createUserResults($user->id, $subjects);      
        }else
        {
            foreach($user->getErrors() as $error)
            {
                echo $error[0]." ";
            }
        }

        $user2 = new User;
        $user2->name = "Test 2";
        $user2->email = "test2@gmail.com";
        $user2->password =  Yii::$app->getSecurity()->generatePasswordHash("password");
        if($user2->save())
        {
            echo "User successfully seeded";

            //get all subjects
            $subjects = Subject::find()->all();

            //create user result for each subject
            $this->createUserResults($user2->id, $subjects);  

        }else
        {
            foreach($user2->getErrors() as $error)
            {
                echo $error[0]." ";
            }
        }
    }

    public function createUserResults($user_id, $subjects)
    {
        //save result for each subject
        foreach($subjects as $subject)
        {
        $result = new Result();
        $result->user_id = $user_id;
        $result->score = rand(1,100);
        $result->subject_id = $subject->id;

        if($result->save())
        {
            echo "Result for " . $subject->name . " added. ";

        }else
        {
            foreach($result->getErrors() as $error)
            {
                echo $error[0]." ";
            }
        }
        }
    }

 }
