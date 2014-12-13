
$(function() {
	
	var name='itemid';
	var ownehid="";
	var arrx = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
	if(arrx != null) (ownehid=arrx[2]);
	str=ownehid; //这是一字符串 
	
	var strs= new Array(); //定义一数组 
	strs=str.split("t"); //字符分割 
	for (i=0;i<strs.length ;i++ ) 
	{ 
	$( "#btnoc"+strs[i] ).hide();
	$( "#btnos"+strs[i] ).show();
} 

	yds();tisi();
	})
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
	tisi();
}
	
function cko(n){

	if($("#btnoc"+n).is(":hidden")){
		cdelitem(n);
		}else{
	
					cadditem(n);
			}
	}
	
		function ctisi(){
			$("#tisi").fadeOut("slow");
			}
	function tisi(){
		var nametisix='tisix';
		var tisixid="";
		var arrtisix = document.cookie.match(new RegExp("(^| )"+nametisix+"=([^;]*)(;|$)"));
		if(arrtisix != null) (tisixid=arrtisix[2]);
		if (tisixid!=""){
		
		 }else{
			 $("#tisi").show();
			 setTimeout("ctisi()", 5000);
			 var valuetisix="ok";
			var Daystisix = 360; 
			var exp = new Date();   
			exp.setTime(exp.getTime() + Daystisix*24*60*60*1000);
		    document.cookie = nametisix + "="+ escape (valuetisix) + ";expires=" + exp.toGMTString()+ ";path=/";

		}


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

function cadditem(n){




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

 var value=sstt+"t"+n;


    var Days = 1; //此 cookie 将被保存 30 天
    var exp = new Date();    //new Date("December 31, 9998");
    exp.setTime(exp.getTime() + Days*24*60*60*1000);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString()+ ";path=/";
	
	 $( "#btnoc"+n).fadeOut(200,function(){ $( "#btnos"+n).fadeIn(200,yds()); });
	}


function cdelitem(n){


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
	$("#xloadorder").load("order.aspx", function(){$("#xloadorder").show();});
	
	
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
	 $( "#btnos"+n).fadeOut(200,function(){ $( "#btnoc"+n).fadeIn(200,yds()); });
}