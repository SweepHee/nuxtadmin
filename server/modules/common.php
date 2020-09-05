<?php

function req_to_array() {
    return json_decode(stripslashes(file_get_contents("php://input")));
}