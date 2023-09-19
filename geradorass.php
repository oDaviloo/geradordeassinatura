<?php

$conn = new mysqli("localhost", "root", "", "gerador");


if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $arquivo_nome = $_FILES["arquivo"]["name"];
    $arquivo_tmp = $_FILES["arquivo"]["tmp_name"];
    $malicioso = isset($_POST["malicioso"]) ? 1 : 0;

    // função de calcular o hash
    $md5_hash = md5_file($arquivo_tmp);


    $sql = "INSERT INTO arquivos (nome_arquivo, md5_hash, malicioso) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $arquivo_nome, $md5_hash, $malicioso);

    if ($stmt->execute()) {
        echo "Arquivo enviado com sucesso!";
    } else {
        echo "Erro ao enviar o arquivo: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> 
</head>

<body>
    <h1>Gerador de Assinatura</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="col-md-3">
            <label for="arquivo" class="form-label">Selecione um arquivo:</label>
            <input type="file" class="form-control" name="arquivo" id="arquivo">
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" name="malicioso" id="malicioso">
            <label class="form-check-label" for="malicioso">É malicioso?</label>
        </div>
        <div class="col-md-auto">
            <input name="enviar" id="enviar" class="btn btn-primary" type="submit" value="Enviar">
        </div>
    </form>
</body>

</html>