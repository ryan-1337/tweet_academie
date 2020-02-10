$(document).ready(function() {
    $.post(
        '../Controller/ajax_list_conv.php', 
        {
            value : "x"
        },
        function(data)
        {
            $('#editable_area').val("");
            $('#conv_list').html(data);  
            var nb_conv = $('#conv_list').find('.input_conv').length;
            if (nb_conv > 0) {
                $.post(
                    '../Controller/ajax_messagerie.php', 
                    {
                        content : $('#editable_area').val(),
                        conv_id : $('input[type=radio][name=conversation]:checked').val()
                    },
                    function(data)
                    {
                        $('#tweet_content').html(data);
                    },
                    'html'
                );
            }
        },
        'html'
        );
	$('#send_button').click(function(e)
    {
        e.preventDefault();
        $.post(
            '../Controller/ajax_messagerie.php', 
            {
                content : $('#editable_area').val(),
                conv_id : $('input[type=radio][name=conversation]:checked').val()
            },
            function(data)
            {
            	$('#tweet_content').html(data);
            	$('#editable_area').val("");
            },
            'html'
        );
        $('#tweet_content').animate({
            scrollTop: $('#tweet_content').get(0).scrollHeight
        }, 500);
    });
    $('#create_conv_button').click(function(e)
    {
        e.preventDefault();
        $.post(
            '../Controller/ajax_new_conv.php', 
            {
                conv_name : $('#create_conv_name').val(),
            },
            function()
            {
                $.post(
                '../Controller/ajax_list_conv.php', 
                {
                    value : "x"
                },
                function(data)
                {
                    $('#editable_area').val("");
                    $('#conv_list').html(data);
                    $.post(
                        '../Controller/ajax_messagerie.php', 
                        {
                            content : $('#editable_area').val(),
                            conv_id : $('input[type=radio][name=conversation]:checked').val()
                        },
                        function(data)
                        {
                            $('#tweet_content').html(data);
                        },
                        'html'
                    ); 
                },
                'html'
                ); 
            },
            'html'
        );  
    });
    $(document).on('click','.input_conv', function (e){
        $('#tweet_content').animate({
            scrollTop: $('#tweet_content').get(0).scrollHeight
        }, 500);
        
        $.post(
        '../Controller/ajax_messagerie.php', 
        {
            content : $('#editable_area').text(),
            conv_id : $(this).val()
        },
        function(data)
        {
            $('#tweet_content').html(data);
            $('#editable_area').val("");
            $('#add_truitos').val("");
            $('#add_truitos').trigger('keyup');
        },
        'html'
        );
    });
    $(document).on('click','.li_result', function (e)
    {
        var username = $(this).text().substring(1);
        var value = $('input[type=radio][name=conversation]:checked').val();
        $('#add_truitos').val("");
        $('#add_truitos').trigger('keyup');
        $.post(
        '../Controller/ajax_add_truitos.php', 
        {
            new_truitos : username,
            conv_id : $('input[type=radio][name=conversation]:checked').val()
        },
        function()
        {
            $.post(
            '../Controller/ajax_list_conv.php', 
            {
                value : value,
            },
            function(data)
            {
                $('#conv_list').html(data);
            },
            'html'
            ); 
        },
        'html'
        );
    });   
    $( "#add_truitos" ).keyup(function() {
        $.post(
        '../Controller/ajax_search_user.php', 
        {
            search_follower : $('#add_truitos').val(),
        },
        function(data)
        {
            $('#result_follower').html(data);
        },
        'html'
        );
    });
    $('#delete').click(function(e)
    {

        $.post(
        '../Controller/ajax_delete_conv.php', 
        {
            id : $('input[type=radio][name=conversation]:checked').val()
        },
        function()
        {
            $.post(
            '../Controller/ajax_list_conv.php', 
            {
                value : "x"
            },
            function(data)
            {
                $('#editable_area').val("");
                $('#conv_list').html(data);
                var nb_conv = $('#conv_list').find('.input_conv').length;
                if (nb_conv > 0) {
                    $.post(
                        '../Controller/ajax_messagerie.php', 
                        {
                            content : $('#editable_area').val(),
                            conv_id : $('input[type=radio][name=conversation]:checked').val()
                        },
                        function(data)
                        {
                           $('#tweet_content').html(data);
                        },
                    'html'
                    ); 
                }
            },
            'html'
            ); 
        },
        'html'
        );
    });
});
function autoRefreshPage()
{
    var nb_conv = $('#conv_list').find('.input_conv').length;
    if (nb_conv > 0) {
        $.post(
            '../Controller/ajax_messagerie.php', 
            {
                content : "",
                conv_id : $('input[type=radio][name=conversation]:checked').val()
            },
            function(data)
            {
                $('#tweet_content').html(data);
            },
            'html'
        );
        var value = $('input[type=radio][name=conversation]:checked').val();
        $.post(
            '../Controller/ajax_list_conv.php', 
            {
                value : value
            },
            function(data)
            {
                $('#conv_list').html(data);
            },
            'html'
        ); 
    }
}
setInterval('autoRefreshPage()', 10000);