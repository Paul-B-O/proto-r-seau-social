<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche</title>
    <link rel="stylesheet" href="css/search">
    <script src="js/search" type="module" defer></script>
</head>
<body>

<div class="page">

    <div class="container">

        <!-- Search -->

        <div class="header">

            <input
                class="search-box"
                type="text"
                placeholder="Rechercher..."
            >

        </div>

        <!-- Tabs -->

        <div class="tabs">

            <div class="tab active">
                Publications
            </div>

            <div class="tab">
                Utilisateurs
            </div>

        </div>

        <!-- Results -->

        <div class="results posts">
        </div>

        <div class="results users" hidden>

            <div class="user-card">

                <div class="user-left">

                    <img
                            class="avatar"
                            src="https://i.pravatar.cc/150?img=8"
                            alt=""
                    >

                    <div class="user-info">

                <span class="nickname">
                    Paul Durand
                </span>

                        <span class="username">
                    @paul
                </span>

                    </div>

                </div>

                <button class="follow-btn">
                    Suivre
                </button>

            </div>

            <div class="user-card">

                <div class="user-left">

                    <img
                            class="avatar"
                            src="https://i.pravatar.cc/150?img=15"
                            alt=""
                    >

                    <div class="user-info">

                <span class="nickname">
                    Sophie Bernard
                </span>

                        <span class="username">
                    @sophie
                </span>

                    </div>

                </div>

                <button class="follow-btn">
                    Suivre
                </button>

            </div>

        </div>

</div>

<!--
SECTION UTILISATEURS (à afficher à la place de .results)
quand l'onglet Utilisateurs est actif
-->
</div>

</body>
</html>