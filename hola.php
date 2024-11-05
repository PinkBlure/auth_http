<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenida</title>
</head>

<body>

  <?php

  // Iniciar sesión o reanudar sesión existente
  session_start();

  if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
  }

  $nombre = htmlspecialchars($_SESSION['usuario']);
  ?>

  <h1>Bienvenido, <?php echo $nombre; ?>!</h1>
  <p>Has iniciado sesión correctamente.</p>
  <a href="logout.php">Cerrar sesión</a>

  <br><br>

  <div class="counter">
    Contador: <span id="count">0</span>
  </div>

  <button onclick="increment()">Sumar</button>
  <button onclick="decrement()">Restar</button>

  <script>
    // Función para incrementar el contador
    function increment() {
      let countElement = document.getElementById('count');
      let currentCount = parseInt(countElement.innerText);
      countElement.innerText = currentCount + 1;
    }

    // Función para decrementar el contador
    function decrement() {
      let countElement = document.getElementById('count');
      let currentCount = parseInt(countElement.innerText);
      if (currentCount > 0) { // Evitar que el contador sea negativo
        countElement.innerText = currentCount - 1;
      }
    }
  </script>

</body>

</html>
