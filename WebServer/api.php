<?php
include 'config.php';

header('Content-Type: application/json');

function encrypt_data($data, $key) { //aes şifreleme
    $cipher = "AES-128-ECB";
    $options = OPENSSL_RAW_DATA;
    $encryptedData = openssl_encrypt($data, $cipher, $key, $options);
    return base64_encode($encryptedData);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") { //AKTİF SORGULARI GETİRİR VE ŞİFRELER
    try {
        $stmt = $conn->query("SELECT * FROM sorgu where active=1");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $response = array();
        if ($result) {
            $response['result'] = true;
            $response['data'] = $result;
        } else {
            $response['result'] = false;
        }
        
        echo encrypt_data(json_encode($response), $key);
    } catch(PDOException $e) {
        echo "Sorgu yapılırken bir hata oluştu: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // JSON verisini al
    $json_data = file_get_contents('php://input');
    // JSON verisini PHP dizisine dönüştür
    try {
   		$data = json_decode($json_data, true);
	} catch(PDOException $e) {
		echo "json izin verilir" . $e->getMessage();
	}
    if(isset($data['id']) && $data['proc'] == "disable"){ // GELEN JSON VERİSİNDE DİSABLE GELMİŞ İSE AKTİF DURUMUNU 0 OLARAK GÜNCELLER
        try {
            $id = $data['id'];
            $stmt = $conn->prepare("UPDATE sorgu SET active = 0 WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            echo "Active değeri başarıyla güncellendi.";
        } catch(PDOException $e) {
            echo "Active değeri güncellenirken bir hata oluştu: " . $e->getMessage();
        }
    }
	if(isset($data['id']) && $data['proc'] == "result"){ //EĞER PYTHON KODU SONUCU ATARSA ALIR VE BUNU KAYDEDER
        try {
            // SQL sorgusunu hazırla
            $stmt = $conn->prepare("INSERT INTO sonuclar (sorguid, sonuc) VALUES (:sorguid, :sonuc)");
            // Değerleri bağla
            $stmt->bindParam(':sorguid', $data['id']);
            $stmt->bindParam(':sonuc', $data['sonuc']);
            // Sorguyu çalıştır
            $stmt->execute();
            echo "Sonuç başarıyla eklendi.";
        } catch(PDOException $e) {
            echo "Sonuç bir hata oluştu: " . $e->getMessage();
        }
	}
}
?>
