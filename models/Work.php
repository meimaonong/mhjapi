<?php

namespace app\models;

use Yii;
use app\models\WorkItem;
use app\libs\Utils;
use app\models\Category;
use app\models\Album;
use app\models\SellRecord;
use app\models\Address;

/**
 * This is the model class for table "work".
 *
 * @property integer $work_id
 * @property string $work_title
 * @property string $work_des
 * @property integer $work_check_status
 * @property integer $work_buy_status
 * @property integer $category_id
 * @property integer $user_id
 * @property integer $album_id
 * @property integer $del_flag
 * @property string $created_time
 * @property string $updated_time
 */
class Work extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'work';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['work_des'], 'string'],
            [['work_check_status', 'work_buy_status', 'category_id', 'user_id', 'album_id', 'del_flag'], 'integer'],
            [['created_time', 'updated_time'], 'safe'],
            [['work_title'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'work_id' => '作品id',
            'work_name' => '作品名称',
            'work_des' => '作品描述',
            'work_check_status' => '作品状态 0 未提审 1 待审核 2 审核通过',
            'work_buy_status' => '购买状态 0 未被购买 1 已被购买',
            'category_id' => '关联分类id',
            'user_id' => '用户id',
            'album_id' => '关联专辑id',
            'del_flag' => '删除标识',
            'created_time' => '创建时间',
            'updated_time' => '更新时间',
        ];
    }

    public static function getHomeData()
    {
        $data = [];

        $data['main'] = static::find()
            ->where(['main_flag' => 1])
            ->limit(6)
            ->asArray()
            ->all();
        
        $categorys = Category::getCategorylist();
        $data['categorys'] = $categorys['data'];

        foreach ($data['categorys'] as &$category) {
            $category['worklist'] = static::find()
                ->where([
                    'category_id' => $category['category_id'],
                    // 'category_flag' => 1,
                    // 'work_check_status'=>3, 
                    // 'work_buy_status'=>0,
                    'del_flag'=>0
                ])
                ->orderBy('work_id desc')
                ->limit(4)
                ->asArray()
                ->all();
        }

        $res = [
        	'code' => 0,
        	'msg'=> '',
        	'data' => $data
        ];

        return $res;

        
    }

    public static function delWork($param)
    {
        $work_id = $param['work_id'];
        $user_id = $param['user_id'];

        $work = static::findOne([
            'work_id' => $work_id,
            'user_id' => $user_id
        ]);

        $work->del_flag = 1;
        $work->save();

        $save_id = $work->attributes['work_id'];

        $res = [
        	'code' => 0,
        	'msg'=> '',
        	'data' => $save_id
        ];

        return $res;

    }

    public static function getWork($param)
    {
        $work_id = $param['work_id'];

        $work = static::find()
            ->where(['work_id' => $work_id])
            ->asArray()
            ->one();

        $workItems = WorkItem::getWorkItems($param);
        $work['workItems'] = $workItems['data'];
        if ($work['category_id']) {
            $category = Category::findOne([
                'category_id' => $work['category_id']
            ]);
            $work['category_name'] = $category['category_name'];
        }
        if ($work['album_id']) {
            $album = Album::findOne([
                'album_id' => $work['album_id']
            ]);
            $work['album_title'] = $album['album_title'];
        }

        $res = [
        	'code' => 0,
        	'msg'=> '',
        	'data' => $work
        ];

        return $res;

    }

    public static function getWaitWorks($param)
    {
        $user_id = $param['user_id'];

        $work_list = static::find()
            ->where(['user_id' => $user_id, 'work_check_status' => 1, 'del_flag'=>0])
            ->asArray()
            ->orderBy('updated_time desc')
            ->all();
        $res = [
        	'code' => 0,
        	'msg'=> '',
        	'data' => [
                'work_list' => $work_list,
                'count' => count($work_list)
            ]
        ];

        return $res;

    }

    public static function getBuyWorks($param)
    {
        $user_id = $param['user_id'];

        $work_list = SellRecord::find()
            ->where(['buyer_id' => $user_id, 'del_flag'=>0])
            ->asArray()
            ->orderBy('sell_record_id desc')
            ->all();

        unset($param['user_id']);

        foreach ($work_list as &$item) {
            $param['work_id'] = intval($item['work_id']);
            $work = static::find()->where($param)->asArray()->one();
            
            $address = Address::find()
                ->select(['receiver', 'receiver_tel', 'address_detail'])
                ->where(['address_id' => $item['address']])
                ->asArray()
                ->one();
            $item = array_merge($item, $work, $address);
        }
        
        $res = [
        	'code' => 0,
        	'msg'=> '',
        	'data' => [
                'work_list' => $work_list,
                'count' => count($work_list)
            ]
        ];

        return $res;

    }

    public static function getSellWorks($param)
    {
        $user_id = $param['user_id'];

        $work_list = SellRecord::find()
            ->where(['seller_id' => $user_id, 'del_flag'=>0])
            ->asArray()
            ->orderBy('sell_record_id desc')
            ->all();

        unset($param['user_id']);

        foreach ($work_list as &$item) {
            $param['work_id'] = intval($item['work_id']);
            $work = static::find()->where($param)->asArray()->one();
            $address = Address::find()
                ->select(['receiver', 'receiver_tel', 'address_detail'])
                ->where(['address_id' => $item['address']])
                ->asArray()
                ->one();
            $item = array_merge($item, $work, $address);
        }
        
        $res = [
        	'code' => 0,
        	'msg'=> '',
        	'data' => [
                'work_list' => $work_list,
                'count' => count($work_list)
            ]
        ];

        return $res;

    }

    public static function getWorklistByAlbum($param)
    {
        $user_id = $param['user_id'];
        $album_id = $param['album_id'];

        $work_list = static::find()
            ->where(['user_id' => $user_id, 'album_id' => $album_id, 'del_flag'=>0])
            ->asArray()
            ->orderBy('work_id desc')
            ->all();
        $res = [
        	'code' => 0,
        	'msg'=> '',
        	'data' => $work_list
        ];

        return $res;

    }

    public static function getWorklistByCategory($param)
    {
        $category_id = $param['category_id'];

        $page = $param['page'] ? $param['page'] : 1;
        $page_size = 20;

        $condition = [
            'category_id' => $category_id, 
            // 'work_check_status'=>3, 
            // 'work_buy_status'=>0,
            'del_flag'=>0
        ];

        $start = ($page - 1) * $page_size;

        $work_list = static::find()
            ->where([])
            ->limit($page_size, $start)
            ->orderBy('work_id desc')
            ->asArray()
            ->all();

        $count = static::find()
            ->where($condition)
            ->count();
        
        $pages = ceil($count / $page_size);

        $res = [
        	'code' => 0,
        	'msg'=> '',
        	'data' => [
                'work_list' => $work_list,
                'pages' => $pages,
                'currentpage' => $page
            ]
        ];

        return $res;

    }
    // backSubmit
    public static function backSubmit($param)
    {
        $user_id = $param['user_id'];
        $work_id = $param['work_id'];
        $reason = $param['reason'];

        $work = static::findOne([
            'work_id' => $work_id,
            'user_id' => $user_id
        ]);

        $work->reason = $reason;
        $work->work_check_status = 2;
        $work->save();

        $save_id = $work->attributes['work_id'];

        $res = [
            'code' => 0,
            'msg' => '保存成功',
            'data' => $save_id
        ];

        return $res;
    }
    // workSubmit
    public static function workSubmit($param)
    {
        $user_id = $param['user_id'];
        $work_id = $param['work_id'];

        $work = static::findOne([
            'work_id' => $work_id,
            'user_id' => $user_id
        ]);

        $work->work_check_status = 1;
        $work->save();

        $save_id = $work->attributes['work_id'];

        $res = [
            'code' => 0,
            'msg' => '保存成功',
            'data' => $save_id
        ];

        return $res;
    }

    // checkWork
    public static function checkWork($param)
    {
        $user_id = $param['user_id'];
        $work_id = $param['work_id'];

        $work = static::findOne([
            'work_id' => $work_id,
            'user_id' => $user_id
        ]);

        $work->work_check_status = 3;
        $work->save();

        $save_id = $work->attributes['work_id'];

        $res = [
            'code' => 0,
            'msg' => '保存成功',
            'data' => $save_id
        ];

        return $res;
    }
    // saveWork
    public static function saveWork($param)
    {

        $work = json_decode($param['work'], true);

        $work_id = $work['work_id'];
        $user_id = $param['user_id'];
        $work_title = $work['work_title'];
        $work_des = $work['work_des'];
        $work_img = $work['work_img'];
        $w = $work['w'];
        $h = $work['h'];
        $ratio = $work['ratio'];
        $work_price = $work['work_price'];
        $category_id = $work['category_id'];
        $album_id = $work['album_id'];
        $workItems = $work['workItems'];

        $save_id = '';

        $t = Utils::getCurrentDateTime();

        if ($work_id && $user_id && $album_id && $category_id) {
            
            $work = static::findOne([
                'work_id' => $work_id,
                'user_id' => $user_id
            ]);
            
            $work->work_title = $work_title;
            $work->work_img = $work_img;
            $work->w = $w;
            $work->h = $h;
            $work->ratio = $ratio;
            $work->work_price = $work_price;
            $work->category_id = $category_id;
            $work->album_id = $album_id;
            $work->save();

            $save_id = $work->attributes['work_id'];


            foreach ($workItems as $key => $workItem) {
                $workItem['work_id'] = $save_id;
                $workItem['num'] = $key;
                WorkItem::saveWorkItem($workItem);
            }

        } else {
            $work = new static();
            $work->user_id = $user_id;
            $work->work_title = $work_title;
            $work->work_des = '';
            $work->work_img = $work_img;
            $work->w = $w;
            $work->h = $h;
            $work->ratio = $ratio;
            $work->category_id = $category_id;
            $work->album_id = $album_id;
            $work->created_time = $t;
            $work->updated_time = $t;
            $work->save();

            $save_id = $work->attributes['work_id'];

            foreach ($workItems as $workItem) {
                $workItem['work_id'] = $save_id;
                WorkItem::saveWorkItem($workItem);
            }
        }

        $res = [
            'code' => 0,
            'msg' => '保存成功',
            'data' => $save_id
        ];

        return $res;

    }

}
