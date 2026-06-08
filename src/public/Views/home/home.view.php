<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réseau IIA</title>

    <link href="/css/home" rel="stylesheet">
    <script src="/js/home" type="module"></script>
</head>

<body>

<div class="page">

    <!-- CENTRE -->
    <div class="container">

        <header>Réseau IIA</header>

        <!-- TOP BAR -->
        <div class="topbar">

            <a class="profile-btn" href="/profile?username=<?= $user['username'] ?>">
                <img src="<?= $user['profile_picture'] ?? "/image/default.png" ?>">
                <div>@toi</div>
            </a>

            <div class="menu">

                <div hidden>
                    <input placeholder="Recherher" type="text" id="search">
                    <button>Valider</button>
                </div>

                <a href="admin" class="admin" hidden>
                    <button>Admin</button>
                </a>
                <a href="logout">
                    <button>Déconnexion</button>
                </a>

            </div>

        </div>

        <!-- FORMULAIRE -->
        <div class="tweet-form">

            <div class="tweet-box">
                <textarea placeholder="Quoi de neuf ?" maxlength="280"></textarea>

                <input hidden type="text" value="<?= $_SESSION['token'] ?>" id="token">
                <div class="tweet-actions">
                    <span style="color:gray;font-size:12px;"><span id="charCounter">0</span>/280</span>
                    <button>Tweet</button>
                </div>
            </div>

        </div>


    </div>
</div>

</body>
</html>