import PostManager from "/common/PostManager.js";
import { $ } from "/common/dom.js"
import Api from "/common/Api.js";

const container = $(".container");
const tweetBox = $(".tweet-box textarea");
const sendBtn = $(".tweet-actions button");
const charCounter = $("#charCounter");



const postManager = new PostManager(
    (elt) => container.appendChild(elt),
    (elt) => container.insertBefore(elt, container.children[3])
);


async function sendPost() {
    const content = tweetBox.value;
    const success = await postManager.sendPost(content);

    if (success) {
        tweetBox.value = "";
        charCounter.textContent = tweetBox.value.length;
    }
}

async function onInput(event){
    if (tweetBox.value.length >= 280) {
        event.preventDefault();
        tweetBox.value = tweetBox.value.substring(0, 280);
    }
    charCounter.textContent = tweetBox.value.length;
}

sendBtn.addEventListener("click", sendPost);
tweetBox.addEventListener("input", onInput);