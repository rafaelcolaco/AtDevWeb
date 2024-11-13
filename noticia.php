<?php
session_start();
include 'conexao.php';

if (!isset($_GET['id'])) {
    echo "Notícia não encontrada.";
    exit();
}

$id_noticia = $_GET['id'];

$sql = "SELECT * FROM noticias WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_noticia);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $noticia = $result->fetch_assoc();
} else {
    echo "Notícia não encontrada.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($noticia['titulo']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2><?php echo htmlspecialchars($noticia['titulo']); ?></h2>
    <p class="text-muted"><?php echo $noticia['data_publicacao']; ?></p>
    <p><?php echo nl2br(htmlspecialchars($noticia['conteudo'])); ?></p>
    <a href="index.php" class="btn btn-secondary">Voltar para as Notícias</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
