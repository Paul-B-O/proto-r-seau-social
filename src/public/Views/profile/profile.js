import PostManager from "/common/PostManager.js";
import { $ } from "/common/dom.js"

const username = new URLSearchParams(location.search).get("username");
const container = $(".container");
const postManager = new PostManager(
    (elt) => container.appendChild(elt),
    (elt) => container.prepend(elt),
    username
);

