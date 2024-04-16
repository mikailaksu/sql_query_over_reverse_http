# Reverse HTTP ile SQL Sorguları / SQL Query Over Reverse HTTP

Dış ağa kapalı (tüm portları kapalı) MySql sunucuna Ters HTTP istekleri ile sorgulama yapmak. / Querying a MySQL server with reverse HTTP requests when all ports are closed to the outside world.

![](https://i.hizliresim.com/a7eut0g.png)

## Senaryo / Scenario

TR:
Bir web sunucunuz var ve başka bir ağda bir mysql sununuz var. İkisini birbiriyle haberleştirmek istiyorsunuz ama mysql sunucunuzun herhangi bir portunu açmak istemiyorsunuz/açamıyorsunuz. (Hassas verileriniz olabilir veya CGNAT olduğu için  PORT açamıyor olabilirsiniz.)
Peki ne yapacaksınız. ya sabit ipsi olan sunucuya taşıyacaksınız. ya bir api servisi kurup http ile haberleşeceksiniz ya da vpn kullanacaksınız başka yolu yok.
bu çözümlerin hepsinde dışarıya bir port açmanız lazım. (vpn'i web sunucusuna kurup sql sunucundan client ile bağlanmak dışında.)

alternatif bir çözüm işte karşınızda! 

SQL Sunucusundan http istekleri göndererek. sql sorgusu var mı? yok mu? kontrol edip var ise sonucu gönderen bir servis!!!

EN:
You have a web server and a MySQL server in different networks. You want to communicate between them, but you don't want or can't open any ports on the MySQL server (for instance, due to sensitive data or being behind a CGNAT). So, what do you do? You might consider moving to a server with a static IP, setting up an API service for communication over HTTP, or using a VPN. However, all of these solutions require opening an external port. Here comes an alternative solution:

 A service that sends HTTP requests from the SQL server, checks for SQL queries, and sends back results!

## Fikir Geliştirme!
TR:
Siber güvenlikte reverse TCP methodu ile sızdığımız bir makinaya bağlanabiliyoruz. Veya reverse proxy ile ağa katılabiliyoruz. fakat bu yol ile web ve sql sunucusunu haberleşmesini sağlayan görmedim sanırım dünyada ilk olabilir.

EN:
In cybersecurity, we can connect to a machine using reverse TCP or join a network using a reverse proxy. However, a solution that facilitates communication between a web server and a SQL server using this method seems to be unprecedented - perhaps it's a first in the world!


## Kurulum / Installation

* İki klasör var. MySQLServer klasöründeki dosyayı sql sunucunuzda çalıştıracaksınız, WebServer klasörünü ise web sitenizin olduğu sunucudur.
* WebServer klasöründe örnek bir index sayfası yaptım. index sayfası üzerinden bir sql sorgusu ekleyebilirsiniz. yine sonuçları da aynı ekrana düşer.

EN:
* There are two folders: MySQLServer and WebServer. Run the file in the MySQLServer folder on your MySQL server and use the WebServer folder on the server where your website is hosted.
* An example index page is provided in the WebServer folder. You can add SQL queries through this page, and the results will be displayed on the same screen.

_WebServer Ayarları_

TR:
* .sql dosyasını import et.
* WebServer klasörünü apache vb bir sunucu ya at.
* config dosyasında veri tabanı ayarlarını ve şifreleme anahtarını (16 karakter olmalı) görebilirsiniz.

EN:
* Import the .sql file.
* Place the WebServer folder on an Apache or similar server.
* You can see database settings and the encryption key (which must be 16 characters) in the config file.

_MySQLServer Ayarları_

TR:
* python dosyasını kullanmam için pip install ile json, urllib3, mysql.connector, mysql.connector.pooling, queue, base64, Crypto, Crypto.Cipher, Crypto.Util.Padding paketlerini kurmalısınız.
* server.py dosyasında sorgulama yapılacak hedef database adını girin.
* şifreleme anahtarı da yine burada 16 karakter olacak şekilde düzenleyebilirsiniz.
* Son ve en önemli kısım api linkini buradan ayarlayabilirsiniz.

EN:
* Install the json, urllib3, mysql.connector, mysql.connector.pooling, queue, base64, Crypto, Crypto.Cipher, Crypto.Util.Padding packages using pip install to use the Python file.
* Enter the target database name for querying in the server.py file.
* You can also edit the encryption key here, ensuring it is 16 characters long.
* Finally, and most importantly, you can set the API endpoint from here.
 
## Özellikler ve Kullanılan diller / Features and Used Languages

TR:
* web tarafı php ile yazılmıştır.
* mysql serverda çalışacak yazılım python ile yazılmıştır.
* threading sayesinde birçok sorguyu aynı anda yapabilecek çoklu işleme sahiptir.

EN:
* The web side is written in PHP.
* The software running on the MySQL server is written in Python.
* Multithreading allows for concurrent processing of multiple queries.
