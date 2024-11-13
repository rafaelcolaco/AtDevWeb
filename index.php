<?php
session_start();
include 'conexao.php';

$logado = isset($_SESSION['usuario_id']);
$nivel_acesso = $logado ? $_SESSION['nivel_acesso'] : null;
$empresa_id = $logado ? $_SESSION['empresa_id'] : null;

$sql = $empresa_id 
    ? "SELECT * FROM noticias WHERE empresa_id = ? ORDER BY data_publicacao DESC" 
    : "SELECT * FROM noticias ORDER BY data_publicacao DESC";
$stmt = $conn->prepare($sql);
if ($empresa_id) {
    $stmt->bind_param("i", $empresa_id);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Portal de Notícias</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if ($logado): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Sair</a>
                    </li>
                    <?php if ($nivel_acesso == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="adicionarNoticia.html">Criar Notícia</a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.html">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Registrar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="mt-4">
        <h2>Últimas Notícias</h2>
        <?php if ($result->num_rows > 0): ?>
            <div class="row">
                <?php while ($noticia = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($noticia['titulo']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars(substr($noticia['conteudo'], 0, 100)) . '...'; ?></p>
                                <p class="text-muted"><?php echo $noticia['data_publicacao']; ?></p>
                                <a href="noticia.php?id=<?php echo $noticia['id']; ?>" class="btn btn-primary">Leia Mais</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p class="text-muted">Nenhuma notícia disponível.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
