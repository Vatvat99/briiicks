$(document).ready(function(){displayChart(),$(".item-type-button").on("click",function(){$(".item-type-button").removeClass("active"),$(this).addClass("active");var t=$(this).data("type");$(".item-list").removeClass("shown"),$("."+t+"-list").addClass("shown")}),$(window).resize(function(){displayChart()})});