import Api from "/common/Api.js";
import {$make, $show} from "/common/dom.js"

class PostManager {

    #api = new Api();
    lastPost = null;
    isAdmin = false;

    constructor(appendPost, prependPost, username) {
        this.appendPost = appendPost;
        this.prependPost = prependPost;
        this.username = username;

        this.getLastPosts();
        setInterval(this.getNewPost.bind(this), 10_000);
    }

    async getOne(postId) {
        return await this.#api.getPost({postId});
    }

    async sendPost(content) {
        const res = await this.#api.newPost(content);
        if (res.success) {
            this.lastPost = res.post;
            const newPost = this.createPost(res.post);
            this.prependPost(newPost);
        }

        return res.success;
    }

    async likePost(postId, postDiv) {
        return this.#api.likePost(postId);
    }

    async deletePost(postId) {
        return this.#api.deletePost(postId);
    }

    async getLastPosts() {
        const params = this.username ? {username: this.username} : {};
        const {posts, success, isAdmin} = await this.#api.getPost(params);

        if (success) {
            this.lastPost = posts[0];
            this.isAdmin = isAdmin;
            if (this.isAdmin && !this.username) {
                $show(".admin", isAdmin);
            }
            posts.forEach((p) => this.appendPost(this.createPost(p)));
        }
    }

    async getNewPost() {
        const {success, posts} = await this.#api.getPost({after: this.lastPost.created_at});
        if (posts.length <= 0) return;


        if (success) {
            posts.forEach((p) => this.prependPost(this.createPost(p)));
            this.lastPost = posts[0];
        }
    }

    createPost(post) {
        const postDiv = $make("div", null, {className: "tweet"});
        const link = $make("a", postDiv, {href: `/profile?username=${post.username}`});
        $make("img", link, {src: post.profile_picture ?? "image/default.png", className: "avatar"});
        const content = $make("div", postDiv, {className: "tweet-content"});
        const user = $make("div", content, {className: "user", textContent: post.nickname});
        $make("span", user, {className: "username", textContent: `@${post.username}`});
        $make("div", content, {className: "text", textContent: post.content});
        const date = new Date(post.created_at);

        const formatted = date.toLocaleString('fr-FR', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        $make("div", content, {className: "date", textContent: formatted});
        const footer = $make("div", content, {className: "tweet-footer"});
        const likeBtn = $make("button", footer, {
            className: "like-btn",
            textContent: `${post.liked_by_me ? "❤" : "🤍"}️ ${post.like_count}`
        });

        likeBtn.addEventListener("click", async () => {
            const result = await this.likePost(post.id);
            if (result.success) {
                likeBtn.textContent = `${result.liked_by_me ? "❤" : "🤍"}️ ${result.like_count}`;
            }
        });

        if (post.isMyPost || this.isAdmin) {
            const deleteButton = $make("button", user, {className: "delete-btn", textContent: "🗑", title: "Supprimer le post"});
            deleteButton.addEventListener("click", async () => {
                if (confirm("Vous êtes sur le point de supprimer ce post, êtes vous sûr.e ?")) {
                    const result = await this.deletePost(post.id);
                    if (result.success) {
                        postDiv.remove();
                    }
                }
            });
        }

        return postDiv;
    }
}

export default PostManager;