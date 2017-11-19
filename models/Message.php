<?php

namespace app\models;

use Yii;
use app\libs\Utils;
use app\models\Work;

/**
 * This is the model class for table "message".
 *
 * @property integer $message_id
 * @property integer $type
 * @property integer $user_id
 * @property string $message_content
 * @property integer $work_id
 * @property integer $is_read
 * @property integer $del_flag
 * @property string $created_time
 * @property string $updated_time
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'user_id', 'work_id', 'is_read', 'del_flag'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['message_content'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_id' => '消息id',
            'type' => '消息类型 1 系统消息',
            'user_id' => '发送的用户',
            'message_content' => '消息内容',
            'work_id' => '消息关联的产品id',
            'is_read' => '是否已读',
            'del_flag' => '删除标识',
            'created_time' => '创建时间',
            'updated_time' => '更新时间',
        ];
    }



    // 保存消息
    public static function saveMessage($param)
    {
        $message_id = $param['message_id'];
        $type = 1;
        $user_id = $param['user_id'];
        $message_content = $param['message_content'];
        $work_id = $param['work_id'];

        $save_id = $message_id;

        if ($message_id && $user_id) {
            $message = static::findOne([
                'message_id' => $message_id,
                'user_id' => $user_id,
                'del_flag' => 0,
            ]);
            $message->type = $type;
            $message->message_content = $message_content;
            $message->work_id = $work_id;
            $message->save();
        } else {

            $t = Utils::getCurrentDateTime();

            $message = new static();
            $message->user_id = $user_id;
            $message->type = $type;
            $message->message_content = $message_content;
            $message->work_id = $work_id;
            $message->created_time = $t;
            $message->updated_time = $t;
            $message->save();

            $save_id = $message->attributes['message_id'];
        }
        
        $res = [
            'code' => 0,
            'msg'=> '',
            'data' => $save_id
        ];

        return $res;

    }

    // 获取消息
    public static function getMessage($param)
    {
        $user_id = $param['user_id'];
        $message_id = $param['message_id'];

        $message = static::find()
            ->where(['message_id' => $message_id, 'user_id' => $user_id,'del_flag'=>0])
            ->asArray()
            ->one();
        
        $res = [
            'code' => 0,
            'msg'=> '',
            'data' => $message
        ];

        return $res;
    }

    // 获取消息
    public static function doRead($param)
    {
        $user_id = $param['user_id'];
        $message_id = $param['message_id'];
        $is_read = $param['is_read'];

        $message = static::findOne([
                'message_id' => $message_id,
                'user_id' => $user_id,
                'del_flag' => 0,
            ]);
            $message->is_read = $is_read;
            $message->save();

        $save_id = $message->attributes['message_id'];
        
        $res = [
            'code' => 0,
            'msg'=> '',
            'data' => $save_id
        ];

        return $res;
    }

    // 获取消息列表
    public static function getMessagelist($param)
    {
        $user_id = $param['user_id'];

        if ($user_id) {
            $messages = static::find()
                ->where(['user_id'=>$user_id ,'del_flag'=>0])
                ->orderBy('message_id desc')
                ->asArray()
                ->all();
            
            foreach ($messages as $key => &$message) {
                $work = Work::find()
                    ->where(['work_id' => $message['work_id']])
                    ->asArray()
                    ->one();
                $tmp = static::doRead([
                    'message_id' => $message['message_id'],
                    'user_id' => $user_id,
                    'is_read' => 1
                ]);
                $message['work'] = $work;
            }
        
            $res = [
                'code' => 0,
                'msg'=> '',
                'data' => $messages
            ];
        } else {
            $res = [
                'code' => 1,
                'msg'=> '用户id不能为空',
                'data' => $messages
            ];
        }

        return $res;
    }

}
