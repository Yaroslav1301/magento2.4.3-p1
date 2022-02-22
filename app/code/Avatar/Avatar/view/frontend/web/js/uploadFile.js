define([
    'jquery'
], function ($) {

    $(document).ready(function(){
        $('#profile_pic').change(function(){
            let name = $(this).attr('name');
            let files = $(this)[0].files[0];
            let formData = new FormData();
            formData.append(name, files);
            $.ajax({
                url: '/avatar/avatar/upload',
                data: formData,
                type: "POST",
                showLoader: true,
                contentType: false,
                processData: false,
                success: function (res) {
                    let message = $('#success-message');
                    if(res.success){
                        $('.save').removeAttr('disabled');
                        $(message).removeAttr('hidden');
                        $(message).html('<p style="color: green">' + res.success + '</p>');
                    }
                    else{
                        $('.save').attr('disabled', 'disabled');
                        $(message).removeAttr('hidden');
                        $(message).html('<p style="color: red">' + res.error + '</p>');
                    }
                },
                error: function () {
                    console.log('Ajax error!');
                }
            });
        });
    });
});