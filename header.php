<!-- header.php -->
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - INCARGO365</title>

  <!-- Favicon -->
  <link rel="icon" href="/imagenes/favico-incargo365.png" type="image/png" />
  <!-- Opción alternativa para navegadores más antiguos -->
  <link rel="shortcut icon" href="/imagenes/favico-incargo365.png" type="image/png" />

  <!-- Fuente y estilos opcionales -->
  <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/main.css" />
</head>
<style>
  header {
    background-color: #ffcc80;
    color: #4d2600;
    padding: 10px 0;
    border-bottom: 5px solid #ff9900;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
  }
  html, body { margin: 0; padding: 0; }

  .container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
  }

  .main-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
  }

  .main-nav li {
    position: relative;
  }

  .main-nav a {
    text-decoration: none;
    color: #4d2600;
    font-weight: bold;
    padding: 10px;
    display: block;
    transition: color 0.3s ease;
  }

  .main-nav a:hover,
  .main-nav a.active {
    color: #b35900;
  }

  /* Submenús simples (NO el mega menú) */
  .main-nav li > ul:not(.principal) {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: #ffcc80;
    border: 1px solid #ff9900;
    border-radius: 5px;
    padding: 10px 0;
    z-index: 1000;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  }

  .main-nav li:hover > ul:not(.principal) {
    display: block;
  }

  .main-nav li ul li {
    padding: 5px 20px;
  }

  .main-nav li ul li a {
    color: #4d2600;
    font-weight: normal;
    transition: background-color 0.3s ease, color 0.3s ease;
  }

  .main-nav li ul li a:hover {
    background-color: #ff9900;
    color: white;
  }

  /* Mega Menú de Servicios */
  .servicios-completo .dropdown-menu.principal {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    flex-direction: row;
    gap: 40px;
    background-color: #ffcc80;
    padding: 25px 30px;
    border-radius: 8px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
    min-width: 700px;
    z-index: 1000;
  }

  .servicios-completo:hover > .dropdown-menu.principal {
    display: flex;
  }

  .servicios-completo .has-submenu {
    min-width: 200px;
  }

  .servicios-completo .has-submenu > a {
    display: block;
    font-weight: bold;
    font-size: 18px;
    color: #4d2600;
    margin-bottom: 10px;
  }

  .servicios-completo .submenu {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .servicios-completo .submenu li {
    margin-bottom: 8px;
  }

  .servicios-completo .submenu li a {
    color: #4d2600;
    font-size: 16px;
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
  }

  .servicios-completo .submenu li a:hover {
    background-color: #ff9900;
    color: white;
  }

  .client-area-container {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-left: auto;
  }

  .client-area-btn {
    background-color: #ff9900;
    color: white;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s ease;
  }

  .client-area-btn:hover {
    background-color: #e68a00;
  }

  .language-dropdown {
    position: relative;
  }

  .language-toggle {
    display: flex;
    align-items: center;
    gap: 5px;
    text-decoration: none;
    color: #4d2600;
    font-weight: bold;
    padding: 10px;
  }

  .language-flag {
    width: 20px;
    height: 15px;
    border-radius: 2px;
  }

  .language-menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background-color: #ffcc80;
    border: 1px solid #ff9900;
    border-radius: 5px;
    padding: 10px 0;
    z-index: 1000;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
  }

  .language-menu li {
    padding: 5px 20px;
  }

  .language-menu li a {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #4d2600;
    text-decoration: none;
    transition: background-color 0.3s ease, color 0.3s ease;
  }

  .language-menu li a:hover {
    background-color: #ff9900;
    color: white;
  }

  .language-dropdown:hover .language-menu {
    display: block;
  }
</style>

<header class="main-header">
  <div class="container">
    <nav class="main-nav">
      <ul>
        <li><a href="index.html">Inicio</a></li>
        <li class="dropdown about-dropdown">
          <a href="#">Empresa</a>
          <ul class="dropdown-menu about-menu">
            <li><a href="historia_empresa.html">Historia</a></li>
            <li><a href="valores.html">Valores</a></li>
            <li><a href="equipo.html">Equipo</a></li>
          </ul>
        </li>
        <li class="dropdown servicios-completo">
          <a href="#">Servicios</a>
          <ul class="dropdown-menu principal">
            <li class="has-submenu">
              <a href="#">Ámbito</a>
              <ul class="submenu">
                <li><a href="transporte-nacional.html">Transporte Nacional</a></li>
                <li><a href="transporte-internacional.html">Transporte Internacional</a></li>
              </ul>
            </li>
            <li class="has-submenu">
              <a href="#">Servicios</a>
              <ul class="submenu">
                <li><a href="paqueteria.html">Paquetería</a></li>
                <li><a href="paleteria.html">Paletería</a></li>
                <li><a href="#">Grupaje</a></li>
                <li><a href="#">Carga Completa</a></li>
                <li><a href="#">Logística y Supply Chain</a></li>
                <li><a href="#">Transporte local</a></li>
              </ul>
            </li>
            <li class="has-submenu">
              <a href="#">Prestaciones</a>
              <ul class="submenu">
                <li><a href="temperatura-controlada.html">Temperatura Controlada</a></li>
                <li><a href="#">Envío Express</a></li>
                <li><a href="#">Mercancías Peligrosas</a></li>
                <li><a href="#">Transportes Especiales</a></li>
                <li><a href="#">Transporte Intermodal</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <li><a href="soluciones.html">Soluciones</a></li>
        <li><a href="resources.html">Recursos</a></li>
        <li><a href="contact.html">Contacto</a></li>
        <li class="client-area-container">
          <a href="/area-clientes/login.php" class="client-area-btn">Área Privada</a>
        </li>
      </ul>
    </nav>
  </div>
</header>
