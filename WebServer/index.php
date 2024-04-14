<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>


	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">


	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
	<meta charset="utf-8">
	<title>SQL QUERY OVER REVERSE HTTP</title>
</head>
<body>

<h2>hedef sunucuda sorgulanacak sorgu formu</h2>

<form id="queryForm">
    sql sorgusu (select * from ornekdb gibi): <input type="text" id="query" name="query" placeholer="select * from ornekdatabase"><br><br>
    <input type="submit" name="submit" value="Gönder">
</form>

<h2>Anlık Veriler Tablosu</h2>
<table id="dataTable" class="table table-hover table-bordered dataTable no-footer table-striped table-dark table-sm" border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Query</th>
            <th>sonuc</th>
        </tr>
    </thead>
    <tbody>
        <!-- Tablo verileri AJAX isteği ile buraya eklenecek -->
    </tbody>
</table>

<script>
$(document).ready(function(){
	$('#dataTable').DataTable( {
					dom: 'Bfrtip',
					buttons: [
						'copy', 'csv', 'excel', 'pdf', 'print'
					]
				} );
     // Sayfa yüklendiğinde ve her 1 saniyede bir verileri yenile
    refreshData(); // İlk verileri al

    setInterval(function(){
        refreshData(); // Her 1 saniyede bir verileri yenile
    }, 1000); // 1000 milisaniye = 1 saniye

    $('#queryForm').submit(function(event){
        event.preventDefault(); // Formun normal submit işlemini engelle
        
        var formData = {
            'query': $('#query').val()
        };

        // AJAX isteğini oluştur
        $.ajax({
            type: 'POST',
            url: 'insert_data.php', // Verilerin gönderileceği PHP dosyasının adı
            data: JSON.stringify(formData), // Veriyi JSON formatına dönüştür
            contentType: 'application/json', // Verinin türünü belirt
            success: function(response){
                console.log(response); // Yanıtı konsola yazdır
                alert(response); // Yanıtı ekrana göster
                // Veri ekledikten sonra tabloyu güncelle
                refreshData();
            },
            error: function(xhr, status, error){
                console.error(xhr.responseText); // Hata mesajını konsola yazdır
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            }
        });
    });
});

// Verileri güncellemek için AJAX isteği gönder
function refreshData() {
    $.ajax({
        type: 'GET',
        url: 'get_data.php', // Verilerin alınacağı PHP dosyasının adı
        success: function(response){
            console.log(response); // Yanıtı konsola yazdır
            // Tabloyu güncelle
            updateTable(response);
        },
        error: function(xhr, status, error){
            console.error(xhr.responseText); // Hata mesajını konsola yazdır
            alert('Bir hata oluştu. Lütfen tekrar deneyin.');
        }
    });
}

// Tabloyu güncelle
function updateTable(data) {
    // Tablo gövdesini boşalt
    $('#dataTable tbody').empty();
    // Verileri tabloya ekle
    $.each(data, function(index, row) {
        $('#dataTable tbody').append('<tr><td>' + row.id + '</td><td>' + row.query + '</td><td>' + row.sonuc + '</td></tr>');
    });
}
</script>

</body>
</html>