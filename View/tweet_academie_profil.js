$(document).ready(function(){
	$.post(
	    '../Controller/ajax_profil_data_recovery.php', 
	    {           
	    },
	    function(data)
	    {
	        $('main').html(data);
	    },
	    'html'
	);
	$(document).on('click','.follow_button', function (e){
		var user = $(this).parent().find('#profil_username').text();
    	var username = user.substring(11);
    	$.post(
		    '../Controller/ajax_follower.php', 
		    {  
			   	user_id : $(this).val(),       
		    }, 
		    function()
		    {  
		        $.post(
	   				'../Controller/ajax_profil_data_recovery_2.php', 
	    			{  
	    				username : username,         
				    },
    				function(data)
				    {
	        			$('main').html(data);
	    			},
	    			'html'
	    		);    	
		    },
		    'html'
	    );

    });
    $(document).on('click','.unfollow_button', function (e){
    	var user = $(this).parent().find('#profil_username').text();
    	var username = user.substring(11);
    	$.post(
		    '../Controller/ajax_unfollower.php', 
		    {  
			   	user_id : $(this).val(),       
		    }, 
		    function()
		    {  
		        $.post(
	   				'../Controller/ajax_profil_data_recovery_2.php', 
	    			{ 
	    				username : username,          
				    },
    				function(data)
				    {
	        			$('main').html(data);
	    			},
	    			'html'
	    		);    	
		    },
		    'html'
	    );

    });
    $(document).on('click','.username_content', function (e){
    	var user = $(this).text();
    	var username = user.substring(1);
    	$.post(
	    '../Controller/ajax_profil_data_recovery_2.php', 
	    {
	    	username : username,      
	    },
	    function(data)
	    {
	        $('main').html(data);
	    },
	    'html'
	);
	});
		$(document).on('click','#button_display', function (e){
		e.preventDefault();
		    $.post(
	            '../Controller/ajax_set_information_account.php', 
	            {
	                set_display: $("#set_display").val()                
	            },
	            function()
	            {
	            	$.post(
					    '../Controller/ajax_profil_data_recovery.php', 
					    {           
					    },
					    function(data)
					    {
					        $('main').html(data);
					    },
					    'html'
					);
	            },
	            'html'
			);
	});

		$(document).on('click','#button_email', function (e){
		e.preventDefault();
		    $.post(
	            '../Controller/ajax_set_information_account.php', 
	            {
	                set_email: $("#set_email").val()                
	            },
	            function()
	            {
	            	$.post(
					    '../Controller/ajax_profil_data_recovery.php', 
					    {           
					    },
					    function(data)
					    {
					        $('main').html(data);
					    },
					    'html'
					);
	            },
	            'html'
			);
	});
		$(document).on('click','#button_city', function (e){
		e.preventDefault();
		    $.post(
	            '../Controller/ajax_set_information_account.php', 
	            {
	                set_city: $("#set_city").val()                
	            },
	            function()
	            {   
	               $.post(
					    '../Controller/ajax_profil_data_recovery.php', 
					    {           
					    },
					    function(data)
					    {
					        $('main').html(data);
					    },
					    'html'
					);
	            },
	            'html'
			);
	});
			$(document).on('click','#button_password', function (e){
		e.preventDefault();
		    $.post(
	            '../Controller/ajax_set_information_account.php', 
	            {
	                set_password: $("#set_password").val()                
	            },
	            function(data)
	            {
	            	$.post(
					    '../Controller/ajax_profil_data_recovery.php', 
					    {           
					    },
					    function(data)
					    {
					        $('main').html(data);
					    },
					    'html'
					);
	            },
	            'html'
			);
	});
	$(document).on('click','.my_tweet_delete', function (e)
	{		
		$.post(
        '../Controller/ajax_delete_tweet.php', 
        {
        	submit_time : $(this).parent().find('.tweet_date').text(),
        },
        function()
        {
         	$.post(
			    '../Controller/ajax_profil_data_recovery.php', 
			    {           
			    },
				function(data)
			    {
			        $('main').html(data);
			    },
			    'html'
			);
        },
        'html'
        );

	});
});