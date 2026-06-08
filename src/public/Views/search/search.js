import PostManager from "/common/PostManager.js";
import { $, $hide, $show } from "/common/dom.js";

const postContainer = $(".results.posts");
const userContainer = $(".results.users");
const username = new URLSearchParams(location.search).get("username");
const tabs = $(".tabs");
const postTabBtn = tabs.children[0];
const userTabBtn = tabs.children[1];

const postManager = new PostManager(
    (elt) => postContainer.appendChild(elt),
    () => void(0)
);
postManager.init();


postTabBtn.addEventListener("click", () => {
    if (postTabBtn.classList.contains("active")) return;
    userTabBtn.classList.toggle("active");
    postTabBtn.classList.toggle("active");

    $hide(userContainer);
    $show(postContainer);
});

userTabBtn.addEventListener("click", () => {
    if (userTabBtn.classList.contains("active")) return;
    userTabBtn.classList.toggle("active");
    postTabBtn.classList.toggle("active");

    $hide(postContainer);
    $show(userContainer);
});
