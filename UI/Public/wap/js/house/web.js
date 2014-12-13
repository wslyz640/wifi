/*
Powered by ly200.com		http://www.ly200.com
广州联雅网络科技有限公司		020-83226791
*/

var web_obj={
	page_init:function(){
		web_obj.a_label('body');	//A连接处理
		
		$('#header li').css({width:100/$('#header li').size()-0.001+'%'});
		$('#footer li').css({width:100/$('#footer li').size()-0.001+'%'}).children('div').data('display', '0').click(function(){
			$('#footer dl').slideUp(100);
			if($(this).data('display')==0){
				$(this).siblings('dl').slideDown(100);
				$('#footer li div').data('display', '0');
				$('#footer a').removeClass('cur');
				$(this).addClass('cur').data('display', '1');
			}else{
				$('#footer li div').data('display', '0');
				$('#footer a').removeClass('cur');
			}
		});
		$('#footer a').each(function(){
			$(this).click(function(){
				$('#footer dl').slideUp(100);
				$('#footer li div').data('display', '0');
			});
		});
		$('#footer li>a').each(function(){
			$(this).click(function(){
				$('#footer a, #footer li div').removeClass('cur');
				$(this).addClass('cur');
			});
		});
	},
	
	a_label:function(range){
		var range=(typeof(arguments[0])!='string')?'body':arguments[0];
		$(range+' a').attr('target', '_self').filter('[ajax_url]').click(function(){
			$('#web_page_contents_loading').css({
				left:$(window).width()/2-45,
				top:$(window).height()/2-45
			}).show();
			$('#web_page_contents').load($(this).attr('ajax_url').replace('#', '')+'ajax/', function(){
				web_obj.a_label('#web_page_contents');
				$('#web_page_contents_loading').hide();
				window.scroll(0, 0);
			});
		});
		
		$(range+' a').each(function(index, item){
			var url=$(item).attr('href');
			if(url && url.indexOf('tel:')==0){
				var b=navigator.userAgent.match(/i(Pod|Pad|Phone)\;.*\sOS\s([\_0-9]+)/);
				
				if(!b){//非iphone，电话链接
					$(item).on('click',function(e){
						if(confirm('你确定拨打'+url.replace('tel:', '')+'吗?')){
							_q = function(s, context){if (context && typeof context === 'string'){ try{context = _q(context);}catch(ex){console.log(ex);return;} } return (context||document).querySelector(s);};
							function _removeClass(obj, clsName) { if (typeof obj==='string'){try{obj = _q(obj);}catch(ex){console.log(ex);return;} } var re = new RegExp('(^|\\s)+('+clsName+')(\\s|$)+', 'g'); try{obj.className = obj.className.replace(re, "$1$3");}catch(ex){} re = null; }
							function _addClass(obj, clsName) { if (typeof obj==='string'){try{obj = _q(obj);}catch(ex){console.log(ex);return;} } _removeClass(obj, clsName); obj.className = _trim(obj.className+" "+clsName); }
							var yes = _q('.confirm .yes');
							var yesClk = null;
							
							yesClk = function(e){
								location.href = url;
								return false;
							};
							yes.addEventListener('click', yesClk);
							_addClass(yes, 'autotel');
							/*e.preventDefault();
							window.setTimeout(function(){location.href=url;}, 1000);
							return false;*/
						}
					}).addClass('autotel');
				}
			}
		});
	}
}