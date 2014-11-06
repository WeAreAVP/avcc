// JavaScript Document
function number_format(number, decimals, dec_point, thousands_sep) {
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	s = '',
	toFixedFix = function(n, prec) {
		var k = Math.pow(10, prec);
		return '' + Math.round(n * k) / k;
	};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}
function ReadCookieValue(cookieName) {
	var theCookie = " " + document.cookie;
	var ind = theCookie.indexOf(" " + cookieName + "=");
	if (ind == -1)
		ind = theCookie.indexOf(";" + cookieName + "=");
	if (ind == -1 || cookieName == "")
		return "";
	var ind1 = theCookie.indexOf(";", ind + 1);
	if (ind1 == -1)
		ind1 = theCookie.length;
	return unescape(theCookie.substring(ind + cookieName.length + 2, ind1));
}
function rand(min, max) {
	var argc = arguments.length;
	if (argc === 0) {
		min = 0;
		max = 2147483647;
	} else if (argc === 1) {
		throw new Error('Warning: rand() expects exactly 2 parameters, 1 given');
	}
	return Math.floor(Math.random() * (max - min + 1)) + min;
}
function implode(glue, pieces) {
	var i = '',
	retVal = '',
	tGlue = '';
	if (arguments.length === 1) {
		pieces = glue;
		glue = '';
	}
	if (typeof (pieces) === 'object') {
		if (Object.prototype.toString.call(pieces) === '[object Array]') {
			return pieces.join(glue);
		}
		for (i in pieces) {
			retVal += tGlue + pieces[i];
			tGlue = glue;
		}
		return retVal;
	}
	return pieces;
}

function checkAll() {
	var boxes = document.getElementsByTagName('input');
	for (var index = 0; index < boxes.length; index++) {
		box = boxes[index];
		if (box.type == 'checkbox' && box.className == 'checkboxes' && box.disabled == false) {

			if (document.getElementById('check_all').checked) {
				if (overall_total > page_total && overall_total>100)
					makeSelectAllMsg(0);
			}
			else
				$('#select_all_msg').hide();
			box.checked = document.getElementById('check_all').checked;
		}

	}
	saveCheckedValues();
	return true;
}
function manageCheck(element) {
	if ($('.checkboxes').length == $('.checkboxes:checked').length) {
		$('#check_all').attr('checked', true);
		makeSelectAllMsg(0);
	}
	else {
		$('#check_all').attr('checked', false);
		$('#select_all_msg').hide();
		overall_checked = 0;
	}
	saveCheckedValues();
}
function saveCheckedValues() {
	if (overall_checked == 1)
		checked_val = 'all';
	else {
		checked_val = '';
		$('.checkboxes:checked').each(function() {
			checked_val += $(this).val() + ',';
		});
		checked_val = checked_val.slice(0, -1);


	}
}
function selectAllFromPages() {
	overall_checked = 1;
	checked_val = 'all';
	makeSelectAllMsg(1);
}
function clearSelection() {
	overall_checked = 0;
	$('#select_all_msg').hide();
	$('#check_all').attr('checked', false);
	checkAll();

}
function str_replace(search, replace, subject, count) {

	var i = 0,
	j = 0,
	temp = '',
	repl = '',
	sl = 0,
	fl = 0,
	f = [].concat(search),
	r = [].concat(replace),
	s = subject,
	ra = Object.prototype.toString.call(r) === '[object Array]',
	sa = Object.prototype.toString.call(s) === '[object Array]';
	s = [].concat(s);
	if (count) {
		this.window[count] = 0;
	}

	for (i = 0, sl = s.length; i < sl; i++) {
		if (s[i] === '') {
			continue;
		}
		for (j = 0, fl = f.length; j < fl; j++) {
			temp = s[i] + '';
			repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
			s[i] = (temp).split(f[j]).join(repl);
			if (count && s[i] !== temp) {
				this.window[count] += (temp.length - s[i].length) / f[j].length;
			}
		}
	}
	return sa ? s : s[0];
}
function in_array(needle, haystack, argStrict) {
	var key = '',
	strict = !!argStrict;

	if (strict) {
		for (key in haystack) {
			if (haystack[key] === needle) {
				return true;
			}
		}
	} else {
		for (key in haystack) {
			if (haystack[key] == needle) {
				return true;
			}
		}
	}

	return false;
}