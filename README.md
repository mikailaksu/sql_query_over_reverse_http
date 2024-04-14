# sql_query_over_reverse_http
Dışa ağa kapalı (tüm portları kapalı) MySql sunucuna Ters HTTP istekleri ile sorgulama yapmak.

![](https://i.hizliresim.com/a7eut0g.png)

## Senaryo
Bir web sunucunuz var ve başka bir ağda bir mysql sununuz var. İkisini birbiriyle haberleştirmek istiyorsunuz ama mysql sunucunuzun herhangi bir portunu açmak istemiyorsunuz/açamıyorsunuz. (Hassas verileriniz olabilir veya CGNAT olduğu için açamıyor olabilirsiniz.)
Peki ne yapacaksınız. ya sabit ipsi olan sunucuya taşıyacaksınız. ya bir api servisi kurup http ile haberleşeceksiniz ya da vpn kullanacaksınız başka yolu yok.
bu çözümlerin hepsinde dışarıya bir port açmanız lazım. (vpn'i web sunucusuna kurup sql sunucundan client ile bağlanmak dışında.)

alternatif bir çözüm işte karşınızda!

SQL Sunucusundan http istekleri göndererek. sql sorgusu var mı? yok mu? kontrol edip var ise sonucu gönderen bir servis!!! 

## Nasıl aklına geldi birader?
Siber güvenlikte reverse TCP methodu ile sızdığımız bir makinaya bağlanabiliyoruz. Veya reverse proxy ile ağa katılabiliyoruz. fakat bu yol ile web ve sql sunucusunu haberleşmesini sağlayan görmedim sanırım dünyada ilk =)


## Kurulum

* İki klasör var. MySQLServer klasöründeki dosyayı sql sunucunuzda çalıştıracaksınız, WebServer klasörünü ise web sitenizin olduğu sunucudur.
* WebServer klasöründe örnek bir index sayfası yaptım. index sayfası üzerinden bir sql sorgusu ekleyebilirsiniz. yine sonuçları da aynı ekrana düşer.

_WebServer Ayarları_

* .sql dosyasını import et.
* WebServer klasörünü apache vb bir sunucu ya at.
* içerisindeki sql dosyasını import et.
* config dosyasında veri tabanı ayarlarını ve şifreleme anahtarını (16 karakter olmalı) görebilirsiniz.

_MySQLServer Ayarları_
* python dosyasını kullanmam için pip install ile json, urllib3, mysql.connector, mysql.connector.pooling, queue, base64, Crypto, Crypto.Cipher, Crypto.Util.Padding paketlerini kurmalısınız.
* server.py dosyasında sorgulama yapılacak hedef database adını girin.
* şifreleme anahtarı da yine burada 16 karakter olacak şekilde düzenleyebilirsiniz.
* Son ve en önemli kısım api linkini buradan ayarlayabilirsiniz.
 
## Özellikler ve Kullanılan diller

* web tarafı php ile yazılmıştır.
* mysql serverda çalışacak yazılım python ile yazılmıştır.
* threading sayesinde birçok sorguyu aynı anda yapabilecek çoklu işleme sahiptir.
