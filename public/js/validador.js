$(document).ready(function(){
  jQuery.validator.addMethod('greaterThan', function(value, element, param) {
return ( value > jQuery(param).val() );
}, 'La fecha es menor que la inicial' );

// custom code for lesser than
jQuery.validator.addMethod('lesserThan', function(value, element, param) {
return ( value < jQuery(param).val() );
}, 'Must be less than end' );


 $('#form').validate(
 {
  rules: {
  },
  highlight: function(element) {
       var id_attr = "#" + $( element ).attr("id") + "1";
       $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
       $(id_attr).removeClass('glyphicon-ok').addClass('glyphicon-remove');
   },
 unhighlight: function(element) {
       var id_attr = "#" + $( element ).attr("id") + "1";
       $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
       $(id_attr).removeClass('glyphicon-remove').addClass('glyphicon-ok');
   },
        errorElement: 'span',
        errorClass: 'color',
        errorPlacement: function(error, element) {
            error.insertAfter(element);
       }
 });

 $('#form_users').validate(
 {
    rules: {
      password : {
          minlength : 6
      },
      remember_pass : {
          minlength : 6,
          equalTo : "#password"
      },
      fin_contrato : {
        greaterThan : "#ini_contrato"
      }
    },
    highlight: function(element) {
         var id_attr = "#" + $( element ).attr("id") + "1";
         $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
         $(id_attr).removeClass('glyphicon-ok').addClass('glyphicon-remove');
     },
    unhighlight: function(element) {
         var id_attr = "#" + $( element ).attr("id") + "1";
         $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
         $(id_attr).removeClass('glyphicon-remove').addClass('glyphicon-ok');
     },
          errorElement: 'span',
          errorClass: 'color',
          errorPlacement: function(error, element) {
              error.insertAfter(element);
         }
 });
 $('#form_changepass').validate(
 {
    rules: {
      password : {
          minlength : 6
      },
      remember_pass : {
          minlength : 6,
          equalTo : "#password"
      }
    },
    highlight: function(element) {
         var id_attr = "#" + $( element ).attr("id") + "1";
         $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
         $(id_attr).removeClass('glyphicon-ok').addClass('glyphicon-remove');
     },
    unhighlight: function(element) {
         var id_attr = "#" + $( element ).attr("id") + "1";
         $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
         $(id_attr).removeClass('glyphicon-remove').addClass('glyphicon-ok');
     },
          errorElement: 'span',
          errorClass: 'color',
          errorPlacement: function(error, element) {
              error.insertAfter(element);
         }
 });

 $('#password').keyup(function () {
        $('#strengthMessage').html(checkStrength($('#password').val()))
    })
    function checkStrength(password) {
        var strength = 0
        if (password.length < 6) {
            $('#strengthMessage').removeClass()
            $('#strengthMessage').addClass('Short')
            return 'Demasiado corto'
        }
        if (password.length > 7) strength += 1
        // Si la contraseña contiene caracteres en mayúsculas y minúsculas, aumente el valor de la fuerza
        if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1
        // Si tiene números y letras, aumenta el valor de la fuerza.
        if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1
        // If it has one special character, increase strength value.
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
        // Aumentar el valor de la fuerza
        if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
        // Valor de fuerza calculado, podemos devolver mensajes.
        // If value is less than 2
        if (strength < 2) {
            $('#strengthMessage').removeClass();
            $('#strengthMessage').addClass('Weak');
            return 'Debil';
        } else if (strength == 2) {
            $('#strengthMessage').removeClass();
            $('#strengthMessage').addClass('Good');
            return 'Buena';
        } else {
            $('#strengthMessage').removeClass();
            $('#strengthMessage').addClass('Strong');
            return 'Fuerte';
        }
    }
    $( "#target" ).submit(function( event ) {
        alert( "Handler for .submit() called." );
        return false;
    });

});

function validaPass() {

    var checkPassword = function(str)
    {
        var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
        return re.test(str);
    };

    var checkForm = function(e)
    {
        if(this.username.value == "") {
            alert("Error: Username cannot be blank!");
            this.username.focus();
            e.preventDefault(); // equivalent to return false
            return;
        }
        re = /^\w+$/;
        if(!re.test(this.username.value)) {
            alert("Error: Username must contain only letters, numbers and underscores!");
            this.username.focus();
            e.preventDefault();
            return;
        }
        if(this.pwd1.value != "" && this.pwd1.value == this.pwd2.value) {
            if(!checkPassword(this.pwd1.value)) {
              alert("The password you have entered is not valid!");
              this.pwd1.focus();
              e.preventDefault();
              return;
            }
        } else {
            alert("Error: Please check that you've entered and confirmed your password!");
            this.pwd1.focus();
            e.preventDefault();
            return;
        }
        alert("Both username and password are VALID!");
    };

    var myForm = document.getElementById("myForm");
    myForm.addEventListener("submit", checkForm, true);

    // HTML5 form validation

    var supports_input_validity = function()
    {
        var i = document.createElement("input");
        return "setCustomValidity" in i;
    }

    if(supports_input_validity()) {
        var usernameInput = document.getElementById("field_username");
        usernameInput.setCustomValidity(usernameInput.title);

        var pwd1Input = document.getElementById("field_pwd1");
        pwd1Input.setCustomValidity(pwd1Input.title);

        var pwd2Input = document.getElementById("field_pwd2");

        // input key handlers

        usernameInput.addEventListener("keyup", function(e) {
            usernameInput.setCustomValidity(this.validity.patternMismatch ? usernameInput.title : "");
        }, false);

        pwd1Input.addEventListener("keyup", function(e) {
            this.setCustomValidity(this.validity.patternMismatch ? pwd1Input.title : "");
            if(this.checkValidity()) {
                pwd2Input.pattern = RegExp.escape(this.value);
                pwd2Input.setCustomValidity(pwd2Input.title);
            } else {
                pwd2Input.pattern = this.pattern;
                pwd2Input.setCustomValidity("");
            }
        }, false);

        pwd2Input.addEventListener("keyup", function(e) {
            this.setCustomValidity(this.validity.patternMismatch ? pwd2Input.title : "");
        }, false);

    }

}
