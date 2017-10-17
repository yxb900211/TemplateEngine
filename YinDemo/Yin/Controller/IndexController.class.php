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
     * [tcpdf tcPDF整理测试]
     * @Author   ヽ(•ω•。)ノ   Mr.Solo
     * @DateTime 2017-10-17
     * @Function []
     * @return   [type]     [description]
     */
    public function tcpdf($src = './Public/test.json')
    {
        //$src 为查询出的模板地址信息，默认为demo
        $t = json_decode(file_get_contents($src),'array');
        // dump($t);die;


        Vendor('TCPDF.tcpdf');
        //引入tcpdf类，
        $tcpdf = new \TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
        $songti = \TCPDF_FONTS::addTTFfont('./Public/Songti.ttf', 'TrueTypeUnicode', '', 32);
        //宋体文字依赖
        $tcpdf->setPrintHeader(false);            //页面头部横线取消
        $tcpdf->setPrintFooter(false);            //页面底部更显取消
        $tcpdf->SetMargins(0,0,0);                //间距设置
        $tcpdf->setHeaderMargin(0);
        $tcpdf->setFooterMargin(0);
        $tcpdf->setPageOrientation('P',0,0);
        //

        //循环模板信息
        foreach ($t as $key => $value) {
            //每一页对应的设置
            //首先新增页
            $tcpdf->AddPage();
            //查看是否有背景图情况，
            if (!empty($value['bg'])) {
                //如果有，则增加背景图渲染
                $tcpdf->Image('.'.$value['bg'], 0, 0,210,'', '', '', '');
                $tcpdf->setImageScale(1.25);
            }
            $lineHeight = 15 * 0.353;
            //在次循环datas,写入数据到页面
            foreach ($value['datas'] as $i => $item) {
                // dump($item);die;
                $tcpdf->setFont('stsongstdlight','',12);
                $w = str_replace('px', '', $item['w']) * 0.353;
                $h = str_replace('px', '', $item['h']) * 0.353;
                $x = str_replace('px', '', $item['x']) * 0.353;
                $y = str_replace('px', '', $item['y']) * 0.353 + $lineHeight;
                // dump($w);die;
                $tcpdf->writeHTMLCell($w, $h, $x, $y, '<p align="center">'.$item['name'].'</p>');//$val['bz']需要写入的字符串
            }
        }
        $tcpdf->Output(); //I输出在浏览器上 S保存
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
                $width = 2480; $height = 3508; //设置需要剪裁的长宽
                $image = new \Think\Image(); //实例化GD库
                $image->open($url); //打开图片
                $image->thumb($width, $height,\Think\Image::IMAGE_THUMB_SCALE)->save($url); //缩放填充 方式生成一个页面图片
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