$(function(){
		var a=$("#regform").validate({
		rules:{
			ordername:{
				required:true,
				chkUserName:true,
				minlength:1,
				maxlength:12
			},
			orderphone:{
				required:true,
				number:true,
				minlength:11,
				maxlength:14
			},
			orderadd:{
				required:true,
				minlength:1,
				maxlength:30
			}
		},
		messages:{
			username:{
				required:"请输入姓名",
				minlength:"姓名长度不够",
				maxlength:"姓名长度不能超过16",
				chkUserName:"请使用[数字/字母/中划线/下划线]！"
			},
			orderphone:{
				required:"请输入手机",
				minlength:"请输入正确的手机号码",
				maxlength:"请输入正确的手机号码",
				number:"请输入正确的手机号码"
			},
			orderadd:{
				required:"请输入送餐地址",
				minlength:"送餐地址长度不够",
				maxlength:"送餐地址长度不能超过30"
			}
			
			
			
		},
		showErrors:function(b,d){
			
			if (d&&d.length>0) {
				$.each(d,function(e,g){
					var f=$(g.element);
				
					$(f).next().show();
				})
			}else {
				var c=$(this.currentElements);
				
				$(c).next().hide();
				
			}
		},
		submitHandler:function(){
			var b=$("#regform");
			var d=$("#btx");
			if(d.hasClass("disabled")){return}
			var c={
				ordername:$("input[name='ordername']",b).val(),
				orderphone:$("input[name='orderphone']",b).val(),
         
                shopid:$('#ordershop').val(),
                address:$("input[name='orderadd']",b).val(),
                note:$("input[name='ordernote']",b).val(),
				__hash__:$("input[name='__hash__']",b).val()
			};
			d.addClass("disabled");
			$.post("/index.php/api/wap/suborder",c,function(e){
				d.removeClass("disabled");
				if(e.error==0){
					//成功
				
					alert('下单成功');
					window.location.href=jumpurl;
				}else{
					//失败
					alert(e.msg);
					
				}
			},"json");
			return false;
		}
	});
			
	$.validator.addMethod("chkUserName",
		function(c){
			var b=/^[0-9a-zA-Z\u4E00-\u9FA5]+$/;
			return b.test(c)
		},"请使用[数字/字母/中文]！");

	$.validator.addMethod("isMobilPhone",
		function(d){
			var b=/((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)/;
			var c=new RegExp(b);
			return c.test(d)
		},"不是有效的手机号码")

});