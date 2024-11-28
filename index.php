<?php
session_start();

// Lista de palabras para el juego
$palabras = ['elefante', 'jirafa', 'hipopotamo', 'rinoceronte', 'cocodrilo', 'camello', 'chimpance', 'leon', 'tigre', 'panda'];

// Inicializar el juego
if (!isset($_SESSION['palabra'])) {
    $_SESSION['palabra'] = $palabras[array_rand($palabras)];
    $_SESSION['vidas'] = 6; // Número máximo de vidas
    $_SESSION['letras_acertadas'] = str_repeat('?', strlen($_SESSION['palabra']));
    $_SESSION['letras_usadas'] = [];
}

// Inicializar el juego
if ($_SESSION['letras_acertadas'] == $_SESSION['palabra']) {
    header('Location: ganar.php');
    session_destroy();
    exit();
} elseif ($_SESSION['vidas'] <= 0) {
    header('Location: perder.php');
    session_destroy();
    exit();
}


// Procesar la letra enviada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['letra'])) {
    $letra = strtolower($_POST['letra']);

    // Verificar si la letra ya se ha usado
    if (in_array($letra, $_SESSION['letras_usadas'])) {
        echo "<p class='mensaje'>Ya has usado la letra '$letra'. Intenta con otra.</p>";
    } else {
        // Añadir la letra a las usadas
        $_SESSION['letras_usadas'][] = $letra;

        // Verificar si la letra está en la palabra secreta
        if (strpos($_SESSION['palabra'], $letra) !== false) {
            for ($i = 0; $i < strlen($_SESSION['palabra']); $i++) {
                if ($_SESSION['palabra'][$i] == $letra) {
                    $_SESSION['letras_acertadas'][$i] = $letra;
                }
            }
        } else {
            $_SESSION['vidas']--;
        }
    }
}

// Comprobar si se ha ganado o perdido
if ($_SESSION['letras_acertadas'] == $_SESSION['palabra']) {
    echo "¡Enhorabuena! Has ganado :) La palabra era: " . $_SESSION['palabra'] . "<br>";
    session_destroy();
    echo '<a href="">Jugar de nuevo</a>';
    exit();
} elseif ($_SESSION['vidas'] <= 0) {
    echo "Lo siento, has perdido :( La palabra era: " . $_SESSION['palabra'] . "<br>";
    session_destroy();
    echo '<a href="">Jugar de nuevo</a>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ahorcado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
            text-align: center;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #007BFF;
        }
        form {
            margin-top: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"] {
            padding: 5px;
            font-size: 16px;
        }
        button {
            padding: 5px 10px;
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .mensaje {
            color: red;
            font-weight: bold;
        }
        footer {
            margin-top: 30px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <h1>Juego del Ahorcado</h1>
    <p>Palabra secreta: <?php echo $_SESSION['letras_acertadas']; ?></p>
    <p>Vidas restantes: <?php echo $_SESSION['vidas']; ?></p>
    <form method="post">
        <label for="letra">Introduce una letra:</label>
        <input type="text" name="letra" id="letra" maxlength="1" required>
        <button type="submit">Adivinar</button>
    </form>
    <p>Letras usadas: <?php echo implode(', ', $_SESSION['letras_usadas']); ?></p>
    <footer>
        <p>&copy; 2024 Mi Aplicación. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
