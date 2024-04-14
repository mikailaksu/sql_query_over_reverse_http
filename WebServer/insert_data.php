<?php
include 'config.php';

#bu sayfa sorgulanacak sql sorguları tablosuna ekleme yapar ve active değerini 1 olarak kaydeder. 
#server py sorgularsa bu değeri 0 yapar

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // JSON verisini al
    $json_data = file_get_contents('php://input');
    // JSON verisini PHP dizisine dönüştür
    $data = json_decode($json_data, true);

    if(isset($data['query'])){
        try {
            $stmt = $conn->prepare("INSERT INTO sorgu (query, active) VALUES (:query, :active)");
            $stmt->bindValue(':query', $data['query']);
            $stmt->bindValue(':active', 1);
            $stmt->execute();
            echo "sorgu eklendi.";
        } catch(PDOException $e) {
            echo "sorgu eklenirken bir hata oluştu: " . $e->getMessage();
        }
    }
}
?>