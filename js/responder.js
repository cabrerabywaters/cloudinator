function getUrlParameter(name) {
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
    );
}
function responderpregunta(idnode, idlev, idsubform, idpregunta, respsubpregunta, idclone){
	console.log("respsubpregunta",respsubpregunta);
	$.post("ajax/ajaxresponder.php",{ 
		idnode: idnode,
		idlev: idlev,
		idsubform: idsubform,
		action: 'insert',
		idpregunta: idpregunta,
		idempresa: getUrlParameter('emp'), 
		respsubpregunta: respsubpregunta,
		idclone: idclone
		},function(respuesta){
			console.log(respuesta);
			var resp = jQuery.parseJSON(respuesta);
			
			//si la respuesta es positiva se continua, si no mensaje de error
			if(resp.result){
				if(idclone!=0){
					window.location.href = "responder.php?idlev=" + idlev + "&idclone=" + idclone;
				}else{
					window.location.href = "responder.php?idlev=" + idlev + "&idsubform=" + idsubform;
				}
			}else{
				alert("problemas con escribir en la base de datos");
			}
		});
}

function borrarUltimaPreguntaRespondida(idsubform, idlev){
	
	$.post("ajax/ajaxresponder.php",{ 
		action: 'deletelast',
		idlev: idlev,
		idsubform: idsubform
		},function(respuesta){
			var resp = jQuery.parseJSON(respuesta);
			console.log("resp", resp);
			//si la respuesta es positiva se continua, si no mensaje de error
			if(resp.result){
				window.location.href = "responder.php?idlev=" + idlev+"&idsubform="+idsubform;
			}else{
				alert("problemas con escribir en la base de datos");
			}
		});
	}

function SubPregunta(idpregunta, idnode, idlev, idsubform, idclone){
	
	$.post("ajax/ajaxresponder.php",{ 
		action: 'subpregunta',
		idpregunta: idnode
		},function(respuesta){
			var resp = jQuery.parseJSON(respuesta);
			//si la respuesta es positiva se continua, si no mensaje de error
			if(resp.result){
				//si tiene subpregunta, se hace, si no se responde pregunta
				if(resp.subpregunta){
					if(resp.node.metatype == "textarea"){
						
						$("#textopregunta").text(resp.node.metaname);
						$("#select-choice").remove();
						$("#select-choice-label").remove();
						$('#popupSubpregunta').popup("open");
						$("#formsubpregunta").append("<input type='hidden' id='idnode' name='idnode' value='"+idnode+"' >");
		
					}else if(resp.node.metatype == "array"){
						$("#textopregunta").text(resp.node.metaname);
						$("#textarea").remove();
						$("#textarea-label").remove();
						
						var array = resp.node.metadata.split(',');
						$("#select-choice").empty();
						$.each( array, function( key, value ) {
							$("#select-choice").append("<option value="+value+">"+value+"</option>");
							});
						
						$("#formsubpregunta").append("<input type='hidden' name='idnode' id='idnode' value='"+idnode+"' >");
						console.log("metadata",array);
						$('#popupSubpregunta').popup("open");
					}
				}else{
					responderpregunta(idnode, idlev, idsubform, idpregunta, null, idclone);
				}
			}else{
				alert("problemas con escribir en la base de datos");
			}
		});		
}

$(document).ready(function(){
	
	$(".backtoRecorrer").on('click', function(){
		var idlev = $(this).data('idlev');
		var idform = $(this).data('idform');
		window.location.href = "recorrer.php?idlev="+idlev+ "&idform="+idform;

	});
	$(".backtoIndex").on('click', function(){
		window.location.href = "index.php";
	});
	$(".backtoLevantamiento").on('click', function(){
		var emp = $(this).data('idemp');
		window.location.href = "levantamiento.php?emp="+emp;
	});
	
	$(".answer").on('click', function(){
	
		var idnode = $(this).data('idnode');	
		var idlev = $(this).data('idlev');	
		var idsubform = $(this).data('idsubform');	
		var idpregunta = $(this).data('idpregunta');
		var idclone =  $(this).data('idclone');
		
		SubPregunta(idpregunta, idnode, idlev, idsubform, idclone);
	});
	$("#responderquit").on('click', function(){
		var emp = $(this).data('emp');
		var idlev = $(this).data('idlev');
		window.location.href = "recorrer.php?emp="+emp+"&idlev="+idlev;
	});
	
	$("#responderback").on('click', function(){
		var idsubform = $(this).data('idsubform');
		var idlev = $(this).data('idlev');
		var idreg = $(this).data('idreg');
		if(idreg == 0){
			alert("No hay preguntas anteriores");
		}else{
			window.location.href = "responder.php?idlev=" + idlev + "&idsubform=" + idsubform + "&idpreg="+idreg;
		}
		//delete ultima pregunta respondida
		//borrarUltimaPreguntaRespondida(idsubform, idlev);
		
	});
	
	$("#respondersubpregunta").on('click', function(){
		var idsubform = $("#idsubform").val();
		var idclone = $("#idclone").val();
		var idlev = $("#idlev").val();
		var idpregunta = $("#idpregunta").val();
		var select = $("#select-choice").val();
		var textarea =$("#textarea").val(); 
		var idnode =$("#idnode").val(); 
		
		if(typeof(select) != "undefined" && select !== null) {
			var response = select;
		}else{
			var response =textarea;
		}
		
		responderpregunta(idnode, idlev, idsubform, idpregunta, response, idclone);
	});
	$("#omitirsubpregunta").on('click', function(){
		var idsubform = $("#idsubform").val();
		var idclone = $("#idclone").val();
		var idlev = $("#idlev").val();
		var idpregunta = $("#idpregunta").val();
		var idnode =$("#idnode").val(); 

		responderpregunta(idnode, idlev, idsubform, idpregunta, "Omitida", idclone);
	});
	$(".gobacktoquestion").on('click', function(){
		var idpregunta = $(this).data('id');
		var idsubform = $(this).data('idsubform');
		var idlev = $(this).data('idlev');
		var idclone = $(this).data('idclone');
		if(idclone){
			window.location.href = "responder.php?idlev=" + idlev + "&idclone=" + idclone + "&idpreg="+idpregunta;
		}else{
			window.location.href = "responder.php?idlev=" + idlev + "&idsubform=" + idsubform + "&idpreg="+idpregunta;
		}
	});
	$(".cerrarsesion").on('click', function(){
		$.post("server/session.php",{ 
			action: "deleteall"
			},function(respuesta){
				window.location.href = "index.php";
				console.log("cierra sesion");
			});
	});
	
	$(".usuarios").on('click', function(){
		window.location.href = "usuarios.php";
	});
	$(".edicion").on('click', function(){
		
		if(getUrlParameter("edit") == 1){
			window.location.href = "recorrer.php?emp="+getUrlParameter("emp")+"&idlev="+getUrlParameter("idlev");
		}else{
			window.location.href = "recorrer.php?emp="+getUrlParameter("emp")+"&idlev="+getUrlParameter("idlev") +"&edit=1";
		}
	});
	$(".editor").on('click', function(){
		window.location.href = "editor.php";
	});
	$(".gestionempresas").on('click', function(){
		window.location.href = "empresas.php";
	});
});