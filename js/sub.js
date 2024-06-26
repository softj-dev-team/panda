$(document).ready(function  () {
    // product_dot 클릭
    $(".product .item_wrap .item").click(function() {
        var index = $(this).index() + 1;
        
        if($(window).width() < 1200) { // 모바일
            var popup = ".pop_box" + index;
            swiperSetting(index);
            $(".product_popup_wrap").show();
            $(popup).addClass("on");
        } else { // PC
            var box = ".box" + index;
            $(this).toggleClass("on");
            $(box).toggleClass("on");
        }
    })

    $(".product .more_btn").click(function() {
        var index = $(this).attr("class");
        var popup = ".pop_box" + index.charAt(index.length - 1);

        swiperSetting(index.charAt(index.length - 1));
        $(".product_popup_wrap").show();
        $(popup).addClass("on");
    })

    $(".product .box").click(function() {
        var index = $(this).attr("class").replace(" on", "");
        var popup = ".pop_box" + index.charAt(index.length - 1);
        swiperSetting(index.charAt(index.length - 1));
        $(".product_popup_wrap").show();
        $(popup).addClass("on");
    })


    // 기술 - Design & Development 슬라이드1
    var design_swiper1 = new Swiper(".design_swiper1", {
        slidesPerView: 1,
        spaceBetween: 0,
        grabCursor: true,
        navigation: {
        nextEl: ".swiper-button-next1",
        prevEl: ".swiper-button-prev1",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });

    // 기술 - Design & Development 슬라이드2
    var design_swiper1 = new Swiper(".design_swiper2", {
        slidesPerView: 1,
        spaceBetween: 0,
        grabCursor: true,
        navigation: {
        nextEl: ".swiper-button-next2",
        prevEl: ".swiper-button-prev2",
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });

    // 기술 - Design & Development - Capability 슬라이드
    var capability_swiper = new Swiper(".capability_swiper", {
        slidesPerView: 1.3,
        spaceBetween: 5,
        initialSlide: 0,
        loop: true,
        grabCursor: true,
        centeredSlides: true,
        autoplay: {
            delay: 3000,
        },
        breakpoints: {
            1440: {
                slidesPerView: 4.3,
                spaceBetween: 20,
                initialSlide: 1,
            },
            959: {
                slidesPerView: 3.5,
                spaceBetween: 15,
                initialSlide: 1,
            },
            540: {
                slidesPerView: 2.1,
                spaceBetween: 10,
                initialSlide: 0,
            },

        },
    });



    // 기술 - CASTING 슬라이드1
    var casting_swiper1 = new Swiper(".casting_swiper1", {
        slidesPerView: 1,
        spaceBetween: 4,
        grabCursor: true,
        navigation: {
        nextEl: ".swiper-button-next1",
        prevEl: ".swiper-button-prev1",
        },
        breakpoints: {
            1025: {
                slidesPerView: 4,
                spaceBetween: 10,
            },
            641: {
                slidesPerView: 2.5,
                spaceBetween: 8,
            },
            479: {
                slidesPerView: 2,
                spaceBetween: 5,
            },
            375: {
                slidesPerView: 1.3,
                spaceBetween: 5,
            },
        },
    });

    // 기술 - CASTING 슬라이드2
    var casting_swiper2 = new Swiper(".casting_swiper2", {
        slidesPerView: 1,
        spaceBetween: 4,
        grabCursor: true,
        navigation: {
        nextEl: ".swiper-button-next2",
        prevEl: ".swiper-button-prev2",
        },
        breakpoints: {
            1025: {
                slidesPerView: 4,
                spaceBetween: 10,
            },
            641: {
                slidesPerView: 2.5,
                spaceBetween: 8,
            },
            479: {
                slidesPerView: 2,
                spaceBetween: 5,
            },
            375: {
                slidesPerView: 1.3,
                spaceBetween: 5,
            },
        },
    });

    // 기술 - MACHINING & ASSEMBLY
    var testing_swiper = new Swiper(".testing_swiper", {
        slidesPerView: 1.3,
        spaceBetween: 5,
        initialSlide: 0,
        loop: true,
        grabCursor: true,
        centeredSlides: true,
        autoplay: {
            delay: 1000,
        },
        breakpoints: {
            1920: {
                slidesPerView: 5.6,
                spaceBetween: 20,
                initialSlide: 2,
            },
            1440: {
                slidesPerView: 4.2,
                spaceBetween: 20,
                initialSlide: 1,
            },
            959: {
                slidesPerView: 3.5,
                spaceBetween: 15,
                initialSlide: 1,
            },
            540: {
                slidesPerView: 2.1,
                spaceBetween: 10,
                initialSlide: 0,
            },

        },
    });





    $(".sec4 .acco_box .title_box").click(function() {
        if($(this).next(".cont_box").css("display") == "none") {
            $(".sec4 .cont_box").slideUp();
            $(".sec4 .arrow").animate({rotate:"0deg"}, {duration:400, queue:false});
            $(this).find(".arrow").animate({rotate:"180deg"}, {duration:400, queue:false});
            $(this).next(".cont_box").slideDown();
        } else {
            $(this).find(".arrow").animate({rotate:"0deg"}, {duration:400, queue:false});
            $(this).next(".cont_box").slideUp();
        }
    });

    // $(window).scroll(function(){
    //     var hT = $('.esdc').offset().top - 500;
    //     var wS = $(this).scrollTop();
    //     console.log(wS + " / " + hT);
    //     if(wS > hT){
    //       if (!$(".gr1").is(".graph_bar1")){
    //         $(".gr1").addClass("graph_bar1");
    //       }
    
    //       if (!$(".gr2").is(".graph_bar2")){
    //         $(".gr2").addClass("graph_bar2");
    //       }
          
    //     }
    //   });

    $(".casting .sec5 .acco_box .title_box").click(function() {
        var index = $(this).closest(".acco_box").index();
        if($(this).next(".cont_box").css("display") == "none") {

            $(".sec5 .cont_box").slideUp();
            $(".sec5 .arrow").animate({rotate:"0deg"}, {duration:400, queue:false});

            $(this).find(".arrow").animate({rotate:"180deg"}, {duration:400, queue:false});
            $(this).next(".cont_box").slideDown();

            var ele = '<div class="circle_graph" data-percent="80"><div class="f20">연신율</div><div class="f32 left_per">9<span class="f20">%</span></div><div class="f32 right_per">7<span class="f20">%</span></div></div>';
            $(".circle_graph").remove();
            $(".circle_graph_container").append(ele);
            circle_graph();

            if(index == '2') {
                $(".gr1").addClass("graph_bar1");
                $(".gr2").addClass("graph_bar2");
                $(".gr3").addClass("graph_bar3");
                $(".gr4").addClass("graph_bar4");
                $(".circle_graph canvas").show();
            }
        } else {
            $(this).find(".arrow").animate({rotate:"0deg"}, {duration:400, queue:false});
            $(this).next(".cont_box").slideUp();
        }
    });




    $(".img_view_wrap .item").click(function() {
        var index = $(this).index();
        $(".img_view_wrap .item").removeClass("active");
        $(this).addClass("active");

        $(".item_detail").hide();
        $(".item_detail:eq("+index+")").show();
    })

    // testing - tab1
    $(".testing .sec5 .tab li .f20").click(function() {
        var index = $(this).closest("li").index() + 1;
        var trigger = $(".testing .sec5 .tab li .f20");
        var element = ".tab_con" + index;

        trigger.removeClass("active");
        $(this).addClass("active");
        $(".tab_con").hide();
        $(element).show();
    })

    // testing - tab2
    $(".testing .sec7 .testing_tab .f20").click(function() {
        var index = $(this).index() + 1;
        var element = ".testing_tab_con" + index;

        $(".testing .sec7 .testing_tab .f20").removeClass("on");
        $(this).addClass("on");
        $(".testing_tab_con").hide();
        $(element).show();
    })

    $(".sec4 .testing_swiper").hover(function(){
        testing_swiper.autoplay.stop();
    }, function() {
        testing_swiper.autoplay.start();
    });
});






$(window).on("scroll", function(e){
    var scrollT = $(this).scrollTop();

    if(scrollT == '0') {
        $(".header").removeClass("active");
    } else {
        $(".header").addClass("active");
    }
})

// product swiper 설정 후 실행
function swiperSetting(index) {
    var active_swiper_class = swiper_class + index;
    index = index - 1;
    var bullet = [];
    for(var i = 0; i < ($(".swiper:eq("+index+") .view_img").length); i++) {   
        var img_src = $(".swiper:eq("+index+") .view_img:eq("+i+")").find("img").attr("src");
        bullet[i] = "<img src='" + img_src + "'>";
    }

    var product_pop_swiper = new Swiper(active_swiper_class, {
        slidesPerView: 1,
        spaceBetween: 0,
        grabCursor: true,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            renderBullet: function (index, className) {
                return '<div class="' + className + '"><span>' + (bullet[index]) + '</span></div>';
            }
        },
    });
}

function circle_graph() {
    $('.esdc .circle_graph_bg').easyPieChart({
        scaleColor: false,
        lineWidth: 40,
        lineCap: 'butt',
        barColor: '#E6E6E6',
        trackColor: false ,
        rackColor: false,
        size: 250,
        animate: 800
    });

    $('.esdc .circle_graph1').easyPieChart({
        scaleColor: false,
        lineWidth: 40,
        lineCap: 'butt',
        barColor: '#20385b',
        trackColor: false ,
        rackColor: false ,
        size: 250,
        animate: 800
    });

    $('.esdc .circle_graph2').easyPieChart({
        scaleColor: false,
        lineWidth: 40,
        lineCap: 'butt',
        barColor: '#ccc',
        trackColor: false ,
        rackColor: false,
        size: 250,
        animate: 800
    });
}








