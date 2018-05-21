<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
   <!--Normal Form Html-->     
</body>
</html>
<script type="text/javascript">
    var vw = new Vue({
        el:"#app",
        data:{
            /*inputval:'',
            upload_type:'po',
            error_tb : false,
            error_msg:'',
            res_tb : false,
            res_title:'',
            res_col_titles:['order_number','sku','buyer','supplier','order_quantity','unit_price','order_date'],
            res : [],
            show_loading:false,*/
        },
        methods:{
            submit_form : function(){
                var self = this;
                
                /*Check input value*/
                /*self.show_loading = true;
                self.error_tb = false;
                self.res_tb = false;
                if(self.inputval.length==0){
                    self.error_tb = true;
                    self.error_msg = "Input can't empty.";
                    self.show_loading = false;
                    return; 
                }*/
                var url = "/tool/po_check/post_api";
                var ajax_type = "POST";
                var data = {type:self.upload_type,val:self.inputval};
                /*Update Csrf Token before Post Ajax Request Each Time*/
                window.bctr_token.ajax_with_token(url,ajax_type,data,callback_success,callback_fail);
                function callback_success(res) {
                    self.show_loading = false;
                    if('po' in res){
                        self.res_tb = true;
                        self.res_title = "PO Information";
                        self.res = res.po;
                    }else{
                        self.error_tb = true;
                        self.error_msg = "No data or incorrect.";
                    }
                };
                function callback_fail(msg,err=false) {
                    self.show_loading = false;
                    if(msg=='error'){
                        self.error_tb = true;
                        self.error_msg = 'System error ('+err.statusText+') , please contact with enginner.';
                    }else if(msg=='token_error'){
                        alert('Session timout,please login buyercenter agin.') 
                        /*Location to Login Page*/
                        window.location.href="/login";
                    }
                };
            }
        },
        ready: function(){
        }
    });
    
</script>
