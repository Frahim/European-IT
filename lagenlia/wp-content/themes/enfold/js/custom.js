jQuery(document).ready(function ($) {
    jQuery(".js-category, .js-date, .js-location").on("change", function () {
        var category = $('.js-category').val();
        var date = $('.js-date').val();
        var location = $('.js-location').val();

        data = {
            'action': 'filterposts',
            'category': category,
            'date': date,
            'location': location,
        };

        $.ajax({
            url: '/lagenlia/wp-admin/admin-ajax.php',
            data: data,
            type: 'POST',
            beforeSend: function (xhr) {
                $('.filtered-posts').html('Loading...');
                $('.js-category').attr('disabled', 'disabled');
                $('.js-date').attr('disabled', 'disabled');
                $('.js-location').attr('disabled', 'disabled');
            },
            success: function (data) {
                console.log(data);
                if (data) {
                    $('.filtered-posts').html(data.posts);

                    $('.js-category').removeAttr('disabled');
                    $('.js-date').removeAttr('disabled');
                    $('.js-location').removeAttr('disabled');
                } else {
                    $('.filtered-posts').html('No Event found.');
                }
            }
        });
    });

});

jQuery(document).ready(function($){
//jQuery(document).on('click', '.LoadMorePostsbtn', function () {
    // var ajaxurl = _frontend_ajax_object.ajaxurl;
    $(".LoadMorePostsbtn").on("click", function(e) {
        var PostType = $('input[name="PostType"]').val();
        var categoryName = $('input[name="categoryName"]').val();
        var offset = parseInt($('input[name="offset"]').val());

        var loadMorePosts = parseInt($('input[name="loadMorePosts"]').val());
        var newOffset = offset + loadMorePosts;
        console.log(categoryName);
        $('.btnLoadmoreWrapper').hide();
        $.ajax({
            type: "POST",
            url: '/lagenlia/wp-admin/admin-ajax.php',
            data: ({
                action: "AjaxLoadMorePostsAjaxReq",
                offset: newOffset,
                postType: PostType,
                category_name: categoryName,
                loadMorePosts: loadMorePosts,
            }),
            success: function (response) {
                if (!$.trim(response)) {
                    // blank output
                    $('.noMorePostsFound').show();
                } else {
                    // append to last div
                    $(response).insertAfter($('.loadMoreRepeat').last());
                    $('input[name="offset"]').val(newOffset);
                    $('input[name="categoryName"]').val(categoryName);

                    $('.btnLoadmoreWrapper').show();
                }
            },
            beforeSend: function () {
                $('.LoaderImg').show();
            },
            complete: function () {
                $('.LoaderImg').hide();
            },
        });
    });
});


// Slider

numberElements = 4;
numberViewed = 4;
currentPosition = 1;

function countLi() {
    return jQuery("#galleries-list ul li").length;
}
;

function slideRight() {
    if (countLi() > numberViewed)
        if (currentPosition == (countLi() - (numberViewed - 1))) {
            jQuery("#galleries-list ul li").css("visibility", "hidden").css("transition", "none");
            setSliderPosition(numberViewed + 1, countLi());
            jQuery("#galleries-list ul li").css("transition", "left .75s ease-out").css("visibility", "visible");
            setSliderPosition(currentPosition + 1, countLi());
            return;
        } else
            return setSliderPosition(currentPosition + 1, countLi());
}
;

function slideLeft() {
    if (countLi() > numberViewed)
        if (currentPosition == 1) {
            jQuery("#galleries-list ul li").css("transition", "none");
            setSliderPosition(countLi() - (2 * numberViewed + 1), countLi()).css("transition", "left .75s ease-out");
            return setSliderPosition(currentPosition - 1, countLi());
        } else
            return setSliderPosition(currentPosition - 1, countLi());
}
;

function setSliderPosition(n1, count) {
    if (n1 < 1) {
        setSliderPosition((count - (numberViewed - 1)) + n1, count);
        return
    }
    ;
    n1 = (n1 - 1) % (count - (numberViewed - 1)) + 1;
    currentPosition = n1;
    return jQuery("#galleries-list ul li").css('left', function () {
        return -(n1 - 1) * jQuery("#galleries-list ul li").width();
    });
}
;

function initWidths() {
    jQuery("#galleries-list ul li").css('width', (100 / countLi()) + '%');
    jQuery("#galleries-list ul").css('width', (100 + (countLi() - numberViewed) * (100 / numberViewed)) + '%');
}
;

function initClones() {
    if (countLi() >= numberViewed) {
        for (i = 1; i < numberViewed + 1; i++) {
            jQuery("#galleries-list ul li:nth-child(" + i + ")").clone().insertAfter("#galleries-list ul li:last-child");
        }
        c = countLi();
        for (i = c - numberViewed + 1; i <= c; i++) {
            console.log(i);
            jQuery("#galleries-list ul li:nth-last-child(" + i + ")").clone().insertBefore("#galleries-list ul li:first-child");
        }
    }
}
;

//function initSlider() {
//    initClones();
//    initResponsive();
//    initWidths();
//}
//;

function initPosition() {
    setSliderPosition(currentPosition, countLi());
}
;

function initResponsive() {
    if (jQuery(window).width() > 1000) {
        if (numberViewed != 4) {
            numberViewed = 4;
            initWidths();
        }
    } else if (jQuery(window).width() > 800) {
        if (numberViewed != 3) {
            numberViewed = 3;
            initWidths();
            var slideIndex = 0;
        showSlides();

        function showSlides() {
            var i;
            var slides = document.getElementsByClassName("slideContent");
            var dots = document.getElementsByClassName("dot");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) {
                slideIndex = 1
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
            setTimeout(showSlides, 2000); // Change image every 2 seconds
        }
        }
    } else if (jQuery(window).width() > 600) {
        if (numberViewed != 2) {
            numberViewed = 2;
            initWidths();
            var slideIndex = 0;
        showSlides();

        function showSlides() {
            var i;
            var slides = document.getElementsByClassName("slideContent");
            var dots = document.getElementsByClassName("dot");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) {
                slideIndex = 1
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
            setTimeout(showSlides, 2000); // Change image every 2 seconds
        }
        }
    } else if (numberViewed != 1) {
        numberViewed = 1;
        initWidths();
        var slideIndex = 0;
        showSlides();

        function showSlides() {
            var i;
            var slides = document.getElementsByClassName("slideContent");
            var dots = document.getElementsByClassName("dot");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) {
                slideIndex = 1
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
            setTimeout(showSlides, 2000); // Change image every 2 seconds
        }
    }
    ;
    initPosition();
}
;
//jQuery(document).ready(function () {
//    initSlider();
//    $(window).resize(initResponsive);
//    $("#galleries-list .slide-left").click(slideLeft);
//    $("#galleries-list .slide-right").click(slideRight);
//
//
//});

