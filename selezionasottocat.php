<?php
require_once('connect.php');

$categoria = $_GET['nomecat'];
if($categoria == 'none'){
    echo 0;
}
else{// Esegue la query per ottenere le sottocategorie corrispondenti alla categoria selezionata
    $querysottocat = $conn->prepare('SELECT DISTINCT nome_sottocategoria FROM sottocategoria WHERE nome_categoria=?');
    $querysottocat -> bind_param('s', $categoria);
    $querysottocat->execute();
    $resultset = $querysottocat->get_result(); 

    for($i=0; $i < $resultset->num_rows; $i++){
        $sottocat[$i] = $resultset->fetch_assoc();
    }
    echo json_encode($sottocat); //permette il passaggio dell'array da PHP a JS
}

?>