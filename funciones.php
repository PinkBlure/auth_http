<?php

function crearConexion()
{
  try {

    $host = 'localhost';
    $db = 'usuarios';
    $user = 'root';
    $pass = '';

    $dns = "mysql:host=$host;dbname=$db";
    $conn = new PDO($dns, $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
  } catch (PDOException $ex) {
    error_log("Error en la conexión a la base de datos: " .
      $ex->getMessage());
    return null;
  }
}

function buscarUsuario($nombre, $pass)
{
  $conexion = crearConexion();
  $query = "SELECT pass FROM usuarios WHERE usuario = :usuario";
  $stmt = $conexion->prepare($query);
  $stmt->bindParam(':usuario', $nombre);
  $stmt->execute();

  $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

  return $resultado && password_verify($pass, $resultado['pass']);
}

function anadirUsuario($nombre, $pass, $rol = "user")
{
  $conexion = crearConexion();
  $query = "INSERT INTO usuarios (usuario, pass, rol) VALUES (:usuario, :pass, :rol)";
  $stmt = $conexion->prepare($query);

  $passHash = password_hash($pass, PASSWORD_DEFAULT);
  $stmt->bindParam(':usuario', $nombre);
  $stmt->bindParam(':pass', $passHash);
  $stmt->bindParam(':rol', $rol);

  try {
    $stmt->execute();
    return "Usuario creado exitosamente.";
  } catch (PDOException $ex) {
    return "Error al crear usuario: " . $ex->getMessage();
  }
}


function eliminarUsuario($nombre)
{
  $conexion = crearConexion();
  $query = "DELETE FROM usuarios WHERE usuario = :usuario";
  $stmt = $conexion->prepare($query);
  $stmt->bindParam(':usuario', $nombre);

  try {
    $stmt->execute();
    return "Usuario eliminado exitosamente.";
  } catch (PDOException $ex) {
    return "Error al eliminar usuario: " . $ex->getMessage();
  }
}
