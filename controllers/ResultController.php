<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;


class ResultController extends Controller
{
    /**
     * Displays dashboard
     *
     * @return string
     */
    public function actionDashboard()
    {
        return $this->render('dashboard');
    }

}
