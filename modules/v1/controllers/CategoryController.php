<?php

namespace app\modules\v1\controllers;

use yii\filters\VerbFilter;

use app\controllers\BaseController;
use app\models\Category;

use app\libs\Utils;


class CategoryController extends BaseController
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

    // 保存分类
    public function actionSaveCategory()
    {
        $param = $_REQUEST;

        $res = Category::saveCategory($param);

        return $res;

    }

    // 获取分类
    public function actionGetCategory()
    {
        $param = $_REQUEST;

        $res = Category::getCategory($param);

        return $res;
    }

    // 获取分类列表
    public function actionGetCategorylist()
    {
        $param = $_REQUEST;

        $res = Category::getCategorylist($param);

        return $res;
    }


}
