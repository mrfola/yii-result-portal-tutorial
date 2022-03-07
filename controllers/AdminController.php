<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use app\models\User;
use Yii;


class AdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
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

    // Use a middleware or guard to protect function
    public function actionDashboard()
    {
        return $this->render('dashboard');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->user_type=="admin")
        {
            return $this->redirect(["result/dashboard"]);
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

        return $this->goHome();
    }

}
