$(document).ready(function() {
    var email_ok    = false;
    var password_ok = false;

    //binding enter
   $(document).keypress(function(e){
        if (e.which == 13)
        {
            $("#submit_login").click();
        }
   });

    //Check Email
    $("#login_label_email").change(
        function() {
            var email = $("#login_label_email").val();
            if (email === "") {
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
            else if(isEmail(email))
            {
                $.ajax
                (
                    {
                        type: "POST",
                        data: 
                        {
                            email: email
                        },
                        url: baseURL + "index.php/login/email",
                        success: function(data)
                        {
                            if(data == "TRUE")
                            {
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
                            }
                            else
                            {
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
                                $("#label_email_helpblock").text("Esse e-mail não está registado neste servico.");

                                // set ok flag
                                email_ok = false;
                            }
                        }
                    }
                )
            }
            else
            {
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
        }
    );


    $("#login_label_password").change(function() {

        // atualizar a password
        password = $("#login_label_password").val();

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

            // set ok flag
            password_ok = false;
        } else {
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

            // set ok flag
            password_ok = true;
        }
    });


    //Authentication
    $("#submit_login").click(
        function () 
        {
            authenticate();
        }
    );

    function authenticate() {
        if(email_ok && password_ok)
        {
            $.ajax
            (
                {
                    type: "POST",
                    data: 
                    {
                        email: $("#login_label_email").val(),
                        password: $("#login_label_password").val(),                    
                    },
                    url: baseURL + "index.php/login/authenticate",
                    success: function(data)
                    {
                        if(data === "TRUE")
                        {
                            redirect(baseURL + "index.php/perfil/");
                        }
                        else
                        {
                            redirect(baseURL + "index.php/login/");
                        }
                    }
                }
            );
        }
    }
});