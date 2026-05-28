<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réseau IIA</title>

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

        /* CENTRE */
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
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        /* ===== PROFIL TOP BAR ===== */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: black;
        }

        .profile-btn img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }

        /* MENU LOGIN */
        .menu {
            position: relative;
        }

        .menu button {
            background: none;
            border: 1px solid #ddd;
            padding: 5px 10px;
            border-radius: 20px;
            cursor: pointer;
        }

        .dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 35px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 150px;
            z-index: 10;
        }

        .dropdown a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: black;
            font-size: 14px;
        }

        .dropdown a:hover {
            background: #f0f0f0;
        }

        .menu:hover .dropdown {
            display: block;
        }

        /* ===== TWEET FORM UNIFIÉ ===== */
        .tweet-form {
            padding: 15px;
            border-bottom: 10px solid #f0f0f0;
        }

        .tweet-box {
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 10px;
            background: #fff;
        }

        .tweet-box textarea {
            width: 100%;
            border: none;
            resize: none;
            height: 60px;
            outline: none;
            font-size: 14px;
        }

        .tweet-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
        }

        .tweet-actions button {
            background: #1da1f2;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 20px;
            cursor: pointer;
        }

        /* TWEET */
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

        .username {
            color: gray;
            font-weight: normal;
            margin-left: 5px;
        }

        .text {
            margin-top: 5px;
        }

        .date {
            margin-top: 6px;
            font-size: 12px;
            color: gray;
        }

        /* ACTIONS TWEET */
        .tweet-footer {
            display: flex;
            gap: 15px;
            margin-top: 8px;
        }

        .like-btn {
            border: none;
            background: none;
            cursor: pointer;
            color: gray;
        }

        .like-btn:hover {
            color: red;
        }

        /* SUGGESTIONS */
        .suggestions {
            background: white;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .suggestion {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .suggestion img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .follow {
            margin-left: auto;
            background: black;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
        }
    </style>
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

                <a href="logout"><button>Déconnexion</button></a>
            </div>

        </div>

        <!-- FORMULAIRE -->
        <div class="tweet-form">

            <div class="tweet-box">
                <textarea placeholder="Quoi de neuf ?"></textarea>

                <div class="tweet-actions">
                    <span style="color:gray;font-size:12px;">0/280</span>
                    <button>Tweet</button>
                </div>
            </div>

        </div>
        

    </div>


    <script>
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

        const tweetBox = document.querySelector(".tweet-box textarea");
        const sendBtn = document.querySelector(".tweet-actions button");
        const container = document.querySelector(".container");

        sendBtn.addEventListener("click", async () => {
            const content = tweetBox.value;
            const res = await fetch("api/newPost",{
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({content})
            });
            const result = await res.json();
            console.log(result);

            if (result.success) {
                tweetBox.value = "";
                const post = await getNewPost();
                container.insertBefore(makePost(post), container.children[3]);
            }
        });

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

        async function getNewPost(postId) {
            const res = await fetch("api/getPost", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({postId})
            });

            const result = await res.json();
            if (result.success) {
                return result.posts[0];
            }
        }

        async function getLastPost() {
            const res = await fetch("api/getPost", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
            });

            const result = await res.json();
            if (result.success) {
                for (const post of result.posts) {
                    container.appendChild(makePost(post))
                }
            }
        }

        async function likePost(postId) {
            const res = await fetch("api/likePost",{
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ postId })
            });

            return await res.json();
        }

        getLastPost();

        // Actualisation brut à changer
        setInterval(() => {
            container.innerHTML = ``;
            getLastPost();
        })

    </script>

</div>

</body>
</html>