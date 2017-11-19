<?php

namespace app\modules\v1\controllers;

use yii\filters\VerbFilter;

use app\controllers\BaseController;
use app\models\SellRecord;

class SellRecordController extends BaseController
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
    public function actionSaveExpress()
    {
        $param = $_REQUEST;

        $res = SellRecord::saveExpress($param);

        return $res;

    }

   


}
