/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var index_obj={
	index_init:function(){

		for(i=0; i<web_skin_data.length; i++){
			var obj=$("#web_skin_index div").filter('[rel=edit-'+web_skin_data[i]['Postion']+']');
			if(web_skin_data[i]['ContentsType']==1){
				var dataImg=eval("("+web_skin_data[i]['ImgPath']+")");
				var dataUrl=eval("("+web_skin_data[i]['Url']+")");
				var dataTitle=eval("("+web_skin_data[i]['Title']+")");
				var _banner='<div class="slider"><div class="flexslider"><ul class="slides">';
				for(var k=0; k<dataImg.length; k++){
					if(web_skin_data[i]['NeedLink']==1){
						var h=(dataUrl[k].indexOf('/web/')==-1 || dataUrl[k].slice(-5)=='/web/')?'href':'ajax_url';
						_banner=_banner+'<li><a '+h+'="'+dataUrl[k]+'"><img src="'+dataImg[k]+'" alt="'+dataTitle[k]+'" /></a></li>';
					}else{
						_banner=_banner+'<li><img src="'+dataImg[k]+'" alt="'+dataTitle[k]+'" /></li>';
					}
				}
				var _banner=_banner+'</ul></div></div>';
				
				obj.find('.img').html(_banner);
				obj.find('.flexslider').flexslider({animation:"slide"});
				$('.flex-control-nav, .flex-direction-nav').remove();
			}else{
				var _Url='', h='';
				if(web_skin_data[i]['NeedLink']==1){
					_Url=web_skin_data[i]['Url']?web_skin_data[i]['Url']:'';
					h=(_Url=='' || _Url.indexOf('/web/')==-1 || _Url.slice(-5)=='/web/')?'href':'ajax_url';
				}
				
				var _Img=_Url?'<a '+h+'="'+_Url+'"><img src="'+web_skin_data[i]['ImgPath']+'" /></a>':'<img src="'+web_skin_data[i]['ImgPath']+'" />';
				var _Title=_Url?'<a '+h+'="'+_Url+'">'+web_skin_data[i]['Title']+'</a>':web_skin_data[i]['Title'];
				obj.find('.img').html(_Img);
				obj.find('.text').html(_Title);
			}
		}
		web_obj.a_label('#web_skin_index');	//A连接处理
		
		if($.isFunction(skin_index_init)){
			skin_index_init();	//风格的首页如果有JS，需全部写入本函数，如果直接写在index.php文件，在后台管理首页广告图片时，会把不必要的JS也执行了
		}
	}
}