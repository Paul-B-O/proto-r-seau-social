<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil de <?= $user["username"]?></title>

    <link href="/css/profile" rel="stylesheet">
    <script src="js/profile" type="module" defer></script>
</head>
<body>

<div class="page">

    <!-- CENTRE -->
    <div class="container">

        <header>

            <a href="/home"
               style="
           text-decoration:none;
           color:black;
           margin-right:10px;
           font-size:22px;
       ">
                ←
            </a>
            Profil
        </header>

        <!-- COVER -->
        <div class="cover"></div>

        <!-- PROFILE -->
        <div class="profile">

            <div class="profile-top">

                <img
                    class="profile-picture"
                    src="<?= htmlspecialchars($user['profile_picture']) ?: "/image/default.png" ?>"
                >

                <?php if ($_SESSION['user_id'] == $user['id']):  ?>
                <a href="/editProfile?username=<?= htmlspecialchars($user['username']) ?>">
                <button class="edit-btn">
                    Modifier le profil
                </button>
                </a>
                <?php endif; ?>

            </div>

            <div class="nickname">
                <?= htmlspecialchars($user['nickname']) ?>
            </div>

            <div class="username">
                @<?= htmlspecialchars($user['username']) ?>
            </div>

            <div class="bio">
                <?= htmlspecialchars($user['bio']) ?>
            </div>

            <div class="profile-stats" style="display: none">
                <div><strong>124</strong> abonnements</div>
                <div><strong>3,2k</strong> abonnés</div>
            </div>

        </div>
</div>

</body>
</html>