window.bctr_token = {
    ajax_with_token : function(url,ajax_type,data,callback_success,callback_fail,ajax_file=true,async_opt=false){
        var content_type = 'application/x-www-form-urlencoded; charset=UTF-8';
        var async = false;
        var token;
        $.ajax({
            url:'/refresh_token',
            type:'GET',
            success:function(res){
                var status = res.status;
                if(status=='success'){
                    token = res.token;
                    $('meta[name="csrf-token"]').attr('content',token);
                    
                    if(ajax_file==false){
                        content_type = false;
                    }
                    if(async_opt==true){
                        async = true;
                    }

                    $.ajax({
                        url:url,
                        type:ajax_type,
                        data:data,
                        async:async,
                        processData: ajax_file,  // tell jQuery not to process the data
                        contentType: content_type, // tell jQuery not to set contentType
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                        success:callback_success,
                        error:function(err){
                          callback_fail('error',err);
                        }
                    })
                }else{
                    callback_fail('token_error');
                }
            }
        });
        
    }
};
