$(document).ready(function(){var e=window.innerWidth||document.documentElement.clientWidth||document.body.clientWidth;$(".item-type-button").on("click",function(){$(".item-type-button").removeClass("active"),$(this).addClass("active");var e=$(this).data("type");$(".item-type-field").val(e)}),732>e?($(".ranges-list").each(function(){$(this).appendTo("body")}),$(".series-list").each(function(){$(this).appendTo("body")})):($(".ranges-list").each(function(){$(this).appendTo(".search-container")}),$(".series-list").each(function(){$(this).appendTo(".search-container")})),$("input#range").focusin(function(){var e=window.innerWidth||document.documentElement.clientWidth||document.body.clientWidth;"Gamme"==$(this).val()&&$(this).val("");var s=$(".item-type-field").val();$(".series-list").addClass("to-left"),$(".search").addClass("to-left"),$(".ranges-list").addClass("to-left"),$('.ranges-list[data-item-type="'+s+'"]').removeClass("to-left"),$('.ranges-list[data-item-type="'+s+'"]').addClass("shown"),732>e&&$(".search-list-overlay").css({filter:"alpha(opacity=50)"}).fadeIn(100)}),$("input#range").focusout(function(){$(".series-list").removeClass("to-left"),$(".search").removeClass("to-left"),$(".ranges-list").removeClass("to-left"),$(".ranges-list").removeClass("shown"),$(".search-list-overlay").fadeOut(100),""==$(this).val()&&($(this).val("Gamme"),$("input#selected-range-alias").val("none"))}),$("input#serie").focusin(function(){var e=window.innerWidth||document.documentElement.clientWidth||document.body.clientWidth,s=$(".item-type-field").val();"Série"==$(this).val()&&$(this).val(""),$(".ranges-list").addClass("to-left"),$(".series-list").addClass("to-left"),$(".search").addClass("to-left"),$('.series-list[data-range="'+$("input#selected-range-alias").val()+'"][data-item-type="'+s+'"]').removeClass("to-left"),$('.series-list[data-range="'+$("input#selected-range-alias").val()+'"][data-item-type="'+s+'"]').addClass("shown"),732>e&&$(".search-list-overlay").css({filter:"alpha(opacity=50)"}).fadeIn(100)}),$("input#serie").focusout(function(){$(".ranges-list").removeClass("to-left"),$(".series-list").removeClass("to-left"),$(".search").removeClass("to-left"),$(".series-list").removeClass("shown"),$(".search-list-overlay").fadeOut(100),""==$(this).val()&&($(this).val("Série"),$("input#selected-serie-alias").val("none"),"Gamme"==$("input#range").val()&&$("input#selected-range-alias").val("none"))}),$(".ranges-list li").mousedown(function(){$("input#range").val($(this).text()),$(".range.erase").removeClass("hidden"),$(".range.erase").addClass("shown"),$("input#selected-range-alias").val($(this).data("alias")),$("input#serie").val("Série"),$(".serie.erase").removeClass("shown"),$(".serie.erase").addClass("hidden"),null==$('.series-list[data-range="'+$(this).data("alias")+'"]').get(0)?($("input#serie").attr("disabled","disabled"),$("input#serie").addClass("disabled")):($("input#serie").removeAttr("disabled"),$("input#serie").removeClass("disabled"))}),$(".series-list li").mousedown(function(){$("input#serie").val($(this).text()),$(".serie.erase").removeClass("hidden"),$(".serie.erase").addClass("shown"),$("input#selected-serie-alias").val($(this).data("alias"))}),$(".range.erase").mousedown(function(){$("input#range").val("Gamme"),$("input#selected-range-alias").val("none"),$("input#serie").val("Série"),$("input#selected-serie-alias").val("none"),$(".range.erase").removeClass("shown"),$(".range.erase").addClass("hidden"),$(".serie.erase").removeClass("shown"),$(".serie.erase").addClass("hidden"),$("input#serie").removeAttr("disabled"),$("input#serie").removeClass("disabled")}),$(".serie.erase").mousedown(function(){$("input#serie").val("Série"),$("input#selected-serie-alias").val("none"),$(".serie.erase").removeClass("shown"),$(".serie.erase").addClass("hidden")}),$(".ranges-list, .series-list").mCustomScrollbar({scrollInertia:1e3,scrollButtons:{enable:!0}})}),$(window).resize(function(){var e=window.innerWidth||document.documentElement.clientWidth||document.body.clientWidth;732>e?($(".ranges-list").each(function(){$(this).appendTo("body")}),$(".series-list").each(function(){$(this).appendTo("body")})):($(".ranges-list").each(function(){$(this).appendTo(".search-container")}),$(".series-list").each(function(){$(this).appendTo(".search-container")}))});