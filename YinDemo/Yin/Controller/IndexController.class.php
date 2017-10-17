<?php
namespace Yin\Controller;
use Think\Controller;
use Think\D;
class IndexController extends Controller {
    public function index(){
    	$this->display();
    }
    public function edit($id)
    {
        //首选查询数据位置信息
        $data = true; //数据查询位置，（替换）
        if ($data) {
            $src = './Public/test.json'; //（替换）文件查询出的地址
            $text = file_get_contents($src);
            $this->assign('json',$text);
            $this->display();
            //查询到位置打开对应位置的文件
        }else{
            //查找不到的话，抛出错误信息
        }
    }
    /**
     * [Uploads 文件上传方法（页面背景图部分）]
     * @Author   尹新斌
     * @DateTime 2017-09-19
     * @Function []
     */
    public function Uploads()
    {
    	$config = [
    		'savePath' => '/Template/',
    		'exts'     => ['png','jpg','jpeg'],
    	];
		$upload = new \Think\Upload($config);
		$info   = $upload->uploadOne($_FILES['file']);
    	if ($info) {
    		$flagId = D::field('Files.id',['md5' => $info['md5']]);
    		if ($flagId) {
    			$return = $flagId;
    			unlink('./Uploads'.$info['savepath'].$info['savename']); //删除文件
    		}else{
                $url = './Uploads'.$info['savepath'].$info['savename']; //取得文件地址 后面进行GD库裁切
                $width = 595; $height = 842; //设置需要剪裁的长宽
                $image = new \Think\Image(); //实例化GD库
                $image->open($url); //打开图片
                $image->thumb($width, $height,\Think\Image::IMAGE_THUMB_FILLED)->save($url); //缩放填充 方式生成一个页面图片
    			$return = D::add('Files',$info);
    		}
    		$this->ajaxReturn([
    			'status' => 1,
    			'data'   => $return,
    			'src'    => '/Uploads'.D::field('Files.concat(savepath,savename)',$return)
    		]);
    	}else{
    		$this->ajaxReturn([
    			'status' => 0,
    			'data'   => $upload->getError()
    		]);
    	}
    }
    /**
     * [save 保存]
     * @Author   ヽ(•ω•。)ノ   Mr.Solo
     * @DateTime 2017-10-16
     * @Function []
     * @return   [type]     [description]
     */
    public function save()
    {
        $datas = json_encode(I('post.data'));
        file_put_contents('./Public/test.json', $datas);
    }
}