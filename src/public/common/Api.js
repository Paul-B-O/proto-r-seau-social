class Api {

    #headers = {
        "Content-Type": "application/json"
    };

    constructor() { }

    async fetch(url, method, body = undefined) {
        const res = await fetch(
            `api/${url}`, {
                headers: this.#headers,
                method,
                body: body ? JSON.stringify(body) : undefined
            });
        return res.json();
    }

    async get(url) {
        return await this.fetch(url, "GET");
    }

    async post(url, body) {
        return await this.fetch(url, "POST", body);
    }

    async newPost(content, token) {
        return await this.post("newPost", { content, token });
    }

    async likePost(postId){
        return await this.post("likePost", { postId });
    }

    async deletePost(postId) {
        return await this.post("deletePost", { postId });
    }

    async getPost(args = {}) {
        const urlParams = new URLSearchParams();

        for (const [key, value] of Object.entries(args)) {
            urlParams.set(key, value);
        }

        return await this.get("getPost"+ (urlParams.size > 0 ? `?${urlParams}` : ""));
    }
}

export default Api;