$(function () {
    $('.workcontrol_socialshare_item a').click(function () {
        var url = $(this).attr('href');
        var width = 600;
        var height = 600;

        var leftPosition, topPosition;
        leftPosition = (window.screen.width / 2) - ((width / 2) + 10);
        topPosition = (window.screen.height / 2) - ((height / 2) + 100);
        window.open(url, "Window2",
                "status=no,height=" + height + ",width=" + width + ",resizable=yes,left="
                + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY="
                + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no");
        return false;
    });

    //Check : Device || pc
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function() {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };


    function addZeros(n) {
        return (n < 10) ? '0' + n : n;
    }

    //GET FACEBOOK SHARE
    if ($(".workcontrol_socialshare_facebook").length) {
        var shareUrl = $('.workcontrol_socialshare_facebook a').attr('rel');
        $.getJSON("//graph.facebook.com/" + shareUrl, function (data) {
            $('.workcontrol_socialshare_facebook span').text((data.share.share_count ? addZeros(data.share.share_count) : '00'));
        });
    }

    //GET GOOGLE SHARE
    if ($(".workcontrol_socialshare_googleplus").length) {
        var shareUrl = $('.workcontrol_socialshare_googleplus a').attr('rel');
        var BASE = $('link[rel="base"]').attr('href');
        $.post(BASE + '/_cdn/widgets/share/google.php', {url: shareUrl}, function (data) {
            if (parseInt(data)) {
                $('.workcontrol_socialshare_googleplus span').text(addZeros(data));
            }
        });
    }
    
    //GET WHATS SHARE
    $(document).on("click",'.whatsapp',function() {
        if( isMobile.any() ) {

                var text = $(this).attr("data-text");
                var url = $(this).attr("data-link");
                var message = encodeURIComponent(text)+" - "+encodeURIComponent(url);
                var whatsapp_url = "whatsapp://send?text="+message;
                window.location.href= whatsapp_url;
        } else {
            // Nesse else você pode trabalhar em um modal. Deixo aqui com você!
            alert("Você precisa de um dispositivo móvel para compartilhar via Whatsapp!");
        }
    });

    //ADD COUNT FOR 6547 TIMES
    $('.workcontrol_socialshare_item a').click(function () {
        var SpanCount = $(this).find('span').attr('class');
        var SpanText = $(this).find('span').text();
        $("." + SpanCount).text(addZeros(parseInt(SpanText) + parseInt(1)));
    });
});