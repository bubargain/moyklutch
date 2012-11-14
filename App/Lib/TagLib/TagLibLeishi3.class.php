<?php
//标签
class TagLibLeishi extends TagLib{

	protected $tags=array(
			'news'=>array('attr'=>'name,limit,order,where,result,sql,tags','level'=>3),
			'ticket'=>array('attr'=>'name,limit,order,where,result,sql,tags,trade_id','level'=>3),
			'shop'=>array('attr'=>'name,limit,order,where,result,sql,tags','level'=>3),
			'cate'=>array('attr'=>'name,limit,order,where,result,sql,tags,type','level'=>3),
			'area'=>array('attr'=>'name,limit,order,where,result,sql,tags','level'=>3),
			'location'=>array('attr'=>'name,limit,order,where,result,sql,tags','level'=>3),
			'huodong'=>array('attr'=>'name,limit,order,where,result,sql,tags,type','level'=>3),
			'partner'=>array('attr'=>'name,limit,order,where,result,sql,tags,type','level'=>3),
		);
	public function _ticket($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'ticket');
			 $result = !empty($tag['result'])?$tag['result']:'ticket';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $tag['name']='Ticket';
		 if($tag['name']){
			 $sql = "M('{$tag['name']}')->";
			 $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
			 $str="status=1";
			 if($tag['tags']){
				 $str.=" and keyword like '%{$tag['tags']}%'";
				 }
			 if($tag['trade_id']){
				 $tag['trade_id']   = $this->autoBuildVar($tag['trade_id']);
				 $str.=" and trade_id =$tag[trade_id]";
				 }
             if ($tag['score'])
             {
                 $str.="and score>1";
             }
             if ($tag['free'])
             {
                $str .= "and score=0";
             }
			     $sql.= "where(\"$str\")->";
			 if($tag['where']){
				 $sql .= ($tag['where'])?"where({$tag['where']})->":'';
				 }
				 $sql .= ($tag['limit'])?"limit({$tag['limit']})->":'';
				 $sql .= "select()";
			   }else{
			      if (!$tag['sql']) return '';
				  $sql .= "M()->query({$tag['sql']})";
			 }
		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }

	public function _news($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'news');
			 $result = !empty($tag['result'])?$tag['result']:'news';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $tag['name']='news';
		 if($tag['name']){
			 $sql = "M('{$tag['name']}')->";
			 $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
			 if($tag['tags']){
				$str="tags like '%{$tag['tags']}%'";
				$sql .= ($tag['tags'])?"where(\"{$str}\")->":'';
				 }else{
				$sql .= ($tag['where'])?"where(\'".$tag['where']."\')->":'';
				 }
			 $sql .= ($tag['limit'])?"limit({$tag['limit']})->":'';
			 $sql .= "select()";
			   }else{
			      if (!$tag['sql']) return '';
				  $sql .= "M()->query({$tag['sql']})";
			 }
		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }

	public function _shop($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'shop');
			 $result = !empty($tag['result'])?$tag['result']:'shop';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $tag['name']='trade';
		 if($tag['name']){
			 $sql = "M('{$tag['name']}')->";
			 $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
			 if($tag['tags']){
				$str="tags like '%{$tag['tags']}%'";
				$sql .= ($tag['tags'])?"where(\"{$str}\")->":'';
				 }else{
				$sql .= ($tag['where'])?"where({$tag['where']})->":'';
				 }
			 $sql .= ($tag['limit'])?"limit({$tag['limit']})->":'';
			 $sql .= "select()";
			   }else{
			      if (!$tag['sql']) return '';
				  $sql .= "M()->query({$tag['sql']})";
			 }
		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }

	public function _cate($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'cate');
			 $result = !empty($tag['result'])?$tag['result']:'cate';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $tag['name']='cate';
		 if($tag['name']){
			 $sql = "M('{$tag['name']}')->";
			 $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
			 $str="1=1";
			 if($tag['type']){
				 $tag['type']   = $this->autoBuildVar($tag['type']);
				    if($tag['type']==0){
					 $str.=" and pid = 0";
					 }else{
					 $str.=" and pid = $tag[type]";
					 }
				 }else{
					 $str.=" and pid > 0";
				 }
			     $sql.= "where(\"$str\")->";
			 if($tag['where']){
				 $sql .= ($tag['where'])?"where({$tag['where']})->":'';
				 }
				 $sql .= ($tag['limit'])?"limit({$tag['limit']})->":'';
				 $sql .= "select()";
			   }else{
			      if (!$tag['sql']) return '';
				  $sql .= "M()->query({$tag['sql']})";
			 }
		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }

	public function _area($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'area');
			 $result = !empty($tag['result'])?$tag['result']:'area';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $tag['name']='area';
		 if($tag['name']){
			 $sql = "M('{$tag['name']}')->";
			 $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
			 $str="1=1";
			 if($tag['type']){
				     $tag['type']   = $this->autoBuildVar($tag['type']);
					 $str.=" and pid = $tag[type]";
				 }else{
					 $str.=" and pid = 0";
				 }
			     $sql.= "where(\"$str\")->";
			 if($tag['where']){
				 $sql .= ($tag['where'])?"where({$tag['where']})->":'';
				 }
				 $sql .= ($tag['limit'])?"limit({$tag['limit']})->":'';
				 $sql .= "select()";
			   }else{
			      if (!$tag['sql']) return '';
				  $sql .= "M()->query({$tag['sql']})";
			 }
		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }

	public function _location($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'location');
			 $result = !empty($tag['result'])?$tag['result']:'location';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $tag['name']='location';
		 if($tag['name']){
			 $sql = "M('{$tag['name']}')->";
			 $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
			 if($tag['tags']){
				$str="tags like '%{$tag['tags']}%'";
				$sql .= ($tag['tags'])?"where(\"{$str}\")->":'';
				 }else{
				$sql .= ($tag['where'])?"where({$tag['where']})->":'';
				 }
			 $sql .= ($tag['limit'])?"limit({$tag['limit']})->":'';
			 $sql .= "select()";
			   }else{
			      if (!$tag['sql']) return '';
				  $sql .= "M()->query({$tag['sql']})";
			 }
		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }


	 public function _huodong($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'huodong');
			 $result = !empty($tag['result'])?$tag['result']:'huodong';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $tag['name']='huodong';
		 if($tag['name']){
			 $sql = "M('{$tag['name']}')->";
			 $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
			 $str="1=1";
			 if($tag['type']){
				     //$tag['type']   = $this->autoBuildVar($tag['type']);
					 $str.=" and typeid = $tag[type]";
				 }else{
					 $str.=" and typeid = 0";
					 }
				 $sql.= "where(\"$str\")->";
			 if($tag['where']){
				 $sql .= ($tag['where'])?"where({$tag['where']})->":'';
				 }
				 $sql .= ($tag['limit'])?"limit({$tag['limit']})->":'';
				 $sql .= "select()";
			   }else{
			      if (!$tag['sql']) return '';
				  $sql .= "M()->query({$tag['sql']})";
			 }
		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }

	 public function _partner($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'partner');
			 $result = !empty($tag['result'])?$tag['result']:'partner';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $tag['name']='partner';
		 if($tag['name']){
			 $sql = "M('{$tag['name']}')->";
			 $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
			 $str="1=1";
			 if($tag['type']){
				     //$tag['type']   = $this->autoBuildVar($tag['type']);
					 $str.=" and typeid = $tag[type]";
				 }else{
					 $str.=" and typeid = 0";
					 }
				 $sql.= "where(\"$str\")->";
			 if($tag['where']){
				 $sql .= ($tag['where'])?"where({$tag['where']})->":'';
				 }
				 $sql .= ($tag['limit'])?"limit({$tag['limit']})->":'';
				 $sql .= "select()";
			   }else{
			      if (!$tag['sql']) return '';
				  $sql .= "M()->query({$tag['sql']})";
			   }
		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }
}
?>