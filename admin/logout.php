<?php

// Destruir sesión activa del usuario

require 'config/config.php';

session_destroy();

header('Location: index.php');