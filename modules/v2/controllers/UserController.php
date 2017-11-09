<?php

namespace app\modules\v2\controllers;

use yii\filters\VerbFilter;

use app\controllers\BossBaseController;
use app\models\User;

use app\libs\Utils;

class UserController extends BossBaseController
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

    // 获取用户列表
    public function actionGetUsers()
    {
        $param = $_REQUEST;
        $res = User::getUsers($param);
        return $res;
    }

    // 获取单个用户信息
    public function actionGetUserFromboss()
    {
        $param = $_REQUEST;
        $res = User::getUser($param);
        return $res;
    }

    // 修改用户禁用状态
    public function actionChangeStatus()
    {
        $param = $_REQUEST;
        $res = User::changeStatus($param);
        return $res;
    }
}
