<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['nivel_acesso'] != 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];
    $empresa_id = $_SESSION['empresa_id'];

    $sql = "INSERT INTO noticias (titulo, conteudo, empresa_id, data_publicacao) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $titulo, $conteudo, $empresa_id);

    if ($stmt->execute()) {
        echo "Notícia criada com sucesso!";
        header("Location: index.php"); 
    } else {
        echo "Erro ao criar notícia: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<form action="adicionarNoticia.php" method="POST" class="container mt-5">
    <h2>Criar Notícia</h2>
    <div class="mb-3">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" class="form-control" name="titulo" required>
    </div>
    <div class="mb-3">
        <label for="conteudo" class="form-label">Conteúdo</label>
        <textarea class="form-control" name="conteudo" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Publicar</button>
</form>
