<?php

require_once "php/database.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "error";

    // Validate password
    } elseif ($password === "") {

        $message = "Please enter your password.";
        $messageType = "error";

    } else {

        // Find user by email
        $stmt = $conn->prepare(
            "SELECT id, name, email, password FROM users WHERE email = ?"
        );

        $stmt->execute([$email]);

        $user = $stmt->fetch();

        // Check user and password
        if ($user && password_verify($password, $user["password"])) {

            // Login successful
            session_start();

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["user_email"] = $user["email"];

            // Redirect to homepage
            header("Location: index.php");
            exit;

        } else {

            $message = "Invalid email or password.";
            $messageType = "error";
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>

    <meta charset="UTF-8" />

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    />

    <title>Login | 🌱 FarmFresh</title>

    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="login.css" />

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    />

</head>

<body class="auth-page">

    <div class="auth-left">

        <div class="auth-overlay">

            <div class="auth-brand">
                🌱 FarmFresh
            </div>

            <div>

                <span>WELCOME BACK</span>

                <h1>
                    Your Own<br />
                    Farm<br />
                    is waiting.
                </h1>

                <p>
                    Discover Fresh Vegetable, Fruits directly from our Farmer.
                </p>

            </div>

        </div>

    </div>


    <div class="auth-right">

        <div class="auth-form">

            <a href="index.php" class="back-home">
                ← Back to home
            </a>

            <h2>Welcome 👋</h2>

            <p class="auth-subtitle">
                Login to continue your healthy journey.
            </p>


            <?php if ($message !== ""): ?>

                <div class="message <?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php endif; ?>


            <form method="POST" action="">

                <label for="loginEmail">
                    Email
                </label>

                <input
                    type="email"
                    id="loginEmail"
                    name="email"
                    autocomplete="email"
                    placeholder="you@example.com"
                    value="<?php echo htmlspecialchars($email ?? ""); ?>"
                    required
                />


                <label for="loginPassword">
                    Password
                </label>

                <input
                    type="password"
                    id="loginPassword"
                    name="password"
                    autocomplete="current-password"
                    placeholder="Enter your password"
                    required
                />


                <div class="form-options">

                    <label class="remember">

                        <input
                            type="checkbox"
                            name="remember"
                        />

                        Remember me

                    </label>

                    <a href="#">
                        Forgot password?
                    </a>

                </div>


                <button
                    type="submit"
                    class="main-auth-btn"
                >
                    Login →
                </button>

            </form>


            <div class="divider">
                <span>OR</span>
            </div>


            <p class="register-text">

                Don't have an account?

                <a href="register.php">
                    Create one
                </a>

            </p>

        </div>

    </div>
</body>

</html>
