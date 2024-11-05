<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Iniciar Sesión</title>
</head>

<body>

  <?php
  require_once "funciones.php";
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  session_start();

  $mensaje = "";
  $intentosPermitidos = 3;
  $bloqueoTiempo = 60;

  if (!isset($_SESSION['intentos'])) {
    $_SESSION['intentos'] = 0;
  }

  if (!isset($_SESSION['ultimo_intento'])) {
    $_SESSION['ultimo_intento'] = time();
  }

  if ($_SESSION['intentos'] >= $intentosPermitidos) {
    $tiempoRestante = $bloqueoTiempo - (time() - $_SESSION['ultimo_intento']);
    if ($tiempoRestante > 0) {
      $mensaje = "Demasiados intentos fallidos. Intente de nuevo en $tiempoRestante segundos.";
    } else {
      $_SESSION['intentos'] = 0;
      $_SESSION['ultimo_intento'] = time();
    }
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['intentos'] < $intentosPermitidos) {
    $nombre = $_POST['name'] ?? '';
    $contraseña = $_POST['pass'] ?? '';

    if (buscarUsuario($nombre, $contraseña)) {
      $_SESSION['intentos'] = 0;
      $_SESSION['usuario'] = $nombre;

      $conexion = crearConexion();
      if ($conexion) {
        $query = "SELECT rol FROM usuarios WHERE usuario = :usuario";
        $stmt = $conexion->prepare($query);
        $stmt->bindParam(':usuario', $nombre);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['rol'] = $user['rol'];

        if ($_SESSION['rol'] == 'admin') {
          header("Location: hola_admin.php");
        } else {
          header("Location: hola.php");
        }
        exit();
      } else {
        $mensaje = "Error de conexión a la base de datos.";
      }
    }
  }
  ?>

  <form method="POST">
    <label for="name">Nombre: </label>
    <input name="name" id="name" type="text" required>
    <label for="pass">Contraseña: </label>
    <input name="pass" id="pass" type="password" required>
    <button type="submit">Iniciar Sesión</button>
  </form>

  <p><?php echo htmlspecialchars($mensaje); ?></p>

</body>

</html>