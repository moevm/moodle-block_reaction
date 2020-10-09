define('block_reaction/script_reaction', ['jquery', 'core/ajax'], function($, ajax) {
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
                let likesCount = "0";
                let dislikesCount = "1";

                /* build plugin interface */
                let wrapper = $('<div class="plugin-wrapper">');
                let ui = $('<div class="likes-dislikes-plugin-ui">');

                let like = $('<input type="checkbox" id="like" onclick="likeClicked()">');
                let likeLabel = $('<label for="like">');
                likeLabel.text(likesCount);

                let barWrapper = $('<div class="bar-wrapper">');
                let bar = $('<span class="bar">');
                let barLikes = $('<span class="bar-likes">');

                let dislike = $('<input type="checkbox" id="dislike" onclick="dislikeClicked()">');
                let dislikeLabel = $('<label for="dislike">');
                dislikeLabel.text(dislikesCount);

                wrapper.append(ui);
                ui.append(like);
                ui.append(likeLabel);

                bar.append(barLikes);
                barWrapper.append(bar);
                ui.append(barWrapper);

                ui.append(dislike);
                ui.append(dislikeLabel);

                $('div[role="main"]').append(wrapper);
                
                urPromise = ajax.call([{
                    methodname: 'mse_ld_get_reaction',
                    args: {
                        moduleid: env.mod_id
                    }
                }])
                $.when(urPromise[0]).done((answ) => console.log('User reaction: ', answ))
                trPromise = ajax.call([{
                    methodname: 'mse_ld_get_total_reaction',
                    args: {
                        moduleid: env.mod_id
                    }
                }])
                $.when(trPromise[0]).done((answ) => console.log('Total reaction: ', answ))
            });
        }
    }
});
