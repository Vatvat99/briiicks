$(document).ready(function(){var e;$("a.delete-link").on("click",function(t){t.preventDefault(),e=$(this).attr("href"),$(".delete-dialog td.serie-name").text($(this).parent().siblings(".serie-name").text()),$(".delete-dialog td.range-name").text($(this).parent().siblings(".range-name").text()),$(".delete-dialog").fadeIn(100).css({"max-width":"460"});var i=$(".delete-dialog").width(),l=$(".delete-dialog").height(),d=$(window).height(),o=$(window).width(),a=d/2-l/2+$("body").scrollTop(),n=o/2-i/2;return $(".delete-dialog").css({"margin-top":a,"margin-left":n}),$("body").append('<div id="overlay" class="overlay"></div>'),$("#overlay").css({filter:"alpha(opacity=50)"}).fadeIn(100),$(window).resize(function(){var e=$(".delete-dialog").width(),t=$(".delete-dialog").height(),i=$(window).height(),l=$(window).width(),d=i/2-t/2+$("body").scrollTop(),o=l/2-e/2;$(".delete-dialog").css({"margin-top":d,"margin-left":o})}),$(window).scroll(function(){var e=$(".delete-dialog").height(),t=$(window).height(),i=t/2-e/2+$("body").scrollTop();$(".delete-dialog").css({"margin-top":i})}),!1}),$("body").on("click",".delete-dialog a.delete",function(t){t.preventDefault(),window.location.href=e}),$("body").on("click",".delete-dialog a.cancel, .delete-dialog a.close, #overlay",function(e){e.preventDefault(),$("#overlay , .delete-dialog").fadeOut(100,function(){$("#overlay").remove()})})});