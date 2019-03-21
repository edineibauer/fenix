<?php
if (!empty($dados['login']) && is_numeric($dados['login'])) {
    $read = new \Conn\Read();
    $read->exeRead("usuarios", "WHERE id = :id", "id={$dados['login']}");
    if($read->getResult()) {
        $del = new \Conn\Delete();
        $del->exeDelete("usuarios", "WHERE id = :id", "id={$dados['login']}");
    }
}