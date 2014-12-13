$(function() {
	var name='itemid';
	var arrxx = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
	var loadhx="";
	if(arrxx != null) (loadhx=arrxx[2]);
	if (loadhx==""){
		document.getElementById('orderhide').addEventListener("click", function() { $("#orderhide").hide();$("#ordershow").hide();}, false);
	}
	}
)

function clearorder(){
	
	
	var keys=document.cookie.match(/[^ =;]+(?=\=)/g); 
	if (keys) { 
	for (var i = keys.length; i--;) 
		document.cookie=keys[i]+'=;expires=' + new Date( 0).toUTCString() ;
	}
	$(".bts").each(function(){
		$(this).hide();
		
	});
	$(".btn").each(function(){
		$(this).show();
		
	});
	yds();
	
}

function cdelitem(n){
	$( "#oitem"+n).fadeOut(300);
	var name='itemid';
	var ownehid="";
	var arrx = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
	if(arrx != null) (ownehid=arrx[2]);
	
	var sstt="";
	var strs= new Array(); //定义一数组 
	strs=ownehid.split("t"); //字符分割 
	for (i=0;i<strs.length ;i++ ) 
	{ 
		if (strs[i]!=n&&strs[i]!=""){
			sstt+="t"+strs[i]
		}
	}

    var value=sstt;

	var Days = 1; //此 cookie 将被保存 30 天
	var exp = new Date();    //new Date("December 31, 9998");
	exp.setTime(exp.getTime() + Days*24*60*60*1000);
	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString()+ ";path=/";

	var arrxx = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
	var loadhx="";
	if(arrxx != null) (loadhx=arrxx[2]);


	var name='itemhowno';
	var howid="";
	var arrx = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
	if(arrx != null) (howid=arrx[2]);



	var ssttx="";
	var strsx= new Array(); //定义一数组 
	strsx=howid.split("t"); //字符分割 
	for (ix=0;ix<strsx.length ;ix++ ) 
	{ 
		if (strsx[ix].split("o")[0]!=n&&strsx[ix].split("o")[0]!=""){
			ssttx+="t"+strsx[ix]
		}
	}

	var value=ssttx;


	var Days = 1; //此 cookie 将被保存 30 天
	var exp = new Date();    //new Date("December 31, 9998");
	exp.setTime(exp.getTime() + Days*24*60*60*1000);
	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString()+ ";path=/";
	$("#ydsleft").load("/index.php?g=api&m=wap&a=loaditems");
	}
		
function selnos(n,x){

		var name='itemid';
		var arrxx = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
		var loadhx="";
		if(arrxx != null) (loadhx=arrxx[2]);
	
		if (loadhx!=""){
			var name='itemhowno';
			var howid="";
			var arrx = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
			if(arrx != null) (howid=arrx[2]);
	
			var ssttx="";
			var strsx= new Array(); //定义一数组 
			strsx=howid.split("t"); //字符分割 
			for (ix=0;ix<strsx.length ;ix++ ) 
			{ 
				if (strsx[ix].split("o")[0]!=x&&strsx[ix].split("o")[0]!=""){
					ssttx+="t"+strsx[ix]
				}
			}
	
			var value=ssttx+"t"+x+"o"+n;
		    var Days = 1; //此 cookie 将被保存 30 天
		    var exp = new Date();    //new Date("December 31, 9998");
		    exp.setTime(exp.getTime() + Days*24*60*60*1000);
		    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString()+ ";path=/";
		//document.cookie = name+"=;expires="+(new Date(0)).toGMTString();
		}

		$("#ydsleft").load("/index.php?g=api&m=wap&a=loaditems");
		
	}
	
	function yds(){
		var namets='itemid';
		var ownehidts="";
		var arrxts = document.cookie.match(new RegExp("(^| )"+namets+"=([^;]*)(;|$)"));
		if(arrxts != null) (ownehidts=arrxts[2]);
		if (ownehidts!=""){
			$("#ydsleft").load("/index.php?g=api&m=wap&a=loaditems");
				$("#yds").show();
		}else{
			$("#yds").hide();
		}
	}
	
	
$(function() {
	yds();
	var name='itemhowno';
	var howid="";
	var arrx = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
	if(arrx != null) (howid=arrx[2]);
	str=howid; //这是一字符串 
	var strs= new Array(); //定义一数组 
	strs=str.split("t"); //字符分割 
	for (i=0;i<strs.length ;i++ ) 
	{ 
	
		var astrs= new Array();
		astrs=strs[i].split("o");
		for (ia=0;ia<astrs.length ;ia++ ) 
		{ 
			var xo0=astrs[0];
			var xo1=astrs[1];
		}

		if (xo0!=""){
			document.getElementsByName("feng"+xo0)[0].value=xo1;
		}
	} 
	})