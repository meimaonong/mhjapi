<?php

namespace app\models;

use Yii;
use app\libs\Utils;

use app\models\Work;

/**
 * This is the model class for table "album".
 *
 * @property integer $album_id
 * @property string $album_title
 * @property string $album_des
 * @property integer $user_id
 * @property integer $del_flag
 * @property string $created_time
 * @property string $updated_time
 */
class Album extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'album';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['album_des'], 'required'],
            [['album_des'], 'string'],
            [['user_id', 'del_flag'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['album_title'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'album_id' => '专辑id',
            'album_title' => '专辑名称',
            'album_des' => '专辑描述',
            'user_id' => 'User ID',
            'del_flag' => '删除标识',
            'created_time' => '创建时间',
            'updated_time' => '更新时间',
        ];
    }


    public static function getAlbums($param) {

        $user_id = $param['user_id'];

        $albums = static::find()
            ->where(['user_id' => $user_id, 'del_flag'=>0])
            ->asArray()
            ->all();

        foreach ($albums as &$album) {
            $album['num'] = Work::find()
                ->where(['album_id' => $album['album_id'], 'del_flag' => 0])
                ->count();
            $work = Work::find()
                ->where(['album_id' => $album['album_id'], 'del_flag' => 0])
                ->asArray()
                ->one();
            if ($work) {
                $album['cover'] = $work['work_img'];
            } else {
                $album['cover'] = '';
            }
        }
        
        $res = [
            'code' => 0,
            'msg'=> '',
            'data' => $albums
        ];

        return $res;

    }

    public static function getAlbum($param) {

        $user_id = $param['user_id'];
        $album_id = $param['album_id'];

        $album = static::find()
            ->where(['user_id' => $user_id, 'album_id' => $album_id, 'del_flag'=>0])
            ->asArray()
            ->one();
        
        $res = [
            'code' => 0,
            'msg'=> '',
            'data' => $album
        ];

        return $res;

    }

    public static function delAlbum($param) {

        $user_id = $param['user_id'];
        $album_id = $param['album_id'];

        $album = static::findOne([
            'user_id' => $user_id,
            'album_id' => $album_id,
        ]);

        $album->save();

        $save_id = $album->attributes['album_id'];
        
        $res = [
            'code' => 0,
            'msg'=> '',
            'data' => $save_id
        ];

        return $res;

    }

    public static function saveAlbum($param) {

        $user_id = $param['user_id'];
        $album_id = $param['album_id'];
        $album_title = $param['album_title'];
        $album_des = $param['album_des'];

        $save_id = '';

        if ($user_id && $album_id) {
            $album = static::findOne([
                'album_id' => $album_id,
                'user_id' => $user_id,
                'del_flag' => 0,
            ]);
            $album->album_title = $album_title;
            $album->album_des = $album_des;
            $album->save();

        } else {

            $t = Utils::getCurrentDateTime();

            $album = new static();
            $album->user_id = $user_id;
            $album->album_title = $album_title;
            $album->album_des = $album_des;
            $album->created_time = $t;
            $album->updated_time = $t;
            $album->save();

            $save_id = $album->attributes['album_id'];
        }
        
        $res = [
            'code' => 0,
            'msg'=> '',
            'data' => $album
        ];

        return $res;

    }

}
