<?php
namespace Admin\Model;
use Think\Model;
class CategoryModel extends Model 
{
	protected $insertFields = array('cat_name','parent_id','search_attr_id');
	protected $updateFields = array('id','cat_name','parent_id','search_attr_id');
	protected $_validate = array(
        	array('cat_name', 'require', '分类名称不能为空！', 1, 'regex', 3),
		array('cat_name', '1,30', '分类名称的值最长不能超过 30 个字符！', 1, 'length', 3),
		array('parent_id', 'number', '上级分类的ID，0：代表顶级必须是一个整数！', 2, 'regex', 3),
	);
	/************************************* 递归相关方法 *************************************/
	public function getTree()
	{
		$data = $this->select();
		return $this->_reSort($data);
	}
	private function _reSort($data, $parent_id=0, $level=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$v['level'] = $level;
				$ret[] = $v;
				$this->_reSort($data, $v['id'], $level+1, FALSE);
			}
		}
		return $ret;
	}
	public function getChildren($id)
	{
		$data = $this->select();
		return $this->_children($data, $id);
	}
	private function _children($data, $parent_id=0, $isClear=TRUE)
	{
		static $ret = array();
		if($isClear)
			$ret = array();
		foreach ($data as $k => $v)
		{
			if($v['parent_id'] == $parent_id)
			{
				$ret[] = $v['id'];
				$this->_children($data, $v['id'], FALSE);
			}
		}
		return $ret;
	}
	/************************************ 其他方法 ********************************************/
	public function _before_delete($option)
	{
		// 先找出所有的子分类
		$children = $this->getChildren($option['where']['id']);
		// 如果有子分类都删除掉
		if($children)
		{
			$children = implode(',', $children);
			$this->execute("DELETE FROM php34_category WHERE id IN($children)");
		}
	}
        
        // 获取嵌套类别模型
        public function getCateData(){
            $cateData = $this->select();
            $data = array();
            foreach($cateData as $k=>$v){
                if($v['parent_id'] === '0'){
                    foreach($cateData as $k1=>$v1){
                        if($v1['parent_id'] === $v['id']){
                            foreach($cateData as $k2=>$v2){
                                if($v2['parent_id'] === $v1['id']){
                                    $v1['children'][] = $v2;
                                }
                            }
                            $v['children'][] = $v1;
                        }
                    }
                    $data[]=$v;
                }
            }
            return $data;
        }
        
        /**
         * 整理search_attr_id数据
         */
        protected function _before_insert(&$data, $options) {
            $attr = array();
            if(I('post.search_attr_id')){
                foreach(I('post.search_attr_id') as $k=>$v){
                    if($v != ''){
                        $attr[] = $v;
                    }
                }
            }
            $data['search_attr_id'] = implode(',', $attr);
        }
        
        /**
         * 整理search_attr_id数据
         * 更新操作
         */
        protected function _before_update(&$data, $options) {
            $attr = array();
            if(I('post.search_attr_id')){
                foreach(I('post.search_attr_id') as $k=>$v){
                    if($v != ''){
                        $attr[] = $v;
                    }
                }
            }
            $data['search_attr_id'] = implode(',', $attr);
        }
}