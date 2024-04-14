<?php
include 'config.php';

//bu sayfada sorgu ve sonuçlarını sorgulayıp tabloya yazdıran bir örnek yaptım. 2 tabloyu birleştiriyor yani

try {
    $stmt = $conn->query("SELECT sorgu.id, sorgu.query, sonuclar.sonuc
FROM sorgu
LEFT JOIN sonuclar ON sorgu.id = sonuclar.sorguid;");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($result);
} catch(PDOException $e) {
    echo "Veri alınırken bir hata oluştu: " . $e->getMessage();
}
?>