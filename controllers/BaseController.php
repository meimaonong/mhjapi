<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use app\models\User;

/**
* BaseController
*/
class BaseController extends Controller
{
    // 关闭csrf
    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {
        //print_r($_REQUEST);exit;
        $filerActions = [
            '/v1/user/wx-login',
            '/v1/data/get-home-data',
            '/v1/category/get-categorylist',
            '/v1/work/get-worklist-by-category',
            '/v1/work/get-work',
        ];

        $url = \Yii::$app->request->url;

        if (!in_array($url, $filerActions)) {
            $headers = Yii::$app->request->headers;
            $token = $headers['access-token'];
            if ($token) {
                $user = User::findOne([
                    'weixin_access_token' => $token,
                    'status' => 0,
                    'del_flag' => 0
                ]);

                if ($user) {
                    $_REQUEST['user_id'] = $user['user_id'];
                } else {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    Yii::$app->response->data = array(
                        'code' => 1,
                        'status' => -1,
                        'message' => '请先登录',
                        'url' => \Yii::$app->request->url
                    );
                    return false;
                }

                
            } else {
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = array(
                    'code' => 1,
                    'status' => -1,
                    'message' => '请先登录',
                    'url' => \Yii::$app->request->url
                );
                return false;
            }
            
        }

        return parent::beforeAction($action); 

        
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