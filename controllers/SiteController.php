<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\User;

class SiteController extends Controller
{
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
                        'roles' => ['@']
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(["site/dashboard"]);
        }

        $request = Yii::$app->request->post();
        $user = new User();
        if($request)
        {
            if ($user->load($request) && $user->login())
            {
                return $this->redirect(["site/dashboard"]);
            }

            $session = Yii::$app->session;
            $session->setFlash('errorMessages', $user->getErrors());
        }


        $user->password = '';
        return $this->render('login', [
            'user' => $user,
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

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionRegister()
    {
        return $this->render('register');
    }

    public function actionSignUp()
    {
        $request = Yii::$app->request->post();

        $user = new User();
        $user->attributes = $request;
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);//Hash password before storing to DB
        $session = Yii::$app->session;

        if($user->validate() && $user->save())
        {
            $session->setFlash('successMessage', 'Registration successful');
            return $this->redirect(['site/login']);
        }

        $session->setFlash('errorMessages', $user->getErrors());
        return $this->redirect(['site/register']);
    }

    public function actionDashboard()
    {
        return $this->render('dashboard');
    }

}
