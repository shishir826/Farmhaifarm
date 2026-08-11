<?php

require_once "php/database.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirmPassword"] ?? "";

    // Validate name
    if ($name === "") {

        $message = "Please enter your full name.";
        $messageType = "error";

    // Validate email
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "error";

    // Validate password
    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";
        $messageType = "error";

    // Confirm password
    } elseif ($password !== $confirmPassword) {

        $message = "Passwords do not match.";
        $messageType = "error";

    } else {

        // Check if email already exists
        $stmt = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $stmt->execute([$email]);

        if ($stmt->fetch()) {

            $message = "An account with this email already exists.";
            $messageType = "error";

        } else {

            // Hash password
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Insert user
            $stmt = $conn->prepare(
                "INSERT INTO users (name, email, password)
                 VALUES (?, ?, ?)"
            );

            $stmt->execute([
                $name,
                $email,
                $hashedPassword
            ]);

            $message = "Account created successfully!";
            $messageType = "success";

            // Clear form values after successful registration
            $name = "";
            $email = "";
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

    <title>Register | 🌱 FarmFresh</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"
    />

    <style>

        :root {
            --bg: #f7f9f2;
            --bg2: #3a7d44;
            --bg3: #e5e5e5;

            --txt: #22301f;
            --txt2: #6b7280;
            --txtInv: #ffffff;

            --pri: #3a7d44;
            --priHov: #2c5f35;

            --sec: #8b5e3c;
            --secHov: #6b4a2d;

            --acc: #f2a649;
            --accHov: #e0932f;

            --err: #ef4444;
            --succ: #22c55e;

            --bd: #d8ded2;

            --shadow: rgba(0, 0, 0, 0.15);
            --overlay: rgba(0, 0, 0, 0.5);

            --rMd: 8px;

            --sp1: 4px;
            --sp2: 8px;
            --sp3: 12px;
            --sp4: 16px;
            --sp5: 24px;
            --sp6: 32px;

            --fsSm: 14px;
            --fsMd: 16px;
            --fsLg: 20px;
            --fsXl: 28px;
            --fsHero: 48px;

            --fwReg: 400;
            --fwMed: 500;
            --fwBold: 700;

            --trans: all 0.3s ease;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            color: var(--txt);
        }

        .auth-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: var(--bg);
        }

        .auth-left {
            background:
                linear-gradient(var(--overlay), var(--overlay)),
                url("https://images.stockcake.com/public/3/8/c/38cf84df-bb2f-46af-8118-1325cc09ac62_large/green-energy-harvest-stockcake.jpg");
            background-size: cover;
            background-position: center;
            color: var(--txtInv);
        }

        .auth-overlay {
            height: 100%;
            padding: var(--sp6) 10%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .auth-brand {
            font-size: var(--fsLg);
            font-weight: var(--fwBold);
        }

        .auth-overlay > div:last-child {
            padding-bottom: var(--sp6);
        }

        .auth-overlay span {
            color: var(--acc);
            font-size: var(--fsSm);
            font-weight: var(--fwBold);
            letter-spacing: 2px;
        }

        .auth-overlay h1 {
            font-size: var(--fsHero);
            line-height: 1.1;
            margin: var(--sp4) 0;
            font-weight: var(--fwBold);
        }

        .auth-overlay p {
            max-width: 400px;
            color: var(--bd);
            line-height: 1.7;
            font-size: var(--fsMd);
        }

        .auth-right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--sp6);
            background: var(--bg);
        }

        .auth-form {
            width: 430px;
            max-width: 100%;
        }

        .back-home {
            display: inline-block;
            color: var(--txt2);
            font-size: var(--fsSm);
            margin-bottom: var(--sp5);
            text-decoration: none;
            transition: var(--trans);
        }

        .back-home:hover {
            color: var(--pri);
        }

        .auth-form h2 {
            font-size: var(--fsXl);
            margin-bottom: var(--sp2);
            font-weight: var(--fwBold);
            color: var(--txt);
        }

        .auth-subtitle {
            color: var(--txt2);
            font-size: var(--fsSm);
            margin-bottom: var(--sp5);
        }

        .auth-form form > label {
            display: block;
            margin-bottom: var(--sp2);
            margin-top: var(--sp4);
            font-size: var(--fsSm);
            font-weight: var(--fwMed);
            color: var(--txt);
        }

        .auth-form input {
            width: 100%;
            padding: var(--sp3) var(--sp4);
            border: 1px solid var(--bd);
            border-radius: var(--rMd);
            outline: none;
            font-family: inherit;
            font-size: var(--fsMd);
            background: var(--txtInv);
            color: var(--txt);
            transition: var(--trans);
        }

        .auth-form input::placeholder {
            color: var(--txt2);
        }

        .auth-form input:focus {
            border-color: var(--acc);
            box-shadow: 0 0 0 3px rgba(242, 166, 73, 0.15);
        }

        .main-auth-btn {
            width: 100%;
            margin-top: var(--sp5);
            padding: var(--sp4);
            border: none;
            border-radius: var(--rMd);
            background: var(--acc);
            color: var(--txtInv);
            font-family: inherit;
            font-size: var(--fsSm);
            font-weight: var(--fwBold);
            cursor: pointer;
            transition: var(--trans);
        }

        .main-auth-btn:hover {
            background: var(--accHov);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px var(--shadow);
        }

        .register-text {
            text-align: center;
            font-size: var(--fsSm);
            color: var(--txt2);
            margin-top: var(--sp5);
        }

        .register-text a {
            color: var(--acc);
            font-weight: var(--fwBold);
            text-decoration: none;
        }

        .message {
            padding: 12px 16px;
            border-radius: var(--rMd);
            margin-bottom: 20px;
            font-size: 14px;
        }

        .message.error {
            background: #fee2e2;
            color: #991b1b;
        }

        .message.success {
            background: #dcfce7;
            color: #166534;
        }

        @media (max-width: 800px) {

            .auth-page {
                grid-template-columns: 1fr;
            }

            .auth-left {
                display: none;
            }

            .auth-right {
                min-height: 100vh;
            }
        }

    </style>

</head>

<body class="auth-page">

    <div class="auth-left">

        <div class="auth-overlay">

            <div class="auth-brand">
                🍴 🌱 FarmFresh
            </div>

            <div>

                <span>JOIN THE COMMUNITY</span>

                <h1>
                    FROM FARM<br />
                    TO YOUR<br />
                    DOORSTEPS.
                </h1>

                <p>
                    Join our community for organic products.
                </p>

            </div>

        </div>

    </div>


    <div class="auth-right">

        <div class="auth-form">

            <a
                href="index.html"
                class="back-home"
            >
                ← Back to home
            </a>

            <h2>Create your account 🍴</h2>

            <p class="auth-subtitle">
                Start Your Healthy Journey Today.
            </p>


            <?php if ($message !== ""): ?>

                <div class="message <?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php endif; ?>


            <form
                method="POST"
                action=""
            >

                <label for="name">
                    Full Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Your full name"
                    value="<?php echo htmlspecialchars($name ?? ''); ?>"
                    required
                />


                <label for="email">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="you@example.com"
                    value="<?php echo htmlspecialchars($email ?? ''); ?>"
                    required
                />


                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Create a password"
                    required
                />


                <label for="confirmPassword">
                    Confirm Password
                </label>

                <input
                    type="password"
                    id="confirmPassword"
                    name="confirmPassword"
                    placeholder="Repeat your password"
                    required
                />


                <button
                    type="submit"
                    class="main-auth-btn"
                >
                    Create Account →
                </button>

            </form>


            <p class="register-text">

                Already have an account?

                <a href="login.html">
                    Login
                </a>

            </p>

        </div>

    </div>

</body>

</html>