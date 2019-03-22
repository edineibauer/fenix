<?php

$create = new \Conn\Create();
$read = new \Conn\Read();

function getBase(string $name): array
{
    if (!file_exists(PATH_HOME . "cep/{$name}.json")) {
        \Helpers\Helper::createFolderIfNoExist(PATH_HOME . "cep");
        $f = fopen(PATH_HOME . "cep/{$name}.json", "w+");
        $base = ["a" => 0, "b" => 0, "c" => 0, "d" => 0, "e" => 0, "f" => 0, "g" => 0, "h" => 0];
        fwrite($f, json_encode($base));
        fclose($f);
        return $base;
    } else {
        return json_decode(PATH_HOME . "cep/{$name}.json", !0);
    }
}

$name = "widenet";
$baseCep = getBase($name);
$baseUrl = "http://apps.widenet.com.br/busca-cep/api/cep/{{cep}}.json";

$conversao = [
    "code" => "cep",
    "state" => "estado",
    "city" => "cidade",
    "district" => "bairro",
    "address" => "logradouro",
    "longitude" => "longitude",
    "latitude" => "latitude",
];

$max = 100;
$count = 0;
for ($h = $baseCep['h']; $h < 10; $h++) {
    for ($g = $baseCep['g']; $g < 10; $g++) {
        for ($f = $baseCep['f']; $f < 10; $f++) {
            for ($e = $baseCep['e']; $e < 10; $e++) {
                for ($d = $baseCep['d']; $d < 10; $d++) {
                    for ($c = $baseCep['c']; $c < 10; $c++) {
                        for ($b = $baseCep['b']; $b < 10; $b++) {
                            for ($a = $baseCep['a']; $a < 10; $a++) {
                                if($count < $max) {

                                    $cep = $d . $c . $b . $a . "0" . "000";
                                    $result = (array) json_decode(file_get_contents(str_replace('{{cep}}', $cep, $baseUrl), !0));

                                    if ($result['status'] === 1) {
                                        foreach ($result as $col => $value) {
                                            if (!empty($value) && isset($conversao[$col]))
                                                $dados[$conversao[$col]] = $conversao[$col] === "cep" ? str_replace('-', '', $value) : $value;
                                        }

                                        if (!empty($dados['cep']) && !empty($dados['estado']) && !empty($dados['cidade'])) {
                                            $read->exeRead("cep", "WHERE cep = :cc", "cc={$dados['cep']}");
                                            if (!$read->getResult())
                                                $create->exeCreate("cep", $dados);
                                        }
                                    }
                                    $count++;

                                } else {

                                    $f = fopen(PATH_HOME . "cep/{$name}.json", "w+");
                                    fwrite($f, json_encode(["a" => $a, "b" => $b, "c" => $c, "d" => $d, "e" => $e, "f" => $f, "g" => $g, "h" => $h]));
                                    fclose($f);

                                    header("Refresh:0");
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}