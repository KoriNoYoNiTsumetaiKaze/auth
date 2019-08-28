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
	
function ajax(send,url)	{
	if (window.XMLHttpRequest) {
		var req = XmlHttp();
		req.open("POST",url,true);
		req.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		req.send(send);
		req.onreadystatechange = function() {
			if (req.readyState == 4 && req.status == 200) {
				var resp	= req.responseText;
				if (resp==1) {
					var checkLogin	= document.getElementById("checkLogin");
					checkLogin.innerHTML	= "Такой логин уже сущетвует";
					}
					else {
						if (resp==0) {
							var checkLogin	= document.getElementById("checkLogin");
							checkLogin.innerHTML	= "Логин доступен";
							}
							else document.location.replace(resp);
						}
				}
			}
		}
	}
 
 function OnEnter() {
	 var login		= document.getElementById("login").value;
	 var password	= document.getElementById("password").value;
	 var send		= 'login='+login+'&password='+password;
	 ajax(send,"php/auth.php");
	 }
 
 function OnRegister() {
	 var login	= document.getElementById("loginReg").value;
	 if (login.trim()=="") {
		var checkLogin	= document.getElementById("checkLogin");
		checkLogin.innerHTML	= "Логин не может быть пустым";
		return;
		}
	 var password	= document.getElementById("passReg").value;
	 var password2	= document.getElementById("pass2Reg").value;
	 var passVerif	= document.getElementById("passVerif");
	 if (password==password2) {
		passVerif.innerHTML	= "Пароли совпадают";
		}
		else {
			passVerif.innerHTML	= "Пароли НЕ совпадают";
			return;
			}	 
	 var email	= document.getElementById("emailReg").value;
	 var fio	= document.getElementById("fioReg").value;
	 var send	= 'login='+login+'&password='+password+'&email='+email+'&fio='+fio;
	 ajax(send,"php/reg.php");
	 }
 
 function OnLoginReg() {
	 var login	= document.getElementById("loginReg").value;
	 var send	= 'login='+login+'&checkLogin=true';
	 ajax(send,"php/reg.php");
	 }

 function OnPassVerif() {
	var passReg		= document.getElementById("passReg").value;
	var pass2Reg	= document.getElementById("pass2Reg").value;
	var passVerif	= document.getElementById("passVerif");
	if (passReg==pass2Reg) {
		passVerif.innerHTML	= "Пароли совпадают";
		}
		else {
			passVerif.innerHTML	= "Пароли НЕ совпадают";
			}
	 }
 
 function funOnload() {
	 var btn_enter = document.getElementById("btn_enter");
	 btn_enter.onclick	= OnEnter;
	 var btn_register = document.getElementById("btn_register");
	 btn_register.onclick	= OnRegister;
	 var loginReg = document.getElementById("loginReg");
	 loginReg.onchange	= OnLoginReg;
	 var passReg = document.getElementById("passReg");
	 passReg.onchange	= OnPassVerif;
	 var pass2Reg = document.getElementById("pass2Reg");
	 pass2Reg.onchange	= OnPassVerif;
	 }

 window.onload = funOnload;
