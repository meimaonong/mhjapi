<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
* BaseController
*/
class BossBaseController extends Controller
{
    // 关闭csrf
    public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
        // 权限判断
        if (true) {
           return parent::beforeAction($action); 
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
            Yii::$app->response->data = array(
                'status' => -1,
                'message' => '请先登录',
                'url' => \Yii::$app->request->url
            );
            return false;
        }

        
    }

    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);

        // 如果是数组就返回json对象
        if (is_array($result)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        } else {
            print_r($result);
        }

    }

    // post请求
	public function httpGet($apiAction, $params = []) {
		$response = Yii::$app->httpclient->get($apiAction, $params, ['http_errors' => false]);
		return $response;
	}

	// post请求
	public function httpPost($apiAction, $params = []) {
		$response = Yii::$app->httpclient->post($apiAction, $params, ['http_errors' => false]);
		return $response;
	}
	
}