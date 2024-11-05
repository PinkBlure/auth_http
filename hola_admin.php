<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Administración</title>
</head>

<body>

  <?php

  require_once "funciones.php";

  session_start();

  if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
  }

  $nombre = htmlspecialchars($_SESSION['usuario']);
  $mensaje = "";

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuarioNombre = $_POST['name'];
    $usuarioPass = $_POST['password'] ?? '';
    $accion = $_POST['funcion'];
    $rol = $_POST['rol'] ?? 'user';

    if ($accion === "crear" && !empty($usuarioPass)) {
      $mensaje = anadirUsuario($usuarioNombre, $usuarioPass, $rol);
    } elseif ($accion === "eliminar") {
      $mensaje = eliminarUsuario($usuarioNombre);
    }
  }

  ?>

  <h1>Bienvenido, administrador: <?php echo $nombre; ?>!</h1>
  <a href="logout.php">Cerrar sesión</a>

  <form method="post">
    <label for="name">Nombre del usuario:</label>
    <input id="name" name="name" type="text" required>

    <label for="password">Contraseña del usuario:</label>
    <input id="password" name="password" type="password" required>

    <label for="rol">Rol del usuario:</label>
    <select id="rol" name="rol">
      <option value="user">Usuario</option>
      <option value="admin">Administrador</option>
    </select>

    <label for="funcion">Elige la función:</label>
    <select id="funcion" name="funcion">
      <option value="crear">Crear usuario</option>
      <option value="eliminar">Eliminar usuario</option>
    </select>

    <input type="submit" value="Realizar función">
  </form>


  <?php if ($mensaje): ?>
    <p><?php echo $mensaje; ?></p>
  <?php endif; ?>

</body>

</html>
