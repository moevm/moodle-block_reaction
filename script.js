function likeClicked() {
    if(document.getElementById("like").checked) {
        document.getElementById("dislike").checked = false;
    }
}

function dislikeClicked() {
    if(document.getElementById("dislike").checked) {
        document.getElementById("like").checked = false;
    }
}