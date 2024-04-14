import json
import urllib3
import mysql.connector
import mysql.connector.pooling
import threading
from queue import Queue
import base64 
from Crypto.Cipher import AES
from Crypto.Util.Padding import pad,unpad

#developed by https://github.com/mikailaksu

def decrypt(enc,key,leng): #gelen aes sql sorgusunu decrypt eder
	enc = base64.b64decode(enc)
	cipher = AES.new(key.encode('utf-8'), AES.MODE_ECB)
	return unpad(cipher.decrypt(enc),leng)

def send_get_request(url): #web sunucunundan gelen isteği kontol eden GET
	http = urllib3.PoolManager()
	response = http.request('GET', url)
	return response.data.decode('utf-8')

def send_post_request(url, data): #sql çıktısını ve apiye kapaması talimatını gönderen post
	http = urllib3.PoolManager()
	headers = {'Content-Type': 'application/json'}
	encoded_data = json.dumps(data).encode('utf-8')
	response = http.request('POST', url, body=encoded_data, headers=headers)
	return response.data.decode('utf-8')

def process_response(sql_query, connection): #gelen sql cümlesini sorgulayıp cevabını json verir
	cursor = connection.cursor()
	cursor.execute(sql_query)
	result = cursor.fetchall()
	cursor.close()
	return json.dumps(result, ensure_ascii=False)

def main(q, conn):
	while True:
		query = q.get()
		if query != None: #dizide sorguvarsa
			disable = {
			"id": query["id"],
			"proc": "disable"
			}
			
			print(send_post_request(url, disable)) #gelen sorguyu daha göstermemesi için istek yollar ki sürekli sorgulamasın
			
			cevap = process_response(query["query"], conn) #sorguyu kendi içerisindeki database de sorgular
			print(cevap)
			
			snc = {
			"id": querys["id"],
			"proc": "result",
			"sonuc": cevap
			}
			print(send_post_request(url, snc)) #cevabı yollar
			
if __name__ == "__main__":
	url = "http://localhost/WebServer/api.php" #web sunucusuna kurduğunuz apinin url adresi
	database = "database" #buraya sorgu çalıştıracağınız database adını gir
	key = "AAAAAAAAAAAAAAAA" #aes cevabı decrypt etmek için
	threadsToRun = 32
	conn_pool = mysql.connector.pooling.MySQLConnectionPool(user='root', password='', host='127.0.0.1', database=database, pool_name='mypool', pool_size = threadsToRun)
	
	print(f"{url} üzerinde çalışıyor. seçilen veri tabanı: {database}, thread: {threadsToRun}")
	q = Queue()
	processed_queries = set() # işlenen idler listesi
	
	def startThread():
		conn = conn_pool.get_connection()
		thread = threading.Thread(target=main, args=(q, conn,))
		thread.start()
		return thread
	
	for _ in range(threadsToRun):
		thread = startThread()
		
	while True:
		response = send_get_request(url) #get isteği atar
		response = json.loads(decrypt(response, key, len(key))) #isteği decrypt eder
		if response["result"] == True: #eğer istek gelmiş ise
			for querys in response["data"]:
				if querys["id"] not in processed_queries: #tekrar etmesin diye kontrol eder
					q.put(querys)
					processed_queries.add(querys["id"]) #işlenen idleri listeye atar 


#developed by https://github.com/mikailaksu
