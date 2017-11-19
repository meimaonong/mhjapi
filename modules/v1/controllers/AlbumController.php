<?php

namespace app\modules\v1\controllers;

use yii\filters\VerbFilter;

use app\controllers\BaseController;
use app\models\Album;

class AlbumController extends BaseController
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

    // getAlbums
    public function actionGetAlbums()
    {
        $param = $_REQUEST;

        $res = Album::getAlbums($param);

        return $res;

    }

    public function actionGetAlbum()
    {
        $param = $_REQUEST;

        $res = Album::getAlbum($param);

        return $res;

    }

    public function actionDelAlbum()
    {
        $param = $_REQUEST;

        $res = Album::delAlbum($param);

        return $res;

    }

    public function actionSaveAlbum()
    {
        $param = $_REQUEST;

        $res = Album::saveAlbum($param);

        return $res;
    }


}
