<?php

namespace app\modules\v2\controllers;

use yii\filters\VerbFilter;

use app\controllers\BossBaseController;
use app\models\Work;

class WorkController extends BossBaseController
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

    // getWorklistByAlbum
    public function actionGetWorklistByAlbum()
    {
        $param = $_REQUEST;

        $res = Work::getWorklistByAlbum($param);

        return $res;

    }

    // workSubmit
    public function actionWorkSubmit()
    {
        $param = $_REQUEST;

        //print_r($param);exit;

        $res = Work::workSubmit($param);

        return $res;

    }

    // getWorklist
    public function actionGetWorklistByCategory()
    {
        $param = $_REQUEST;

        $res = Work::getWorklistByCategory($param);

        return $res;

    }

    // 获取单个作品
    public function actionGetWork()
    {
        $param = $_REQUEST;

        $res = Work::getWork($param);

        return $res;

    }

    // saveWork
    public function actionSaveWork()
    {
        $param = $_REQUEST;

        $res = Work::saveWork($param);

        return $res;
    }

    // checkWork
    public function actionCheckWork()
    {
        $param = $_REQUEST;

        $res = Work::checkWork($param);

        return $res;
    }

}
