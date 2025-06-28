<?php

$chaveAES = '6d404ff9e2b95bc0c8d6377c06cbf9074cf8797f809a23fcd7a7a85a2c83758a'; 

function descriptografarAES($texto, $chave) {

    $iv = str_repeat('0', 16); 
    return openssl_decrypt($texto, 'aes-256-cbc', $chave, 0, $iv);
}

?>
