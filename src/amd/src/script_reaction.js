/* 
 * @copyright  2020 Konstantin Grishin, Anna Samoilova, Maxim Udod, Ivan Grigoriev, Dmitry Ivanov
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax'], function($, ajax) {
    return {
        init: function(env) {
            console.log("We init plugin reaction")
            console.log(env)
            /* include stylesheet */
            $('head').append('<link rel="stylesheet" type="text/css" href="style.css">');

            /* include business script */
            $('head').append('<script type="text/javascript" src="script.js">');

            /* Wait that the DOM is fully loaded */
            $(() => {
                /*get values likes and dislikes count */
                let likesCount = env.total_reaction.likes;
                let dislikesCount = env.total_reaction.dislikes;
                console.log(`likesCount = ${env.total_reaction.likes}`)
                console.log(`dislikesCount = ${env.total_reaction.dislikes}`)
                /* build plugin interface */
                let wrapper = $('<div class="mse-likes-dislikes-plugin plugin-wrapper">');
                let ui = $('<div class="mse-likes-dislikes-plugin likes-dislikes-plugin-ui">');

                let like = $(`<input type="checkbox" class="mse-likes-dislikes-plugin" id="like">`).click(() => {likeClicked(ajax, env.mod_id)});
                let likeLabel = $('<label for="like" class="mse-likes-dislikes-plugin" id="like-label">');
                likeLabel.text(likesCount);

                let barWrapper = $('<div class="mse-likes-dislikes-plugin bar-wrapper">');
                let bar = $('<span class="mse-likes-dislikes-plugin bar">');
                let barLikes = $('<span class="mse-likes-dislikes-plugin bar-likes" id="bar-likes">');

                let dislike = $(`<input type="checkbox" class="mse-likes-dislikes-plugin" id="dislike">`).click(() => {dislikeClicked(ajax, env.mod_id)});
                let dislikeLabel = $('<label for="dislike" class="mse-likes-dislikes-plugin" id="dislike-label">');
                dislikeLabel.text(dislikesCount);

                /* find and set ratio */
                const ratio = likesCount / (likesCount + dislikesCount) * 100;
                if(isNaN(ratio)){
                    barLikes.css({
                        "width": "100%",
                        "background-color": "#D1D1D1"
                    });
                } else {
                    barLikes.css("width", `${ratio}%`);
                }

                console.log(ratio);

                /* check button being pressed */
                switch (env.user_reaction) {
                    case "0":
                        dislike.prop("checked", true);
                        break;

                    case "1":
                        like.prop("checked", true);
                        break;

                    default: break;
                }

                /* embed plugin */
                wrapper.append(ui);
                ui.append(like);
                ui.append(likeLabel);

                bar.append(barLikes);
                barWrapper.append(bar);
                ui.append(barWrapper);

                ui.append(dislike);
                ui.append(dislikeLabel);

                $('div[role="main"]').append(wrapper);
            });
        }
    }
});

