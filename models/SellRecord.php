<?php

namespace app\models;

use Yii;
use app\libs\Utils;

/**
 * This is the model class for table "sell_record".
 *
 * @property integer $sell_record_id
 * @property integer $buyer_id
 * @property integer $work_id
 * @property integer $del_flag
 * @property string $created_time
 * @property string $updated_time
 */
class SellRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sell_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['buyer_id', 'work_id', 'del_flag'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sell_record_id' => 'Sell Record ID',
            'buyer_id' => 'Buyer ID',
            'work_id' => 'Work ID',
            'del_flag' => 'Del Flag',
            'created_time' => 'Created Time',
            'updated_time' => 'Updated Time',
        ];
    }

    // 前台用户修改资料
    public static function saveExpress($param)
    {

        $sell_record_id = $param['sell_record_id'];
        $express = $param['express'];

        $sell_record = static::findOne([
            'sell_record_id' => $sell_record_id
        ]);

        $sell_record->express = $express;
        $sell_record->save();
   
        $save_id = $sell_record->attributes['sell_record_id'];

        $res = [
        	'code' => 0,
        	'msg'=> '',
        	'data' => $save_id
        ];

        return $res;

    }
}
