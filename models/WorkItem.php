<?php

namespace app\models;

use Yii;
use app\libs\Utils;

/**
 * This is the model class for table "work_item".
 *
 * @property integer $work_item_id
 * @property integer $work_id
 * @property string $work_item_title
 * @property string $work_item_des
 * @property integer $num
 * @property string $work_item_img
 * @property integer $w
 * @property integer $h
 * @property double $ratio
 * @property integer $del_flag
 * @property string $created_time
 * @property string $updated_time
 */
class WorkItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'work_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['work_id', 'num', 'w', 'h', 'del_flag'], 'integer'],
            [['work_item_des'], 'required'],
            [['work_item_des'], 'string'],
            [['ratio'], 'number'],
            [['created_time', 'updated_time'], 'safe'],
            [['work_item_title', 'work_item_img'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'work_item_id' => '作品项id',
            'work_id' => '所属作品id',
            'work_item_title' => '作品项标题',
            'work_item_des' => '作品项描述',
            'num' => '排序数字',
            'work_item_img' => '作品项图片',
            'w' => '作品宽度',
            'h' => '作品高度',
            'ratio' => '长宽比',
            'del_flag' => '删除标识',
            'created_time' => '创建时间',
            'updated_time' => '更新时间',
        ];
    }

    public static function saveWorkItem($param)
    {
        $work_item_id = $param['work_item_id'];
        $work_id = $param['work_id'];
        $work_item_title = $param['work_item_title'];
        $work_item_des = $param['work_item_des'];
        $num = $param['num'];
        $work_item_img = $param['work_item_img'];
        $w = $param['w'];
        $h = $param['h'];
        $ratio = $param['ratio'];
        $t = Utils::getCurrentDateTime();

        if ($work_item_id) {
            $workItem = static::findOne([
                'work_item_id' => $work_item_id
            ]);
            $workItem->work_id = $work_id;
            $workItem->work_item_title = $work_item_title;
            $workItem->work_item_des = $work_item_des;
            $workItem->num = $num;
            $workItem->work_item_img = $work_item_img;
            $workItem->w = $w;
            $workItem->h = $h;
            $workItem->ratio = $ratio;
            $workItem->save();

            $save_id = $workItem->attributes['work_item_id'];

        } else {
            
            $workItem = new static();
       
            
            $workItem->work_id = $work_id;
            $workItem->work_item_title = $work_item_title;
            $workItem->work_item_des = $work_item_des;
            $workItem->num = $num;
            $workItem->work_item_img = $work_item_img;
            $workItem->w = $w;
            $workItem->h = $h;
            $workItem->ratio = $ratio;
            $workItem->created_time = $t;
            $workItem->updated_time = $t;
            
  
            $flag = $workItem->save();
            //print_r($flag);exit;
            $save_id = $workItem->attributes['work_item_id'];
        }

        $res = [
            'code' => 0,
            'msg' => '保存成功',
            'data' => $save_id
        ];

        return $res;

    }
}
