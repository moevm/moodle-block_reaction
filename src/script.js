/* 
 * @copyright  2020 Konstantin Grishin, Anna Samoilova, Maxim Udod, Ivan Grigoriev, Dmitry Ivanov
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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
    likeDOM = document.getElementById("like-label");
    dislikeDOM = document.getElementById("dislike-label");
    bar = document.getElementById("bar-likes");

    const likes = parseInt(likeDOM.innerText || likeDOM.textContent);
    const dislikes = parseInt(dislikeDOM.innerText || dislikeDOM.textContent);

    const ratio = likes / (likes + dislikes) * 100;

    if(isNaN(ratio)){
        bar.style.width = `100%`;
        bar.style.background = "#D1D1D1";
    } else {
        bar.style.width = `${ratio}%`;
        bar.style.background = "#90EE90";
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

function allTurnOn(courseId) {
    let settings_wrapper = document.querySelector(".reactions-course-settings-wrapper")
    let wrapper = settings_wrapper.parentNode
    let div_msg = document.querySelector(".course-settings-message")

    if(!div_msg){
        div_msg = document.createElement("div");
        div_msg.classList.add("course-settings-message");
        wrapper.appendChild(div_msg);
    }

    console.log(courseId);
    
    require(['core/ajax'], (ajax) => {
        ajax.call([{
            methodname: "mse_ld_set_course_modules_reactions_visible",
            args: {
                courseid: courseId,
                visible: 1
            }
        }])[0]
            .done(() => {
                div_msg.innerHTML = settings_wrapper.dataset.successOn
            })
            .fail((err) => {
                div_msg.innerHTML = settings_wrapper.dataset.error
                console.log(err)
            })
            .always(() => {
                this.timer = setTimeout(() => {
                    div_msg.remove();
                }, 2000)
            })
    })
}

function allTurnOff(courseId) {
    let settings_wrapper = document.querySelector(".reactions-course-settings-wrapper")
    let wrapper = settings_wrapper.parentNode
    let div_msg = document.querySelector(".course-settings-message")

    if(!div_msg){
        div_msg = document.createElement("div");
        div_msg.classList.add("course-settings-message");
        wrapper.appendChild(div_msg);
    }

    console.log(courseId);
    
    require(['core/ajax'], (ajax) => {
        ajax.call([{
            methodname: "mse_ld_set_course_modules_reactions_visible",
            args: {
                courseid: courseId,
                visible: 0
            }
        }])[0]
            .done(() => {
                div_msg.innerHTML = settings_wrapper.dataset.successOff
            })
            .fail((err) => {
                div_msg.innerHTML = settings_wrapper.dataset.error
                console.log(err)
            })
            .always(() => {
                this.timer = setTimeout(() => {
                    div_msg.remove();
                }, 2000)
            })
    })
}





