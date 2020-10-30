define(['jquery', 'core/ajax'], function($, ajax) {
    return {
        init: function(env) {
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

                /* build plugin interface */
                let wrapper = $('<div class="plugin-wrapper">');
                let ui = $('<div class="likes-dislikes-plugin-ui">');

                let like = $(`<input type="checkbox" id="like">`).click(() => {likeClicked(ajax, env.mod_id)});
                let likeLabel = $('<label for="like" id="like-label">');
                likeLabel.text(likesCount);

                let barWrapper = $('<div class="bar-wrapper">');
                let bar = $('<span class="bar">');
                let barLikes = $('<span class="bar-likes" id="bar-likes">');

                let dislike = $(`<input type="checkbox" id="dislike">`).click(() => {dislikeClicked(ajax, env.mod_id)});
                let dislikeLabel = $('<label for="dislike" id="dislike-label">');
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

