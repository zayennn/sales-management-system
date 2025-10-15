cara clone project :

pastiin kalian punya:
- php versi 8
- xampp ( atau database lain juga boleh seperti postgresql/dll )
- sudah menginstall composer 


langkah langkah :
- copy link dari repository ini
- buka terminal/bash/command prompt kalian lalu arahkan ke bagian xampp/htdocts ( biasa nya ada di disk c )
- jalankan command : git clone https://github.com/zayennn/sales-management-system.git
- lalu jalankan command berikut di dalam terminal directory project nya : 1) composer install , 2) php artisan key:generate , 3) php artisan storage:link , 4) cp .env.example .env ( jangan lupa ganti isi dari .env nya => di sesuaikan dengan database kalian ), php artisan migrate , 5) php artisan db:seed , 6) php artisan serve
- akses link nya maka kalian sudah bisa akses web nya

kalian bisa ubah isi web nya secara mandiri untuk di sesuaikan dengan keinginan kalian , karena web ini default nya adalah 3vours , jadi kalian bisa ubah asset" di dalam nya

untuk account nya saya uda bikin seeder , hanya ada dua account sebagai default nya yaitu : 1) admin@sales.com , pw : admin123 => role = admin , 2) petugas@sales.com , pw : petugas123 => role = petugas

kalian bisa ubah juga di file DatabaseSeeder.php
