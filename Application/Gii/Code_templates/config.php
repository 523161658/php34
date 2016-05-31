return array(
	'tableName' => '<?php echo $___v; ?>',    // 表名
	'tableCnName' => '<?php echo $_tableInfo['comment']; ?>',  // 表的中文名
	'moduleName' => 'Admin',  // 代码生成到的模块
	'digui' => 0,             // 是否无限级（递归）
	'diguiName' => '',        // 递归时用来显示的字段的名字，如cat_name（分类名称）
        'topPriName' => '',    // 父类权限名称
<?php
	$_fields_arr = array();
	$_pk = 'id';
	foreach ($_tableFields as $k => $v)
	{
		if($v['key'] == 'PRI')
		{
			$_pk = $v['field'];
			continue ;
		}
		if($v['field'] == 'addtime')
			continue ;
		if(preg_match('/(image|logo|face|img|pic)/', $v['field']))
			continue ;
		$_fields_arr[] = "'{$v['field']}'";
	}
	$_fields_arr = implode(',', $_fields_arr);
?>
	'pk' => '<?php echo $_pk; ?>',    // 表中主键字段名称
	/********************* 要生成的模型文件中的代码 ******************************/
	'insertFields' => "array(<?php echo $_fields_arr; ?>)",
	'updateFields' => "array('<?php echo $_pk; ?>',<?php echo $_fields_arr; ?>)",
	'validate' => "
<?php foreach ($_tableFields as $k => $v): 
$_chkTime = 2;
if($v['field'] == $_pk || $v['field'] == 'addtime' || preg_match('/(image|logo|face|img|pic)/', $v['field'])){
    continue;
}
if($v['null'] == 'NO' && $v['default'] === NULL):$_chkTime=1; ?>
		array('<?php echo $v['field']; ?>', 'require', '<?php echo $v['comment']; ?>不能为空！', <?php echo $_chkTime; ?>, 'regex', 3),
<?php endif;
if($v['field'] == 'email'): ?>
		array('<?php echo $v['field']; ?>', 'email', '<?php echo $v['comment']; ?>格式不正确！', <?php echo $_chkTime; ?>, 'regex', 3),
<?php endif;
if(strpos($v['type'], 'int') !== FALSE): ?>
		array('<?php echo $v['field']; ?>', 'number', '<?php echo $v['comment']; ?>必须是一个整数！', <?php echo $_chkTime; ?>, 'regex', 3),
<?php endif;
if(strpos($v['type'], 'decimal') !== FALSE): ?>
		array('<?php echo $v['field']; ?>', 'currency', '<?php echo $v['comment']; ?>必须是货币格式！', <?php echo $_chkTime; ?>, 'regex', 3),
<?php endif;
if(strpos($v['type'], 'enum') !== FALSE): ?>
		array('<?php echo $v['field']; ?>', <?php $_s1 = str_replace(array('enum(', ')', "','"), array('','',','), $v['type']);echo $_s1; ?>, \"<?php echo $v['comment']; ?>的值只能是在 <?php echo $_s1; ?> 中的一个值！\", <?php echo $_chkTime; ?>, 'in', 3),
<?php endif;
if(strpos($v['type'], 'varchar') === 0): ?>
		array('<?php echo $v['field']; ?>', '1,<?php $_s1 = str_replace(array('varchar(', ')'), array('',''), $v['type']);echo $_s1; ?>', '<?php echo $v['comment']; ?>的值最长不能超过 <?php echo $_s1; ?> 个字符！', <?php echo $_chkTime; ?>, 'length', 3),
<?php endif;
if(strpos($v['type'], 'char') === 0): ?>
		array('<?php echo $v['field']; ?>', '1,<?php $_s1 = str_replace(array('char(', ')'), array('',''), $v['type']);echo $_s1; ?>', '<?php echo $v['comment']; ?>的值最长不能超过 <?php echo $_s1; ?> 个字符！', <?php echo $_chkTime; ?>, 'length', 3),
<?php endif;
endforeach; ?>
<?php foreach ($_tableFields as $k => $v): 
$_chkTime = 2;
if($v['field'] == $_pk)
	continue ;
if($v['Null'] == 'NO' && $v['default'] === null)
	$_chkTime=1;
if($v['key'] == 'UNI'): ?>
		array('<?php echo $v['field']; ?>', '', '<?php echo $v['comment']; ?>的值已经存在，不能重复添加！', <?php echo $_chkTime; ?>, 'unique', 3),
<?php endif;
endforeach; ?>
	",
	/********************** 表中每个字段信息的配置 ****************************/
	'fields' => array(
<?php foreach ($_tableFields as $k => $v): 
				if($v['field'] == $_pk || $v['field'] == 'addtime' || preg_match('/(sm_|mid_|big_)/', $v['field'])) continue ;?>
		'<?php echo $v['field']; ?>' => array(
			'text' => '<?php echo $v['comment']; ?>',
<?php if(preg_match('/(image|logo|face|img|pic)/', $v['field'])): ?>
			'type' => 'file',
			'thumbs' => array(
				array(350, 350, 2),
				array(150, 150, 2),
				array(50, 50, 2),
			),
			'save_fields' => array('<?php echo $v['field']; ?>', 'big_<?php echo $v['field']; ?>', 'mid_<?php echo $v['field']; ?>', 'sm_<?php echo $v['field']; ?>'),
<?php elseif($v['type'] == 'text'): ?>
			'type' => 'html',
<?php elseif(strpos($v['type'], 'enum') !== FALSE): ?>
			'type' => 'radio',
			'values' => array(
<?php $_arr = explode("','", $v['type']);
				foreach ($_arr as $k1 => $v1):
					$_value = str_replace(array("enum('", "')"), array('',''), $v1);?>
				'<?php echo $_value; ?>' => '<?php echo $_value; ?>',
<?php endforeach; ?>
			),
<?php elseif ($v['field'] == 'password'): ?>
			'type' => 'password',
<?php else: ?>
			'type' => 'text',
<?php endif; ?>
			'default' => '<?php echo $v['default']; ?>',
		),
<?php endforeach; ?>
	),
	/**************** 搜索字段的配置 **********************/
	'search' => array(
<?php foreach ($_tableFields as $k => $v):
	if($v['field'] == $_pk || $v['field'] == 'order_num' || preg_match('/(image|logo|face|img|pic)/', $v['field'])) continue ;
	if(strpos($v['type'], 'char') !== FALSE): ?>
		array('<?php echo $v['field']; ?>', 'normal', '', 'like', '<?php echo $v['comment']; ?>'),
<?php elseif(strpos($v['type'], 'enum') !== FALSE): ?>
		array('<?php echo $v['field']; ?>', 'in', <?php echo str_replace(array('enum(', ')', "','"), array('','',','), $v['type']); ?>, '', '<?php echo $v['comment']; ?>'),
<?php elseif(strpos($v['type'], 'decimal') !== FALSE): ?>
		array('<?php echo $v['field']; ?>', 'between', '<?php echo $v['field']; ?>from,<?php echo $v['field']; ?>to', '', '<?php echo $v['comment']; ?>'),
<?php elseif(strpos($v['type'], 'time') !== FALSE): ?>
		array('<?php echo $v['field']; ?>', 'betweenTime', '<?php echo $v['field']; ?>from,<?php echo $v['field']; ?>to', '', '<?php echo $v['comment']; ?>'),
<?php else: ?>
		array('<?php echo $v['field']; ?>', 'normal', '', 'eq', '<?php echo $v['comment']; ?>'),
<?php endif;
endforeach; ?>
	),
);