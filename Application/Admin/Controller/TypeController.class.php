<?php
namespace Admin\Controller;
class TypeController extends PlateformController 
{
    public function add()
    {
    	if(IS_POST)
    	{
    		$model = D('Admin/Type');
    		if($model->create(I('post.'), 1))
    		{
    			if($id = $model->add())
    			{
    				$this->success('添加成功！', U('lst?p='.I('get.p')));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}

		$this->setPageInfo('添加商品类型', '商品类型列表', U('lst?p='.I('get.p')));
		$this->display();
    }
    public function edit()
    {
    	$id = I('get.type_id');
    	if(IS_POST)
    	{
    		$model = D('Admin/Type');
    		if($model->create(I('post.'), 2))
    		{
    			if($model->save() !== FALSE)
    			{
    				$this->success('修改成功！', U('lst', array('p' => I('get.p', 1))));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}
    	$model = M('Type');
    	$data = $model->find($id);
    	$this->assign('data', $data);

		$this->setPageInfo('修改商品类型', '商品类型列表', U('lst?p='.I('get.p')));
		$this->display();
    }
    public function delete()
    {
    	$model = D('Admin/Type');
    	if($model->delete(I('get.type_id', 0)) !== FALSE)
    	{
    		$this->success('删除成功！', U('lst', array('p' => I('get.p', 1))));
    		exit;
    	}
    	else 
    	{
    		$this->error($model->getError());
    	}
    }
    public function lst()
    {
    	$model = D('Admin/Type');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
    	));

		$this->setPageInfo('商品类型列表', '添加商品类型', U('add'));
    	$this->display();
    }
}