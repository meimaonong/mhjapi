<?php

namespace app\modules\v1\controllers;

use yii\filters\VerbFilter;

use app\controllers\BaseController;

use app\models\Work;
use app\models\WorkItem;
use app\models\Address;
use app\models\Album;
use app\models\Category;
use app\models\Message;
use app\models\SellRecord;
use app\models\User;

use app\libs\Utils;

class DataController extends BaseController
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

    // 
    public function actionGetHomeData()
    {
        $res = Work::getHomeData();

        return $res;

    }

    //
    public function actionGetMyData()
    {
        $param = $_REQUEST;
        $user_id = $param['user_id'];

        $res = [];

        $res['wait_num'] = Work::find()
            ->where(['user_id' => $user_id, 'work_check_status' => 1,'del_flag' => 0])
            ->count();
        $res['sell_num'] = SellRecord::find()
            ->where(['seller_id' => $user_id, 'del_flag' => 0])
            ->count();
        $res['buy_num'] = SellRecord::find()
            ->where(['buyer_id' => $user_id, 'del_flag' => 0])
            ->count();
        $res['msg_num'] = Message::find()
            ->where(['user_id' => $user_id, 'is_read' => 0,'del_flag' => 0])
            ->count();
        $res['album_num'] = Album::find()
            ->where(['user_id' => $user_id, 'del_flag' => 0])
            ->count();

        return [
            'code' => 0,
            'msg' => '',
            'data' => $res
        ];
    }

    private function getCount($num) {
        if ($num == 1) {
            return round(600/465, 2);
        } else if ($num == 2) {
            return round(550/550, 2);
        } else if ($num == 3) {
            return round(600/326, 2);
        } else if ($num == 4) {
            return round(292/550, 2);
        } else if ($num == 5) {
            return round(386/550, 2);
        } else if ($num == 6) {
            return round(600/448, 2);
        } else if ($num == 7) {
            return round(520/550, 2);
        } else if ($num == 8) {
            return round(550/550, 2);
        } else if ($num == 9) {
            return round(290/550, 2);
        } else if ($num == 10) {
            return round(600/423, 2);
        } else if ($num == 11) {
            return round(600/442, 2);
        } else if ($num == 12) {
            return round(600/262, 2);
        } else if ($num == 13) {
            return round(264/550, 2);
        } else if ($num == 14) {
            return round(435/550, 2);
        } else if ($num == 15) {
            return round(600/451, 2);
        } else if ($num == 16) {
            return round(437/550, 2);
        } else if ($num == 17) {
            return round(600/479, 2);
        } else if ($num == 18) {
            return round(600/289, 2);
        } else if ($num == 19) {
            return round(417/550, 2);
        } else if ($num == 20) {
            return round(555/550, 2);
        }
    }

    public function actionMakeData()
    {
        $pic_url = 'https://www.meimaonong.com/uploads/imgs2/';
        $t = Utils::getCurrentDateTime();

        $categorys = Category::find()
            ->where(['del_flag'=>0])
            ->asArray()
            ->all();


        $users = User::find()
            ->select(['user_id'])
            ->asArray()
            ->all();

        foreach ($users as $user) {
            $user_id = $user['user_id'];

            for ($j1=0; $j1 < 2; $j1++) { 
                $address = new Address();
                $address->user_id = $user_id;
                $address->receiver = '张扬' . $j1;
                $address->receiver_tel = '1339988888' . $j1;
                $address->province_id = 110000;
                $address->city_id = 110000;
                if ($j1==0){
                    $address->district_id = 110104;
                } else {
                    $address->district_id = 110105;
                }
                $address->address_detail = '测试详细地址';
                $address->created_time = $t;
                $address->updated_time = $t;
                $address->save();
            }

            

            for ($x=0;$x<3;$x++) {
                $album = new Album();
                $album->album_title = '测试mc' . $x;
                $album->album_des = '测试ms' . $x;
                $album->user_id = $user_id;
                $album->created_time = $t;
                $album->updated_time = $t;
                $album->save();

                $album_id = $album->attributes['album_id'];

                for ($x1=0;$x1<count($categorys);$x1++) {

                    $category_id = $categorys[$x1]['category_id'];
                    
                    for ($x2=0; $x2 < 20; $x2++) { 
                        $work = new Work();
                        $work->work_title = '测试' . $x2;
                        $t1 = rand(1, 20);
                        $work->work_img = $pic_url . $t1 . '.jpg';
                        $work->work_price = 3200;
                        $work->w = 1200;
                        $work->h = 800;
                        $work->ratio = $this->getCount($t1);
                        $work->work_des = '测试内容';
                        if ($x2>3&&$x2<8) {
                            $work->category_flag = 1;
                        }
                        if ($x2>10&&$x2<13) {
                            $work->main_flag = 1;
                        }
                        $work->user_id = $user_id;
                        $work->category_id = $category_id;
                        $work->album_id = $album_id;
                        $work->created_time = $t;
                        $work->updated_time = $t;
                        $work->save();

                        $work_id = $work->attributes['work_id'];

                        if ($x2<6) {
                            $message = new Message();
                            $message->user_id = $user_id;
                            $message->type = 1;
                            $message->message_content = '测试消息内容';
                            $message->work_id = $work_id;
                            $message->created_time = $t;
                            $message->updated_time = $t;
                            $message->save();
                        }

                        for ($x3=0; $x3 < 10; $x3++) { 
                            $workItem = new WorkItem();
                            $workItem->work_id = $work_id;
                            $workItem->work_item_des = '测试内容测试内容';
                            $workItem->num = $x3;
                            $t2 = rand(1, 20);
                            $workItem->work_item_img = $pic_url . $t2 . '.jpg';
                            $workItem->w = 1200;
                            $workItem->h = 800;
                            $workItem->ratio =  $this->getCount($t2);
                            $workItem->created_time = $t;
                            $workItem->updated_time = $t;
                            $workItem->save();
                        }

                    }

                }
            }
        }

        return ['code'=>0];
    }


}
