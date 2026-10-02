@echo off
echo ============================================================
echo MENGEMBALIKAN DATA TOKO DAN MENU KE SERVER UBUNTU
echo ============================================================
echo.
echo [1/2] Mengupload file database dari Windows ke Ubuntu...
echo [!] Tolong masukkan password server Ubuntu (sulis) jika diminta:
scp C:\xampp\htdocs\geprek_bestie\geprekbestie_dump.sql sulis@172.16.63.152:~/
echo.
echo.
echo [2/2] Memasukkan data ke dalam database website...
echo [!] Tolong masukkan password server Ubuntu (sulis) sekali lagi:
ssh sulis@172.16.63.152 "export DB_PASS=$(grep '^DB_PASSWORD=' /var/www/geprek_bestie/.env | cut -d= -f2 | tr -d '\"'); mysql -u geprek_user -p\"$DB_PASS\" geprek_bestie < ~/geprekbestie_dump.sql"
echo.
echo ============================================================
echo RESTORE SELESAI! Silakan refresh website Bestie Geprek Anda!
echo (Jika ada menu/toko yang fotonya tidak muncul, pastikan Anda
echo juga sudah mengupload folder storage/app/public)
echo ============================================================
pause
