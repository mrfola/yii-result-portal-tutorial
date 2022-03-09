<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use app\models\User;
use app\models\Result;
use Yii;


class AdminController extends Controller
{

    //override default layout and use admin.php instead
    public $layout = 'admin';

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'dashboard'],
                'rules' => [
                    [
                        'actions' => ['dashboard'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionDashboard()
    {
        if (\Yii::$app->user->can('viewResultDashboard'))
        {
            //get all students from db
            $studentResults = User::getAllResults();

            $data = ["students" => $studentResults];

            return $this->render('dashboard', $data);
        }else
        {
            return "You are not authorized to view this page.";
        }
    }


    /**
     * Login action.
     *
     * @return Response|string
     */

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest)
        {
            return $this->redirect(["admin/dashboard"]);
        }

        $request = Yii::$app->request->post();
        $admin = new User();
        if($request)
        {
            if ($admin->load($request) && $admin->adminLogin())
            {
                return $this->redirect(["admin/dashboard"]);
            }

            $session = Yii::$app->session;
            $session->setFlash('errorMessages', $admin->getErrors());
        }


        $admin->password = '';
        return $this->render('login', [
            'user' => $admin,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

         return $this->redirect(["admin/index"]);
    }

    public function actionUpdateUserResult()
    {
        if (\Yii::$app->user->can('updateResult'))
        {
        $request = Yii::$app->request->post();
        $user_id = $request["user_id"];
        unset($request["user_id"]);

        //loop through all subjects and update each one.
        foreach($request as $subject_id => $score)
        {
            //to ignore the csrf field
            if(!is_int($subject_id))
            {
                continue;
            }

            $result = Result::findOne([
                "user_id" => $user_id,
                "subject_id" => $subject_id
            ]);

            $result->score = $score;
            $result->save();
        }

        $session = Yii::$app->session;
        $session->setFlash('successMessage', "Result Updated");
        return $this->redirect(["admin/dashboard"]);

        }else
        {
            return "You are not authorized to view this page.";
        }

    }


}
