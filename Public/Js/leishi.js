var URL = '__URL__';
var APP	 =	 '__APP__';
var PUBLIC = '__PUBLIC__';

function showarea(id){
	var area = document.getElementById("leishiarea").childNodes.length;
	for(var i=1;i<area+1;i++){
		if(id==i){
			document.getElementById("showmenu"+id).className="cks";
			document.getElementById("showarea"+id).style.display="block";
			}else{
		    document.getElementById("showmenu"+i).className="";	
			document.getElementById("showarea"+i).style.display="none";	
			}
		}		
	}
	
function showcate(id){
	for(var i=1;i<7;i++){
		if(id==i){
			document.getElementById("catemenu"+i).className="qs1";
			if(document.getElementById("catelist"+i).style.display=="block"){
			    document.getElementById("catelist"+i).style.display="none";
				}else{
				document.getElementById("catelist"+i).style.display="block";
				}
			}else{
			document.getElementById("catemenu"+i).className="qs12";
			document.getElementById("catelist"+i).style.display="none";
			}
		}
	}
function checkvalue(myform){//表单数据验证
	if(myform.content.value == "")
	{
		alert("内容不能为空!");
		myform.content.focus();
		return (false);
	}
	}
	
	
	
function copyToClipBoard(){   
    var clipBoardContent="";   
      clipBoardContent+=this.location.href;   
    window.clipboardData.setData("Text",clipBoardContent);   
    alert("复制成功，请粘贴到你的QQ/MSN上推荐给你的好友");   
}

