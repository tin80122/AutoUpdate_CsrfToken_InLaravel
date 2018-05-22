# Refresh CSRF Token in Laravel
> Laravel 自動更新Csrf Token

## Enviroment
### Laravel
<a href="https://laravel.com/">Laravel Docs</a>
### CSRF Token
<a href="https://laravel.com/docs/5.6/csrf">CSRF Protection</a>

## Previously on CSRF Token
There are a verified key called CSRF Token in Laravel that communicates between View and Controller.<br>
If users keep the brower stop too long,the CSRF token would expired.<br>
> Laravel這套Framework的View與Controller之間有內建CSRFToken驗證，但是若瀏覽器停留過久，會有時效過期的問題，若Ajax的Request未特別處理，會跳出系統的錯誤訊息

## Structure
> 架構說明
### MVC Structure,Route and Javascript file
1. Javascript file
  * ajax_with_token.js<br>
  The custom public Ajax function that get the newest CSRF Token request each time.
	  > 自定義全域JS函式，再每次Ajax之前都先再要一個新的CSRF Token。
2. View
  * po_check.blade<br>
  Using the custom js function to ajax instead default ajax function.<br>
	  > 呼叫自定義全域JS函式去做Ajax的動作，而不是直接使用預設的Ajax函式。
3. Contoller
  * TokenController.php<br>
  Send the new CSRF Token request to Laravel framework.
	  > 和Laravel框架要求更新CSRF Token的值<br>
4. Route
  * routes.php<br>
  Define a route name that helps to commuicate View and Controller.<br>
	  > 定義好恰當的Route的名字，以便View與Controller溝通。<br>
  
  
### Code Docs

1. View
* po_check.blade


	var url = "/tool/po_check/post_api";
	var ajax_type = "POST";
	var data = {type:self.upload_type,val:self.inputval};

	**Calling Custom Public Function bctr_token.ajax_with_token to Update Csrf Token before Post Ajax Request Each Time.
	Beside,We need to define successful callback function and failure callback function first .**
	> 將欲送出的Ajax Request的相關參數，EX: url、ajax_type 、成功後回傳的函式、失敗後回傳的函式等等都定義好，再根據我們自定義的全域Js函式的格式，呼叫它，完成Ajax的請求。

	window.bctr_token.ajax_with_token(url,ajax_type,data,callback_success,callback_fail);

	**Custom successful callback function**
	>自定義的成功後回傳的函式 
	
	<pre><code>function callback_success(res) {
	    self.show_loading = false;
	    if('po' in res){
	        self.res_tb = true;
	        self.res_title = "PO Information";
	        self.res = res.po;
	    }else{
	        self.error_tb = true;
	        self.error_msg = "No data or incorrect.";
	    }
	};</code></pre>


	**Custom failure callback function**
	>自定義的失敗後回傳的函式 
	
	<pre><code>function callback_fail(msg,err=false) {
	    self.show_loading = false;
	    if(msg=='error'){
	        self.error_tb = true;
	        
	        self.error_msg = 'System error ('+err.statusText+') , please contact with enginner.';
	        
	    }else if(msg=='token_error'){
	        alert('Session timout,please login buyercenter agin.') 
	        
	        /*Location to Login Page*/
	        window.location.href="/login";
	        
	    }
	};</code></pre>

2. Public Javascript Function
  * ajax_with_token.js

> 自定義全域Js函式，定義好預設的參數
<pre><code>window.bctr_token = {
    ajax_with_token : function(url,ajax_type,data,callback_success,callback_fail,ajax_file=true,async_opt=false){
    </code></pre>
    
 <pre><code>   
        var content_type = 'application/x-www-form-urlencoded; charset=UTF-8';
        var async = false;
        var token;
        $.ajax({
            url:'/refresh_token',
            type:'GET',
            success:function(res){
 </code></pre>
   >  如果成功要到最新的Token，則更新html標籤中的token參數；失敗則回傳參數到失敗函式中。
             
  <pre><code>
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
 </code></pre>
 > 確認要到最新的Token後，才執行欲送出的Ajax請求事件。
  <pre><code>
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
};</code></pre>

3. Controller
* TokenController.php

> <a href="https://laravel.com/docs/5.6/helpers#method-csrf-token">csrf_token()</a> 是Laravel的內建函式，用以取得最新的token值
<pre><code>class TokenController extends Controller
{
    public function refresh_token()
    {
        return ['status'=>'success','token'=>csrf_token()];
    }
}</code></pre>



