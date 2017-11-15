<?php

namespace app\modules\v1\controllers;

use yii\filters\VerbFilter;

use app\controllers\BaseController;
use app\models\Work;

class DataController extends BaseController
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    '*' => ['post'],
                ],
            ],
        ];
    }

    // 
    public function actionGetHomeData()
    {
        $res = Work::getHomeData();

        return $res;

    }




}
