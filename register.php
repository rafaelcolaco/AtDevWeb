<?php
session_start();
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    $nivel_acesso = $_POST['nivel_acesso']; 
    $empresa_id = $_POST['empresa_id']; 

    if ($senha !== $confirmar_senha) {
        echo "As senhas não coincidem!";
        exit();
    }

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Este e-mail já está registrado. Tente outro.";
        exit();
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, senha, nivel_acesso, empresa_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nome, $email, $senha_hash, $nivel_acesso, $empresa_id);

    if ($stmt->execute()) {
        echo "Usuário registrado com sucesso!";
        header("Location: login.html");
    } else {
        echo "Erro ao registrar o usuário: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

$sql = "SELECT * FROM empresas";
$stmt = $conn->prepare($sql);
$stmt->execute();
$empresas_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Registrar Usuário</h2>
    <form action="register.php" method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" name="nome" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label">Senha</label>
            <input type="password" class="form-control" name="senha" required>
        </div>
        <div class="mb-3">
            <label for="confirmar_senha" class="form-label">Confirmar Senha</label>
            <input type="password" class="form-control" name="confirmar_senha" required>
        </div>
        <div class="mb-3">
            <label for="nivel_acesso" class="form-label">Nível de Acesso</label>
            <select name="nivel_acesso" class="form-control" required>
                <option value="logado">Logado</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="empresa_id" class="form-label">Escolha a Empresa</label>
            <select name="empresa_id" class="form-control" required>
                <?php while ($empresa = $empresas_result->fetch_assoc()): ?>
                    <option value="<?php echo $empresa['id']; ?>"><?php echo $empresa['nome']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
