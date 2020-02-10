$(document).ready(function(){
	$.post(
	    '../Controller/ajax_short_information_account.php', 
	    {           
	    },
	    function(data)
	    {
	        $("#info").html(data);
	    },
	    'html'
	    );
	$( "#home_search" ).keyup(function() {

		$("#field").hide();
		$("#profil").show();
		$('#search_result').show();
        $.post(
        '../Controller/ajax_home_search.php', 
        {
            search_follower : $('#home_search').val(),
        },
        function(data)
        {
            $('#search_result').html(data);
        },
        'html'
        );
	});

	$(document).on('click','a[href="#"]', function (e) {
		var hashtag = $(this).text();
        $("#field").hide();
        $("#profil").show();
        $('#search_result').show();
		$.post(
			'../Controller/ajax_read_hashtag.php',
			{
				hashtag : hashtag
			},
			function (data) 
			{
	            $("#search_result").html(data);
	        },
	        'html'
	    	);
	});

    $(document).on('click','.follow_button', function (e){
    	$.post(
	            '../Controller/ajax_follower.php', 
	            {  
	            	user_id : $(this).val(),       
	            }, 
	            function()
	            {  
	            	$.post(
			        '../Controller/ajax_home_search.php', 
			        {
			            search_follower : $('#home_search').val(),
			        },
			        function(data)
			        {
			            $('#search_result').html(data);
			        },
			        'html'
			        );
	            },
	            'html'
	        );
    });
    $(document).on('click','.unfollow_button', function (e){
    	$.post(
	            '../Controller/ajax_unfollower.php', 
	            {  
	            	user_id : $(this).val(),       
	            }, 
	            function()
	            {  
				    $.post(
			        '../Controller/ajax_home_search.php', 
			        {
			            search_follower : $('#home_search').val(),
			        },
			        function(data)
			        {
			            $('#search_result').html(data);
			        },
			        'html'
			        );
	            },
	            'html'
	        );
    });
    $(document).on('click','#close_search', function (e){
			$('#home_search').val("");
			$('#search_result').show();
			$("#field").show();
			$("#profil").hide();
			$.post(
	        '../Controller/ajax_read_tweet.php',
	        function (data) {
	            $("#actuality").html(data);
	        },
	        'html'
	    	);
		});
    $(document).on('click','.search_username', function (e){
		$.post(
        '../Controller/ajax_go_on_profil.php', 
        {
            follower_find_username : $(this).text(),
        },
        function(data)
        {
            window.location.href = 'tweet_profil.html';
        },
        'html'
        );

	});
	$(document).on('click','#username_click', function (e){
		$.post(
        '../Controller/ajax_go_on_profil.php', 
        {
            follower_find_username : $(this).text(),
        },
        function(data)
        {
            window.location.href = 'tweet_profil.html';
        },
        'html'
        );
	});
    $(document).on('click','.retweet', function (e){
        var msg = $(this).parent().html();
        e.preventDefault();
        $.post(
            '../Controller/ajax_retweet.php',
            {
                msg: msg
            },
            function() {
                $.post(
                '../Controller/ajax_read_tweet.php',
                function (data) {
                    $("#actuality").html(data);
                },
                'html'
            );
            },
           'html'
        );
    });   
	$(document).on('click','#close_account', function (e){
		$("#set_info").hide();
		$.post(
	        '../Controller/ajax_read_tweet.php',
	        function (data) {
	            $("#actuality").html(data);
	        },
	        'html'
	    );
	});
	$.post(
        '../Controller/ajax_information_account.php', 
        {           
        }, 
        function(data)
        {  
            $("#set_info").html(data);
        },
        'html'
    );
	$.post(
        '../Controller/ajax_read_tweet.php',
        function (data) {
            $("#actuality").html(data);
        },
        'html'
    );

    $("#sub_tweet").click(function (e) {
        e.preventDefault();
        $.post(
            '../Controller/ajax_tweet.php',
            {
                msg: $('#msg').val(),
            },
            function () {
                    $('#msg').val("");
            },
            'text'
        );
    })

    $("#sub_tweet").click(function (e) {
        e.preventDefault();
        $.post(
            '../Controller/ajax_read_tweet.php',
            function (data) {
                $("#actuality").html(data);
                console.log(data);
            },
            'html'
        );
    })

});
function autoRefreshPage()
{
    $.post(
        '../Controller/ajax_read_tweet.php',
        function (data) {
            $("#actuality").html(data);
        },
        'html'
    );
}
setInterval('autoRefreshPage()', 10000);

   (function ($) {
    $.fn.my_tweet=function(options) {
        var btn = {
            buttons: ["bold", "italic", "underline", "strike", "color", "size", "link", "left", "right", "center", "justify", "switch", "youtube"]
        };
        var settings = $.extend(btn, options);

        $(this).each(function () {
            var my_parent = $(this).parent();
			my_parent.prepend("<div class='menu'></div>");
            my_parent.find(".menu").append("<button class='bold btn'><span class='fas fa-bold'></span></button>");
            my_parent.find(".bold").click(function() {
                document.execCommand('bold');
            });


        })
    }
})($);
