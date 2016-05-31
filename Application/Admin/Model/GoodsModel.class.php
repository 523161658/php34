<?php

namespace Admin\Model;

use Think\Model;

class GoodsModel extends Model {

    protected $insertFields = array('goods_name', 'cat_id', 'brand_id', 'market_price', 'shop_price', 'jifen', 'jyz', 'jifen_price', 'is_promote', 'promote_price', 'promote_start_time', 'promote_end_time', 'is_hot', 'is_new', 'is_best', 'is_on_sale', 'seo_keyword', 'seo_description', 'type_id', 'sort_num', 'is_delete', 'goods_desc');
    protected $updateFields = array('id', 'goods_name', 'cat_id', 'brand_id', 'market_price', 'shop_price', 'jifen', 'jyz', 'jifen_price', 'is_promote', 'promote_price', 'promote_start_time', 'promote_end_time', 'is_hot', 'is_new', 'is_best', 'is_on_sale', 'seo_keyword', 'seo_description', 'type_id', 'sort_num', 'is_delete', 'goods_desc');
    protected $_validate = array(
        array('goods_name', 'require', '商品名称不能为空！', 1, 'regex', 3),
        array('goods_name', '1,45', '商品名称的值最长不能超过 45 个字符！', 1, 'length', 3),
        array('cat_id', 'require', '主分类的id不能为空！', 1, 'regex', 3),
        array('cat_id', 'number', '主分类的id必须是一个整数！', 1, 'regex', 3),
        array('brand_id', 'number', '品牌的id必须是一个整数！', 1, 'regex', 3),
        array('market_price', 'currency', '市场价必须是货币格式！', 2, 'regex', 3),
        array('shop_price', 'currency', '本店价必须是货币格式！', 2, 'regex', 3),
        array('jifen', 'require', '赠送积分不能为空！', 1, 'regex', 3),
        array('jifen', 'number', '赠送积分必须是一个整数！', 1, 'regex', 3),
        array('jyz', 'require', '赠送经验值不能为空！', 1, 'regex', 3),
        array('jyz', 'number', '赠送经验值必须是一个整数！', 1, 'regex', 3),
        array('jifen_price', 'require', '如果要用积分兑换，需要的积分数不能为空！', 1, 'regex', 3),
        array('jifen_price', 'number', '如果要用积分兑换，需要的积分数必须是一个整数！', 1, 'regex', 3),
        array('is_promote', 'number', '是否促销必须是一个整数！', 2, 'regex', 3),
        array('promote_start_time', 'require', '促销开始时间不能为空！', 0, 'regex', 3),
        array('promote_end_time', 'require', '促销结束时间不能为空！', 0, 'regex', 3),
        array('promote_price', 'currency', '促销价必须是货币格式！', 2, 'regex', 3),
        array('is_hot', 'number', '是否热卖必须是一个整数！', 2, 'regex', 3),
        array('is_new', 'number', '是否新品必须是一个整数！', 2, 'regex', 3),
        array('is_best', 'number', '是否精品必须是一个整数！', 2, 'regex', 3),
        array('is_on_sale', 'number', '是否上架：1：上架，0：下架必须是一个整数！', 2, 'regex', 3),
        array('seo_keyword', '1,150', 'seo优化[搜索引擎【百度、谷歌等】优化]_关键字的值最长不能超过 150 个字符！', 2, 'length', 3),
        array('seo_description', '1,150', 'seo优化[搜索引擎【百度、谷歌等】优化]_描述的值最长不能超过 150 个字符！', 2, 'length', 3),
        array('type_id', 'number', '商品类型id必须是一个整数！', 2, 'regex', 3),
        array('sort_num', 'number', '排序数字必须是一个整数！', 2, 'regex', 3),
        array('is_delete', 'number', '是否放到回收站：1：是，0：否必须是一个整数！', 2, 'regex', 3),
    );
    protected $_auto = array(
        array('addtime', 'time', 1, 'function'), // 对addtime字段在添加的时候写入当前时间戳
        array('promote_start_time', 'strtotime', 3, 'function'), // 对promote_start_time字段在添加的时候写入当前时间戳
        array('promote_end_time', 'strtotime', 3, 'function'), // 对promote_end_time字段在添加的时候写入当前时间戳
    );

    public function search($pageSize = 20, $is_delete = 0) {
        /*         * ************************************** 搜索 *************************************** */
        $where = array("is_delete" => array('eq', $is_delete));
        if ($goods_name = I('get.goods_name'))
            $where['goods_name'] = array('like', "%$goods_name%");
        if ($cat_id = I('get.cat_id'))
            $where['cat_id'] = array('eq', $cat_id);
        if ($brand_id = I('get.brand_id'))
            $where['brand_id'] = array('eq', $brand_id);
        $shop_pricefrom = I('get.shop_pricefrom');
        $shop_priceto = I('get.shop_priceto');
        if ($shop_pricefrom && $shop_priceto)
            $where['shop_price'] = array('between', array($shop_pricefrom, $shop_priceto));
        elseif ($shop_pricefrom)
            $where['shop_price'] = array('egt', $shop_pricefrom);
        elseif ($shop_priceto)
            $where['shop_price'] = array('elt', $shop_priceto);
        $is_hot = I('get.is_hot');
        if ($is_hot != '' && $is_hot != '-1')
            $where['is_hot'] = array('eq', $is_hot);
        $is_new = I('get.is_new');
        if ($is_new != '' && $is_new != '-1')
            $where['is_new'] = array('eq', $is_new);
        $is_best = I('get.is_best');
        if ($is_best != '' && $is_best != '-1')
            $where['is_best'] = array('eq', $is_best);
        $is_on_sale = I('get.is_on_sale');
        if ($is_on_sale != '' && $is_on_sale != '-1')
            $where['is_on_sale'] = array('eq', $is_on_sale);
        if ($addtime = I('get.addtime'))
            $where['addtime'] = array('eq', $addtime);
        /*         * *********************************** 翻页 *************************************** */
        $count = $this->alias('a')->where($where)->count();
        $page = new \Think\Page($count, $pageSize);
        // 配置翻页的样式
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $data['page'] = $page->show();
        /*         * ************************************ 取数据 ***************************************** */
        $data['data'] = $this->alias('a')->field('a.*,ifnull(sum(b.goods_number),0) goods_number')->join('LEFT JOIN php34_goods_number b on a.id = b.goods_id')->where($where)->group('a.id')->limit($page->firstRow . ',' . $page->listRows)->select();
        return $data;
    }

    // 添加前
    protected function _before_insert(&$data, $option) {

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $ret = uploadOne('logo', 'Admin', array(
                array(150, 150, 2),
            ));
            if ($ret['ok'] == 1) {
                $data['logo'] = $ret['images'][0];
                $data['sm_logo'] = $ret['images'][1];
            } else {
                $this->error = $ret['error'];
                return FALSE;
            }
        }
    }

    // 添加后
    protected function _after_insert($data, $options) {
        $goods_id = $data['id'];
        /*         * *******************添加扩展分类************************ */
        $ext_cat_model = M('GoodsCat');
        $ext_cat_ids = I('post.ext_cat_id');
        if ($ext_cat_ids) {
            foreach ($ext_cat_ids as $v) {
                if (empty($v)) {
                    continue;
                }
                $ext_cat_model->add(array(
                    'goods_id' => $goods_id,
                    'cat_id' => $v
                ));
            }
        }

        /*         * *******************添加会员价格************************ */
        $mp_model = M('MemberPrice');
        $member_price = I('post.member_price');
        if ($member_price) {
            foreach ($member_price as $k => $v) {
                if (empty($v)) {
                    continue;
                }
                $mp_model->add(array(
                    'goods_id' => $goods_id,
                    'level_id' => $k,
                    'price' => $v == '' ? '0.00' : $v
                ));
            }
        }
        /*         * *******************添加属性值************************** */
        $ga_model = M('GoodsAttr');
        $attr_value = I('post.attr_value');
        $attr_price = I('post.attr_price');
        if ($attr_value) {
            foreach ($attr_value as $k => $v) {
                foreach ($v as $k1 => $v1) {
                    if ($v1) {
                        $ga_model->add(array(
                            'goods_id' => $goods_id,
                            'attr_id' => $k,
                            'attr_value' => $v1,
                            'attr_price' => $attr_price[$k][$k1] === '' ? '0.00' : $attr_price[$k][$k1],
                        ));
                    }
                }
            }
        }

        /*         * *******************添加商品相册*********************** */
        $gp_model = M('GoodsPics');
        $goods_pics = $_FILES['pics'];
        $pics = array();
        foreach ($goods_pics['name'] as $k => $v) {
            if (!empty($v)) {
                $pics[] = array(
                    'name' => $goods_pics['name'][$k],
                    'type' => $goods_pics['type'][$k],
                    'tmp_name' => $goods_pics['tmp_name'][$k],
                    'error' => $goods_pics['error'][$k],
                    'size' => $goods_pics['size'][$k],
                );
            }
        }
        $_FILES = $pics;
        foreach ($pics as $k => $v) {
            $ret = uploadOne($k, 'Goods', $thumb = array(
                array(150, 150, 2),
            ));
            if ($ret['ok'] == 1) {
                $gp_model->add(array(
                    'pic' => $ret['images'][0],
                    'sm_pic' => $ret['images'][1],
                    'goods_id' => $goods_id
                ));
            }
        }
    }

    // 修改前
    protected function _before_update(&$data, $option) {
        // 如果没有勾选促销价格就手动设置为更新成0
        if (!isset($_POST['is_promote'])) {
            $data['is_promote'] = 0;
        }


        if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
            $ret = uploadOne('logo', 'Goods', array(
                array(150, 150, 2),
            ));
            if ($ret['ok'] == 1) {
                $data['logo'] = $ret['images'][0];
                $data['sm_logo'] = $ret['images'][1];
            } else {
                $this->error = $ret['error'];
                return FALSE;
            }
            deleteImage(array(
                I('post.old_logo'),
                I('post.old_sm_logo'),
            ));
        }

        // 判断商品属性类型有没有被修改，如被修改，则删除旧的商品属性
        if ($data['type_id'] != I('post.old_type_id')) {
            $goodsAttr_model = M('GoodsAttr');
            $goodsAttr_model->where(array('goods_id' => array('eq', $option['where']['id'])))->delete();
        }
    }

    // 修改后
    protected function _after_update($data, $options) {
        /*         * *******************添加扩展分类************************ */
        $ext_cat_model = M('GoodsCat');
        // 修改前先清空数据
        $ext_cat_model->where(array('goods_id' => array('eq', $options['where']['id'])))->delete();
        // 如果有数据再次添加
        $ext_cat_ids = I('post.ext_cat_id');
        if ($ext_cat_ids) {
            foreach ($ext_cat_ids as $v) {
                if (empty($v)) {
                    continue;
                }
                $ext_cat_model->add(array(
                    'goods_id' => $options['where']['id'],
                    'cat_id' => $v
                ));
            }
        }

        /*         * *******************添加会员价格************************ */
        $mp_model = M('MemberPrice');
        // 修改前先清空数据
        $mp_model->where(array('goods_id' => array('eq', $options['where']['id'])))->delete();
        // 如果有数据再次添加
        $member_price = I('post.member_price');
        if ($member_price) {
            foreach ($member_price as $k => $v) {
                if (empty($v)) {
                    continue;
                }
                $mp_model->add(array(
                    'goods_id' => $options['where']['id'],
                    'level_id' => $k,
                    'price' => $v == '' ? '0.00' : $v
                ));
            }
        }

        /*         * *******************添加属性值************************** */
        $ga_model = M('GoodsAttr');
        $attr_value = I('post.attr_value');
        $attr_price = I('post.attr_price');
        if ($attr_value) {
            foreach ($attr_value as $k => $v) {
                foreach ($v as $k1 => $v1) {
                    if ($v1) {
                        $ga_model->add(array(
                            'goods_id' => $options['where']['id'],
                            'attr_id' => $k,
                            'attr_value' => $v1,
                            'attr_price' => $attr_price[$k][$k1] == '' ? '0.00' : $attr_price[$k][$k1],
                        ));
                    }
                }
            }
        }

        /*         * *******************修改属性值************************** */
        $old_attr_value = I('post.old_attr_value');
        $old_attr_price = I('post.old_attr_price');
        if ($old_attr_value) {
            foreach ($old_attr_value as $k => $v) {
                foreach ($v as $k1 => $v1) {
                    if ($v1) {
                        $ga_model->where(array('id' => array('eq', $k1)))->save(array(
                            'goods_id' => $options['where']['id'],
                            'attr_id' => $k,
                            'attr_value' => $v1,
                            'attr_price' => $old_attr_price[$k][$k1] == '' ? '0.00' : $old_attr_price[$k][$k1],
                        ));
                    }
                }
            }
        }

        /*         * *******************添加商品相册*********************** */
        $gp_model = M('GoodsPics');
        $goods_pics = $_FILES['pics'];
        $pics = array();
        foreach ($goods_pics['name'] as $k => $v) {
            if (!empty($v)) {
                $pics[] = array(
                    'name' => $goods_pics['name'][$k],
                    'type' => $goods_pics['type'][$k],
                    'tmp_name' => $goods_pics['tmp_name'][$k],
                    'error' => $goods_pics['error'][$k],
                    'size' => $goods_pics['size'][$k],
                );
            }
        }
        $_FILES = $pics;
        foreach ($pics as $k => $v) {
            $ret = uploadOne($k, 'Goods', $thumb = array(
                array(150, 150, 2),
            ));
            if ($ret['ok'] == 1) {
                $gp_model->add(array(
                    'pic' => $ret['images'][0],
                    'sm_pic' => $ret['images'][1],
                    'goods_id' => $options['where']['id'],
                ));
            }
        }
    }

    // 删除前
    protected function _before_delete($option) {
        if (is_array($option['where']['id'])) {
            $this->error = '不支持批量删除';
            return FALSE;
        }
        $images = $this->field('logo,sm_logo')->find($option['where']['id']);
        deleteImage($images);

        /*         * ************************删除商品其他信息****************************** */
        // 删除扩展属性
        $model = M('GoodsCat');
        $model->where(array('goods_id' => array('eq', $option['where']['id'])))->delete();
        // 删除会员价格
        $model = M('MemberPrice');
        $model->where(array('goods_id' => array('eq', $option['where']['id'])))->delete();
        // 删除商品属性
        $model = M('GoodsAttr');
        $model->where(array('goods_id' => array('eq', $option['where']['id'])))->delete();
        // 删除商品图片
        $model = M('GoodsPics');
        $pics = $model->field('pic,sm_pic')->where(array('goods_id' => array('eq', $option['where']['id'])))->select();
        foreach ($pics as $p) {
            deleteImage($p);
        }
        $model->where(array('goods_id' => array('eq', $option['where']['id'])))->delete();
    }

    /*     * ********************************** 其他方法 ******************************************* */
    /*
     * 获取疯狂抢购商品
     */

    public function getCrazyGoods($limit = 5) {
        return $this->where(array(
                    'is_delete' => array('eq', 0), // 商品不能在回收站中
                    'is_on_sale' => array('eq', 1), // 上架商品
                    'is_promote' => array('eq', 1), // 促销商品
                    'promote_start_time' => array('lt', time()), // 开始时间
                    'promote_end_time' => array('gt', time()), // 结束时间
                ))->order('sort_num desc')->limit($limit)->select();
    }

    /*
     * 获取热卖商品
     */

    public function getHotGoods($limit = 5) {
        return $this->where(array(
                    'is_delete' => array('eq', 0), // 商品不能在回收站中
                    'is_on_sale' => array('eq', 1), // 上架商品
                    'is_hot' => array('eq', 1), // 促销商品
                ))->order('sort_num desc')->limit($limit)->select();
    }

    /*
     * 获取精品商品
     */

    public function getBestGoods($limit = 5) {
        return $this->where(array(
                    'is_delete' => array('eq', 0), // 商品不能在回收站中
                    'is_on_sale' => array('eq', 1), // 上架商品
                    'is_best' => array('eq', 1), // 促销商品
                ))->order('sort_num desc')->limit($limit)->select();
    }

    /*
     * 获取新品商品
     */

    public function getNewGoods($limit = 5) {
        return $this->where(array(
                    'is_delete' => array('eq', 0), // 商品不能在回收站中
                    'is_on_sale' => array('eq', 1), // 上架商品
                    'is_new' => array('eq', 1), // 促销商品
                ))->order('sort_num desc')->limit($limit)->select();
    }

    /**
     * 获取商品会员价格
     */
    public function getMemberPrice($id) {
        $goods = $this->where(array('id' => array('eq', $id)))->find();
        // 如有促销价获取促销价
        if ($goods['is_promote'] == '1' && $goods['promote_start_time'] <= time() && $goods['promote_end_time'] >= time()) {
            return $goods['promote_price'];
        }


        // 如已登录则获取会员价格
        if (session('member')) {
            $user_id = session('member.id');
            $member_model = M('Member');
            $jyz = $member_model->where(array('id' => array('eq', $user_id)))->getField('jyz');
            $member_level_model = M('MemberLevel');
            $level = $member_level_model->where("$jyz BETWEEN bottom_num and top_num")->find();
            $member_price_model = M('MemberPrice');
            $memberPrice = $member_price_model->where(array('goods_id' => array('eq', $id), 'level_id' => array('eq', $level['id'])))->find();
            $member_price = $memberPrice['price'] == '-1.00' ? ($level['rate'] / 100 * $goods['shop_price']) : $memberPrice['price'];
            return $member_price;
        }
    }

}
