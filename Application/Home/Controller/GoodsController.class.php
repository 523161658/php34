<?php
namespace Home\Controller;
class GoodsController extends PlateformController {
    public function detail(){
        # 获取商品的ID
        $id = I('get.id');
        
        # 获取商品的基本信息
        $goods_model = M('Goods');
        $goodsInfo = $goods_model->where(array('id'=>array('eq',$id)))->find();
        
        # 获取属性
        $goods_attr_model = M('GoodsAttr');
        $goodsAttr = $goods_attr_model->field('a.*,b.attr_name,b.attr_type,b.attr_option_values,b.type_id')->alias('a')->join('LEFT JOIN php34_attribute b ON a.attr_id = b.id')->where(array('a.goods_id'=>array('eq',$id)))->select();
        $attr_single = array();
        $attr_mutiple = array();
        foreach ($goodsAttr as $k=>$v){
            if($v['attr_type'] == '1'){
                // 多选
                $attr_mutiple[$v['attr_name']][] = $v;
            }else{
                // 单选
                $attr_single[] = $v;
            }
        }
        
        # 获取商品的图片
        $goods_pics_model = M('GoodsPics');
        $goodsPics = $goods_pics_model->where(array('goods_id'=>array('eq',$id)))->select();

        # 赋值到页面
        $this->assign(array(
            'goodsInfo'     =>  $goodsInfo,
            'attr_single'   =>  $attr_single,
            'attr_mutiple'  =>  $attr_mutiple,
            'goodsPics'     =>  $goodsPics,
        ));
//        echo "<pre>";
//        var_dump($attr_mutiple);
        
        # 设置页面属性
        $this->setPageInfo($goodsInfo['goods_name'].'详情页', $goodsInfo['seo_keyword'], $goodsInfo['seo_description'],0,array('goods','jqzoom','common'),array('goods','jqzoom-core'));
        $this->display();
    }
    
    /**
     * 获取商品会员价格
     */
    public function getMemberPrice(){
        $id = I('get.id');
        
        $goods_model = D('Admin/Goods');
        $price = $goods_model->getMemberPrice($id);
        $this->ajaxReturn($price);
    }

    /**
     * 将该商品存入COOKIE中
     */
    public function getRecentlyGoods(){
        $id = I('get.id',0);
        // 先读取最近浏览过的商品
        $recentlyGoods = cookie('recentlyGoods') != '' ? unserialize(cookie('recentlyGoods')) : array();
        // 如果有则返回商品数据
        if($recentlyGoods){
            $goods_model = M('Goods');
            $str = implode(',',$recentlyGoods);
            $data = $goods_model->field('id,goods_name,sm_logo')->where(array('id'=>array('in',$recentlyGoods)))->order("INSTR(',$str,',CONCAT(',',id,','))")->select();
            echo json_encode($data);
        }

        // 将该商品存入COOKIE中
        array_unshift($recentlyGoods, $id);
        $recentlyGoods = array_unique($recentlyGoods);     
        if(count($recentlyGoods) > 10){
            $recentlyGoods = array_slice($recentlyGoods, 0, 10);
        } 
        cookie('recentlyGoods',serialize($recentlyGoods),array('expire'=>86400,'path'=>'/','domain'=>'php34.com'));
    }

    /**
     * 判断是否登录，用于判断是否显示评论表单
     */
    public function checkIsLogin(){
        if(session('member') != null){
            $this->ajaxReturn('1');
        }else{
            $this->ajaxReturn('0');
        }
    }

    /**
     * 从商品详情页中登录（登录成功后需跳回该商品详情页）
     */
    public function login(){
        // var_dump($_SERVER['HTTP_REFERER']);
        session('from',$_SERVER['HTTP_REFERER']);
        Header("Location: /Home/Member/login "); 
    }
}