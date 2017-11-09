<?php

namespace app\modules\v1\controllers;

use yii\filters\VerbFilter;

use app\controllers\BaseController;
use app\models\User;
use app\libs\Utils;

use Yii;


class UserController extends BaseController
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

    // 为登录用户生成登录aceesstoken
    // public function actionGetWeixinAccesstoken() {
        
    // }
    
    // 微信注册
    /*
    public function actionWxRegister()
    {
        $code = $_REQUEST['code'];

        $AppId = 'wx9fbec546b8295192';
        $AppSecret = 'fc3339db92ca797f92e85ddc2371822b';

        $reqUrl = 'https://api.weixin.qq.com/sns/jscode2session?appid='. $AppId .'&secret='. $AppSecret .'&js_code='. $code .'&grant_type=authorization_code';
 
        $result = $this->httpGet($reqUrl);
        $rObj = json_decode($result, true);

        $user = User::findOne([
            'weixin_openid' => $rObj['openid']
        ]);

        $access_token = md5($rObj['openid'] . $rObj['session_key']);

        if ($user) {
            $user->weixin_access_token = $access_token;
            $user->save();
        } else {
            $user = new User();
            $user->weixin_openid = $rObj['openid'];
            $user->weixin_access_token = $access_token;
            $user->created_time = Utils::getCurrentDateTime();
            $user->updated_time = Utils::getCurrentDateTime();
            $flag = $user->save();
        }

        
        $res = [
        	'code' => 0,
        	'msg'=> '',
        	'data' => $access_token
        ];

        return $res;
    }*/

    // 判断登录
    public function actionWxLogin()
    {
        $param = $_REQUEST;
        $res = User::wxLogin($param);
        return $res;
        
    }

    // 获取单个用户信息
    public function actionGetUser()
    {
        $param = $_REQUEST;
        $res = User::getUser($param);
        return $res;
    }

    // 保存用户信息
    public function actionSaveUser()
    {
        $param = $_REQUEST;
        $res = User::saveUser($param);
        return $res;
    }

}
