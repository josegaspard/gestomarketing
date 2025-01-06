<?php
session_start();
// Initialize session variables if they don't exist
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if (!isset($_SESSION['last_attempt_time'])) {
    $_SESSION['last_attempt_time'] = 0;
}

require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user has exceeded the maximum number of attempts
    if (time() - $_SESSION['last_attempt_time'] < 60 && $_SESSION['login_attempts'] >= 4) {
        $login_err = "Has excedido el número máximo de intentos. Debes esperar 60 segundos antes de intentar de nuevo.";
    } else {
        // Check credentials and redirect based on user type
        if ($username === "user" && $password === "pass") {
            // CEO credentials
            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_attempt_time'] = 0;
            header("location: https://josegaspard.dev/gesto/saas.html");
            exit();
        } elseif ($username === "ventas1" && $password === "pass") {
            // Sales credentials
            $_SESSION['login_attempts'] = 0;
            $_SESSION['last_attempt_time'] = 0;
            header("location: https://josegaspard.dev/gesto/saas-ventas.html");
            exit();
        } else {
            // Invalid credentials
            $_SESSION['login_attempts']++;
            
            if ($_SESSION['login_attempts'] == 1) {
                $login_err = "Credenciales incorrectas. Te quedan 3 intentos.";
            } elseif ($_SESSION['login_attempts'] == 2) {
                $login_err = "Credenciales incorrectas. Te quedan 2 intentos.";
            } elseif ($_SESSION['login_attempts'] == 3) {
                $login_err = "Credenciales incorrectas. Te queda 1 intento.";
            } elseif ($_SESSION['login_attempts'] >= 4) {
                $_SESSION['last_attempt_time'] = time();
                $login_err = "Has excedido el número máximo de intentos. Debes esperar 60 segundos antes de intentar de nuevo.";
            }
        }
    }
}

// Verificar si ha pasado el tiempo de espera
if (time() - $_SESSION['last_attempt_time'] < 60 && $_SESSION['login_attempts'] >= 4) {
    $login_err = "Debes esperar 60 segundos antes de intentar de nuevo.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TurboPost - Iniciar Sesión</title>
    <style>
        :root {
            --primary-color: #FF1F8E;
            --secondary-color: #4E1183;
            --background-color: #0f0f1a;
            --text-color: #ffffff;
            --card-bg: rgba(255, 255, 255, 0.1);
            --card-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            --input-bg: rgba(255, 255, 255, 0.07);
            --input-border: 1px solid rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            font-family: 'Arial', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            height: 100%;
            overflow: hidden;
        }

        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: var(--background-color);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: 50% 50%;
        }

        .login-container {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
            z-index: 10;
        }

        .login-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: var(--card-shadow);
        }

        .logo {
            width: 180px;
            margin-bottom: 30px;
        }

        h1 {
            color: var(--text-color);
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 300;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group input {
            width: 100%;
            padding: 12px 20px;
            background-color: var(--input-bg);
            border: var(--input-border);
            border-radius: 25px;
            font-size: 16px;
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--primary-color);
        }

        .login-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 0;
            border-radius: 25px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .login-btn:hover {
            background-color: #e6006e;
            transform: translateY(-2px);
        }

        .forgot-password {
            display: inline-block;
            margin-top: 15px;
            color: var(--text-color);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--primary-color);
        }

        .error-message {
            color: #ffffff;
            margin-bottom: 15px;
            font-size: 16px;
            background-color: rgba(255, 31, 142, 0.7);
            border: 2px solid #FF1F8E;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>
    <div class="login-container">
        <div class="login-card">
            <img src="https://i.imgur.com/S7dXitD.png" alt="TurboPost Logo" class="logo">
            <h1>Bienvenido a TurboPost</h1>
            <?php if (isset($login_err)): ?>
                <div class="error-message"><?php echo htmlspecialchars($login_err); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="input-group">
                    <input type="text" name="username" placeholder="Usuario" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Contraseña" required>
                </div>
                <button type="submit" class="login-btn">Iniciar Sesión</button>
            </form>
            <a href="restore-password.html" class="forgot-password">¿Olvidaste tu contraseña?</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": {
                    "value": 80,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": "#ffffff"
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#000000"
                    },
                    "polygon": {
                        "nb_sides": 5
                    }
                },
                "opacity": {
                    "value": 0.5,
                    "random": false,
                    "anim": {
                        "enable": false,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 3,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 40,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#ffffff",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 6,
                    "direction": "none",
                    "random": false,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false,
                    "attract": {
                        "enable": false,
                        "rotateX": 600,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "repulse"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 400,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "bubble": {
                        "distance": 400,
                        "size": 40,
                        "duration": 2,
                        "opacity": 8,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 200,
                        "duration": 0.4
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            },
            "retina_detect": true
        });
    </script>
</body>
</html>

