// JScript source code
var Http_Request;//Ajax请求对象
var XMLDocument;//Ajax返回数据对象
function AjaxCls()
{
   this.sendUrl="";//URL请求地址
   //获得Ajax请求对象的方法
   this.getRequest=function()
   {
       Http_Request=null;
       if(window.XMLHttpRequest)
       {
          Http_Request=new XMLHttpRequest();
          if(Http_Request.overrideMimeType)
             Http_Request.overrideMimeType("text/xml");
       }
       else
       {
          if(window.ActiveXObject)
          {
             try
             {
                Http_Request=new ActiveXObject("Msxml2.XMLHTTP");
             }
             catch(e)
             {
                try
                {
                   Http_Request=new ActiveXObject("Microsoft.XMLHTTP");
                }
                catch(e)
                {
                   Http_Request=null;
                }
             }
          }
       }
   };
   //获得Ajax相应对象的方法
   this.getXMLResponse=function()
   {
       Http_Request.onreadystatechange=alertContents;
       Http_Request.open("get",this.sendUrl,true);
       //alert(this.sendUrl);
       Http_Request.send(null);
   };
}
//创建Ajax对象
var oAjax=new AjaxCls();

