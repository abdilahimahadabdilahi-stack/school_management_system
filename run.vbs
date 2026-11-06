Set WShell = CreateObject("WScript.Shell")
WShell.Run "cmd.exe /c cd /d C:\xampp\htdocs\school_management_system && php artisan serve", 0, False
WScript.Sleep 3000
WShell.Run "http://127.0.0.1:8000"