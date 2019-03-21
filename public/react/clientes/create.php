<?php

if(!empty($dados['senha'])) {
    $user = [
        "nome" => $dados['nome_razao_social'],
        "email" => $dados['email'] ?? "",
        "password" => $dados['senha'],
        "setor" => 6,
        "nivel" => 1,
        "status" => $dados['ativo']
    ];

    $read = new \Conn\Read();
    $read->exeRead("usuarios", "WHERE nome = :n", "n={$user['nome']}");
    $user['nome'] = $user['nome'] . ($read->getResult() ? "-" . strtotime('now') : "");
    $user["nome_usuario"] = \Helpers\Check::name($user['nome']);

    $dic = new \Entity\Dicionario("usuarios");
    $dic->setData($user);
    $dic->save();

    if(!$dic->getError()) {
        $up = new \Conn\Update();
        $up->exeUpdate("clientes", ['login' => $dic->search(0)->getValue()], "WHERE id = :id", "id={$dados['id']}");
    }
}