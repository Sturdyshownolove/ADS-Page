jQuery(function ($) {

    // FUCKING AWESOME CODE

    window.da_share = {
        fb: function (url) {
            if (event) event.preventDefault();
            url = url || window.location.href;
            var link = 'https://www.facebook.com/share.php?u=';
            link += encodeURI(url);
            da_share.popup(encodeURI(link));
        },
        vk: function (url, title, description, image) {
            if (event) event.preventDefault();
            url = url || window.location.href;
            var link = 'https://vk.com/share.php?url=' + encodeURI(url);
            if (title) link += '&title=' + title;
            if (description) link += '&description=' + description;
            if (image) link += '&image=' + image;
            da_share.popup(encodeURI(link));
        },
        tw: function (url, text, hashtags, via) {
            if (event) event.preventDefault();
            url = url || window.location.href;
            var link = 'https://twitter.com/intent/tweet?url=';
            link += encodeURI(url);
            if (text) link += '&text=' + text;
            if (hashtags) link += '&hashtags=' + hashtags;
            if (via) link += '&via=' + via;
            da_share.popup(encodeURI(link));
        },
        ok: function (url, title, image) {
            if (event) event.preventDefault();
            url = url || window.location.href;
            var link = 'https://connect.ok.ru/offer?url=' + encodeURI(url);
            if (title) link += '&title=' + title;
            if (image) link += '&imageUrl=' + image;
            da_share.popup(encodeURI(link));
        },
        gp: function (url) {
            if (event) event.preventDefault();
            url = url || window.location.href;
            var link = 'https://plus.google.com/share?url=' + encodeURI(url);
            da_share.popup(encodeURI(link));
        },
        pi: function (url, description, image) {
            if (event) event.preventDefault();
            url = url || window.location.href;
            var link = 'https://www.pinterest.com/pin/create/button/?url=' + encodeURI(url);
            if (description) link += '&description=' + description;
            if (image) link += '&media=' + image;
            da_share.popup(encodeURI(link));
        },
        popup: function(link) {
            //console.log({'link': link});
            window.open(link,'_blank','toolbar=0,status=0,width=626,height=436');
        }
    };

    // STUPID HTML TEMPLATE

    var da_share_elements = $('.da-share-html');

    da_share_elements.each(function(index){

        var da_share_html = "<div class='btn-group'>";

        if ( da_share_elements.eq(index).hasClass('da-fb') ) {
            da_share_html += "<a class='btn btn-md text-white' style='background:#3b5998;' onclick='da_share.fb()'><i class='fa fa-facebook-square fa-fw fa-sm my-1'></i></a>";
        }
		if ( da_share_elements.eq(index).hasClass('da-tw') ) {
            da_share_html += "<a class='btn btn-md text-white' style='background:#55acee' onclick='da_share.tw()'><i class='fa fa-twitter fa-fw fa-sm my-1'></i></a>";
        }
        if ( da_share_elements.eq(index).hasClass('da-vk') ) {
            da_share_html += "<a class='btn btn-md text-white' style='background:#0976b4' onclick='da_share.vk()'><i class='fa fa-sm fa-vk fa-fw my-1'></i></a>";
        }        
        if ( da_share_elements.eq(index).hasClass('da-ok') ) {
            da_share_html += "<a class='btn btn-md text-white' style='background:#ed812b' onclick='da_share.ok()'><i class='fa fa-odnoklassniki fa-fw fa-sm my-1'></i></a>";
        }
        if ( da_share_elements.eq(index).hasClass('da-pi') ) {
            da_share_html += "<a class='btn btn-md text-white' style='background:#E60019' onclick='da_share.pi()'><i class='fa fa-fw fa-sm fa-pinterest my-1'></i></a>";
        }
        if ( da_share_elements.eq(index).hasClass('da-gp') ) {
            da_share_html += "<a class='btn btn-md text-white' style='background:#cc2127' onclick='da_share.gp()'><i class='fa fa-fw fa-sm fa-google-plus-circle my-1'></i></a>";
        }

        da_share_html += "</div>";

        da_share_elements.eq(index).html(da_share_html);

    });

});
