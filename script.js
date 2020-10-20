function likeClicked(ajax, moduleID) {

    /* user set like*/
    if(document.getElementById("like").checked) {
        if (document.getElementById("dislike").checked) {
            document.getElementById("dislike").checked = false;
            changeDislikes(-1);
        }

        reactionRequest(ajax, moduleID, 1)
            .done(() => {console.log("like done")})
            .fail(() => {console.log("like fail")})
        changeLikes(1);
    }

    /* user unset like*/
    else {
        reactionRequest(ajax, moduleID, 2)
            .done(() => {console.log("unset done")})
            .fail(() => {console.log("unset fail")})
        changeLikes(-1);
    }

    recalculateRatio();
}


function dislikeClicked(ajax, moduleID) {

    /* user set dislike*/
    if(document.getElementById("dislike").checked) {
        if (document.getElementById("like").checked) {
            document.getElementById("like").checked = false;
            changeLikes(-1);
        }

        reactionRequest(ajax, moduleID, 0)
            .done(() => {console.log("dislike done")})
            .fail(() => {console.log("dislike fail")})
        changeDislikes(1);
    }

    /* user unset dislike*/
    else {
        reactionRequest(ajax, moduleID, 2)
            .done(() => {console.log("unset done")})
            .fail(() => {console.log("unset fail")})
        changeDislikes(-1);
    }

    recalculateRatio();
}


function reactionRequest(ajax, moduleID, reaction) {
    return ajax.call([{
            methodname: "mse_ld_set_reaction",
            args: {
                moduleid: moduleID,
                reaction: reaction
            }
        }])[0];
}


function recalculateRatio(){
    if(typeof recalculateRatio.likeDOM === undefined || typeof recalculateRatio.dislikeDOM === undefined || typeof recalculateRatio.bar === undefined){
        recalculateRatio.likeDOM = document.getElementById("like-label");
        recalculateRatio.dislikeDOM = document.getElementById("dislike-label");
        recalculateRatio.bar = document.getElementById("bar-likes");
    }

    const likes = parseInt(recalculateRatio.likeDOM.innerText || recalculateRatio.likeDOM.textContent);
    const dislikes = parseInt(recalculateRatio.dislikeDOM.innerText || recalculateRatio.dislikeDOM.textContent);

    const ratio = likes / (likes + dislikes) * 100;

    if(isNaN(ratio)){
        recalculateRatio.bar.style.width = `100%`;
        recalculateRatio.bar.style.background = "#D1D1D1";
    } else {
        recalculateRatio.bar.style.width = `${ratio}%`;
        recalculateRatio.bar.style.background = "#90EE90";
    }
}


function changeLikes(delta){
    const label = document.getElementById("like-label");
    const newValue = parseInt(label.innerText || label.textContent) + delta;
    label.innerHTML = newValue;
}


function changeDislikes(delta) {
    const label = document.getElementById("dislike-label");
    const newValue = parseInt(label.innerText || label.textContent) + delta;
    label.innerHTML = newValue;
}

function switcher(id) {
    require(['core/ajax'], (ajax) => {
        ajax.call([{
            methodname: "mse_ld_toggle_module_reaction_visibility",
            args: {
                moduleid: id
            }
        }])[0]
            .done(() => {console.log("Switched: ", id)})
            .fail(() => {
                document.getElementsByName("plugin-state")[0].checked = !document.getElementsByName("plugin-state")[0].checked;
            })
    })    
}

