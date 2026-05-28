<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f8fa;
        }

        .page {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 0 20px;
        }

        /* TIMELINE */
        .container {
            width: 520px;
            background: white;
            min-height: 100vh;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            padding: 15px;
        }

        /* HEADER */
        header {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            font-size: 20px;
        }

        /* COVER */
        .cover {
            height: 140px;
            background: #1da1f2;
        }

        /* PROFILE */
        .profile {
            padding: 15px;
            border-bottom: 10px solid #f0f0f0;
        }

        .profile-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .profile-picture {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 4px solid white;
            margin-top: -45px;
            background: white;
        }

        .edit-btn {
            border: 1px solid #ddd;
            background: white;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
        }

        .nickname {
            font-size: 22px;
            font-weight: bold;
            margin-top: 10px;
        }

        .username {
            color: gray;
            margin-top: 2px;
        }

        .bio {
            margin-top: 12px;
            line-height: 1.4;
        }

        .profile-stats {
            display: flex;
            gap: 15px;
            margin-top: 12px;
            color: gray;
            font-size: 14px;
        }

        .profile-stats strong {
            color: black;
        }

        /* POSTS */
        .tweet {
            display: flex;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .tweet-content {
            flex: 1;
        }

        .user {
            font-weight: bold;
        }

        .user span {
            color: gray;
            font-weight: normal;
            margin-left: 5px;
        }

        .text {
            margin-top: 5px;
        }

        .date {
            margin-top: 8px;
            color: gray;
            font-size: 12px;
        }

        .tweet-footer {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }

        .like-btn {
            border: none;
            background: none;
            color: gray;
            cursor: pointer;
        }

        .like-btn:hover {
            color: red;
        }

        /* SIDEBAR */
        .card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 15px;
        }

        .card h3 {
            margin-top: 0;
        }

        .suggestion {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .suggestion img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .follow-btn {
            margin-left: auto;
            border: none;
            background: black;
            color: white;
            border-radius: 20px;
            padding: 5px 12px;
            cursor: pointer;
        }
    </style>

</head>
<body>

<div class="page">

    <!-- CENTRE -->
    <div class="container">

        <header>Profil</header>

        <!-- COVER -->
        <div class="cover"></div>

        <!-- PROFILE -->
        <div class="profile">

            <div class="profile-top">

                <img
                    class="profile-picture"
                    src="<?= $user['profile_picture'] ?? "/image/default.png" ?>"
                >

                <?php if ($_SESSION['id'] === $user['id']) : ?>
                <button class="edit-btn">
                    Modifier le profil
                </button>
                <?php endif; ?>

            </div>

            <div class="nickname">
                <?= $user['nickname'] ?>
            </div>

            <div class="username">
                @<?= $user['username'] ?>
            </div>

            <div class="bio">
            </div>

            <div class="profile-stats" style="display: none">
                <div><strong>124</strong> abonnements</div>
                <div><strong>3,2k</strong> abonnés</div>
            </div>

        </div>

        <!-- POSTS -->
    <script>

        const container = document.querySelector(".container");

        function $make(tagName, parent, props) {
            const elt = document.createElement(tagName);
            if (parent != null) parent.appendChild(elt);
            for (const key in props) {
                if (key === "dataset") {
                    for (const dataKey in props[key]) elt.dataset[dataKey] = props.dataset[dataKey];
                } else elt[key] = props[key];
            }
            return elt;
        }

        function makePost(post) {
            const postDiv = $make("div", null, { className: "tweet" });
            const link = $make("a", postDiv, {href: `/profile?username=${post.username}`});
            $make("img", link, {src: post.profile_picture ?? "image/default.png", className: "avatar"});
            const content = $make("div", postDiv, { className: "tweet-content" });
            const user = $make("div", content, { className: "user", textContent: post.nickname });
            $make("span", user, { className: "username", textContent: `@${post.username}` });
            $make("div", content, {className: "text", textContent: post.content});
            $make("div", content, {className: "date", textContent: post.created_at});
            const footer = $make("div", content, {className: "tweet-footer"});
            const likeBtn = $make("button", footer, {className: "like-btn", textContent: `❤️ ${post.like_count}`});

            likeBtn.addEventListener("click", async () => {
                const result = await likePost(post.id);
                if (result.success) {
                    likeBtn.textContent = `❤️ ${result.like_count}`;
                }
            });

            return postDiv;
        }

        async function getLastPost() {
            const username = new URLSearchParams(location.search).get("username");
            const res = await fetch("api/getPost", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({username: username})
            });

            const result = await res.json();
            console.log(result);
            if (result.success) {
                for (const post of result.posts) {
                    container.appendChild(makePost(post))
                }
            }
        }

        getLastPost();
    </script>

</div>

</body>
</html>