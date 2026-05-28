<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion / Inscription</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f8fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* CONTAINER GLOBAL */
        .wrapper {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        /* CARD FORM */
        .card {
            width: 320px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 20px;
        }

        h2 {
            margin-top: 0;
            text-align: center;
        }

        /* INPUTS */
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #1da1f2;
        }

        /* BUTTON */
        button {
            width: 100%;
            padding: 10px;
            background: #1da1f2;
            border: none;
            color: white;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #1991da;
        }

        /* SWITCH TEXT */
        .switch {
            text-align: center;
            margin-top: 10px;
            font-size: 12px;
            color: gray;
        }

        .error {
            color: red;
            font-size: 13px;
            margin-bottom: 10px;
        }
    </style>

</head>

<body>

<div class="wrapper">


    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $e): ?>
                <div><?= htmlspecialchars($e) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- REGISTER -->
    <div class="card">
        <h2>Créer un compte</h2>

        <form method="POST" action="register">

            <input type="text" name="nickname" placeholder="Pseudo" required>

            <input type="text" name="username" placeholder="Identifiant" required>

            <input type="password" name="password" placeholder="Mot de passe" required>

            <button type="submit">S'inscrire</button>

        </form>

        <div class="switch">
            <a href="login">
            Déjà un compte ?
            </a>
        </div>
    </div>

</div>

</body>
</html>