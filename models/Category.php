<?php

namespace app\models;

use Yii;
use app\libs\Utils;

/**
 * This is the model class for table "category".
 *
 * @property integer $category_id
 * @property string $category_name
 * @property integer $del_flag
 * @property string $created_time
 * @property string $updated_time
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['del_flag'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['category_name'], 'string', 'max' => 45],
            [['category_img'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category_id' => '分类id',
            'category_name' => '分类名称',
            'category_img' => '分类图片',
            'del_flag' => '删除标识',
            'created_time' => '创建时间',
            'updated_time' => '更新时间',
        ];
    }


    // 保存分类
    public static function saveCategory($param)
    {
        $category_id = $param['category_id'];
        $category_name = $param['category_name'];

        if ($category_id) {
            $category = static::findOne([
                'category_id' => $category_id,
                'del_flag' => 0,
            ]);
            $category->category_name = $category_name;
            $category->category_img = $category_img;
            $category->save();
        } else {

            $t = Utils::getCurrentDateTime();

            $category = new static();
            $category->category_name = $category_name;
            $category->category_img = $category_img;
            $category->created_time = $t;
            $category->updated_time = $t;
            $category->save();

            $save_id = $category->attributes['category_id'];
        }
        
        $res = [
            'code' => 0,
            'msg'=> '',
            'data' => $save_id
        ];

        return $res;

    }

    // 获取分类
    public static function getCategory()
    {
        $category_id = $param['category_id'];

        $category = static::find()
            ->where(['category_id' => $category_id, 'del_flag'=>0])
            ->asArray()
            ->one();
        
        $res = [
            'code' => 0,
            'msg'=> '',
            'data' => $category
        ];

        return $res;
    }

    // 获取分类列表
    public static function getCategorylist()
    {

        $categorys = static::find()
            ->where(['del_flag'=>0])
            ->asArray()
            ->all();
        
        $res = [
            'code' => 0,
            'msg'=> '',
            'data' => $categorys
        ];

        return $res;
    }

}
