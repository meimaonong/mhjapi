<?php
namespace app\modules\v1\controllers;

use yii\filters\VerbFilter;

use app\controllers\BaseController;

use app\libs\Utils;

use app\libs\upload\uploadHandler;

class FileController extends BaseController
{

    public $enableCsrfValidation = false;
    
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

	private function UploadProcess(&$handle, $dir_dest) {

		$handle->Process($dir_dest);

	}

	/**
	* 图片上传
	*/
	public function actionImageUpload()
	{
		$params = $_REQUEST;
        $files = $_FILES;

		$dir_dest = $params['dir'];
		$dir_pics = $params['pics'];
        $dir_action = $params['action'];

		$dir_dest = isset($dir_dest) ? $dir_dest : 'uploads/images';
		$dir_pics = isset($dir_pics) ?$dir_pics : $dir_dest;

		$sizeList = json_decode($params['sizeList'], true);

		if (count($files) > 0)
		{
			// 初始化
		    $handle = new uploadHandler($files['file']);
		    // then we check if the file has been uploaded properly
		    // in its *temporary* location in the server (often, it is /tmp)
		    if ($handle->uploaded) {

		    	/******自定义******/

		    	$dayDir = '/' . date('Y') . '/' . date('m') . '/' . date('d');
		    	$handle->file_new_name_body = uniqid('a');

		    	/******自定义结束******/

		        $handle->Process($dir_dest . $dayDir);

		        $fileOriginName = $handle->file_dst_name;
		        $imageWidth = $handle->image_src_x;
		        $imageHeight = $handle->image_src_y;

		        if ($handle->processed) {

		        	if ($sizeList && count($sizeList) > 0) {

		        		foreach ($sizeList as $size) {
		        			$handle->image_resize          = true;

		        			if ($size['w'] && $size['h']) {
		        				$handle->image_y               = $size['h'];
			        			$handle->image_x               = $size['w'];
			        			$handle->file_new_name_body = $fileOriginName . ('_w' . $size['w'].'_h'.$size['h']);
		        			}

		        			if ($size['w'] && !$size['h']) {
			        			$handle->image_x               = $size['w'];
			        			$handle->file_new_name_body = $fileOriginName . ('_w' . $size['w']);
		        			}

		        			if (!$size['w'] && $size['h']) {
			        			$handle->image_y               = $size['h'];
			        			$handle->file_new_name_body = $fileOriginName . ('_h' . $size['h']);
		        			}
		        			
		        			$this->UploadProcess($handle, $dir_dest . $dayDir);
		        			//$handle->Process($dir_dest . $dayDir);
		        		}

		        	}

		            $res = [
		            	'code' => 0,
		            	'data' => [
		            		'fileName'  => $fileOriginName,
		            		'fileUrl'   => ($dir_dest . $dayDir.'/' . $fileOriginName),
		            		'imageWidth' => $imageWidth,
		            		'imageHeight' => $imageHeight,
		            		'msg'       => 'File uploaded with success'
		            	]
		            ];

					
					echo json_encode($res);
					

		        } else {
		        	$res = [
		        		'code' => -1,
		        		'msg'   	=> 'File not uploaded to the wanted location',
		        		'errorInfo' => ('Error: ' . $handle->error . '')
		        	];

		        	echo json_encode($res);
		        }

		        // we delete the temporary files
		        $handle->Clean();

		    } else {
		        // if we're here, the upload file failed for some reasons
		        // i.e. the server didn't receive the file
		        echo '<p class="result">';
		        echo '  <b>File not uploaded on the server</b><br />';
		        echo '  Error: ' . $handle->error . '';
		        echo '</p>';
		    }


		}


	}

    
}