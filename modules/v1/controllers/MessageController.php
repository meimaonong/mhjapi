<?php

namespace app\modules\v1\controllers;

use yii\filters\VerbFilter;

use app\controllers\BaseController;
use app\models\Message;

class MessageController extends BaseController
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

    // doread
    public function actionDoRead()
    {
        $param = $_REQUEST;

        $res = Message::doRead($param);

        return $res;
    }

    // getMessages
    public function actionGetMessagelist()
    {
        $param = $_REQUEST;

        $res = Message::getMessagelist($param);

        return $res;

    }

    public function actionGetMessage()
    {
        $param = $_REQUEST;

        $res = Message::getMessage($param);

        return $res;

    }

    public function actionSaveMessage()
    {
        $param = $_REQUEST;

        $res = Message::saveMessage($param);

        return $res;
    }


}
