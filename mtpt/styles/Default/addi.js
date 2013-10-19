// additional style for New UI theme
// by chengs
// color animate start
(function(d){function i(){var b=d("script:first"),a=b.css("color"),c=false;if(/^rgba/.test(a))c=true;else try{c=a!=b.css("color","rgba(0, 0, 0, 0.5)").css("color");b.css("color",a)}catch(e){}return c}function g(b,a,c){var e="rgb"+(d.support.rgba?"a":"")+"("+parseInt(b[0]+c*(a[0]-b[0]),10)+","+parseInt(b[1]+c*(a[1]-b[1]),10)+","+parseInt(b[2]+c*(a[2]-b[2]),10);if(d.support.rgba)e+=","+(b&&a?parseFloat(b[3]+c*(a[3]-b[3])):1);e+=")";return e}function f(b){var a,c;if(a=/#([0-9a-fA-F]{2})([0-9a-fA-F]{2})([0-9a-fA-F]{2})/.exec(b))c=
[parseInt(a[1],16),parseInt(a[2],16),parseInt(a[3],16),1];else if(a=/#([0-9a-fA-F])([0-9a-fA-F])([0-9a-fA-F])/.exec(b))c=[parseInt(a[1],16)*17,parseInt(a[2],16)*17,parseInt(a[3],16)*17,1];else if(a=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(b))c=[parseInt(a[1]),parseInt(a[2]),parseInt(a[3]),1];else if(a=/rgba\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9\.]*)\s*\)/.exec(b))c=[parseInt(a[1],10),parseInt(a[2],10),parseInt(a[3],10),parseFloat(a[4])];return c}
d.extend(true,d,{support:{rgba:i()}});var h=["color","backgroundColor","borderBottomColor","borderLeftColor","borderRightColor","borderTopColor","outlineColor"];d.each(h,function(b,a){d.fx.step[a]=function(c){if(!c.init){c.a=f(d(c.elem).css(a));c.end=f(c.end);c.init=true}c.elem.style[a]=g(c.a,c.end,c.pos)}});d.fx.step.borderColor=function(b){if(!b.init)b.end=f(b.end);var a=h.slice(2,6);d.each(a,function(c,e){b.init||(b[e]={a:f(d(b.elem).css(e))});b.elem.style[e]=g(b[e].a,b.end,b.pos)});b.init=true}})(jQuery);

var styleDir='styles/NewUI/';var bgImg=[{name:'Default',small:'Cyan_small.png'},{name:'Peace',small:'Peace_small.png'},{name:'Wood',small:'Wood_small.png'}];var definition=[{width:800,height:600},{width:800,height:640},{width:800,height:640},{width:1024,height:768},{width:1280,height:720},{width:1280,height:960},{width:1280,height:1024},{width:1366,height:768},{width:1400,height:1050},{width:1440,height:810},{width:1440,height:900},{width:1600,height:1200},{width:1680,height:945},{width:1680,height:1050},{width:1920,height:1080},{width:1920,height:1200},];function changeBg(name){jQuery.cookie('bgPicName',name,{expires:365,secure:true});var url=styleDir+name+'/';var pic='1280x800.jpg';var cover=true;for(var i=0,l=definition.length;i<l;i++){if(definition[i].width==window.screen.width&&definition[i].height==window.screen.height){pic=definition[i].width+'x'+definition[i].height+'.jpg';cover=false;break}}url+=pic;jQuery(document.body).css({'background':'url('+url+')'});if(cover){jQuery(document.body).css({'background-size':'cover'})}}if($.cookie('bgPicName')){changeBg($.cookie('bgPicName'))}else{changeBg('Default')}jQuery(document.body).ready(function($){var bgDiv=$('<div id="bgDiv"></div>').hide();$(bgDiv).append('<h2>Background</h2>');$(document.body).append(bgDiv);var bgBtn=$('<span><a href="#" ><img class="bgBtn" width=14 height=14 src="'+styleDir+'bgBtn.png" /></a></span>');bgBtn.click(function(){$.facebox({div:'#bgDiv'})});$('#userbarPanel').prepend(bgBtn);for(var i=0,l=bgImg.length;i<l;i++){var img=$('<a href="#" onclick="javascript:changeBg(\''+bgImg[i].name+'\')"><img src='+styleDir+bgImg[i].small+' width=124 height=53 /></a>');$('#bgDiv').append(img)}jQuery('.shadetabs b[name=menu]:not(.selected) a').hover(function(obj){$(obj.target).animate({color:'#FFF'},300,function(){})},function(obj){$(obj.target).animate({color:'#000'},300,function(){})})});