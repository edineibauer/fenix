<?php

if(!empty($dados['senha'])) {
    $user = [
        "nome" => $dados['agente'],
        "email" => $dados['email'] ?? "",
        "password" => $dados['senha'],
        "setor" => 3,
        "nivel" => 1,
        "status" => $dados['status']
    ];

    $read = new \Conn\Read();
    $read->exeRead("usuarios", "WHERE nome = :n", "n={$user['nome']}");
    $user['nome'] = $user['nome'] . ($read->getResult() ? "-" . strtotime('now') : "");
    $user["nome_usuario"] = \Helpers\Check::name($user['nome']);

    $dic = new \Entity\Dicionario("usuarios");
    $dic->setData($user);
    $dic->save();

    if(!$dic->getError()) {
        $dados['usuario_id'] = $dic->search(0)->getValue();
        $up = new \Conn\Update();
        $up->exeUpdate("agentes", $dados, "WHERE id = :id", "id={$dados['id']}");
    }
}