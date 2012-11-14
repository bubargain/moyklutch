// JScript source code
var Http_Request2;//Ajax请求对象
var XMLDocument2;//Ajax返回数据对象
function AjaxCls()
{
   this.sendUrl="";//URL请求地址
   //获得Ajax请求对象的方法
   this.getRequest=function()
   {
       Http_Request2=null;
       if(window.XMLHttpRequest)
       {
          Http_Request2=new XMLHttpRequest();
          if(Http_Request2.overrideMimeType)
             Http_Request2.overrideMimeType("text/xml");
       }
       else
       {
          if(window.ActiveXObject)
          {
             try
             {
                Http_Request2=new ActiveXObject("Msxml2.XMLHTTP");
             }
             catch(e)
             {
                try
                {
                   Http_Request2=new ActiveXObject("Microsoft.XMLHTTP");
                }
                catch(e)
                {
                   Http_Request2=null;
                }
             }
          }
       }
   };
   //获得Ajax相应对象的方法
   this.getXMLResponse=function()
   {
       Http_Request2.onreadystatechange=alertContents2;
       Http_Request2.open("get",this.sendUrl,true);
       //alert(this.sendUrl);
       Http_Request2.send(null);
   };
}
//创建Ajax对象
var oAjax2=new AjaxCls();

