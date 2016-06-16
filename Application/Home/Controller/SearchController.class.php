<?php

namespace Home\Controller;

class SearchController extends PlateformController {

    public function search(){
        $categoryModel = D('Admin/Category');
        $id = I('get.id');
        $cat = $categoryModel->find($id);
        $data = array();
        if($cat['search_attr_id'] != ''){
            $attributeModel = M('Attribute');
            $data = $attributeModel->where(array('id'=>array('in',$cat['search_attr_id'])))->select();
            if(count($data)>0){
                $goodsAttrModel = M('GoodsAttr');
                foreach($data as $k=>$v){
                    $goods_attr = $goodsAttrModel->field('attr_value')->distinct(true)->where(array('attr_id'=>array('eq',$v['id'])))->select();
                    if(count($goods_attr)>0){
                        $data[$k]['attr_value'] = $goods_attr;
                    }else{
                        unset($data[$k]);
                    }
                }                  
            }
        }
        $this->assign('searchData',$data);
        
        $goods_cat_model = M('GoodsCat');
        $goods_id = $goods_cat_model->field('goods_id')->where(array('cat_id'=>array('eq',$id)))->select();

        foreach($goods_id as $k=>$v){
            $ids[] = $v['goods_id'];
        }

        $priceList = array();
        $goodsModel = D('Admin/Goods');
        if($ids){
            //获取该商品类型下商品的最高价与商品的最低价
            
            $goods = $goodsModel->field('MAX(shop_price) maxprice,MIN(shop_price) minprice')->where(array('id'=>array('in',$ids)))->find();

            $max_price = $goods['maxprice'];
            $min_price = $goods['minprice'];
            $section = 7;
            $weishu = strlen(floor(($max_price-$min_price)/$section));
            
            
            $start_price = floor($min_price/pow(10,$weishu-1))*pow(10,$weishu-1);
            $price_section =  ceil((($max_price-$min_price)/$section)/pow(10,$weishu-2))*pow(10,$weishu-2);
            for($i=0; $i<$section; ++$i){
                $section_start = $start_price + $price_section*$i;
                $section_end = $start_price + $price_section*($i+1)-1;
                
                if($i< $section-1){
                    $goodsCount = $goodsModel->field('COUNT(*) goods_number')->where(array('id'=>array('in',$ids),'shop_price'=>array('between',array($section_start,$section_end))))->find();
                }else{
                    $goodsCount = $goodsModel->field('COUNT(*) goods_number')->where(array('id'=>array('in',$ids),'shop_price'=>array('gt',$section_start)))->find();
                }
                

                if($goodsCount['goods_number']==0){
                    continue;
                }
                if($i < $section-1){
                    $priceList[] = $section_start . '-' . $section_end;
                }else{
                    $priceList[] = $section_start . '-以上';
                }
            }
        }

        
        $this->assign('priceList',$priceList);
        
        
        /**********************获取商品数据***********************/
        $goodsList = $goodsModel->search_goods();
        $this->assign('goodsList',$goodsList);
        
//        echo "<pre>";
//        var_dump($data);die;
        # 设置页面属性
        $this->setPageInfo('搜索页', '搜索', '搜索', 0, array('list', 'common'), array('list'));
        $this->display();
    }
    
    
}
