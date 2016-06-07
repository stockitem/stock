var firstcity = { "key": "101280601", "value": "深圳" };
var regcity = /[^\u4E00-\u9FA5]/g;
$(document).ready(function () {
	geolocation();
	$("#weather").css("background", "url(Images/onloading.gif) no-repeat center");
	changeStyle();
	$(window).resize(function () {
		changeStyle();
	});
	$("#city .city").unbind();
	$("#city .change").click(function (e) {
		var cityname = $("#city .city").text();
		var citycode=$("#city .city").attr("rel");
		$("#city .city").hide();
		$(this).hide();
		var left = $(".Infobottom").offset().left+56;
		var top = $(".Infobottom").offset().top + 31;
		$(".demo").css({ "top": top - 30, "left": left });
		$(".demo").show();
		$(".blackone").show();
		$("#citySelect").attr("value", cityname);
		$("#citySelect").attr("rel", citycode);
		$("#citySelect")[0].click();

		var $city = $("#cityBox a:visible");
		$city.unbind();
		$city.click(function () {
			var valueone = $("#citySelect").attr("rel");
			var cityname = $("#citySelect").val();
			emptyAll();
			loadJsonp(valueone);
			var $selCity = $("#city .city");
			$selCity.text(cityname);
			$selCity.attr("rel",valueone);
			$selCity.show();
			$("#city .change").show();
			$(".blackone").hide();
			$(".demo").hide();
		});
		//$(".cityslide li").unbind();
		//$(".cityslide").on("click","li",function(){

		//});
		e.stopPropagation();
	});
	$(".blackone").click(function () {
		var valueone = $("#citySelect").attr("rel");
		var cityname1 = $("#citySelect").val();
		// console.log(valueone.replace(regcity,""));
		if (findcity(cityname1.replace(regcity, "") + "|") != -1 && cityname1.replace(regcity, "") != "") {
			cityname1 = cityname1.replace(regcity, "");
			emptyAll();
			loadJsonp(valueone);
		} else {
			cityname1 = firstcity.value;
			valueone = firstcity.key;
		}
		$("#city .city").text(cityname1);
		$("#city .city").attr("rel", valueone);
		$("#city .city").show();
		$("#city .change").show();
		$(this).hide();
		$(".demo").hide();
	});

});
function geolocation() {
	$.ajax({
		type: "get",
		async: false,
		url: "http://riliajax.updrv.com/ip.php",
		dataType: "jsonp",
		jsonp: "callback",//传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名(一般默认为:callback)
		jsonpCallback: "locationHandler",//自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名，也可以写"?"，jQuery会自动为你处理数据
		success: function (data) {
			if (data["AreaCode"]) {
				firstcity.key = data["AreaCode"];
				firstcity.value = data["AreaName"];
				$("#city .city").text(firstcity.value);
				$("#city .city").attr("rel", firstcity.key);
			} else {
				$("#city .city").text(firstcity.value);
				$("#city .city").attr("rel", firstcity.key);
			}
		},
		error: function () {
			$("#city .city").text(firstcity.value);
			$("#city .city").attr("rel", firstcity.key);
		},
		complete: function () {
			loadJsonp(firstcity.key);
		}
	});
}
function findcity(item) {
	for (var i = 0; i < Vcity.allCity.length - 1; ++i) {
		if (Vcity.allCity[i].indexOf(item) == 0)
			return i;
	};
	return -1;
}
function emptyAll() {
	$("#city .city").empty();
	$(".airquality").text("获取中");
	//	var li=$(".mid li")
	//	for(var i=0;i<li.length;++i)
	//	 if(!li.eq(i).children("img")&&!li.eq(i).children("img"))
	//	     li.eq(i).empty(); 
	$(".mid li:not(:has(img)):not(:has(span))").empty();
	$(".mid li img").attr("src", "");
	$(".mid li span").empty();
	$("#warning").empty();
	$("#weather").css("background", "url(Images/onloading.gif) no-repeat center");
}
function selfliCallback() {
	var valueone = $("#citySelect").attr("rel");
	var cityname = $("#citySelect").val();
	// console.log(valueone);
	// console.log(Vcity.allCity.indexOf(valueone.replace(regcity,"")+"|"));
	if (valueone != "对不起") {
		emptyAll();
		loadJsonp(valueone);
		$("#city .city").text(cityname);
		$("#city .city").show();
		$("#city .change").show();
		$(".demo").hide();
		$(".blackone").hide();
	}
	else {
		$(".demo").hide();
		$(".blackone").hide();
		$("#city .city").show();
		$("#city .change").show();
	};
}
//初始化改变部分样式
function changeStyle() {
	var left = $(".Infobottom").offset().left;
	$(".demo").css({ "top": top - 30, "left": left });
	$(".citySelector").css({ "top": top, "left": left });
	var screenHeight = $(window).height();
	if (screenHeight > 780) {
		$(".footerfloat").removeClass("footerfloat").addClass("footer");
	} else {
		$(".footer").removeClass("footer").addClass("footerfloat");
	}
}

function setLeftHeight() {
	$(".everydayinfo").height($("#mainCal").prev()[0].clientHeight + $("#mainCal")[0].clientHeight);
}

function loadJsonp(w_city) {
	$.ajax({
		type: "get",
		async: false,
		url: "http://riliajax.updrv.com/weather.php?cc=" + w_city,
		dataType: "jsonp",
		jsonp: "callback",//传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名(一般默认为:callback)
		jsonpCallback: "flightHandler",//自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名，也可以写"?"，jQuery会自动为你处理数据
		success: function (data) {
			var text1 = data.Text1;
			var wind1 = data.Wind1;
			text1 = text1.length > 3 ? (text1.substr(0, 3)) : text1;
			text1 = text1.padleft(5, "　");
			wind1 = wind1.length > 2 ? (wind1.substr(0, 2)) : wind1;
			wind1 = wind1.padleft(3, "　");
			$("#todayweather").html("今天："+data.Temp1 + " " + text1 + wind1);
			$("#todayweather").attr("title", data.Text1 + "　" + data.Wind1);
			var text2 = data.Text2;
			var wind2 = data.Wind2;
			text2 = text2.length > 3 ? (text2.substr(0, 3)) : text2;
			text2 = text2.padleft(5, "　");
			wind2 = wind2.length > 2 ? (wind2.substr(0, 2)) : wind2;
			wind2 = wind2.padleft(3, "　");
			$("#tomorrowweather").html("明天：" + data.Temp2 + " " + text2 + wind2);
			$("#tomorrowweather").attr("title",data.Text2+"　"+data.Wind2);
		},
		error: function () {
			emptyAll();
			$("#weather").css("background", "url(Images/wrong.png) no-repeat center");
		}
	});
};
String.prototype.padleft = function (len, ch) {
	ch = typeof (ch) === 'undefined' ? ' ' : ch;
	var s = String(this);
	while (s.length < len)
		s = s + ch;
	return s;
}