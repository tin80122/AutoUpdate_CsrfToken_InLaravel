# Automatically Update CSRF Token in Laravel
> Laravel 自動更新Csrf Token

## Enviroment
### Laravel
<a href="https://laravel.com/">Laravel Docs</a>
### CSRF Token
<a href="https://laravel.com/docs/5.6/csrf">CSRF Protection</a>

## Previously on Csrf Token
There are a verified key called CSRF Token in Laravel that communicates between View and Controller.
If user keep the brower stop too long,the CSRF token would expired.
> Laravel這套Framework的View與Controller之間有內建Csrf Token驗證，但是若瀏覽器停留過久，會有時效過期的問題，若Ajax的Request未特別處理，會跳出系統的錯誤訊息

## Structure
> 架構說明
### MVC Structure,Route and Javascript file
1. Js file
  * ajax_with_token.js
  The custom public Ajax function that get the newest CSRF Token request each time.
  > 自定義全域JS函式，再每次Ajax之前都先再要一個新的CSRF Token。
2. View
  * po_check.blade
  Using the custom js function to ajax instead default ajax function.
  > 呼叫自定義全域JS函式去做Ajax的動作，而不是直接使用預設的Ajax函式。
3. Contoller
  Send the new CSRF Token request to Laravel framework.
  >和Laravel框架要求更新CSRF Token的值
4. Route
  Define a route name that helps to commuicate View and Controller.
  定義好恰當的Route的名字，以便View與Controller溝通。
  
  
### Code Docs
Continuing...
