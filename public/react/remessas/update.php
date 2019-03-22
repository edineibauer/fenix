<?php

//gera código de rastreio caso não tenha
if (empty($dados['codigo_de_rastreio']) && $dados['status'] == 1) {
    $remessa['codigo_de_rastreio'] = "FNX" . str_pad($dados['id'], 10, "0", STR_PAD_LEFT);
    $up = new \Conn\Update();
    $up->exeUpdate("remessas", $remessa, "WHERE id =:id", "id={$dados['id']}");
}
