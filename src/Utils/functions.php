<?php

function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

//TODO: write function testlogin to check if the user is login and redirect if he's not