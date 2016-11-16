/**
 * Created by PhpStorm.
 * User: Jose Franco
 * Date: 18/04/2016
 * Time: 19:39
 */
//-----------------------------------------------------------------------------

const DEFAULT_SPEED = 750;
const EMAIL_REGEX = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
var baseURL = "";
var uploadedImage = "";

function isEmail(text) {
	return EMAIL_REGEX.test(text);
}

function redirect (url) {
    var ua        = navigator.userAgent.toLowerCase(),
        isIE      = ua.indexOf('msie') !== -1,
        version   = parseInt(ua.substr(4, 2), 10);

    // Internet Explorer 8 and lower
    if (isIE && version < 9) {
        var link = document.createElement('a');
        link.href = url;
        document.body.appendChild(link);
        link.click();
    }

    // All other browsers can use the standard window.location.href (they don't lose HTTP_REFERER like IE8 & lower does)
    else { 
        window.location.href = url; 
    }
}

function applyBaseURL(url) {
	baseURL = url;
}

function applyUploadedImage(url) {
	uploadedImage = url;
}


$(document).ready(function() {

	/*
	 *
	 *    DISTRITO -> CONCELHO -> FREGUESIA
	 *
	 */
	$("#select_distrito").change(function () {
		if ( $("#select_distrito").val() == 0 ) {
			$("#optgroup_concelho").empty();
			$("#optgroup_concelho").append("<option value=\"0\">Concelho</option>");
			$("#optgroup_freguesia").empty();
			$("#optgroup_freguesia").append("<option value=\"0\">Freguesia</option>");
			$("#select_concelho").val("0");
			$("#select_freguesia").val("0");
			$("#select_concelho").select2();
			$("#select_freguesia").select2();
		}
		else {
			$.ajax({
				type: "POST",
				data: {
					distrito: $("#select_distrito").val()
				},
				url: baseURL + "index.php/localidades/parse/",


				success: function(data) {
					$("#optgroup_concelho").empty();
					$("#optgroup_concelho").append("<option value=\"0\">Concelho</option>");
					$.each(data, function(i, data) {
						$("#optgroup_concelho").append("<option value='"+ data.ID_CONCELHO +"'>" + data.CONCELHO + "</option>");
					});
					$("#select_concelho").select2();
				}
			});
		}
	});

	$("#select_concelho").change(function () {
		if ( $("#select_concelho").val() == 0 ) {
			$("#optgroup_freguesia").empty();
			$("#optgroup_freguesia").append("<option value=\"0\">Freguesia</option>");
			$("#select_freguesia").val("0");
			$("#select_freguesia").select2();
		}
		else {
			$.ajax({
				type: "POST",
				data: {
					concelho: $("#select_concelho").val()
				},
				url: baseURL + "index.php/localidades/parse/",


				success: function(data) {
					$("#optgroup_freguesia").empty();
					$("#optgroup_freguesia").append("<option value=\"0\">Freguesia</option>");
					$.each(data, function(i, data) {
						$("#optgroup_freguesia").append("<option value='"+ data.ID_FREGUESIA +"'>" + data.FREGUESIA + "</option>");
					});
					$("#select_freguesia").select2();
				}
			});
		}
	});


	/*
	 *
	 * USER PASSWORD/EMAIL REGISTRATION FORM
	 *
	 ------------------------------------------------------------------------*/
	var email_ok    = false;
	var password_ok = false;

	/*
	 * DEFAULT VALUES
	 */
	$("#label_email_icon").hide();
	$("#label_email_helpblock").hide();
	$("#label_password_icon").hide();
	$("#label_password_helpblock").hide();
	$("#label_password_confirm_icon").hide();
	$("#label_password_confirm_helpblock").hide();
    $("#label_funcao_icon").hide();
    $("#label_funcao_helpblock").hide();

	// change button to disabled
	$(".submit_user_auth").attr({
		"class" : "btn btn-lg btn-success btn-embossed btn-wide pull-right submit_user_auth disabled"
	});

	/*
	 * Email input form
	 */
   	$("#label_email").change(function() {
   		var email = $("#label_email").val();

   		if ( email === "" ) {
   			// change div_label_email to default!
   			$("#div_label_email").attr({
   				"class" : "form-group has-feedback"
   			});

   			// hide
   			$("#label_email_icon").hide(DEFAULT_SPEED);

   			// hide any helper text
   			$("#label_email_helpblock").hide(DEFAULT_SPEED);

   			// set ok flag
   			email_ok = false;

   		}
   		else if ( email.length > 50 ) {
   			// change div_label_email to red!
			$("#div_label_email").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a check mark
			$("#label_email_icon").show(DEFAULT_SPEED);
			$("#label_email_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_email_helpblock").show(DEFAULT_SPEED);
			$("#label_email_helpblock").text("O e-mail que inseriu e demasiado grande.");

			// set ok flag
			email_ok = false;
   		}
   		else if ( isEmail(email) ) {

			$.ajax({
				type: "POST",
				data: {
					email_check: email
				},
				url: baseURL + "index.php/registo/",

				success: function(data) {
					if ( data.available ) {
						// change div_label_email to green!
						$("#div_label_email").attr({
							"class" : "form-group has-success has-feedback"
						});

						// show the icon as a check mark
						$("#label_email_icon").show(DEFAULT_SPEED);
						$("#label_email_icon").attr({
							"class" : "glyphicon glyphicon-ok form-control-feedback"
						});

						// hide any helper text
						$("#label_email_helpblock").hide(DEFAULT_SPEED);

						// set ok flag
						email_ok = true;

						if ( password_ok != false ) {
							$(".submit_user_auth").attr({
								"class": "btn btn-lg btn-success btn-embossed btn-wide pull-right submit_user_auth"
							});
						}
						else {
							$(".submit_user_auth").attr({
								"class" : "btn btn-lg btn-success btn-embossed btn-wide pull-right submit_user_auth disabled"
							});
						}
					}
					else {
						// change div_label_email to red!
						$("#div_label_email").attr({
							"class" : "form-group has-error has-feedback"
						});

						// show the icon as a check mark
						$("#label_email_icon").show(DEFAULT_SPEED);
						$("#label_email_icon").attr({
							"class" : "glyphicon glyphicon-remove form-control-feedback"
						});

						// give helper text
						$("#label_email_helpblock").show(DEFAULT_SPEED);
						$("#label_email_helpblock").text("Esse e-mail ja esta registado neste servico.");

						// set ok flag
						email_ok = false;

						$(".submit_user_auth").attr({
							"class" : "btn btn-lg btn-success btn-embossed btn-wide pull-right submit_user_auth disabled"
						});
					}
				}
			})

		}
		else {

			// change div_label_email to red!
			$("#div_label_email").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a check mark
			$("#label_email_icon").show(DEFAULT_SPEED);
			$("#label_email_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_email_helpblock").show(DEFAULT_SPEED);
			$("#label_email_helpblock").text("O e-mail que inseriu nao e valido.");

			// set ok flag
			email_ok = false;
		}

		// is everything ok?
		if ( email_ok && password_ok ) {
			$(".submit_user_auth").attr({
				"class" : "btn btn-lg btn-success btn-embossed btn-wide pull-right submit_user_auth"
			});
		}
		else {
			$(".submit_user_auth").attr({
				"class" : "btn btn-lg btn-success btn-embossed btn-wide pull-right submit_user_auth disabled"
			});
		}
   	});

   	/*
	 * Password form
	 */
	var password = "";
	$("#label_password").change(function() {

		// atualizar a password
		password = $("#label_password").val();

      // colocar a confirmacao da password a default
      $("#div_label_password_confirm").attr({
         "class" : "form-group has-feedback"
      });
      $("#label_password_confirm").val("");
      $("#label_password_confirm_icon").hide(DEFAULT_SPEED);
      $("#label_password_confirm_helpblock").hide(DEFAULT_SPEED);

		if ( password === "" ) {
			// change div_label_email to red!
   			$("#div_label_password").attr({
   				"class" : "form-group has-error has-feedback"
   			});

   			// show the icon as a check mark
   			$("#label_password_icon").show(DEFAULT_SPEED);
   			$("#label_password_icon").attr({
   				"class" : "glyphicon glyphicon-remove form-control-feedback"
   			});

   			// give helper text
   			$("#label_password_helpblock").show(DEFAULT_SPEED);
   			$("#label_password_helpblock").text("A password nao pode estar vazia.");

			password_ok = false;
		}
		else {
			// change div_label_email to green!
   			$("#div_label_password").attr({
   				"class" : "form-group has-success has-feedback"
   			});

   			// show the icon as a check mark
   			$("#label_password_icon").show(DEFAULT_SPEED);
   			$("#label_password_icon").attr({
   				"class" : "glyphicon glyphicon-ok form-control-feedback"
   			});

   			// hide any helper text
   			$("#label_password_helpblock").hide(DEFAULT_SPEED);
		}

		// is everything ok?
		if ( email_ok && password_ok ) {
			$(".submit_user_auth").attr({
				"class" : "btn btn-lg btn-success btn-embossed btn-wide pull-right submit_user_auth"
			});
		}
		else {
			$(".submit_user_auth").attr({
				"class" : "btn btn-lg btn-success btn-embossed btn-wide pull-right submit_user_auth disabled"
			});
		}
	});
	 /*
	  * Password Confirmation form
	  */
	$("#label_password_confirm").change(function() {

		// atualizar a password
		passwordConfirm = $("#label_password_confirm").val();


		if ( passwordConfirm === "" ) {
		   // change div_label_email to red!
			$("#div_label_password_confirm").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a check mark
			$("#label_password_confirm_icon").show(DEFAULT_SPEED);
			$("#label_password_confirm_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_password_confirm_helpblock").show(DEFAULT_SPEED);
			$("#label_password_confirm_helpblock").text("A password nao pode estar vazia.");

			// set ok flag
			password_ok = false;
		}
		else if ( password != passwordConfirm ) {
		   // change div_label_email to red!
			$("#div_label_password_confirm").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a check mark
			$("#label_password_confirm_icon").show(DEFAULT_SPEED);
			$("#label_password_confirm_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_password_confirm_helpblock").show(DEFAULT_SPEED);
			$("#label_password_confirm_helpblock").text("A password inserida nao e igual.");

			// set ok flag
			password_ok = false;
		}
		else {
			// change div_label_email to green!
			$("#div_label_password_confirm").attr({
				"class" : "form-group has-success has-feedback"
			});

			// show the icon as a check mark
			$("#label_password_confirm_icon").show(DEFAULT_SPEED);
			$("#label_password_confirm_icon").attr({
				"class" : "glyphicon glyphicon-ok form-control-feedback"
			});

			// hide any helper text
			$("#label_password_confirm_helpblock").hide(DEFAULT_SPEED);

			// set ok flag
			password_ok = true;
		}

		// is everything ok?
		if ( email_ok && password_ok ) {
			$(".submit_user_auth").attr({
				"class" : "btn btn-lg btn-success btn-embossed btn-wide pull-right submit_user_auth"
			});
		}
		else {
			$(".submit_user_auth").attr({
				"class" : "btn btn-lg btn-success btn-embossed btn-wide pull-right submit_user_auth disabled"
			});
		}
	});

	$("#submit_voluntario").click(function () {
		if ( email_ok && password_ok ) {
			$.ajax({
				type: "POST",
				data: {
					email: $("#label_email").val(),
					password: $("#label_password_confirm").val(),
					voluntario: true
				},
				url: baseURL + "index.php/registo/",

				success: function(data) {
					if ( data.response )
						redirect(baseURL + "index.php/registo/voluntario/");
				}
            });
		}
	});

	$("#submit_inst").click(function () {
		if ( email_ok && password_ok ) {
			$.ajax({
				type: "POST",
				data: {
					email: $("#label_email").val(),
					password: $("#label_password_confirm").val(),
					voluntario: false
				},
				url: baseURL + "index.php/registo/",

				success: function(data) {
					if ( data.response )
						redirect(baseURL + "index.php/registo/instituicao/");
				}
            });
		}
	});


	/*
	 *
	 * VOLUNTEER & INSTITUTION REGISTRATION PROCESS
	 *
	 ------------------------------------------------------------------------*/
	var nome_ok         = false;
	var phone_ok        = false;
	var birth_ok        = false;
	var interesses_ok   = false;
	var localidade_ok   = false;
	var grupos_ok       = false;
	var habilitacoes_ok = false;
	var website_ok  	= false;
	var nomerep_ok 		= false;
	var emailrep_ok 	= false;
	var morada_ok 		= false;
	var descricao_ok 	= false;

	/*
	 * DEFAULT VALUES
	 */
	$("#label_nome_icon").hide();
	$("#label_nome_helpblock").hide();
	$("#label_phone_icon").hide();
	$("#label_phone_helpblock").hide();
	$("#label_birth_icon").hide();
	$("#label_birth_helpblock").hide();
	$("#radio_helpblock").hide();
	$("#label_website_icon").hide();
	$("#label_website_helpblock").hide();
	$("#label_nomerep_icon").hide();
	$("#label_nomerep_helpblock").hide();
	$("#label_emailrep_icon").hide();
	$("#label_emailrep_helpblock").hide();
	$("#label_morada_icon").hide();
	$("#label_morada_helpblock").hide();
	$("#label_desc_icon").hide();
	$("#label_desc_helpblock").hide();

	// nome do voluntario / Instituicao
	$("#label_nome").change(function() {
		if ( $("#label_nome").val() === "" ) {
			// change to red
			$("#div_label_nome").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_nome_icon").show(DEFAULT_SPEED);
			$("#label_nome_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_nome_helpblock").show(DEFAULT_SPEED);
			$("#label_nome_helpblock").text("Este campo nao pode estar vazio.");

			nome_ok = false;
		}
		else if ( $("#label_nome").val().length > 50 ) {
   			// change to red
			$("#div_label_nome").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_nome_icon").show(DEFAULT_SPEED);
			$("#label_nome_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_nome_helpblock").show(DEFAULT_SPEED);
			$("#label_nome_helpblock").text("Nome demasiado grande.");

			nome_ok = false;
   		}
		else {
			// change to green
			$("#div_label_nome").attr({
				"class" : "form-group has-success has-feedback"
			});

			// show the icon as a X mark
			$("#label_nome_icon").show(DEFAULT_SPEED);
			$("#label_nome_icon").attr({
				"class" : "glyphicon glyphicon-ok form-control-feedback"
			});

			// give helper text
			$("#label_nome_helpblock").hide(DEFAULT_SPEED);

			nome_ok = true;
		}
	});

	//nome_representante
	$("#label_nomerep").change(function() {
		if ( $("#label_nomerep").val() === "" ) {
			// change to red
			$("#div_label_nomerep").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_nomerep_icon").show(DEFAULT_SPEED);
			$("#label_nomerep_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_nomerep_helpblock").show(DEFAULT_SPEED);
			$("#label_nomerep_helpblock").text("Este campo nao pode estar vazio.");

			nomerep_ok = false;
		}
		else if ( $("#label_nomerep").val().length > 50 ) {
   			// change to red
			$("#div_label_nomerep").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_nomerep_icon").show(DEFAULT_SPEED);
			$("#label_nomerep_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_nomerep_helpblock").show(DEFAULT_SPEED);
			$("#label_nomerep_helpblock").text("Nome do representante demasiado grande.");

			nomerep_ok = false;
   		}
		else {
			// change to green
			$("#div_label_nomerep").attr({
				"class" : "form-group has-success has-feedback"
			});

			// show the icon as a X mark
			$("#label_nomerep_icon").show(DEFAULT_SPEED);
			$("#label_nomerep_icon").attr({
				"class" : "glyphicon glyphicon-ok form-control-feedback"
			});

			// give helper text
			$("#label_nomerep_helpblock").hide(DEFAULT_SPEED);

			nome_ok = true;
		}
	});

	//email do representante
	$("#label_emailrep").change(function() {

		var emailrep = $("#label_emailrep").val();

		if ( $("#label_emailrep").val() === "" ) {
			// change to red
			$("#div_label_emailrep").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_emailrep_icon").show(DEFAULT_SPEED);
			$("#label_emailrep_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_emailrep_helpblock").show(DEFAULT_SPEED);
			$("#label_emailrep_helpblock").text("Este campo nao pode estar vazio.");

			emailrep_ok = false;
		}
		else if ( !isEmail(emailrep) ) {
			// change to red
			$("#div_label_emailrep").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_emailrep_icon").show(DEFAULT_SPEED);
			$("#label_emailrep_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_emailrep_helpblock").show(DEFAULT_SPEED);
			$("#label_emailrep_helpblock").text("Tem que estar no formato de email");

			emailrep_ok = false;
		}
		else {
			// change to green
			$("#div_label_emailrep").attr({
				"class" : "form-group has-success has-feedback"
			});

			// show the icon as a X mark
			$("#label_emailrep_icon").show(DEFAULT_SPEED);
			$("#label_emailrep_icon").attr({
				"class" : "glyphicon glyphicon-ok form-control-feedback"
			});

			// give helper text
			$("#label_emailrep_helpblock").hide(DEFAULT_SPEED);

			emailrep_ok = true;
		}
	});

	// telefone do voluntario
	$("#label_phone").change(function() {

		var phoneNumber = $("#label_phone").val();
		var isNumber = /^\d{9}$/.test(phoneNumber);

		if ( $("#label_phone").val() === "" ) {
			// change to red
			$("#div_label_phone").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_phone_icon").show(DEFAULT_SPEED);
			$("#label_phone_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_phone_helpblock").show(DEFAULT_SPEED);
			$("#label_phone_helpblock").text("Este campo nao pode estar vazio.");

			phone_ok = false;
		}
		else if ( !isNumber ) {
			// change to red
			$("#div_label_phone").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_phone_icon").show(DEFAULT_SPEED);
			$("#label_phone_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_phone_helpblock").show(DEFAULT_SPEED);
			$("#label_phone_helpblock").text("Tem que ser um numero de telefone.");

			phone_ok = false;
		}
		else {
			// change to green
			$("#div_label_phone").attr({
				"class" : "form-group has-success has-feedback"
			});

			// show the icon as a X mark
			$("#label_phone_icon").show(DEFAULT_SPEED);
			$("#label_phone_icon").attr({
				"class" : "glyphicon glyphicon-ok form-control-feedback"
			});

			// give helper text
			$("#label_phone_helpblock").hide(DEFAULT_SPEED);

			phone_ok = true;
		}
	});

	// data de nascimento do voluntario
	$("#label_birth").change(function() {

		var birthDate = $("#label_birth").val();
		var isBirth = /^(\d{1,2})\-(\d{1,2})\-(\d{4})$/.test(birthDate);

		if ( $("#label_birth").val() === "" ) {
			// change to red
			$("#div_label_birth").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_birth_icon").show(DEFAULT_SPEED);
			$("#label_birth_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_birth_helpblock").show(DEFAULT_SPEED);
			$("#label_birth_helpblock").text("Este campo nao pode estar vazio.");

			birth_ok = false;
		}
		else if ( !isBirth ) {
			// change to red
			$("#div_label_birth").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_birth_icon").show(DEFAULT_SPEED);
			$("#label_birth_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_birth_helpblock").show(DEFAULT_SPEED);
			$("#label_birth_helpblock").text("Tem que estar no formato dd-mm-aaaa");

			birth_ok = false;
		}
		else {
			// change to green
			$("#div_label_birth").attr({
				"class" : "form-group has-success has-feedback"
			});

			// show the icon as a X mark
			$("#label_birth_icon").show(DEFAULT_SPEED);
			$("#label_birth_icon").attr({
				"class" : "glyphicon glyphicon-ok form-control-feedback"
			});

			// give helper text
			$("#label_birth_helpblock").hide(DEFAULT_SPEED);

			birth_ok = true;
		}
	});

	// website instituicao
	$("#label_website").change(function() {

		var website = $("#label_website").val();
		var isWebsite = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi.test(website);

		if ( $("#label_website").val() === "" ) {
			// change to red
			$("#div_label_website").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_website_icon").show(DEFAULT_SPEED);
			$("#label_website_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_website_helpblock").show(DEFAULT_SPEED);
			$("#label_website_helpblock").text("Este campo nao pode estar vazio.");

			website_ok = false;
		}
		else if ( !isWebsite ) {
			// change to red
			$("#div_label_website").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_website_icon").show(DEFAULT_SPEED);
			$("#label_website_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_website_helpblock").show(DEFAULT_SPEED);
			$("#label_website_helpblock").text("Tem que estar no formato de site");

			website_ok = false;
		}
		else {
			// change to green
			$("#div_label_website").attr({
				"class" : "form-group has-success has-feedback"
			});

			// show the icon as a X mark
			$("#label_website_icon").show(DEFAULT_SPEED);
			$("#label_website_icon").attr({
				"class" : "glyphicon glyphicon-ok form-control-feedback"
			});

			// give helper text
			$("#label_website_helpblock").hide(DEFAULT_SPEED);

			website_ok = true;
		}
	});

	// morada instituicao
	$("#label_morada").change(function() {

		if ( $("#label_morada").val() === "" ) {
			// change to red
			$("#div_label_morada").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_morada_icon").show(DEFAULT_SPEED);
			$("#label_morada_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_morada_helpblock").show(DEFAULT_SPEED);
			$("#label_morada_helpblock").text("Este campo nao pode estar vazio.");

			morada_ok = false;
		}
		else {
			// change to green
			$("#div_label_morada").attr({
				"class" : "form-group has-success has-feedback"
			});

			// show the icon as a X mark
			$("#label_morada_icon").show(DEFAULT_SPEED);
			$("#label_morada_icon").attr({
				"class" : "glyphicon glyphicon-ok form-control-feedback"
			});

			// give helper text
			$("#label_morada_helpblock").hide(DEFAULT_SPEED);

			morada_ok = true;
		}
	});

	//descricao da instituicao
	$("#label_desc").change(function() {

		if ( $("#label_desc").val() === "" ) {
			// change to red
			$("#div_label_desc").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_desc_icon").show(DEFAULT_SPEED);
			$("#label_desc_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_desc_helpblock").show(DEFAULT_SPEED);
			$("#label_desc_helpblock").text("Este campo nao pode estar vazio.");

			descricao_ok = false;
		}
		else {
			// change to green
			$("#div_label_desc").attr({
				"class" : "form-group has-success has-feedback"
			});

			// show the icon as a X mark
			$("#label_desc_icon").show(DEFAULT_SPEED);
			$("#label_desc_icon").attr({
				"class" : "glyphicon glyphicon-ok form-control-feedback"
			});

			// give helper text
			$("#label_desc_helpblock").hide(DEFAULT_SPEED);

			descricao_ok = true;
		}
	});




	// genero
	$("input:radio[name=\"gender\"]").change(function() {
		$("#div_radio").attr({
			"class" : "form-group has-feedback has-success"
		})
		$("#radio_helpblock").hide(DEFAULT_SPEED);
	})


	// interesses
	$("#select_interesses").change(function() {
		var interesses = $("#select_interesses").val();


		if ( interesses == null || interesses.length === 0 ) {
			interesses_ok = false;
		}
		else {
			interesses_ok = true;
		}
	});

	// grupos atuacao
	$("#select_grupos_atuacao").change(function() {
		var grupos = $("#select_grupos_atuacao").val();


		if ( grupos == null || grupos.length === 0 ) {
			grupos_ok = false;
		}
		else {
			grupos_ok = true;
		}
	});

	// habilitacoes academicas
	$("#select_habilitacoes").change(function() {
		var habilitacoes = $("#select_habilitacoes").val();


		if ( habilitacoes == null || habilitacoes.length === 0 ) {
			habilitacoes_ok = false;
		}
		else {
			habilitacoes_ok = true;
		}
	});

	// image upload
	uploadedImage = baseURL + "res/uploads/profile_default.jpg";
	function submitFile(){
		var formUrl = baseURL + "index.php/upload/parse/";
		var formData = new FormData($("#form_file_foto")[0]);

		$.ajax({
			url: formUrl,
			type: "POST",
			data: formData,
			mimeType: "multipart/form-data",
			contentType: false,
			cache: false,
			processData: false,
			success: function(data){
				if ( JSON.parse(data).response ) {
					$("#user_profile_pic").attr({
						"class" : "img-responsive",
						"src" : JSON.parse(data).file_path
					});
					uploadedImage = JSON.parse(data).file_path;
				}
				else {
					console.log(JSON.parse(data).errors);
				}
			},
			error: function(request, status, error){
				console.log(request.responseText);
			}
		});
	}

	$("#file_foto").click(function() {
		submitFile();
	});

	// Submit do registo
	$("#submit_registo_voluntario").click(function() {
		
		if ( $("#label_nome").val() != "" )
			nome_ok = true;

		if ( !nome_ok ) {
			// change to red
			$("#div_label_nome").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_nome_icon").show(DEFAULT_SPEED);
			$("#label_nome_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_nome_helpblock").show(DEFAULT_SPEED);
			$("#label_nome_helpblock").text("Este campo nao pode estar vazio.");
		}

		var gender = $("input:radio[name=\"gender\"]:checked").val();
		if (gender == null) {
			gender_ok = false;
			$("#div_radio").attr({
				"class" : "form-group has-feedback has-error"
			});
			$("#radio_helpblock").show(DEFAULT_SPEED);
			$("#radio_helpblock").text("Tem que escolher um genero.");
		}
		else {
			gender_ok = true;
		}


		if ( $("#label_phone").val() != "" )
			phone_ok = true;

		if ( !phone_ok ) {
			// change to red
			$("#div_label_phone").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_phone_icon").show(DEFAULT_SPEED);
			$("#label_phone_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_phone_helpblock").show(DEFAULT_SPEED);
			$("#label_phone_helpblock").text("Este campo nao pode estar vazio.");
		}

		if ( $("#label_birth").val() != "" )
			birth_ok = true;

		if ( !birth_ok ) {
			// change to red
			$("#div_label_birth").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_birth_icon").show(DEFAULT_SPEED);
			$("#label_birth_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_birth_helpblock").show(DEFAULT_SPEED);
			$("#label_birth_helpblock").text("Este campo nao pode estar vazio.");
		}

		var distrito = $("#select_distrito").val();
		var concelho = $("#select_concelho").val();
		var freguesia = $("#select_freguesia").val();
		if ( distrito == 0 ) {
			$("#select_distrito").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_distrito").select2();
			localidade_ok = false;
		}

		if ( concelho == 0 ) {
			$("#select_concelho").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_concelho").select2();
			localidade_ok = false;
		}

		if ( freguesia == 0 ) {
			$("#select_freguesia").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_freguesia").select2();
			localidade_ok = false;
		}

		if ( freguesia != 0 && concelho != 0 && distrito != 0 )
			localidade_ok = true;

		if ( !interesses_ok ) {

			$("#select_interesses").attr({
				"class" : "form-control multiselect multiselect-danger"
			});

		}

		if ( !grupos_ok ) {

			$("#select_grupos_atuacao").attr({
				"class" : "form-control multiselect multiselect-danger"
			});

		}

		if ( !habilitacoes_ok ) {

			$("#select_habilitacoes").attr({
				"class": "form-control multiselect multiselect-danger"
			});

		}
		$("select").select2({dropdownCssClass: 'dropdown-inverse'});

		if ( nome_ok && phone_ok && birth_ok
			&& gender && localidade_ok
			&& interesses_ok && grupos_ok && habilitacoes_ok ) {
			var data = {
				nome: $("#label_nome").val(),
				phone: $("#label_phone").val(),
				gender: $("input:radio[name=\"gender\"]:checked").val(),
				birth: $("#label_birth").val(),
				disponibilidade: $("#select_disponibilidade").val(),
				distrito: $("#select_distrito").val(),
				concelho: $("#select_concelho").val(),
				freguesia: $("#select_freguesia").val(),
				interesses: $("#select_interesses").val(),
				grupos_atuacao: $("#select_grupos_atuacao").val(),
				habilitacoes_academicas: $("#select_habilitacoes").val(),
				foto: uploadedImage
			};

			$.ajax({
				type: "POST",
				data: data,
				url: baseURL + "index.php/registo/confirmacao_voluntario/",

				success: function(data) {
					redirect( baseURL + "index.php/perfil/");
				},

				error: function (request, status, error) {
					console.log(request.responseText);
				}
			});
		}
	});

    //submit registo instituicao
    $("#submit_registo_instituicao").click(function() {
		
		if ( $("#label_nome").val() != "" )
			nome_ok = true;

		if ( !nome_ok ) {
			// change to red
			$("#div_label_nome").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_nome_icon").show(DEFAULT_SPEED);
			$("#label_nome_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_nome_helpblock").show(DEFAULT_SPEED);
			$("#label_nome_helpblock").text("Este campo nao pode estar vazio.");
		}


		if ( $("#label_phone").val() != "" )
			phone_ok = true;

		if ( !phone_ok ) {
			// change to red
			$("#div_label_phone").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_phone_icon").show(DEFAULT_SPEED);
			$("#label_phone_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_phone_helpblock").show(DEFAULT_SPEED);
			$("#label_phone_helpblock").text("Este campo nao pode estar vazio.");
		}

		
		if ( $("#label_nomerep").val() != "" )
			nomerep_ok = true;

		if ( !nomerep_ok ) {
			// change to red
			$("#div_label_nomerep").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_nomerep_icon").show(DEFAULT_SPEED);
			$("#label_nomerep_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_nomerep_helpblock").show(DEFAULT_SPEED);
			$("#label_nomerep_helpblock").text("Este campo nao pode estar vazio.");
		}

		if ( $("#label_emailrep").val() != "" )
			emailrep_ok = true;

		if ( !emailrep_ok ) {
			// change to red
			$("#div_label_emailrep").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_emailrep_icon").show(DEFAULT_SPEED);
			$("#label_emailrep_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_emailrep_helpblock").show(DEFAULT_SPEED);
			$("#label_emailrep_helpblock").text("Este campo nao pode estar vazio.");
		}

		if ( $("#label_morada").val() != "" )
			morada_ok = true;

		if ( !morada_ok ) {
			// change to red
			$("#div_label_morada").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_morada_icon").show(DEFAULT_SPEED);
			$("#label_morada_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_morada_helpblock").show(DEFAULT_SPEED);
			$("#label_morada_helpblock").text("Este campo nao pode estar vazio.");
		}

		if ( $("#label_website").val() != "" )
			website_ok = true;

		if ( !website_ok ) {
			// change to red
			$("#div_label_website").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_website_icon").show(DEFAULT_SPEED);
			$("#label_website_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_website_helpblock").show(DEFAULT_SPEED);
			$("#label_website_helpblock").text("Este campo nao pode estar vazio.");
		}

		if ( $("#label_desc").val() != "" )
			descricao_ok = true;

		if ( !descricao_ok ) {
			// change to red
			$("#div_label_desc").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_desc_icon").show(DEFAULT_SPEED);
			$("#label_desc_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_descricao_helpblock").show(DEFAULT_SPEED);
			$("#label_descricao_helpblock").text("Este campo nao pode estar vazio.");
		}

	

		var distrito = $("#select_distrito").val();
		var concelho = $("#select_concelho").val();
		var freguesia = $("#select_freguesia").val();
		if ( distrito == 0 ) {
			$("#select_distrito").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_distrito").select2();
			localidade_ok = false;
		}

		if ( concelho == 0 ) {
			$("#select_concelho").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_concelho").select2();
			localidade_ok = false;
		}

		if ( freguesia == 0 ) {
			$("#select_freguesia").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_freguesia").select2();
			localidade_ok = false;
		}

		if ( freguesia != 0 && concelho != 0 && distrito != 0 )
			localidade_ok = true;

		
		$("select").select2({dropdownCssClass: 'dropdown-inverse'});

		if ( nome_ok && phone_ok && localidade_ok && descricao_ok 
            && nomerep_ok && emailrep_ok && website_ok && morada_ok ) {
			var data = {
				nome: $("#label_nome").val(),
				phone: $("#label_phone").val(),
                morada: $("#label_morada").val(),
                descricao: $("#label_desc").val(),
                website: $("#label_website").val(),
                nome_rep: $("#label_nomerep").val(),
                email_rep: $("#label_emailrep").val(),
				distrito: $("#select_distrito").val(),
				concelho: $("#select_concelho").val(),
				freguesia: $("#select_freguesia").val()
			};

			$.ajax({
				type: "POST",
				data: data,
				url: baseURL + "index.php/registo/confirmacao_instituicao/",

				success: function(data) {
					redirect( baseURL + "index.php/perfil/");
				},

				error: function (request, status, error) {
					console.log(request.responseText);
				}
			});
		}
	});

	// Submit da edicao
	$("#submit_edit_voluntario").click(function() {

		if ( $("#label_nome").val() != "" )
			nome_ok = true;

		if ( !nome_ok ) {
			// change to red
			$("#div_label_nome").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_nome_icon").show(DEFAULT_SPEED);
			$("#label_nome_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_nome_helpblock").show(DEFAULT_SPEED);
			$("#label_nome_helpblock").text("Este campo nao pode estar vazio.");
		}

		var gender = $("input:radio[name=\"gender\"]:checked").val();
		if (gender == null) {
			gender_ok = false;
			$("#div_radio").attr({
				"class" : "form-group has-feedback has-error"
			});
			$("#radio_helpblock").show(DEFAULT_SPEED);
			$("#radio_helpblock").text("Tem que escolher um genero.");
		}
		else {
			gender_ok = true;
		}

		if ( $("#label_phone").val() != "" )
			phone_ok = true;

		if ( !phone_ok ) {
			// change to red
			$("#div_label_phone").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_phone_icon").show(DEFAULT_SPEED);
			$("#label_phone_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_phone_helpblock").show(DEFAULT_SPEED);
			$("#label_phone_helpblock").text("Este campo nao pode estar vazio.");
		}

		if ( $("#label_birth").val() != "" )
			birth_ok = true;

		if ( !birth_ok ) {
			// change to red
			$("#div_label_birth").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_birth_icon").show(DEFAULT_SPEED);
			$("#label_birth_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_birth_helpblock").show(DEFAULT_SPEED);
			$("#label_birth_helpblock").text("Este campo nao pode estar vazio.");
		}

		var distrito = $("#select_distrito").val();
		var concelho = $("#select_concelho").val();
		var freguesia = $("#select_freguesia").val();
		if ( distrito == 0 ) {
			$("#select_distrito").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_distrito").select2();
			localidade_ok = false;
		}

		if ( concelho == 0 ) {
			$("#select_concelho").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_concelho").select2();
			localidade_ok = false;
		}

		if ( freguesia == 0 ) {
			$("#select_freguesia").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_freguesia").select2();
			localidade_ok = false;
		}

		if ( freguesia != 0 && concelho != 0 && distrito != 0 )
			localidade_ok = true;

		if ( $("#select_interesses").val().length > 0 )
			interesses_ok = true;

		if ( $("#select_grupos_atuacao").val().length > 0 )
			grupos_ok = true;

		if ( $("#select_habilitacoes").val().length > 0 )
			habilitacoes_ok = true;

		if ( !interesses_ok ) {

			$("#select_interesses").attr({
				"class" : "form-control multiselect multiselect-danger"
			});

		}

		if ( !grupos_ok ) {

			$("#select_grupos_atuacao").attr({
				"class" : "form-control multiselect multiselect-danger"
			});

		}

		if ( !habilitacoes_ok ) {

			$("#select_habilitacoes").attr({
				"class": "form-control multiselect multiselect-danger"
			});

		}
		$("select").select2({dropdownCssClass: 'dropdown-inverse'});

		if ( nome_ok && phone_ok && birth_ok
			&& gender && localidade_ok
			&& interesses_ok && grupos_ok && habilitacoes_ok ) {
			var data = {
				nome: $("#label_nome").val(),
				phone: $("#label_phone").val(),
				gender: $("input:radio[name=\"gender\"]:checked").val(),
				birth: $("#label_birth").val(),
				disponibilidade: $("#select_disponibilidade").val(),
				distrito: $("#select_distrito").val(),
				concelho: $("#select_concelho").val(),
				freguesia: $("#select_freguesia").val(),
				interesses: $("#select_interesses").val(),
				grupos_atuacao: $("#select_grupos_atuacao").val(),
				habilitacoes_academicas: $("#select_habilitacoes").val(),
				foto: uploadedImage
			};

			$.ajax({
				type: "POST",
				data: data,
				url: baseURL + "index.php/editar/update/",

				success: function(data) {
					redirect( baseURL + "index.php/perfil/");
				},

				error: function (request, status, error) {
					console.log(request.responseText);
				}
			});
		}
	});


    // Submit da edicao Instituicao
	$("#submit_edit_instituicao").click(function() {

		if ( $("#label_nome").val() != "" )
			nome_ok = true;

		if ( !nome_ok ) {
			// change to red
			$("#div_label_nome").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_nome_icon").show(DEFAULT_SPEED);
			$("#label_nome_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_nome_helpblock").show(DEFAULT_SPEED);
			$("#label_nome_helpblock").text("Este campo nao pode estar vazio.");
		}

		if ( $("#label_phone").val() != "" )
			phone_ok = true;

		if ( !phone_ok ) {
			// change to red
			$("#div_label_phone").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_phone_icon").show(DEFAULT_SPEED);
			$("#label_phone_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_phone_helpblock").show(DEFAULT_SPEED);
			$("#label_phone_helpblock").text("Este campo nao pode estar vazio.");
		}

		if ( $("#label_nomerep").val() != "" )
			nomerep_ok = true;

		if ( !nomerep_ok ) {
			// change to red
			$("#div_label_nomerep").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_nomerep_icon").show(DEFAULT_SPEED);
			$("#label_nomerep_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_nomerep_helpblock").show(DEFAULT_SPEED);
			$("#label_nomerep_helpblock").text("Este campo nao pode estar vazio.");
		}

		if ( $("#label_emailrep").val() != "" )
			emailrep_ok = true;

		if ( !emailrep_ok ) {
			// change to red
			$("#div_label_emailrep").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_emailrep_icon").show(DEFAULT_SPEED);
			$("#label_emailrep_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_emailrep_helpblock").show(DEFAULT_SPEED);
			$("#label_emailrep_helpblock").text("Este campo nao pode estar vazio.");
		}

		if ( $("#label_morada").val() != "" )
			morada_ok = true;

		if ( !morada_ok ) {
			// change to red
			$("#div_label_morada").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_morada_icon").show(DEFAULT_SPEED);
			$("#label_morada_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_morada_helpblock").show(DEFAULT_SPEED);
			$("#label_morada_helpblock").text("Este campo nao pode estar vazio.");
		}

		if ( $("#label_website").val() != "" )
			website_ok = true;

		if ( !website_ok ) {
			// change to red
			$("#div_label_website").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_website_icon").show(DEFAULT_SPEED);
			$("#label_website_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_website_helpblock").show(DEFAULT_SPEED);
			$("#label_website_helpblock").text("Este campo nao pode estar vazio.");
		}

		if ( $("#label_descricao").val() != "" )
			descricao_ok = true;

		if ( !descricao_ok ) {
			// change to red
			$("#div_label_descricao").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_descricao_icon").show(DEFAULT_SPEED);
			$("#label_descricao_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_descricao_helpblock").show(DEFAULT_SPEED);
			$("#label_descricao_helpblock").text("Este campo nao pode estar vazio.");
		}


		var distrito = $("#select_distrito").val();
		var concelho = $("#select_concelho").val();
		var freguesia = $("#select_freguesia").val();
		if ( distrito == 0 ) {
			$("#select_distrito").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_distrito").select2();
			localidade_ok = false;
		}

		if ( concelho == 0 ) {
			$("#select_concelho").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_concelho").select2();
			localidade_ok = false;
		}

		if ( freguesia == 0 ) {
			$("#select_freguesia").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_freguesia").select2();
			localidade_ok = false;
		}

		if ( freguesia != 0 && concelho != 0 && distrito != 0 )
			localidade_ok = true;

		
		$("select").select2({dropdownCssClass: 'dropdown-inverse'});

		if ( nome_ok && phone_ok && localidade_ok && descricao_ok && emailrep_ok
			&& nomerep_ok && descricao_ok && morada_ok && website_ok ) {
			var data = {
				nome: $("#label_nome").val(),
				phone: $("#label_phone").val(),
				distrito: $("#select_distrito").val(),
				concelho: $("#select_concelho").val(),
				freguesia: $("#select_freguesia").val(),
				descricao: $("#label_desc").val(),
				nomerep: $("#label_nomerep").val(),
				emailrep: $("#label_emailrep").val(),
				morada: $("#label_morada").val(),
				website: $("#label_website").val(),

			};

			$.ajax({
				type: "POST",
				data: data,
				url: baseURL + "index.php/editar/update_instituicao/",

				success: function(data) {
					redirect( baseURL + "index.php/perfil/");
				},

				error: function (request, status, error) {
					console.log(request.responseText);
				}
			});
		}
	});

    var funcao_ok = true;
    
	$("#label_funcao").change(function() {
		
		var data = {
			nome: $("#label_funcao").val(),
		};

		$.ajax
		(
			{
				type: "POST",
				data: data,
				url: baseURL + "index.php/perfil/exist_oportunidade/",

				success: function(result) {
					//Not Exist
					if(result === "FALSE"){						
						// change div_label_email to green!
						$("#div_label_funcao").attr({
							"class" : "form-group has-success has-feedback"
						});
						// show the icon as a check mark
						$("#label_funcao_icon").show(DEFAULT_SPEED);
						$("#label_funcao_icon").attr({"class" : "glyphicon glyphicon-ok form-control-feedback"});
                        
                        $("#label_funcao_helpblock").hide(DEFAULT_SPEED);
                        funcao_ok = true;

					}else{
						// change div_label_email to red!
						$("#div_label_funcao").attr({
							"class" : "form-group has-error has-feedback"
						});
						// show the icon as a check mark
						$("#label_funcao_icon").show(DEFAULT_SPEED);
						$("#label_funcao_icon").attr({"class" : "glyphicon glyphicon-remove form-control-feedback"});
                        
                        $("#label_funcao_helpblock").show(DEFAULT_SPEED);
                        $("#label_funcao_helpblock").text("Essa oportunidade ja se encontra no sistema.");
                        funcao_ok = false;
					}
				},

				error: function (request, status, error) {
					console.log(request.responseText);
				}			
			}
		);
	});	
    
    $("#submit_criacao_oportunidade2").click(function() 
    {
        var nome_ok = false;
        if ( $("#label_funcao").val() != "" )
			nome_ok = true;

		if ( !nome_ok ) {
			// change to red
			$("#div_label_funcao").attr({
				"class" : "form-group has-error has-feedback"
			});

			// show the icon as a X mark
			$("#label_funcao_icon").show(DEFAULT_SPEED);
			$("#label_funcao_icon").attr({
				"class" : "glyphicon glyphicon-remove form-control-feedback"
			});

			// give helper text
			$("#label_funcao_helpblock").show(DEFAULT_SPEED);
			$("#label_funcao_helpblock").text("Este campo nao pode estar vazio.");
		}
        
        var localidade_ok = false;
        var distrito = $("#select_distrito").val();
		var concelho = $("#select_concelho").val();
		var freguesia = $("#select_freguesia").val();
		if ( distrito == 0 ) {
			$("#select_distrito").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_distrito").select2();
			localidade_ok = false;
		}

		if ( concelho == 0 ) {
			$("#select_concelho").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_concelho").select2();
			localidade_ok = false;
		}

		if ( freguesia == 0 ) {
			$("#select_freguesia").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_freguesia").select2();
			localidade_ok = false;
		}

		if ( freguesia != 0 && concelho != 0 && distrito != 0 )
			localidade_ok = true;
        
        if ( nome_ok && localidade_ok && funcao_ok ) {
        
            var data = 
            {
              nome: $("#label_funcao").val(),
              distrito: $("#select_distrito").val(),
              concelho: $("#select_concelho").val(),
              freguesia: $("#select_freguesia").val(),
              interesse: $("#select_interesse").val(),
              grupos_atuacao: $("#select_grupo").val(),
              habilitacoes: $("#select_habilitacoes").val(),
              disponibilidade: $("#select_disponibilidade").val()
            };

            $.ajax
            (
                {
                    type: "POST",
                    data: data,
                    url: baseURL + "index.php/perfil/submeter_oportunidade/",

                    success: function(data) {
                        redirect( baseURL + "index.php/perfil/oportunidades");
                    },

                    error: function (request, status, error) {
                        console.log(request.responseText);
                    }			
                }
            );
        }
	});

	$("#submit_edit_oportunidade").click(function() 
    {
        var localidade_ok = false;   
        var distrito = $("#select_distrito").val();
		var concelho = $("#select_concelho").val();
		var freguesia = $("#select_freguesia").val();
		if ( distrito == 0 ) {
			$("#select_distrito").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_distrito").select2();
			localidade_ok = false;
		}

		if ( concelho == 0 ) {
			$("#select_concelho").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_concelho").select2();
			localidade_ok = false;
		}

		if ( freguesia == 0 ) {
			$("#select_freguesia").attr({
				"class" : "form-control select select-danger"
			});
			$("#select_freguesia").select2();
			localidade_ok = false;
		}

		if ( freguesia != 0 && concelho != 0 && distrito != 0 )
			localidade_ok = true;
        
        if ( localidade_ok ) {
        
            var data = 
            {
              nome: $("#label_funcao").val(),
              distrito: $("#select_distrito").val(),
              concelho: $("#select_concelho").val(),
              freguesia: $("#select_freguesia").val(),
              interesse: $("#select_interesse").val(),
              grupos_atuacao: $("#select_grupo").val(),
              habilitacoes: $("#select_habilitacoes").val(),
              disponibilidade: $("#select_disponibilidade").val()
            };

            console.log(data.disponibilidade);

            $.ajax
            (
                {
                    type: "POST",
                    data: data,
                    url: baseURL + "index.php/perfil/atualizar_oportunidade/",

                    success: function(data) {
                        redirect( baseURL + "index.php/perfil/oportunidade/" + data.url);
                        
                    },

                    error: function (request, status, error) {
                        console.log(request.responseText);
                    }			
                }
            );
        }
	});

	// Search Bar
	$("#search").on("keyup", function() {
	    var value = $(this).val().toLowerCase();

	    $("table tr").each(function(index) {
	        if (index !== 0) {

	            $row = $(this);

	            var idInst  =  $row.find("td:nth-child(1)").text().toLowerCase();
	            var idOport = $row.find("td:nth-child(2)").text().toLowerCase();
	            var idTest = $row.find("td:nth-child(3)").textContent;

	            if (idOport.indexOf(value) !== 0 && idInst.indexOf(value) !== 0) {
	                $row.hide();
	            }else {
	                $row.show();
	            }
	        }
	    });
	});
});