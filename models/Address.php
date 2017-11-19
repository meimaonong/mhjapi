<?php

namespace app\models;

use Yii;
use app\models\District;

use app\libs\Utils;

/**
 * This is the model class for table "address".
 *
 * @property integer $address_id
 * @property integer $user_id
 * @property string $receiver
 * @property string $receiver_tel
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $district_id
 * @property string $address_detail
 * @property integer $del_flag
 * @property string $created_time
 * @property string $updated_time
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'province_id', 'city_id', 'district_id', 'del_flag'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['receiver'], 'string', 'max' => 11],
            [['receiver_tel'], 'string', 'max' => 15],
            [['address_detail'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'address_id' => '地址id',
            'user_id' => '用户id',
            'receiver' => '收件人',
            'receiver_tel' => '收件人手机号',
            'province_id' => '省份id',
            'city_id' => '城市id',
            'district_id' => '区县id',
            'address_detail' => '详细地址',
            'del_flag' => '删除标识',
            'created_time' => '创建时间',
            'updated_time' => '更新时间',
        ];
    }

    // 获取地址列表
    public static function getAddresslist($param)
    {
        $user_id = $param['user_id'];

        if ($user_id) {

            $address_list = static::find()
                ->select([
                    'address_id',
                    'user_id',
                    'receiver',
                    'receiver_tel',
                    'province_id',
                    'city_id',
                    'district_id',
                    'address_detail',
                    'del_flag',
                    'created_time',
                    'updated_time'
                ])
                ->where(['user_id' => $user_id, 'del_flag' => 0])
                ->asArray()
                ->all();
            
            $res = [
                'code' => 0,
                'msg'=> '',
                'data' => $address_list
            ];

        } else {
            $res = [
                'code' => 1,
                'msg'=> '用户id不能为空',
                'data' => []
            ];
        }

        return $res;
    }

    // getAddress
    public static function getAddress($param) {

        $user_id = $param['user_id'];
        $address_id = $param['address_id'];
        
        $address = static::find()
            ->where(['address_id' => $address_id, 'user_id' => $user_id, 'del_flag'=>0])
            ->asArray()
            ->one();

        if ($address) {
            // $province = District::findOne(['code' => $address['province_id'], 'level'=>2]);
            // $city = District::findOne(['code' => $address['city_id'], 'level'=>3]);
            // $district = District::findOne(['code' => $address['district_id'], 'level'=>4]);

            // $address['province_name'] = $province->name;
            // $address['city_name'] = $city->name;
            // $address['district_name'] = $district->name;

            $res = [
                'code' => 0,
                'msg'=> '',
                'data' => $address
            ];

        } else {
            $res = [
                'code' => 1,
                'msg'=> '地址不存在',
                'data' => null
            ];
        }

        return $res;
    }

    public static function delAddress($param) {
        $user_id = $param['user_id'];
        $address_id = $param['address_id'];

        $address = Address::findOne([
            'address_id' => $address_id,
            'user_id' => $user_id,
            'del_flag' => 0,
        ]);

        $address->del_flag = 1;
        $address->save();

        $save_id = $address->attributes['address_id'];

        $res = [
            'code' => 0,
            'msg'=> '',
            'data' => $save_id
        ];

        return $res;
    }

    // saveAddress
    public static function saveAddress($param) {

        $user_id = $param['user_id'];
        $address_id = $param['address_id'];
        $receiver = $param['receiver'];
        $receiver_tel = $param['receiver_tel'];
        // $province_id = $param['province_id'];
        // $city_id = $param['city_id'];
        // $district_id = $param['district_id'];
        $address_detail = $param['address_detail'];

        $save_id = $address_id;

        if ($address_id) {
            $address = Address::findOne([
                'address_id' => $address_id,
                'user_id' => $user_id,
                'del_flag' => 0,
            ]);
            $address->receiver = $receiver;
            $address->receiver_tel = $receiver_tel;
            // $address->province_id = $province_id;
            // $address->city_id = $city_id;
            // $address->district_id = $district_id;
            $address->address_detail = $address_detail;
            $address->save();
        } else {

            $t = Utils::getCurrentDateTime();

            $address = new static();
            $address->user_id = $user_id;
            $address->receiver = $receiver;
            $address->receiver_tel = $receiver_tel;
            // $address->province_id = $province_id;
            // $address->city_id = $city_id;
            // $address->district_id = $district_id;
            $address->address_detail = $address_detail;
            $address->created_time = $t;
            $address->updated_time = $t;
            $address->save();

            $save_id = $address->attributes['address_id'];
        }

        $res = [
            'code' => 0,
            'msg'=> '',
            'data' => $address
        ];

        return $res;

    }


}
