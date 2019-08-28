function XmlHttp() {
	var xmlhttp	= false;
	try {
		xmlhttp	= new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e) {
			try {
				xmlhttp	= new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e) {
					xmlhttp	= false;
					}
				}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
		}
	return xmlhttp;
	}

function setUserName(resp) {
	document.title = "Привет, "+resp['fio']+" ( "+resp['login']+" ) "+"!";
	document.getElementById("title").innerHTML	= document.title;
	}

function setTableEmails(resp,genInfo) {
	var div = document.createElement('div');
	div.innerHTML	= "Список email'лов встречающихся более чем у одного пользователя:";
	//document.body.append(div);
	genInfo.append(div);
	var table = document.createElement('table');
	div.append(table);
	var emails	= resp['email'];
	for (var i=0; i<emails.length; i++) {
		var tr = document.createElement('tr');
		table.append(tr);
		var td = document.createElement('td');
		td.innerHTML	= emails[i];
		tr.append(td);
		}
	if (emails.length==0) {
		var tr = document.createElement('tr');
		table.append(tr);
		var td = document.createElement('td');
		td.innerHTML	= "Нет таких!";
		tr.append(td);
		}
	}

function setTableNotOrder(resp,genInfo) {
	var div = document.createElement('div');
	div.innerHTML	= "Список логинов пользователей, которые не сделали ни одного заказа:";
	//document.body.append(div);
	genInfo.append(div);
	var table = document.createElement('table');
	div.append(table);
	var NotOrder	= resp['NotOrder'];
	for (var i=0; i<NotOrder.length; i++) {
		var tr = document.createElement('tr');
		table.append(tr);
		var td = document.createElement('td');
		td.innerHTML	= NotOrder[i];
		tr.append(td);
		}
	if (NotOrder.length==0) {
		var tr = document.createElement('tr');
		table.append(tr);
		var td = document.createElement('td');
		td.innerHTML	= "Нет таких!";
		tr.append(td);
		}
	}

function setTable2Order(resp,genInfo) {
	var div = document.createElement('div');
	div.innerHTML	= "Список логинов пользователей которые сделали более двух заказов:";
	//document.body.append(div);
	genInfo.append(div);
	var table = document.createElement('table');
	div.append(table);
	var Order2	= resp['Order2'];
	for (var i=0; i<Order2.length; i++) {
		var tr = document.createElement('tr');
		table.append(tr);
		var td = document.createElement('td');
		td.innerHTML	= Order2[i];
		tr.append(td);
		}
	if (Order2.length==0) {
		var tr = document.createElement('tr');
		table.append(tr);
		var td = document.createElement('td');
		td.innerHTML	= "Нет таких!";
		tr.append(td);
		}
	}


function ajax(send,url)	{
	if (window.XMLHttpRequest) {
		var req = XmlHttp();
		req.open("POST",url,true);
		req.withCredentials = true;
		req.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		//req.setRequestHeader("Cookie", document.cookie);
		req.send(send);
		req.onreadystatechange = function() {
			if (req.readyState == 4 && req.status == 200) {
				var resp	= req.responseText;
				if (resp.trim()=="") {
					document.location.replace("../index.html");
					}
					else {
						resp	= JSON.parse(resp);
						if (typeof resp['ChangePssword'] != "undefined") {
							if (resp['ChangePssword']) alert("Пароль изменен");
								else alert("Пароль НЕ изменен");
							return;
							}
						if (typeof resp['ChangeFIO'] != "undefined") {
							if (resp['ChangeFIO']){ 
								alert("ФИО изменено");
								setUserName(resp);
								}
								else alert("ФИО НЕ изменено");
							return;
							}
						setUserName(resp);
						var genInfo = document.getElementById("genInfo");
						setTableEmails(resp,genInfo);
						setTableNotOrder(resp,genInfo);
						setTable2Order(resp,genInfo);
						}
				
				}
			}
		}
	}
 
  function OnExit() {
	 var send	= document.cookie+'&exit='+true;
	 ajax(send,"../php/lk.php");
	 }

  function OnSettings() {
	  var btn_settings	= document.getElementById("btn_settings");
	  var settings		= document.getElementById("settings");
	  if (settings.getAttribute('hidden')) {
		  settings.removeAttribute('hidden');
		  btn_settings.innerHTML	= "Скрыть настройки";
		  }
		  else {
			  settings.setAttribute('hidden', true);
			  btn_settings.innerHTML	= "Настройки";
			  }
	 }

 function OnPassVerif() {
	var pass	= document.getElementById("pass").value;
	var pass2	= document.getElementById("pass2").value;
	var passVerif	= document.getElementById("passVerif");
	if (pass==pass2) {
		passVerif.innerHTML	= "Пароли совпадают";
		}
		else {
			passVerif.innerHTML	= "Пароли НЕ совпадают";
			}
	 }

function OnChangePssword() {
	var pass	= document.getElementById("pass").value;
	var send	= document.cookie+'&ChangePssword='+true+'&password='+pass;
	ajax(send,"../php/lk.php");
	}

function OnChangeFIO() {
	var fio	= document.getElementById("fio").value;
	var send	= document.cookie+'&ChangeFIO='+true+'&fio='+fio;
	ajax(send,"../php/lk.php");
	}
	 
 function funOnload() {
	 ajax(document.cookie,"../php/lk.php");//я знаю куки должны идти в заголовках, но почему-то не идут
	 var btn_exit = document.getElementById("btn_exit");
	 btn_exit.onclick	= OnExit;
	 var btn_settings = document.getElementById("btn_settings");
	 btn_settings.onclick	= OnSettings;
	 var pass = document.getElementById("pass");
	 pass.onchange	= OnPassVerif;
	 var pass2 = document.getElementById("pass2");
	 pass2.onchange	= OnPassVerif;
	 var btn_change_password = document.getElementById("btn_change_password");
	 btn_change_password.onclick	= OnChangePssword;
	 var btn_change_fio = document.getElementById("btn_change_fio");
	 btn_change_fio.onclick	= OnChangeFIO;
	 }

 window.onload = funOnload;
