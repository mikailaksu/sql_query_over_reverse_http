# sql_query_over_reverse_http
Dışa ağa kapalı (tüm portları kapalı) MySql sunucuna Ters HTTP istekleri ile sorgulama yapmak.

![](https://i.hizliresim.com/a7eut0g.png)

## Senaryo
Bir web sunucunuz var ve başka bir ağda bir mysql sununuz var. İkisini birbiriyle haberleştirmek istiyorsunuz ama mysql sunucunuzun herhangi bir portunu açmak istemiyorsunuz/açamıyorsunuz. (Hassas verileriniz olabilir veya CGNAT olduğu için açamıyor olabilirsiniz.)
Peki ne yapacaksınız. ya sabit ipsi olan sunucuya taşıyacaksınız. bir api servisi kurup http ile haberleşeceksiniz ya da vpn kullanacaksınız başka yolu yok.
bu çözümlerin hepsinde dışarıya bir port açmanız lazım. (vpn'i web sunucusuna kurup sql sunucundan client ile bağlanmak dışında.)

alternatif bir yol işte karşınızda.

SQL Sunucusundan http istekleri göndererek. sql sorgusu var mı yokmu kontrol edip var ise sonucu gönderen bir servis!!! 

# Nasıl aklına geldi birader?
Siber güvenlikte reverse TCP methodu ile sızdığımız bir makinaya bağlanabiliyoruz. Veya reverse proxy ile ağa katılabiliyoruz. fakat bu yol ile web ve sql sunucusunu haberleşmesini sağlayan görmedim sanırım dünyada ilk =)
