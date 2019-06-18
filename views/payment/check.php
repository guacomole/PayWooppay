<?php
file_put_contents('check.pdf', $check->checkOnPrint->content);
?>
<label for="showCheckBtn" class="btn btn-default btn-info">Показать чек</label>
<input type="checkbox" id="showCheckBtn">
<div class="text"><?php
    /*echo '<br>' .
         'Номер квитанции: ' . $check->receipt . '<br>' .
         'Название сервиса: ' . $check->service_title . '<br>' .
         'Сумма платежа: ' . $check->amount . '<br>' .
         'Комиссия: ' . $check->commission . '<br>' .
         'Итого: ' . $check->admit . '<br>' .
         'Время: ' . $check->time . '<br>';
    foreach ($check->ident as $key => $value){
        echo $key . ': ' . $value . '<br>';
    }*/
    ?><embed src="check.pdf" width="400px" height="500px" />
</div>

<label for="printBtn" class="btn btn-default btn-info" onclick="print('check.pdf')">Распечатать чек</label>
<script>
    function print(doc) {
        var objFra = document.createElement('iframe');   // Create an IFrame.
        objFra.style.visibility = "hidden";    // Hide the frame.
        objFra.src = doc;                      // Set source.
        document.body.appendChild(objFra);  // Add the frame to the web page.
        objFra.contentWindow.focus();       // Set focus.
        objFra.contentWindow.print();      // Print it.
    }
</script>