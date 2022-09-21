call ng build -c=production --base-href /code_drops/my-world-app/
call rmdir /Q /S D:\com.sandeep.org\com.sandeep.edu\code_drops\my-world-app-prod
call Xcopy /E /I D:\com.sandeep.org\com.sandeep.edu\git\my-world-app\dist\my-world-app D:\com.sandeep.org\com.sandeep.edu\code_drops\my-world-app-prod
call cd D:\com.sandeep.org\com.sandeep.edu\code_drops\my-world-app-prod\php_includes
call del config.php
call ren config.prod.php config.php