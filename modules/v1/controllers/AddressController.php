<?php

namespace app\modules\v1\controllers;

use yii\filters\VerbFilter;

use app\controllers\BaseController;
use app\models\Address;

class AddressController extends BaseController
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
