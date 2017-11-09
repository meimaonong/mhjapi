<?php

namespace app\modules\v2\controllers;

use yii\filters\VerbFilter;

use app\controllers\BossBaseController;
use app\models\Address;
use app\libs\Utils;


class AddressController extends BossBaseController
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

    // saveAddress

    public function actionSaveAddress()
    {
        $param = $_REQUEST;

        $res = Address::saveAddress($param);

        return $res;

    }

    // 获取用户地址
    public function actionGetAddress()
    {
        $param = $_REQUEST;

        $res = Address::getAddress($param);

        return $res;
    }

    // 获取地址列表
    public function actionGetAddresslist()
    {
        $param = $_REQUEST;

        $res = Address::getAddresslist($param);

        return $res;
    }


}
