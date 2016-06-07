var getYearWeek = function (year, month)
{
	var date = new Date (year, month - 1, 1);
	var date2 = new Date (year, 0, 1);
	var day1 = date.getDay ();
	if (day1 == 0)
		day1 = 7;
	var day2 = date2.getDay ();
	if (day2 == 0)
		day2 = 7;
	var d = Math.round ((date.getTime () - date2.getTime () + (day2 - day1) * (24 * 60 * 60 * 1000)) / 86400000);
	return Math.ceil (d / 7) + 1;
};

var setTime = function (timeElement)
{
	var now = new Date ();
	var hours = now.getHours () > 9 ? now.getHours () : "0" + now.getHours ();
	var minutes = now.getMinutes () > 9 ? now.getMinutes () : "0" + now.getMinutes ();
	var seconds = now.getSeconds () > 9 ? now.getSeconds () : "0" + now.getSeconds ();
	var timeStr = hours + ":" + minutes + ":" + seconds;
	timeElement.innerHTML = "北京时间&nbsp;" + timeStr;
};

var createDate = function (date, index)
{
	var solarDiasplay = date.lunarDayName;
	if (date.lunarDay == 1)
	{
		solarDiasplay = date.lunarMonthName;
	}
	
	// 添加节日显示信息
	if (date.term)
	{
		solarDiasplay = date.term;
	}
	if (date.lunarFestival)
	{
		solarDiasplay = date.lunarFestival;
	}
	if (date.solarFestival)
	{
		solarDiasplay = date.solarFestival;
	}
	if (date.lunarHolidays)
	{
		solarDiasplay = date.lunarHolidays;
	}
	if (date.solarHolidays)
	{
		solarDiasplay = date.solarHolidays;
	}
	
	//by xiaofu.
	var _date=new Date();
	var _year=_date.getFullYear();
	var _month=_date.getMonth();
	_month+=1;
	var _day=_date.getDate();
	if (_year==date.year && _month==date.month && _day==date.day) {
		var classname='"dateli bgtoday"';
	}else{
		var classname='"dateli"';
	}
	var dd=date.year+'-'+date.month+'-'+date.day;
	//
	var dateHtml = $ ("<li class="+classname+" date=\"" + date.year + "-" + date.month + "-" + date.day + "\" month=\"" + date.month
	        + "\"><div class=\"daywrap\"><div class=\"solarday\">" + date.day + "</div><div class=\"lunarday\">"
	        + solarDiasplay + "</div></div><div class=\"select\"></div></li>");
	if (index % 7 == 0 || index % 7 == 6)
	{
		dateHtml.addClass ("weekend");
	}
	if (date.day - 7 > index || date.day + 7 < index)
	{
		dateHtml.addClass ("othermonth");
	}
	if (date.worktime == 1)
	{
		dateHtml.addClass ("work");
	}
	else if (date.worktime == 2)
	{
		dateHtml.addClass ("rest");
	}
	if (solarDiasplay != date.lunarDayName && solarDiasplay != date.lunarMonthName)
	{
		dateHtml.addClass ("festival");
	}
	return dateHtml.prop ("outerHTML");
};

var setLunarDate = function (year, month, day)
{
	var today = new Date ();
	if (today.getFullYear () == year && today.getMonth () + 1 == month && today.getDate () == day)
	{
		$ (".huangli").addClass ("huanglitoday");
	}
	else
	{
		$ (".huangli").removeClass ("huanglitoday");
	}
	var weekend = LunarCalendar.weekendFromate (year, month, day);
	$ (".huangli .date").html (year + "年" + month + "月" + day + "日&nbsp;&nbsp;&nbsp;" + weekend);
	$ (".huangli .bigday").html (day);
	var lunarDate = LunarCalendar.solarToLunar (year, month, day);
	$ (".huangli .lunardate").html (
	        "&nbsp;农历" + lunarDate.lunarMonthName + lunarDate.lunarDayName + "【" + lunarDate.zodiac + "年】");
	$ (".huangli .ganzhidate").html (
	        lunarDate.GanZhiYear + "年&nbsp;" + lunarDate.GanZhiMonth + "月&nbsp;" + lunarDate.GanZhiDay + "日");
	var datestring = LunarCalendar.formateDayD4 (month - 1, day);
	var hl = HuangLi["y" + year];
	var yi = $ (".huangli .yiji .yi"), ji = $ (".huangli .yiji .ji");
	if (hl)
	{
		hl = hl[datestring];
		yi.html (hl["y"].length > 13 ? hl["y"].replace (/\./g, " ").substr (0, 13) + "..." : hl["y"].replace (/\./g,
		        " "));
		yi.attr ("title", hl["y"].replace (/\./g, " "));
		ji.html (hl["j"].length > 13 ? hl["j"].replace (/\./g, " ").substr (0, 13) + "..." : hl["j"].replace (/\./g,
		        " "));
		ji.attr ("title", hl["j"].replace (/\./g, " "));
	}
	else
	{
		yi.html ("");
		ji.html ("");
	}
};

function layer_note(v){
	layer.alert(v);
}

var bindDateClick = function ()
{
	$ (".calendarmain .calendarcontent li").each (function (index, value)
	{
		$ (this).click (function ()
		{
			//日期点击事件在此
			$(".dateli").removeClass('bgtoday');
			$(this).addClass('bgtoday');

			var datestr = $ (value).attr ("date");
			var date = datestr.split ("-");
			//layer_note(date[0]+'-'+date[1]+'-'+date[2]);
			location.href='remind.php?date='+date[0]+'-'+date[1]+'-'+date[2];//跳转
			setLunarDate (date[0], date[1], date[2]);
			var preSelectElement = $ (".calendarcontent li.selected .select");
			if (preSelectElement.length > 0)
			{
				preSelectElement.width (preSelectElement.width () + 6);
				preSelectElement.height (preSelectElement.height () + 6);
			}
			$ (".calendarcontent li.selected").removeClass ("selected");
			$ (this).addClass ("selected");
			var selectElement = $ (".calendarcontent li.selected .select");
			selectElement.width (selectElement.width () - 6);
			selectElement.height (selectElement.height () - 6);
		});
	});
};

var bindDateHover = function ()
{
	$ (".calendarcontent li.othermonth").hover (function ()
	{
		var month = $ (this).attr ("month");
		$ (".calendarcontent li.othermonth[month='" + month + "']").removeClass ("othermonth").addClass ("hover");
	}, function ()
	{
		$ (".calendarcontent li.hover").removeClass ("hover").addClass ("othermonth");
	});
};

var setToday = function ()
{
	var today = new Date ();
	todayStr = today.getFullYear () + "-" + (today.getMonth () + 1) + "-" + today.getDate ();
	$ (".calendarcontent li[date='" + todayStr + "']").addClass ("today");
};

var setWeekNums = function (year, month)
{
	var week = getYearWeek (year, month);
	var row = $ (".calendarcontent li").length / 7;
	var weekNums = "";
	for ( var i = 0; i < row; i++)
	{
		weekNums += "<li>" + (week + i) + "<br/>周</li>";
	}
	if (row == 5)
	{
		$ (".calendarweeknum ul").removeClass ("sixrow").addClass ("fiverow");
	}
	else
	{
		$ (".calendarweeknum ul").removeClass ("fiverow").addClass ("sixrow");
	}
	$ (".calendarweeknum ul").html (weekNums);
};

var fillCalendar = function (data)
{
	var monthData = data.monthData;
	if ((data.monthDays == 30 && data.firstDay == 6) || (data.monthDays == 31 && data.firstDay >= 5))
	{
		$ (".calendarmain .calendarcontent ul").removeClass ("fiverow").addClass ("sixrow");
	}
	else
	{
		$ (".calendarmain .calendarcontent ul").removeClass ("sixrow").addClass ("fiverow");
		monthData = data.monthData.slice (0, 35);
	}
	var calendarHtml = "";
	for ( var i = 0, length = monthData.length; i < length; i++)
	{
		calendarHtml = calendarHtml.concat (createDate (monthData[i], i));
	}
	$ (".calendarmain .calendarcontent ul").html (calendarHtml);
	bindDateClick ();
	bindDateHover ();
	setToday ();
};

var setCalendar = function (year, month)
{
	$ ("#yeardrop").text (year + "年");
	$ ("#monthdrop").text (month + "月");
	var MonthData = LunarCalendar.calendar (year, month, true);
	fillCalendar (MonthData);
	setWeekNums (year, month);
	var hlscript = $ ("script[src='Scripts/hl" + year + ".min.js']");
	if (hlscript.length <= 0 && year < 2020 && year > 2008)
	{
		$ ("body").append ("<script src=\"Scripts/hl" + year + ".min.js\"></script>");
	}
};

var setYearSelect = function ()
{
	for ( var i = 1891; i <= 2100; i++)
	{
		var yearli = $ ("<li value=\"" + i + "\">" + i + "年</li>");
		$ ("#yearlist ul").append (yearli);
	}
	$ ("#yearlist ul li").click (function ()
	{
		var year = $ (this).attr ("value");
		var month = parseInt ($ ("#monthdrop").text ().replace ("月", ""));
		setCalendar (year, month);
	});
};

var setTheme = function ()
{
	var theme = $.cookie ("theme");
	if (theme)
	{
		$ ("#skin").attr ("href", "styles/" + theme + ".css");
	}
	else
	{
		$ ("#skin").attr ("href", "styles/blue.css");
	}
};

var setFooter = function ()
{
	var screenHeight = $ (window).height ();
	if (screenHeight > 780)
	{
		$ (".footerfloat").removeClass ("footerfloat").addClass ("footer");
	}
};

var init = function ()
{
	var today = new Date ();
	var year = today.getFullYear ();
	var month = today.getMonth () + 1;
	var day = today.getDate ();
	setCalendar (year, month);
	setLunarDate (year, month, day);
	var timeElement = document.getElementById ("time");
	setInterval (function ()
	{
		setTime (timeElement);
	}, 1000);
	setYearSelect ();
};

var loadJs = function (url)
{
	var script = document.createElement ('script');
	script.src = url;
	script.type = 'text/javascript';
	var head = document.head;
	head.appendChild (script);
};

$ (function ()
{
	document.onselectstart = document.oncontextmenu = document.ondragstart = function ()
	{
		return false;
	};
	
	$ ("body>a").hide ();
	setFooter ();
	setTheme ();
	init ();
	$ (".colorpicker a").click (function (event)
	{
		var colorpanel = $ (".colorpanel");
		if (colorpanel.is (":visible"))
		{
			colorpanel.hide ();
		}
		else
		{
			colorpanel.show ();
		}
		if (event.stopPropagation)
		{
			event.stopPropagation ();
		}
		else
		{
			event.cancelBubble = true;
		}
	});
	$ (".colorpanel li").each (function (index, value)
	{
		$ (value).click (function ()
		{
			var colorselect = "";
			switch (index)
			{
				case 0:
					colorselect = "blue";
					break;
				case 1:
					colorselect = "green";
					break;
				case 2:
					colorselect = "red";
					break;
				case 3:
					colorselect = "orange";
			}
			$.cookie ("theme", colorselect);
			$ ("#skin").attr ("href", "styles/" + colorselect + ".css");
		});
	});
	$ ("html").click (function (event)
	{
		$ (".dropdownlist").hide ();
		$ (".colorpanel").hide ();
	});
	$ (".dropdown").click (function (event)
	{
		var dropdownlist = $ ("+ .dropdownlist", $ (this).parent ());
		if (dropdownlist.is (":visible"))
		{
			dropdownlist.hide ();
		}
		else
		{
			$ (".dropdownlist").hide ();
			dropdownlist.show ();
		}
		var wrapheight = dropdownlist.height ();
		var listheight = $ ("ul", dropdownlist).height ();
		if (listheight > wrapheight)
		{
			var currentYear = $ (this).text ().replace ("年", "");
			var currentYearLi = $ ("li[value='" + currentYear + "']", dropdownlist);
			dropdownlist.scrollTop (dropdownlist.scrollTop () + currentYearLi.offset ().top - 270); // 后面须改成相对父容器的offsettop
		}
		event.stopPropagation ();
	});
	$ ("#monthlist li").click (function ()
	{
		var month = parseInt ($ (this).html ().replace ("月", ""));
		var year = parseInt ($ ("#yeardrop").text ().replace ("年", ""));
		setCalendar (year, month);
	});
	$ ("#festivallist li").click (function ()
	{
		var date = $ (this).attr ("date");
		var datearr = date.split ("-");
		setCalendar (parseInt (datearr[0]), parseInt (datearr[1]));
		$ ("#holidaydrop").text ($ (this).text ());
		setLunarDate (parseInt (datearr[0]), parseInt (datearr[1]), parseInt (datearr[2]));
	});
	$ ("#monthdecrease").click (function ()
	{
		var year = parseInt ($ ("#yeardrop").text ().replace ("年", ""));
		var month = parseInt ($ ("#monthdrop").text ().replace ("月", ""));
		var date = new Date (year, month - 1);
		date.setMonth (date.getMonth () - 1);
		setCalendar (date.getFullYear (), date.getMonth () + 1);
	});
	$ ("#monthincrease").click (function ()
	{
		var year = parseInt ($ ("#yeardrop").text ().replace ("年", ""));
		var month = parseInt ($ ("#monthdrop").text ().replace ("月", ""));
		var date = new Date (year, month - 1);
		date.setMonth (date.getMonth () + 1);
		setCalendar (date.getFullYear (), date.getMonth () + 1);
	});
	$ ("#today").click (function ()
	{
		var today = new Date ();
		setCalendar (today.getFullYear (), today.getMonth () + 1);
		setLunarDate (today.getFullYear (), today.getMonth () + 1, today.getDate ());
	});
	$ ("body>span").hide ();
});
