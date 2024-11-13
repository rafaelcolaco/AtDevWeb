<?php
session_start();
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nivel_acesso'] = $usuario['nivel_acesso'];
        $_SESSION['empresa_id'] = $usuario['empresa_id'];
        header("Location: index.php");
    } else {
        echo "Email ou senha incorretos!";
    }

    $stmt->close();
    $conn->close();
}
?>
