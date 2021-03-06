<?php
//标签
class TagLibLeishi extends TagLib{

	protected $tags=array(
			// 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
			'query'=>array('attr'=>'sql,result','close'=>0),
			'data'=>array('attr'=>'name,field,limit,order,where,table,result,gc,','level'=>2),
            'datalist'=>array('attr'=>'name,field,limit,order,where,table,result,key,mod,gc','level'=>3),
			'value'=>array('attr'=>'name,table,where,type,field','alias'=>'max,min,avg,sum,count','close'=>0),
            'call'=>array('attr'=>'name,id,result'),
			'news'=>array('attr'=>'name,limit,order,where,result,sql,tags','level'=>3),
			'ticket'=>array('attr'=>'name,limit,order,where,result,sql,tags,trade_id,cate_id','level'=>3),
			'shop'=>array('attr'=>'name,limit,order,where,result,sql,tags','level'=>3),
			'cate'=>array('attr'=>'name,limit,order,where,result,sql,tags,type,pid','level'=>3),
			'area'=>array('attr'=>'name,limit,order,where,result,sql,tags,type,pid','level'=>3),
			'location'=>array('attr'=>'name,limit,order,where,result,sql,tags','level'=>3),
			'huodong'=>array('attr'=>'name,limit,order,where,result,sql,tags,type','level'=>3),
			'partner'=>array('attr'=>'name,limit,order,where,result,sql,tags,type','level'=>3),
			'cards'=>array('attr'=>'name,limit,order,where,result,sql,tags,type','level'=>3),
			'tag'=>array('attr'=>'name,limit,order,where,result,sql,tags','level'=>3),
                        'tradebranch'=>array('attr'=>'name,limit,order,where,result,sql','level'=>3),
		);

    // sql查询
    public function _query($attr,$content) {
        $tag        =	$this->parseXmlAttr($attr,'query');
        $sql   = $tag['sql'];
        $result      =  !empty($tag['result'])?$tag['result']:'result';
        $parseStr  =  '<?php $'.$result.' = M()->query("'.$sql.'");';
        $parseStr .=  'if($'.$result.'):?>'.$content;
        $parseStr .= "<?php endif;?>";
        return $parseStr;
    }

  
    // 获取字段值 包括统计数据
    // type 包括 getField count max min avg sum
    public function _value($attr,$content,$type='getField'){
        $tag        =	$this->parseXmlAttr($attr,'value');
		$name	=	!empty($tag['name'])?$tag['name']:'Ticket';
        $type = !empty($tag['type'])?$tag['type']:$type;
        $filter  =  !empty($tag['filter'])?$tag['filter']:'';
        $parseStr   =  '<?php echo '.$filter.'(M("'.$name.'")';
        if(!empty($tag['table'])) {
            $parseStr .= '->table("'.$tag['table'].'")';
        }
        if(!empty($tag['where'])){
            $parseStr .= '->where("'.$tag['where'].'")';
        }
        $parseStr .= '->'.$type.'("'.$tag['field'].'"));?>';
        return $parseStr;
    }


    public function _count($attr,$content){
        return $this->_value($attr,$content,'count');
    }
    public function _sum($attr,$content){
        return $this->_value($attr,$content,'sum');
    }
    public function _max($attr,$content){
        return $this->_value($attr,$content,'max');
    }
    public function _min($attr,$content){
        return $this->_value($attr,$content,'min');
    }
    public function _avg($attr,$content){
        return $this->_value($attr,$content,'avg');
    }

    // 调用模型类的方法
    public function _call($attr,$content){
        $tag        =	$this->parseXmlAttr($attr,'call');
		$name	=	!empty($tag['name'])?$tag['name']:'Article';
        $result      =  !empty($tag['result'])?$tag['result']:'data';
        $method = $tag['method'];
        $vars = !empty($tag['vars'])?$tag['vars']:'';
        $parseStr   =  '<?php parse_str("'.$vars.'",$_var_);$'.$result.' =D("'.$name.'")';
        $parseStr   .= '->'.$method.'($_var_);';
        $parseStr .=  'if($'.$result.'):?>'.$content;
        $parseStr .= "<?php endif;?>";
        return $parseStr;
    }

    public function _data($attr,$content){
        $tag        =	$this->parseXmlAttr($attr,'data');
		$name	=	!empty($tag['name'])?$tag['name']:'Ticket';
        $result      =  !empty($tag['result'])?$tag['result']:'vo';
        if(!empty($tag['name'])) {
            $parseStr   =  '<?php $'.$result.' =M("'.$name.'")';
        }
//        if(!empty($tag['table'])) {
//            $parseStr .= '->table("'.$tag['table'].'")';
//        }
        if(!empty($tag['where'])){
            $parseStr .= '->where("'.$tag['where'].'")';
        }
        if(!empty($tag['order'])){
            $parseStr .= '->order("'.$tag['order'].'")';
        }
        if(!empty($tag['limit'])){
            $parseStr .= '->limit("'.$tag['limit'].'")';
        }
        if(!empty($tag['field'])){
            $parseStr .= '->field("'.$tag['field'].'")';
        }
        $parseStr .= '->find();if($'.$result.'):$id=$'.$result.'["id"];?>'.$content;
        if(!empty($tag['gc'])) {
            $parseStr .= '<?php unset($'.$result.');?>';
        }
        $parseStr .= "<?php endif;?>";
        return $parseStr;
    }

    public function _datalist($attr,$content)
    {
        $tag        =	$this->parseXmlAttr($attr,'datalist');
		$name	=	!empty($tag['name'])?$tag['name']:'Ticket';
        $result      =  !empty($tag['result'])?$tag['result']:'vo';
        $key     =   !empty($tag['key'])?$tag['key']:'i';
        $mod    =   isset($tag['mod'])?$tag['mod']:'2';
        if(!empty($tag['name'])) {
            $parseStr   =  '<?php $_result =M("'.$name.'")';
        }
        if(!empty($tag['where'])){
            $parseStr .= '->where("'.$tag['where'].'")';
        }
        if(!empty($tag['order'])){
            $parseStr .= '->order("'.$tag['order'].'")';
        }
        if(!empty($tag['limit'])){
            $parseStr .= '->limit("'.$tag['limit'].'")';
        }
        $parseStr .= '->select();if($_result):$'.$key.'=0;foreach($_result as $key=>$'.$result.'): ';
        $parseStr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		$parseStr .=  $content;
        $parseStr .= '<?php  endforeach; endif?>';
		if(!empty($tag['gc'])) {
            $parseStr .= '<?php unset($'.$result.');?>';
        }
        return $parseStr;
    }

	public function _ticket($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'ticket');
			 $result = !empty($tag['result'])?$tag['result']:'vo';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $name=!empty($tag['name'])?$tag['name']:'Ticket';
			 $order   =  empty($tag['order'])?'id desc':$tag['order'];
             $time = time();   //修改 hedong 2011-6-3
			 $sql = "D('$name')->getlist(";
			 $where="where ticket.status=1 and ticket.close_time > $time"; //修改 hedong 2011-6-3
			 if($tag['tags']){
				 $where.=" and ticket.tags like '%{$tag['tags']}%'";
				 }
			 if($tag['where']){
					 $where.=' and ' .$tag['where'];
				 }
			 if($tag['trade_id']){
				 $trade_id  = $this->autoBuildVar($tag['trade_id']);
					 if($tag['order']=="trade_id"){
						$where.=" and ticket.trade_id <=$trade_id";
					 }else{
						$where.=" and ticket.trade_id =$trade_id";
					 }
				 }
			 if($tag['cate_id']){
				 $cate_id  = $this->autoBuildVar($tag['cate_id']);
				 $where.=" and ticket.cate_id in(SELECT id from " . C('DB_PREFIX') . "cate where pid=$cate_id)";
				 }
			     $sql.= "\"$where\",";
				 $sql .= ($tag['order'])?"'order by ticket.$tag[order]',":"'',";
				 $limit=($tag['limit'])?"'$tag[limit]',":"'',";
				 $sql.= $limit."'')";
			  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
			  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
			  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
			  $parsestr .= $content;
			  $parsestr .= '<?php endforeach; endif;?>';

              return $parsestr;
	}

	public function _news($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'news');
			 $result = !empty($tag['result'])?$tag['result']:'vo';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $name=!empty($tag['name'])?$tag['name']:'News';

			 $sql = "M('$name')->";
			 $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
			 if($tag['tags']){
				$str="tags like '%{$tag['tags']}%'";
				$sql .= ($tag['tags'])?"where(\"{$str}\")->":'';
				 }else{
				$sql .= ($tag['where'])?"where({$tag['where']})->":'';
				 }
			 $sql .= ($tag['limit'])?"limit({$tag['limit']})->":'';
			 $sql .= "select()";

		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }

	public function _shop($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'shop');
			 $result = !empty($tag['result'])?$tag['result']:'vo';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $name=!empty($tag['name'])?$tag['name']:'Trade';
			 $sql = "M('$name')->";
			 $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
			 if($tag['tags']){
				$str="tags like '%{$tag['tags']}%'";
				$sql .= ($tag['tags'])?"where( \"{$str} and status>0\")->":"where('status >0')->";
				 }else{
				$sql .= ($tag['where'])?"where({$tag['where']} and status>0)->":"where('status >0')->";
				 }
             if($tag['type']){
				$str="tags pid ={$tag['type']}";
				$sql .= ($tag['type'])?"where( \"{$str} and status>0\")->":"where('status >0')->";
				 }else{
				$sql .= ($tag['where'])?"where({$tag['where']} and status>0)->":"where('status >0')->";
				 }
                      
			 $sql .= ($tag['limit'])?"limit({$tag['limit']})->":'';
			 $sql .= "select()";

		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }

	public function _cate($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'cate');
			 $result = !empty($tag['result'])?$tag['result']:'vo';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $name=!empty($tag['name'])?$tag['name']:'Cate';
			 $sql = "M('$name')->";
			 $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
			 $str="1=1";
			 if($tag['type']){
				     $tag['type']   = $this->autoBuildVar($tag['type']);
					 $str.=" and pid = $tag[type]";
				 }else{
					 $str.=" and pid = 0";
				 }
             if($tag['pid']){
				 $str="pid = $tag[pid]";
				 }
			 $sql.= "where(\"$str\")->";
			 if($tag['where']){
				 $sql .= ($tag['where'])?"where({$tag['where']})->":'';
				 }
				 $sql .= ($tag['limit'])?"limit({$tag['limit']})->":'';
				 $sql .= "select()";

		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }

	public function _area($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'area');
			 $result = !empty($tag['result'])?$tag['result']:'vo';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $name=!empty($tag['name'])?$tag['name']:'Area';

			 $sql = "M('$name')->";
			 $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
			 $str="1=1";
			 if($tag['type']){
				    // $tag['type']   = $this->autoBuildVar($tag['type']);
					 $str.=" and pid = $tag[type]  and status>0";
				 }else{
					 $str.=" and pid = 0  and status>0";
				 }
			 if($tag['pid']){
				 $str="pid > 0 and status>0";
				 }
			     $sql.= "where(\"$str\")->";
			 if($tag['where']){
				 $sql .= ($tag['where'])?"where({$tag['where']})->":"";
				 }
				 $sql .= ($tag['limit'])?"limit({$tag['limit']})->":'';
				 $sql .= "select()";

		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }

	public function _location($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'location');
			 $result = !empty($tag['result'])?$tag['result']:'vo';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $name= !empty($tag['name'])?$tag['name']:'Location';

			 $sql = "M('$name')->";
			 $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
			 if($tag['tags']){
				$str="tags like '%{$tag['tags']}%'";
				$sql .= ($tag['tags'])?"where(\"{$str}\")->":'';
				 }else{
				$sql .= ($tag['where'])?"where({$tag['where']})->":'';
				 }
			 $sql .= ($tag['limit'])?"limit({$tag['limit']})->":'';
			 $sql .= "select()";


		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }


	 public function _huodong($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'huodong');
			 $result = !empty($tag['result'])?$tag['result']:'vo';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $name=!empty($tag['name'])?$tag['name']:'Huodong';

			 $sql = "M('$name')->";
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

		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }

	 public function _partner($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'partner');
			 $result = !empty($tag['result'])?$tag['result']:'vo';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			$name=!empty($tag['name'])?$tag['name']:'Partner';

			 $sql = "M('$name')->";
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

		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }

	 public function _cards($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'cards');
			 $result = !empty($tag['result'])?$tag['result']:'vo';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			$name=!empty($tag['name'])?$tag['name']:'Cards';

			 $sql = "M('$name')->";
			 $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
			 $str="1=1";
				 $sql.= "where(\"$str\")->";
			 if($tag['where']){
				 $sql .= ($tag['where'])?"where({$tag['where']})->":'';
				 }
				 $sql .= ($tag['limit'])?"limit({$tag['limit']})->":'';
				 $sql .= "select()";

		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }

	public function _tag($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'tag');
			 $result = !empty($tag['result'])?$tag['result']:'vo';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $name= !empty($tag['name'])?$tag['name']:'Tag';

			 $sql = "M('$name')->";
			 $sql .= ($tag['order'])?"order('{$tag['order']} desc')->":'';
			 if($tag['tags']){
				$str="tags like '%{$tag['tags']}%'";
				$sql .= ($tag['tags'])?"where(\"{$str}\")->":'';
				 }else{
				$sql .= ($tag['where'])?"where({$tag['where']})->":'';
				 }
			 $sql .= ($tag['limit'])?"limit({$tag['limit']})->":'';
			 $sql .= "select()";


		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }
        public function _tradebranch($attr,$content){
			 $tag=$this->parseXmlAttr($attr,'tradebranch');
			 $result = !empty($tag['result'])?$tag['result']:'vo';
			 $key = !empty($tag['key'])?$tag['key']:'i';
			 $mod = isset($tag['mod'])?$tag['mod']:'2';
			 $name=!empty($tag['name'])?$tag['name']:'TradeBranch';

			 $sql = "M('$name')->";
			 $sql .= ($tag['order'])?"order('{$tag['order']}')->":'';
			 $str="1=1";
			 if($tag['type']){
				       // $tag['type']   = $this->autoBuildVar($tag['type']);
					 $str.=" and area = $tag[type]";
				 }else{
					 $str.=" and area >0";
				 }
			 if($tag['pid']){
				 $str="pid > 0";
				 }
			     $sql.= "where(\"$str\")->";
			 if($tag['where']){
				 $sql .= ($tag['where'])?"where({$tag['where']})->":'';
				 }
				 $sql .= ($tag['limit'])?"limit({$tag['limit']})->":'';
				 $sql .= "select()";

		  $parsestr = '<?php $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		  $parsestr .= 'foreach($_result as $key=>$'.$result.'):';
		  $parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		  $parsestr .= $content;
		  $parsestr .= '<?php endforeach; endif;?>';
		  return $parsestr;

	  }


}
?>