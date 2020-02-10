$(document).ready(function() {
    $("#registration").click(function(){
        $('#registration').toggle();
        $('#connection').toggle();
        $('#form_registration').slideToggle(800, 'linear');
         $('#form_connection').slideToggle(800, 'linear');
    });
      $("#connection").click(function(){
        $('#registration').toggle();
        $('#connection').toggle();
        $('#form_connection').slideToggle(800, 'linear');
        $('#form_registration').slideToggle(800, 'linear');
    });
	$("#c_button").click(function(e)
    {
        e.preventDefault();
        $.post(
            '../Controller/ajax_connection.php', 
            {
                email : $('#c_email').val(),
                password : $('#c_password').val()
            },
            function(data)
            {
            	if (data == 'OK') {
            		window.location.href = 'tweet_accueil.html';
            	}
            	else if (data == 'BAD') {
            		$('#error').html('Email ou password incorrect ...');
            	}
                else if(data == 'EMPTY') {  
                    $('#error').html('Champs manquants ...');
                }
            },
            'text'
        );  
    });

	$("#r_button").click(function(e)
    {
        e.preventDefault();
        $.post(
            '../Controller/ajax_registration.php', 
            {
                username : $('#r_username').val(),
                display_name : $('#r_display_name').val(),
                email : $('#r_email').val(),
                password : $('#r_password').val(),
                birth_date : $('#r_birth_date').val(),
                city : $("#r_city").val()
            },
            function(data)
            {
                if(data == 'DOUBLON')
                {  
                    $("#error").html("<p>Mail deja existant</p>");    
                }
                else if (data == 'USERNAME')
                {
                    $("#error").html("<p>Username deja existant !</p>");
                }
                else if (data == 'EMPTY')
                {
                    $("#error").html("<p>champs manquants !</p>");
                }
                else if (data == 'MAIL')
                {
                    $("#error").html("<p>Format email incorrect !</p>");
                }
                else
                {
                    $("#error").html("<p>inscription effectuée !</p>");   
                }
            },
            'text'
        );  
    });
});
