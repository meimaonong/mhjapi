<?php

namespace app\models;

use Yii;
use app\libs\Utils;

/**
 * This is the model class for table "user".
 *
 * @property integer $user_id
 * @property string $tel
 * @property string $email
 * @property string $salt
 * @property string $password
 * @property string $weixin_openid
 * @property string $weixin_name
 * @property string $weixin_avatar_img
 * @property integer $status
 * @property string $login_ip
 * @property integer $login_time
 * @property integer $login_count
 * @property integer $del_flag
 * @property string $created_time
 * @property string $updated_time
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'login_time', 'login_count', 'del_flag'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['tel', 'salt'], 'string', 'max' => 15],
            [['email', 'password', 'weixin_openid'], 'string', 'max' => 50],
            [['weixin_name'], 'string', 'max' => 100],
            [['weixin_avatar_img'], 'string', 'max' => 1000],
            [['login_ip'], 'string', 'max' => 22],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '用户id',
            'tel' => '用户手机号',
            'email' => '用户email',
            'salt' => '密码盐',
            'password' => '用户密码',
            'weixin_openid' => '微信用户openid',
            'weixin_name' => '微信名',
            'weixin_avatar_img' => '微信头像',
            'status' => '状态 0 正常 1 禁用',
            'login_ip' => '登录ip',
            'login_time' => '登录时间',
            'login_count' => '登录次数',
            'del_flag' => 'Del Flag',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }

    // 微信登录
    public static function wxLogin($param)
    {
        $code = $param['code'];

        $AppId = 'wx9fbec546b8295192';
        $AppSecret = 'fc3339db92ca797f92e85ddc2371822b';

        $reqUrl = 'https://api.weixin.qq.com/sns/jscode2session?appid='. $AppId .'&secret='. $AppSecret .'&js_code='. $code .'&grant_type=authorization_code';
 
        $result = Yii::$app->httpclient->get($reqUrl, [], ['http_errors' => false]);
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
        	'data' => $rObj
        ];

        return $res;
    }
    
    // 获取用户列表
    public static function getUsers($param) 
    {

        $user_list = User::find()
            ->select([
                'user_id',
                'tel',
                'email',
                'status',
                'login_ip',
                'login_time',
                'login_count',
                'del_flag',
                'created_time',
                'updated_time'
            ])
            ->asArray()
            ->all();
        
        $res = [
        	'code' => 0,
        	'msg'=> '',
        	'data' => $user_list
        ];

        return $res;

    }

    // 获取单个用户
    public static function getUser($param) 
    {

        $user_id = $param['user_id'];

        $user = User::find()
            ->select([
                'user_id',
                'tel',
                'email',
                'status',
                'login_ip',
                'login_time',
                'login_count',
                'del_flag',
                'created_time',
                'updated_time'
            ])
            ->where(['user_id'=>$user_id,'status'=>0])
            ->asArray()
            ->one();

        $res = [
        	'code' => 0,
        	'msg'=> '',
        	'data' => $user
        ];

        return $res;

    }

    // boss获取单个用户
    public static function getUserFromboss($param) 
    {

        $user_id = $param['user_id'];

        $user = User::find()
            ->select([
                'user_id',
                'tel',
                'email',
                'status',
                'login_ip',
                'login_time',
                'login_count',
                'del_flag',
                'created_time',
                'updated_time'
            ])
            ->where(['user_id'=>$user_id])
            ->asArray()
            ->one();

        $res = [
        	'code' => 0,
        	'msg'=> '',
        	'data' => $user
        ];

        return $res;

    }

    // 前台用户修改资料
    public static function saveUser($param)
    {
        $user_id = $param['user_id'];
        $user_name = $param['user_name'];
        $tel = $param['tel'];

        $user = static::findOne([
            'user_id' => $user_id
        ]);

        $user->user_name = $user_name;
        $user->tel = $tel;
        $user->save();

        $save_id = $user->attribute['user_id'];

        $res = [
        	'code' => 0,
        	'msg'=> '',
        	'data' => $save_id
        ];

        return $res;

    }

    // 修改用户状态
    public static function changeStatus($param)
    {
        $user_id = $param['user_id'];
        $status = $param['status'];

        $user = static::findOne([
            'user_id' => $user_id
        ]);
        $user->status = $status;
        $user->save();

        $save_id = $user->attributes['user_id'];
        
        $res = [
        	'code' => 0,
        	'msg'=> '',
        	'data' => $save_id
        ];

        return $res;

    }

}
