function validaForm() { 

	var nome = document.forms["formLocandina"]["nome"].value;
	var cognome = document.forms["formLocandina"]["cognome"].value;
	var seminario = document.forms["formLocandina"]["seminario"].value;
	var data = document.forms["formLocandina"]["data"].value;
	var luogo = document.forms["formLocandina"]["luogo"].value;
	var orario_inizio = document.forms["formLocandina"]["orario_inizio"].value;
	var descrizione = document.forms["formLocandina"]["descrizione"].value;
	var descr_dividi = descrizione.split(/\r|\r\n|\n/);
	var descr_conta = descr_dividi.length;

	if (nome == ""){
	document.getElementById("nome").innerHTML = "Inserisci nome";  
	return false;
	};

	if (cognome == ""){
	document.getElementById("cognome").innerHTML = "Inserisci cognome";  
	return false;
	};
	if (seminario == ""){
	document.getElementById("seminario").innerHTML = "Inserisci titolo seminario";  
	return false;
	};

	if (data == ""){
	document.getElementById("data").innerHTML = "Inserisci data";  
	return false;
	};

	if (luogo == ""){
	document.getElementById("luogo").innerHTML = "Inserisci luogo";  
	return false;
	};

	if (orario_inizio == ""){
	document.getElementById("orario_inizio").innerHTML = "Inserisci l'orario dell'inizio";  
	return false;
	};

	if (descrizione == ""){
	document.getElementById("descrizione").innerHTML = "Inserisci la descrizione";  
	return false;
	};

	if (descr_conta > 46){
	document.getElementById("descrizione").innerHTML = "Numero righe descrizione superato";  
	return false;
	};

}
