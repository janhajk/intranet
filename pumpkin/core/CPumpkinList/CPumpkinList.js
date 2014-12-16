/*
  ,ad8888ba,   88888888ba                                                 88         88               88           88                      
 d8"'    `"8b  88      "8b                                                88         ""               88           ""               ,d     
d8'            88      ,8P                                                88                          88                            88     
88             88aaaaaa8P'  88       88  88,dPYba,,adPYba,   8b,dPPYba,   88   ,d8   88  8b,dPPYba,   88           88  ,adPPYba,  MM88MMM  
88             88""""""'    88       88  88P'   "88"    "8a  88P'    "8a  88 ,a8"    88  88P'   `"8a  88           88  I8[    ""    88     
Y8,            88           88       88  88      88      88  88       d8  8888[      88  88       88  88           88   `"Y8ba,     88     
 Y8a.    .a8P  88           "8a,   ,a88  88      88      88  88b,   ,a8"  88`"Yba,   88  88       88  88           88  aa    ]8I    88,    
  `"Y8888Y"'   88            `"YbbdP'Y8  88      88      88  88`YbbdP"'   88   `Y8a  88  88       88  88888888888  88  `"YbbdP"'    "Y888  
                                                             88                                                                            
                                                             88 
*/

// Einfärbung der Zeilen (Zeilennummer-Variablen)
var mouseOverRow = [];
/*
	Lädt eine Liste per AJAX
*/
var Pumpkin_loadData = function (pumpkinlist) {
		$.ajax({
		type: "GET",
		url: pumpkinlist.phpLoadList,
		data: {'kind' : pumpkinlist.list},
		cache: true,
		dataType: "json",
		contentType: "application/json; charset=utf-8",
		success: function (d) {pumpkinlist.data = d;if(pumpkinlist.visible){$('#m_work').append(pumpkinlist.menulink);}if(pumpkinlist.DL){pumpkinlist.draw(1);}}
		});
};

/*
	updated eine Liste per AJAX
*/
var Pumpkin_updateData = function (pumpkinlist) {
	$.ajax({
	type: "GET",
	url: pumpkinlist.phpLoadList,
	data: {'kind' : pumpkinlist.list},
	cache: true,
	dataType: "json",
	contentType: "application/json; charset=utf-8",
	success: function (d) {pumpkinlist.data = d;pumpkinlist.sortme(pumpkinlist.sortCol,true);pumpkinlist.draw(pumpkinlist.page);}
	});
};

/*
	sendet einen neuen Eintrag an die Datenbank
*/
var Pumpkin_saveData = function (pumpkinlist) {
	var params = $('#pmkLSTnewfrm'+pumpkinlist.varname).serialize();
	$.get(pumpkinlist.phpSaveList, params, function (d) {
			pumpkinlist.update();
			$('#pmkLSTnew'+pumpkinlist.varname).remove();
			alert('neuer Eintrag erstellt!');
	});
};

/*
	editiert einen  Eintrag der Datenbank
*/
var Pumpkin_editData = function (pumpkinlist, id) {
	var params = $('#pmkLSTeditfrm'+pumpkinlist.varname+id).serialize();
	$.get(pumpkinlist.phpSaveList, params, function (d) {
			pumpkinlist.update();
			$('#pmkLSTwTBL'+pumpkinlist.varname).remove();
	});
};

var Pumpkin_deleteData = function (pumpkinlist,id) {
	if(confirm("Soll Eintrag wirklich gelöscht werden?")) {
		$('#pmkLSTlnID'+pumpkinlist.varname+id).remove();
		$('#pmkLSTlnWid'+pumpkinlist.varname+id).remove();
		$("#"+pumpkinlist.varname+"_liste_body tr:even").css("backgroundColor", "#bbbbff");	// alle gerade Zeilen einfärben
		$.get(pumpkinlist.phpDeleteList, {'id':pumpkinlist.data[id].id,'table':pumpkinlist.mainTable}, function(d) {
				alert('Eintrag gelöscht!');
				pumpkinlist.update();
		});
	}
};


	/* ***********************************************************************
	**************************************************************************
		Klasse
	**************************************************************************
	*********************************************************************** */ 

var CPumpkin_list = function (title, myvarname, io) {
	
	/* ***********************************************************************
		Variablen
	*********************************************************************** */ 
	// Anzahl Zeilen pro Seite
	this.itemsPerPage = 15;
	
	// Titel der Liste
	this.Title = title;
	
	// Spalten
	this.cols = [];
	
	// PHP Datei, welche die JSON Liste lädt
	this.phpLoadList = 'pumpkin/core/CPumpkinList/CPumpkinList.load.list.php';
	// PHP Datei, welche Eintrag speichert/neu erstellt
	this.phpSaveList = 'pumpkin/core/CPumpkinList/CPumpkinList.save.list.php';
	// PHP Datei, welche Eintrag löscht
	this.phpDeleteList = 'pumpkin/core/CPumpkinList/CPumpkinList.delete.list.php';	
	
	// Eine Zeile in der Tabelle
	this.line = '';
	
	// Name der Variable der Liste
	this.varname = myvarname;
	
	// Aktuelle Seitenposition
	this.page = 1;
	
	// Geschwindigkeit in  mms in welcher die Tabelle beim schliessen zugeht
	this.vhide = 300;
	// Geschwindigkeit in  mms in welcher die Tabelle beim öffnen zugeht
	this.vshow = 400;
	
	// Schreibschutz
	this.io = io;
	
	// Wenn True, dann wird direkt nach dem Laden der Daten die Liste geprintet
	this.DL = false;
	
	// ist die Tabelle nur als Daten verfügbar (zb als Sublist, => false) oder ist sie normal sichtbar
	// default: true => Tabelle bekommt einen Eintrag im Menu
	this.visible = true;
	
	// Tabelle in welche geschrieben wird
	this.mainTable = '';
	
	this.menulink = '<div class="listentry" id="pmkLSTmn'+this.varname+'">- '+this.Title+': '+ 
					'<a id="pmkLSTmnlist'+this.varname+'" href="javascript:'+this.varname+'.draw(1);">Liste</a></div>';
					
					
					
					
	
	// Mit einer Unterliste kann man zwei Pumpklinlisten über einen Identifier A und B verknüpfen
	// Die Verknüpfung geht nur von A nach B
	// Bsp:
	//		this: Adressbuchtabelle -> A
	// 		B	: Freunde von Personen aus Tabelle A
	//		Wenn man in Tabelle A auf eine Person drückt, dann kommen
	//		zusätzlich alle Freunde des Eintrages aufgelistet;
	//		also im Grunde genommen A LEFT JOIN B ON (A.PersonenID = B.PersonenID)
	// allg:
	// this.data LEFT JOIN this.sublistTable ON (this.sublistidentifierA = this.sublistidentifierB)
	
	this.sublist = false;			// per Default besitzt eine Liste keine Unterliste, dieser Wert kann auch zur Laufzeit ein- bzw. ausgeschalten werden
	this.sublistTable = false;		// die Tabelle, welche Untereinträge von A hat
	this.sublistIdentifierA = '';
	this.sublistIdentifierB = '';
	
	

	/* ***********************************************************************
		Eigenschaften
	*********************************************************************** */ 
	
	
	/* ***********************************************************************
		Methoden
	*********************************************************************** */ 
	
	this.loadData = function (list) {
		this.list = list;
		var i = new Pumpkin_loadData (this);
	};
	
	this.update = function () {
		var i = new Pumpkin_updateData (this);
	};
	
	this.setMainTable = function(table) {
		this.mainTable = table;
	};
	
	this.deleteEntry = function(id) {
		var i = new Pumpkin_deleteData(this,id);
	};
	this.editEntry = function(id) {
		var i = new Pumpkin_editData(this,id);
	};
	this.changeItemsPerPage = function(ipp) {
		this.itemsPerPage = ipp;
		this.draw(1);
	};
	
	
	
	
	/*
		Fügt eine Spalte an die Tabelle
		
		Es gibt folgende Spalten Typen:
			FIX: Die Werte der Spalte werden alle in voller Länge dargestellt, egal wie lange sie sind
			DAT: Datum 'yyyy.mm.dd'
			STR: Die werde werden nach x Zeichen abgetrennt, wenn sie diesen Wert überschreiten
			CUR: Währung; der Wert wird auf Franken-Beträge gerundet und gemäss Formatiert
	*/
	this.addCol = function(title, dbtitle, type, io, visibility, input) {
		this.cols[this.cols.length] = [title, dbtitle, type, io, visibility, input];
	};
	
	
	
	
	// Sortierung der Liste
	this.sortCol = 'id';
	this.sortDirection = true;  // true: asc, false: desc
	
	this.sortme = function(col, sortonly){  /* sortonly: wenn auf false, werden bloss die Daten sortiert; die Tabelle wird dann nicht neu gezeichnet */
		$('#wait').show();
		if(this.sortCol!=col){this.sortDirection=true;}
		if(sortonly){this.sortDirection = this.sortDirection?false:true;} // Die Sortierreihenfolge muss wieder rückgängig gemacht werden, da nichts angezeigt wird, wenn sortonly==false
		this.sortCol = col;
		var sortDir = this.sortDirection;
		this.data.sort(function (a, b) {
								var x = a[col].toLowerCase();
								var y = b[col].toLowerCase();
								if (isNum(a[col])&&isNum(b[col])) {
									x = parseFloat(a[col]);
									y = parseFloat(b[col]);
								}
								return (sortDir?1:-1)*(x<y?-1:(x>y?1:0));
		});
		this.sortDirection = sortDir?false:true;
		if(!sortonly){this.draw(this.page);}
		$('#wait').hide();
	};
	
	function isNum(n) {
		return (parseFloat(n)+"")==n;
	}
	
	
	
	/*
	
_____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ 
\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\
	 ______   _______  _______          
	(  __  \ (  ____ )(  ___  )|\     /|
	| (  \  )| (    )|| (   ) || )   ( |
	| |   ) || (____)|| (___) || | _ | |
	| |   | ||     __)|  ___  || |( )| |
	| |   ) || (\ (   | (   ) || || || |
	| (__/  )| ) \ \__| )   ( || () () |
	(______/ |/   \__/|/     \|(_______)
_____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ 
\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\


	*/
	
	/*
		Schreibt die Tabelle und gibt sie aus
	*/
	this.draw = function (page) {
		$('#wait').show();
		var code = '';
		this.page = page;
		if($('#pmkLST'+this.varname).length===0){ // Wenn Liste noch nicht angezeigt wird
			$('#sheet-inside').prepend(this.makeListConstructor(this.cols, this.varname, this.Title, this.vhide)); // Tabellen Konstrukt der Liste einfügen
		}
		else {
			$('#'+this.varname+'_liste_body').empty();  // Zeilen alle löschen / mit draw wird Tabelle in jedem Fall neu gezeichnet
		}
		// Paginater erstellen
		$('#pmkLSTpgn'+this.varname+' ul').empty();	// zuerst alter Seitensatz löschen
		var paginater = '';
		for(i=1;i<(this.data.length/this.itemsPerPage)+1;i++) {
			paginater += '<li><a style="'+(page==i?'color:#F00;background:#B7F8AB':'')+'" href="javascript:" onclick="'+this.varname+'.draw(\''+i+'\')">'+i+'</a></li>';
		}
		$("#pmkLSTpgn"+this.varname+" ul:first").append(paginater); // Paginater Anhängen
		// Zeilen anhängen
		if(this.data.length>0) {
			$('#'+this.varname+'_liste_body').append(this.tablebody(page));	// Zeilen einfügen
		}
		$("#"+this.varname+"_liste_body tr:even").css("backgroundColor", "#bbbbff");	// alle gerade Zeilen einfärben
		// MouseOver-Event
		$("#"+this.varname+"_liste_body tr").mouseover(function() {
			mouseOverRow[this.id] = this.style.backgroundColor;
			this.style.backgroundColor 	= '#777';
			this.style.color		   	= '#FFF';
		});
		// MouseOut-Event
		$("#"+this.varname+"_liste_body tr").mouseout(function() {
			this.style.backgroundColor = mouseOverRow[this.id];
			this.style.color		   = '#000';
		});
		// RightClick Event
		$("#"+this.varname+"_liste_body tr").noContext();
		// null entfernen
		
		if($('#pmkLST'+this.varname).css('display')!='true'){$('#pmkLST'+this.varname).show(this.vshow);}	// Liste anzeigen, wenn nicht schon true
		// Beim ersten mal Programmlogo entfernen
		if($('#pmkPrgrmLogo').css('display')=='block'){$('#pmkPrgrmLogo').remove();}
		$('#wait').hide();
	};
	
	/*
		erstellt Zeilen der Liste
		
		diese Funktion erstellt alle Zeilen der aktuellen 
		Seite der Liste in HTML-Form; dazu wird die
		Template Zeile erstellt, und dannach für jede
		Zeile benutzt.
	*/
	this.tablebody = function (page) {
		var ipp = this.itemsPerPage; 	// Anzahl Zeilen pro Seite
		var start = (page-1)*ipp;		// Erster Datensatz der Seite
		var ende  = (page-1)*ipp+ipp-1;	// Letzter Datensatz der Seite
		var L = this.data.length;if(ende>=L){ende=L-1;}
		var zeilen = '';				// Variable wo alle HTML Zeilen gespeichert werden
		var one_line=this.makeLineTemplate(this.cols);
		for(var i=start;i<=ende;i++) {
			zeilen += '<tr style="cursor:pointer;height:15px;vertical-align:top;" id="pmkLSTlnID'+this.varname+i+'" onclick="'+this.varname+'.showSublist(\''+i+'\');" onmousedown="'+this.varname+'.rightClick(event,'+i+');">'+eval(one_line)+'</tr>';
		}
		return zeilen;
	};
	
	
	this.rightClick = function(evt,i) {
		if( evt.button == 2 ) {
			this.edit(i);
			return false;
		} else {
			return true;
		}		
	};
/*
 _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _ 
(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)
                                                                                                                                       
*/
	
	
	
	
	/*
	
_____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ 
\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\
	 _______  _______  _        _______  _        _______          
	(       )(  ___  )| \    /\(  ____ \( (    /|(  ____ \|\     /|
	| () () || (   ) ||  \  / /| (    \/|  \  ( || (    \/| )   ( |
	| || || || (___) ||  (_/ / | (__    |   \ | || (__    | | _ | |
	| |(_)| ||  ___  ||   _ (  |  __)   | (\ \) ||  __)   | |( )| |
	| |   | || (   ) ||  ( \ \ | (      | | \   || (      | || || |
	| )   ( || )   ( ||  /  \ \| (____/\| )  \  || (____/\| () () |
	|/     \||/     \||_/    \/(_______/|/    )_)(_______/(_______)
	
_____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ 
\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\

		neuer Eintrag erstellen
		editieren eines Eintrages geschieht durch doppelclick eines Eintrages;
		die Zeile vergrössert sich in der Höhe, so dass alle Felder des Eintrages
		sichtbar werden. SO können alle Felder editiert werden;
		
		Funktionen:
		
		ToDo:
		
	*/
	
	this.makenew = function () {
		if($('#pmkLSTnew'+this.varname).length===0){ // Wenn Liste noch nicht angezeigt wird
			var code = '';
			for(var i = 0;i<this.cols.length;i++) {
				// Nur wenn Feld editierbar
				if(this.cols[i][3]=='w' || this.cols[i][3]=='n') {
					switch(this.cols[i][2]) {
						case 'FIX': code += "<tr><td>"+this.cols[i][0]+"</td><td><input type=\"text\" name=\""+this.cols[i][1]+"\" value=\"\" tag=\"pmkinpFIX\" /></td></tr>";break;
						case 'CUR': code += "<tr><td>"+this.cols[i][0]+"</td><td>CHF<input type=\"text\" name=\""+this.cols[i][1]+"\" value=\"\" style=\"text-align:right;\" tag=\"pmkinpCUR\" /></td></tr>";break;
						case 'NUM': code += "<tr><td>"+this.cols[i][0]+"</td><td><input type=\"text\" name=\""+this.cols[i][1]+"\" value=\"\" tag=\"pmkinpNUM\" /></td></tr>";break;
						case 'STR': code += "<tr><td>"+this.cols[i][0]+"</td><td><input type=\"text\" name=\""+this.cols[i][1]+"\" value=\"\" style=\"width:200px;\" tag=\"pmkinpSTR\" /></td></tr>";break;
						case 'DAT': code += "<tr><td>"+this.cols[i][0]+"</td><td><input type=\"text\" name=\""+this.cols[i][1]+"\" value=\"\" style=\"width:80px;\" tag=\"pmkinpDAT\" /></td></tr>";break;
						case 'TXT': code += "<tr><td>"+this.cols[i][0]+"</td><td><textarea name=\""+this.cols[i][1]+"\" style=\"width:200px;border:1px solid;\" rows=\"3\" tag=\"pmkinpTXT\"></textarea></td></tr>";break;
						default: 	code += "<tr><td>"+this.cols[i][0]+"</td><td><input type=\"text\" name=\""+this.cols[i][1]+"\" value=\"\" /></td></tr>";break;
					}
				}
			}
			$('#'+this.varname+'_liste_body').prepend('<tr id="pmkLSTlnWid'+this.varname+'">'+ 
													  '<td colspan="'+this.cols.length+'">'+ 
														  '<form id="pmkLSTnewfrm'+this.varname+'" action="" method="get" enctype="application/x-www-form-urlencoded">'+ 
														  '<table style="border:2px solid #33FF33;width:100%;" id="pmkLSTnew'+this.varname+'">'+ 
															  code+ 
															  '<tr><td>'+ 
																'<input type="button" value="neu" onclick="Pumpkin_saveData('+this.varname+');" />'+ 
																'<input type="button" value="cancel" onclick="$(\'#pmkLSTlnWid'+this.varname+'\').remove();" />'+ 
																'<input type="hidden" value="new" name="act" />'+ 
																'<input type="hidden" value="'+this.mainTable+'" name="table" />'+ 
																'<input type="hidden" value="1" name="typ" />'+ 
															  '</td></tr>'+ 
														  '</table>'+ 
														  '</form>'+ 
													  '</td></tr>');
			// DatePicker für alle Felder mit Datum
			$("#pmkLSTnew"+this.varname+" input[tag='pmkinpDAT']").datepicker({ clearText: '' });
			// Nur Zahlen in den Feldern CUR und NUM erlauben
			$("#pmkLSTnew"+this.varname+" input[tag='pmkinpCUR']").keypress(function (e){if( e.which!=8 && e.which!==0 && e.which!=46 && (e.which<48 || e.which>57)){return false;}});
			$("#pmkLSTnew"+this.varname+" input[tag='pmkinpNUM']").keypress(function (e){if( e.which!=8 && e.which!==0 && e.which!=46 && (e.which<48 || e.which>57)){return false;}});
		}
	};
/*
 _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _ 
(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)
                                                                                                                                       
*/
	
	
	
	/*
	
_____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ 
\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\
	 _______           _______           _______           ______   _       _________ _______ _________
	(  ____ \|\     /|(  ___  )|\     /|(  ____ \|\     /|(  ___ \ ( \      \__   __/(  ____ \\__   __/
	| (    \/| )   ( || (   ) || )   ( || (    \/| )   ( || (   ) )| (         ) (   | (    \/   ) (   
	| (_____ | (___) || |   | || | _ | || (_____ | |   | || (__/ / | |         | |   | (_____    | |   
	(_____  )|  ___  || |   | || |( )| |(_____  )| |   | ||  __ (  | |         | |   (_____  )   | |   
		  ) || (   ) || |   | || || || |      ) || |   | || (  \ \ | |         | |         ) |   | |   
	/\____) || )   ( || (___) || () () |/\____) || (___) || )___) )| (____/\___) (___/\____) |   | |   
	\_______)|/     \|(_______)(_______)\_______)(_______)|/ \___/ (_______/\_______/\_______)   )_( 	
_____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ 
\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\
	
	zeigt eine Unterliste einer Zeile an.

	*/
	
	this.showSublist = function (id) {
			if(this.sublist) {
				if($('#pmkLSTlnSUBrID'+window[this.sublistTable].varname+id).length===0){ // Wenn Liste noch nicht angezeigt wird
					var code_sublist = '';				
					// Suchindex von Tabelle A
					var sublistIdentifierAValue = this.data[id][this.sublistIdentifierA];
					// Zeilen Template von Tabelle B
					var one_line=window[this.sublistTable].makeLineTemplateExt(window[this.sublistTable].cols, this.sublistIdentifierB);
					// Nach suchindex Tabelle A in Tabelle B suchen; bei Erfolg Zeile schreiben
					for(var i = 0;i<window[this.sublistTable].data.length;i++) {
							if(window[this.sublistTable].data[i][this.sublistIdentifierB]==sublistIdentifierAValue) { 
								code_sublist += '<tr style="cursor:pointer;height:15px;vertical-align:top;" id="pmkLSTlnID'+window[this.sublistTable].varname+i+'">'+eval(one_line)+'</tr>';											
							}
					}
					var visible_cols = -1;
					for(i=0;i<window[this.sublistTable].cols.length;i++) {if(window[this.sublistTable].cols[i][4]){visible_cols++;}}
					$('#pmkLSTlnID'+this.varname+id).after('<tr id="pmkLSTlnSUBrID'+window[this.sublistTable].varname+id+'">'+ 
															   '<td colspan="'+((visible_cols>3)?this.cols.length:visible_cols)+'">'+ 
																	'<table style="border:2px solid #F30;width:100%;">'+ 
																		'<thead>'+ 
																			window[this.sublistTable].makeHeaderRowExt(window[this.sublistTable].cols, this.sublistIdentifierB)+ 
																		'</thead>'+ 
																		'<tbody>'+ 
																			code_sublist+ 
																		'</tbody>'+ 
																	'</table>'+ 
																'</td>'+ 
															'</tr>');
				}
			else {
				$('#pmkLSTlnSUBrID'+window[this.sublistTable].varname+id).remove();
			}
		}
	};	
	/*
		erstellt eine "Vorlage" einer Zeile der SUBLISTE
		hat als Rückgabewert einen eval-Code;
		eval()-Form ist notwendig, da er den Code um ca. 500%
		beschleunigt, gegenüber einem normalen Wert;
	*/
	this.makeLineTemplateExt = function (cols, sublistIdentifierB) {
		var code = '';
		for(var i = 0;i<cols.length;i++) {
			// Nur wenn Feld sichtbar und nicht identifier-Spalte
			if(cols[i][4] && cols[i][1]!=sublistIdentifierB) {
				switch(cols[i][2]) {
					case 'FIX': code += "'<td>'+window[this.sublistTable].data[i][\'"+cols[i][1]+"\']+'</td>'+";break;
					case 'NUM': code += "'<td>'+window[this.sublistTable].data[i][\'"+cols[i][1]+"\']+'</td>'+";break;
					case 'DAT': code += "'<td>'+date_mysql2german(window[this.sublistTable].data[i][\'"+cols[i][1]+"\'])+'</td>'+";break;
					case 'STR': code += "'<td>'+window[this.sublistTable].data[i][\'"+cols[i][1]+"\'].substr(0,25)+((window[this.sublistTable].data[i][\'"+cols[i][1]+"\'].length>=25)?'...':'')+'</td>'+";break;
					case 'CUR': code += "'<td align=\"right\">'+number_format(window[this.sublistTable].data[i][\'"+cols[i][1]+"\'],0,\",\")+'.-</td>'+";break;
					default   : code += "'<td>'+window[this.sublistTable].data[i][\'"+cols[i][1]+"\']+'</td>'+";break;
				}
			}
		}
		return code.substr(0,code.length-1);  // letztes Plus wieder löschen!
	};	
	
	/*
		erstellt die Kopfzeile der Liste
	*/
	this.makeHeaderRowExt = function (cols, sublistIdentifierB) {
		// Spaltentitel
		var code = '<tr>';
		for(var i=0;i<this.cols.length;i++) {
			if(this.cols[i][4] && cols[i][1]!=sublistIdentifierB) {
				var prefix = '';
				switch(this.cols[i][2]) {
					case 'FIX':prefix='';break;
					case 'NUM':prefix='';break;
					case 'STR':prefix='';break;
					case 'DAT':prefix='';break;
					case 'CUR':prefix=' align="right"';break;
				}
				code += '<th'+prefix+'>'+this.cols[i][0]+'</th>';
			}
		}
		code += '</tr>';
		return code;
	};
/*
 _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _ 
(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)
                                                                                                                                       
*/

	
	/*
	
_____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ 
\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\
 _______  ______  __________________
(  ____ \(  __  \ \__   __/\__   __/
| (    \/| (  \  )   ) (      ) (   
| (__    | |   ) |   | |      | |   
|  __)   | |   | |   | |      | |   
| (      | |   ) |   | |      | |   
| (____/\| (__/  )___) (___   | |   
(_______/(______/ \_______/   )_(  
_____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ _____ 
\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\\____\


	*/
	
	
	/*
		Eintrag editieren
		editieren eines Eintrages geschieht durch doppelclick eines Eintrages;
		die Zeile vergrössert sich in der Höhe, so dass alle Felder des Eintrages
		sichtbar werden. SO können alle Felder editiert werden;
		
		Funktionen:
		- Verschiedene Inputfelder für versch. Typen
		- Datepicker für Datumsfelder "DAT"
		- Zeichenüberprüfung für NUM und CUR Felder
		
		ToDo:
		- verschiedene Darstellungen; zB Felder in mehreren Spateln oder nur in einer nach unten
		- Speichern Button mit Funktion verknüpfen
	*/
	this.edit = function (id) {
		var code = '';
		if($('#pmkLSTlnWid'+this.varname+id).length===0){ // Wenn Liste noch nicht angezeigt wird
			for(var i = 0;i<this.cols.length;i++) {
				// Nur wenn Feld editierbar
				if(this.cols[i][3]=='w') {
					switch(this.cols[i][2]) {
						case 'FIX': code += "<tr><td>"+this.cols[i][0]+"</td><td><input type=\"text\" name=\""+this.cols[i][1]+"\" value=\""+this.data[id][this.cols[i][1]]+"\" tag=\"pmkinpFIX\" /></td></tr>";break;
						case 'CUR': code += "<tr><td>"+this.cols[i][0]+"</td><td>CHF<input type=\"text\" name=\""+this.cols[i][1]+"\" value=\""+this.data[id][this.cols[i][1]]+"\" style=\"text-align:right;\" tag=\"pmkinpCUR\" /></td></tr>";break;
						case 'NUM': code += "<tr><td>"+this.cols[i][0]+"</td><td><input type=\"text\" name=\""+this.cols[i][1]+"\" value=\""+this.data[id][this.cols[i][1]]+"\" tag=\"pmkinpNUM\" /></td></tr>";break;
						case 'STR': code += "<tr><td>"+this.cols[i][0]+"</td><td><input type=\"text\" name=\""+this.cols[i][1]+"\" value=\""+this.data[id][this.cols[i][1]]+"\" style=\"width:200px;\" tag=\"pmkinpSTR\" /></td></tr>";break;
						case 'DAT': code += "<tr><td>"+this.cols[i][0]+"</td><td><input type=\"text\" name=\""+this.cols[i][1]+"\" value=\""+date_mysql2german(this.data[id][this.cols[i][1]])+"\" style=\"width:80px;\" tag=\"pmkinpDAT\" /></td></tr>";break;
						case 'TXT': code += "<tr><td>"+this.cols[i][0]+"</td><td><textarea name=\""+this.cols[i][1]+"\" style=\"width:200px;border:1px solid;\" rows=\"3\" tag=\"pmkinpTXT\">"+this.data[id][this.cols[i][1]]+"</textarea></td></tr>";break;
						default: 	code += "<tr><td>"+this.cols[i][0]+"</td><td><input type=\"text\" name=\""+this.cols[i][1]+"\" value=\""+this.data[id][this.cols[i][1]]+"\" /></td></tr>";break;
					}
				}
			}
						
			$('#pmkLSTlnID'+this.varname+id).after('<tr id="pmkLSTlnWid'+this.varname+id+'">'+ 
												   '<td colspan="'+this.cols.length+'">'+ 
												   '<form id="pmkLSTeditfrm'+this.varname+id+'" action="" method="get" enctype="application/x-www-form-urlencoded">'+ 
												   		'<table style="border:2px solid #F30;width:100%;display:block;" id="pmkLSTwTBL'+this.varname+'">'+ 
															'<thead></thead>'+ 
															'<tbody>'+ 
																code+ 
																'<tr>'+ 
																	'<td>'+ 
																		'<input type="button" value="speichern" onclick="'+this.varname+'.editEntry(\''+id+'\');" />'+ 
																		'<input type="button" value="cancel" onclick="$(\'#pmkLSTlnWid'+this.varname+id+'\').remove();" />'+ 
																		((this.io)?'<input type="button" value="l&ouml;schen" onclick="'+this.varname+'.deleteEntry(\''+id+'\');" />':'')+ 
																		'<input type="hidden" value="edit" name="act" />'+ 
																		'<input type="hidden" value="'+this.mainTable+'" name="table" />'+ 
																		'<input type="hidden" value="'+this.data[id].id+'" name="id" />'+ 
																	'</td>'+ 
																'</tr>'+ 
															'</tbody>'+ 
														'</table>'+ 
														'</form>'+ 
													'</td>'+ 
													'</tr>');
			// DatePicker für alle Felder mit Datum
			$("#pmkLSTwTBL"+this.varname+" input[tag='pmkinpDAT']").datepicker({ clearText: '' });
			// Nur Zahlen in den Feldern CUR und NUM erlauben
			$("#pmkLSTwTBL"+this.varname+" input[tag='pmkinpCUR']").keypress(function (e){if( e.which!=8 && e.which!==0 && e.which!=46 && (e.which<48 || e.which>57)){return false;}});
			$("#pmkLSTwTBL"+this.varname+" input[tag='pmkinpNUM']").keypress(function (e){if( e.which!=8 && e.which!==0 && e.which!=46 && (e.which<48 || e.which>57)){return false;}});
		}
		// Editfeld wird bereits angezeigt, dann machen wir es wieder weg
		else {
			$('#pmkLSTlnWid'+this.varname+id).remove();
		}
	};
/*
 _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _ 
(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)
                                                                                                                                       
*/
	
	
	/*
		erstellt die Kopfzeile der Liste
	*/
	this.makeHeaderRow = function () {
		// Spaltentitel
		var code = '<tr>';
		for(var i=0;i<this.cols.length;i++) {
			if(this.cols[i][4]) {
				var prefix = '';
				switch(this.cols[i][2]) {
					case 'FIX':prefix='';break;
					case 'NUM':prefix='';break;
					case 'STR':prefix='';break;
					case 'DAT':prefix='';break;
					case 'CUR':prefix=' align="right"';break;
				}
				code += '<th'+prefix+' onclick="'+this.varname+'.sortme(\''+this.cols[i][1]+'\', false);" style="cursor:n-resize;">'+this.cols[i][0]+'</th>';
			}
		}
		code += '</tr>';
		return code;
	};
	
	
/*
 _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _ 
(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)
                                                                                                                                       
*/
	
	
	/* ***********************************************************************
		Private Functions
	*********************************************************************** */ 
	
	
	
	/*
		Gesamtes Tabellen Konstrukt, jedoch noch ohne Daten
	*/
	
	this.makeListConstructor = function (cols, varname, Title, vhide) {
		return 	'<div id="pmkLST'+varname+'" style="display:none;">'+ 
					'<h1 style="margin:0px;">'+Title+'</h1>'+ 
					'<h6 style="margin:0px;">'+ 
						(io?'<a href="javascript:'+varname+'.makenew();" id="pmkLSTmnnew'+varname+'">neu</a>&nbsp;|&nbsp;':'')+ 
						'<a href="javascript:" onclick="'+varname+'.update();">aktualisieren</a>&nbsp;|&nbsp;'+ 
						'<a href="javascript:" onclick="$(\'#pmkLST'+varname+'\').hide('+vhide+');">schliessen</a>'+ 
					'</h6>'+ 
					'<div class="pmkLSTpgn" id="pmkLSTpgn'+varname+'"><ul><li>&nbsp;</li></ul></div>'+ 
					'<div style="font-size:8pt;position:relative;top:-80px;margin:7px;left:780px;text-align:left;float:left;">'+ 
						'zeige<form action="return false;" method="GET" name="pmkLSTippForm'+this.varname+'">'+ 
							'<select name="pmkLSTippSelect'+this.varname+'" style="width:40px;" onchange="'+this.varname+'.changeItemsPerPage(this.value);">'+ 
								'<option value="15">15</option>'+ 
								'<option value="25">25</option>'+ 
								'<option value="50">50</option>'+ 
								'<option value="100">100</option>'+ 
							'</select>'+ 
						'</form>Eintr&auml;ge</div>'+ 
					'<table border="0" id="table_'+varname+'" class="tableListe1" cellspacing="0">'+ 
						'<thead>'+this.makeHeaderRow()+'</thead>'+ 
						'<tbody id="'+varname+'_liste_body"></tbody>'+ 
					'</table><p>&nbsp;</p>'+ 
				'</div>';
	};
/*
 _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _ 
(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)
                                                                                                                                       
*/

	
	/*
		erstellt eine "Vorlage" einer Zeile der Liste;
		hat als Rückgabewert einen eval-Code;
		dieser ist notwendig, da er den Code um ca. 500%
		beschleunigt, gegenüber einem normalen Wert;
	*/
	this.makeLineTemplate = function (cols) {
		var code = '';
		for(var i = 0;i<cols.length;i++) {
			// Nur wenn Feld editierbar
			if(cols[i][4]) {
				switch(cols[i][2]) {
					case 'FIX': code += "'<td>'+this.data[i][\'"+cols[i][1]+"\']+'</td>'+";break;
					case 'NUM': code += "'<td>'+this.data[i][\'"+cols[i][1]+"\']+'</td>'+";break;
					case 'DAT': code += "'<td>'+date_mysql2german(this.data[i][\'"+cols[i][1]+"\'])+'</td>'+";break;
					case 'STR': code += "'<td>'+this.data[i][\'"+cols[i][1]+"\'].substr(0,25)+((this.data[i][\'"+cols[i][1]+"\'].length>=25)?'...':'')+'</td>'+";break;
					case 'CUR': code += "'<td align=\"right\">'+number_format(this.data[i][\'"+cols[i][1]+"\'],0,\",\",\"`\")+'.-</td>'+";break;
					default   : code += "'<td>'+this.data[i][\'"+cols[i][1]+"\']+'</td>'+";break;
				}
			}
		}
		return code.substr(0,code.length-1);  // letztes Plus wieder löschen!
	};
	
/*
 _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _ 
(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)(_)
                                                                                                                                       
*/

};


/*
End of Class
 _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _  _ 
( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )( \/ )
 )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  (  )  ( 
(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)(_/\_)
End of Class
*/










