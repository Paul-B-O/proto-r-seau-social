<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le profil</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f8fa;
        }

        .page {
            display: flex;
            justify-content: center;
            padding: 30px;
        }

        /* CONTAINER */
        .container {
            width: 520px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 16px;
            overflow: hidden;
        }

        /* HEADER */
        .header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            font-size: 20px;
            font-weight: bold;
        }

        /* COVER */
        .cover {
            height: 140px;
            background: #1da1f2;
        }

        /* PROFILE PICTURE */
        .profile-picture-container {
            display: flex;
            justify-content: center;
            margin-top: -50px;
        }

        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            background: white;
        }

        /* FORM */
        .form {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: bold;
        }

        input,
        textarea {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
            font-size: 14px;
            font-family: Arial;
        }

        textarea {
            resize: vertical;
            min-height: 90px;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: #1da1f2;
        }

        /* FILE INPUT */
        .file-input {
            border: none;
            padding: 0;
        }

        /* PASSWORD BLOCK */
        .password-box {
            background: #f7f9fa;
            padding: 15px;
            border-radius: 12px;
            border: 1px solid #eee;
        }

        /* BUTTON */
        .save-btn {
            width: 100%;
            padding: 12px;
            border: none;
            background: #1da1f2;
            color: white;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }

        .save-btn:hover {
            background: #1991da;
        }

        /* SMALL TEXT */
        .small {
            font-size: 12px;
            color: gray;
            margin-top: 5px;
        }
    </style>

</head>
<body>

<div class="page">

    <div class="container">

        <!-- HEADER -->
        <div class="header">
            Modifier le profil
        </div>

        <!-- COVER -->
        <div class="cover"></div>

        <!-- PROFILE PICTURE -->
        <div class="profile-picture-container">
            <img
                class="profile-picture"
                src="https://i.pravatar.cc/150?img=12"
            >
        </div>

        <!-- FORM -->
        <form class="form" method="POST" enctype="multipart/form-data">

            <!-- PHOTO -->
            <div class="form-group">
                <label>Photo de profil</label>

                <input
                    class="file-input"
                    type="file"
                    name="profile_picture"
                    accept="image/*"
                >

                <div class="small">
                    JPG, PNG ou WEBP
                </div>
            </div>

            <!-- NICKNAME -->
            <div class="form-group">
                <label>Pseudo</label>

                <input
                    type="text"
                    name="nickname"
                    value="Jean Dupont"
                    maxlength="30"
                >
            </div>

            <!-- USERNAME -->
            <div class="form-group">
                <label>Nom d'utilisateur</label>

                <input
                    type="text"
                    name="username"
                    value="jean"
                    maxlength="30"
                >
            </div>

            <!-- BIO -->
            <div class="form-group">
                <label>Biographie</label>

                <textarea
                    name="bio"
                    maxlength="280"
                >Développeur web PHP • Passionné de backend 🚀</textarea>

                <div class="small">
                    280 caractères maximum
                </div>
            </div>

            <!-- PASSWORD -->
            <div class="password-box">

                <div class="form-group">
                    <label>Ancien mot de passe</label>

                    <input
                        type="password"
                        name="old_password"
                    >
                </div>

                <div class="form-group">
                    <label>Nouveau mot de passe</label>

                    <input
                        type="password"
                        name="new_password"
                    >
                </div>

            </div>

            <!-- BUTTON -->
            <button class="save-btn" type="submit">
                Enregistrer les modifications
            </button>

        </form>

    </div>

</div>

</body>
</html>