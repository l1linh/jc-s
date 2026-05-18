<?php
$sliderImgList = array(
    'pc' => array(
//        'mv_movie_pc.mp4',
        'top_img_main_01_pc.png',
        'top_img_main_02_pc.png'
    ),
    'sp' => array(
//        'mv_movie_sp.mp4',
        'top_img_main_01_sp.png',
        'top_img_main_02_sp.png'
    ),
);
$mvCatchCopy = '';//メインビジュアルのキャッチコピーを「''」内に入力してください
?>

@if(($sliderImgList['pc'] && count($sliderImgList['pc']) > 0) || ($sliderImgList['sp'] && count($sliderImgList['sp']) > 0))




<style>
#mvSlider .swiper-slide {
    position: relative;
}

#mvSlider .swiper-slide .block-banner {
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: 99;
    top: 0px;
    left: 0px;
}

#mvSlider .swiper-slide .inner-banner {
    position: relative;
    z-index: 100;
    max-width: 1200px;
    margin: auto;
    height: 100%;
}
#mvSlider .swiper-wrapper .icon {
    position: absolute;
    z-index: 2;
}
#mvSlider .swiper-wrapper .img-icon01 {
    width: 30%;
    top: 5%;
    right: 3%;
}
#mvSlider .swiper-wrapper .img-icon02 {
    width: 30%;
    top: 5%;
    right: 10%;
}
#mvSlider .swiper-wrapper .img-icon03 {
    width: 30%;
    top: 5%;
    right: 15%;
}
#mvSlider .swiper-wrapper .img-icon04 {
    width: 23%;
    bottom: 35px;
    left: 73%;
}

#mvSlider .swiper-wrapper .box-text {
    position: absolute;
    width: 90%;
    top: 54%;
    left: 5%;
}

#mvSlider .swiper-wrapper .icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    min-width: auto !important;
}

#mvSlider .swiper-wrapper .swiper-slide {
    z-index: 10;
}

#mvSlider .swiper-slide {
    transition: opacity 1s ease-in-out; 
}


#mvSlider .swiper-slide-prev, 
#mvSlider .swiper-slide-duplicate-prev {
    opacity: 0 !important;
    transition: opacity 0.5s ease-in; 
}
#mvSlider .swiper-slide img {
    width: 100%;
}




@keyframes floating {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-15px); 
    }
    100% {
        transform: translateY(0px);
    }
}

#mvSlider .swiper-wrapper .icon {
    position: absolute;
    z-index: 2;
    transition: opacity 0.5s ease; 
    animation: floating 4s ease-in-out infinite;
}
#mvSlider .swiper-wrapper .icon.img-icon04 {
    animation :normal
}
#mvSlider .swiper-wrapper .img-icon01 {
    width: 37.4%;
    top: 23%;
    right: 62%;
    animation-delay: 0s;
}

#mvSlider .swiper-wrapper .img-icon02 {
    width: 45.8%;
    top: 1%;
    right: 20%;
    animation-delay: 1s;
}

#mvSlider .swiper-wrapper .img-icon03 {
    width: 33.2%;
    top: 30%;
    right: 2%;
    animation-delay: 2s;
}



#mvSlider .title-main {
    font-size: 30px;
    font-weight: 700;
}

#mvSlider .other-col{
    color: #D06B6E;
}
#mvSlider  .note-banner {
    color: #47B08B;
    font-style: italic;
    font-size: 22px;
    transform: rotate(-3.91deg);
    font-family: "adobe-handwriting-ernie", sans-serif;
    font-weight: 600;
    line-height: 1.6;
}


@media print, screen and (min-width: 768px) {

    #mvSlider .title-main {
        font-size: 38px;
        font-weight: 700
   }
    #mvSlider  .note-banner {
        color: #47B08B;
        font-style: italic;
        font-size: 26px;
        line-height: 1.8;
    }
    #mvSlider .swiper-wrapper .box-text {
        position: absolute;
        width: 50%;
        top: 26%;
        left: 3%;
    }
    #mvSlider .swiper-wrapper .img-icon04 {
        width: 108px;
        bottom: 23px;
        left: 40px;
    }
    #mvSlider .swiper-wrapper .img-icon01 {
        width: 212px;
        top: 29%;
        right: 31%;
    }
    #mvSlider .swiper-wrapper .img-icon02 {
        width: 275px;
        top: 2%;
        right: 6%;
    }
    #mvSlider .swiper-wrapper .img-icon03 {
        width: 225px;
        bottom: 4%;
        right: 0%;
        top: unset;
    }
}



@media print, screen and (min-width: 1400px) {
    #mvSlider .swiper-wrapper .box-text {
        top: 26%;
        left: -6%;
    }
    #mvSlider .title-main {
        font-size: min(60px,3.13vw);
        font-weight: 700
    }

    #mvSlider  .note-banner {
        font-size: min(42px,2.19vw);
    }

    #mvSlider .swiper-wrapper .img-icon04 {
        width: 15%;
        bottom: 7%;
        left: 0%;
    }
    #mvSlider .swiper-wrapper .img-icon01 {
        width: min(340px,17.71vw);
        top: 29%;
        right: 34%;
    }
    #mvSlider .swiper-wrapper .img-icon02 {
        width: min(442px,23.02vw);
        top: 0%;
        right: 5%;
    }
    #mvSlider .swiper-wrapper .img-icon03 {
        width: min(362px,18.85vw);
        bottom: 4%;
        right: -6%;
        top: unset;
    }
    #mvSlider .swiper-wrapper .img-icon04 {
        position: absolute;
        width: 10%;
        bottom: 4%;
        left: -2%;
    }
}
@media print, screen and (min-width: 1500px) {
    #mvSlider .swiper-wrapper .img-icon02 {
        top: 0%;
        right: 0%;
    }
    #mvSlider .swiper-wrapper .img-icon03 {
        width: min(362px,18.85vw);
        bottom: 4%;
        right: -16%;
    }
}

@media print, screen and (min-width: 1800px) {
    #mvSlider .title-main {
        font-size: min(60px,3.13vw);
        font-weight: 700
    }
    #mvSlider  .note-banner {
        color: #47B08B;
        font-style: italic;
        font-size: min(42px,2.19vw);
        line-height: 1.8;
    }
    #mvSlider .swiper-wrapper .box-text {
        position: absolute;
        width: 50%;
        top: 25%;
        left: -11%;
    }
    #mvSlider .swiper-wrapper .img-icon04 {
        width: 15%;
        bottom: 7%;
        left: -9%;
    }
    #mvSlider .swiper-wrapper .img-icon01 {
        width: min(340px,17.71vw);
        top: min(203px,10.57vw);
        right: min(372px,19.38vw);
    }
    #mvSlider .swiper-wrapper .img-icon02 {
        width: min(442px,23.02vw);
        top: 2%;
        right: -6%;
    }
    #mvSlider .swiper-wrapper .img-icon03 {
        width: min(362px,18.85vw);
        bottom: 11%;
        right: -24%;
        top: unset;
    }

}


</style>



<div id="mvSlider">
    <div class="mvWrapper">
        <div class="mvInner">
            <?php
            $imgListPc = $sliderImgList[ 'pc' ];
            $imgListSp = $sliderImgList[ 'sp' ];
            ?>
            <?php if(count($imgListPc) == 1): ?>
            <div class="mvOnlyOne">
                <picture>
                    <source media="(max-width: 767px)" srcset="{{asset('images/mvSlider/'. $imgListSp[0])}}">
                    <source media="(min-width: 768px)" srcset="{{asset('images/mvSlider/'. $imgListPc[0])}}">
                    <img class="mvPicture" src="{{asset('images/mvSlider/'. $imgListPc[0])}}" alt="<?php echo $mvCatchCopy; ?>">
                </picture>
            </div>
            <?php elseif(count($imgListPc) > 1): ?>
            <div class="mvMultiple js-mvSlider">
                <div class="swiper-wrapper">
                    <?php foreach($imgListPc as $key => $imgName): ?>
                        <div class="swiper-slide">
                            <picture>
                                <source media="(max-width: 767px)" srcset="{{asset('images/mvSlider/'. $imgListSp[$key])}} 2x">
                                <source media="(min-width: 768px)" srcset="{{asset('images/mvSlider/'. $imgName)}} 2x">
                                <img class="mvPicture" src="{{asset('images/mvSlider/'. $imgName)}}" alt="<?php echo $mvCatchCopy; ?>">
                            </picture>
                            <?php if($key == 0): // add first img-icon01 in slider one ?>
                            <div class="block-banner">
                                <div class="inner-banner">
                                    <p class="img-icon01 icon">
                                        <picture>
                                            <source media="(min-width: 768px)" srcset="{{asset('images/mvSlider/icon_banner_01_pc.png')}}">
                                                <source media="(max-width: 768px)" srcset="{{asset('images/mvSlider/icon_banner_01_sp.png')}}">   
                                            <img class="mvPicture" src="{{asset('images/mvSlider/icon_banner_01_pc.png')}}" alt="">
                                        </picture>
                                    </p>
                                    <p class="img-icon02 icon">
                                        <picture>
                                            <source media="(min-width: 768px)" srcset="{{asset('images/mvSlider/icon_banner_02_pc.png')}}">
                                                <source media="(max-width: 768px)" srcset="{{asset('images/mvSlider/icon_banner_02_sp.png')}}">   
                                            <img class="mvPicture" src="{{asset('images/mvSlider/icon_banner_02_pc.png')}}" alt="">
                                        </picture>
                                    </p>
                                    <p class="img-icon03 icon">
                                        <picture>
                                            <source media="(min-width: 768px)" srcset="{{asset('images/mvSlider/icon_banner_03_pc.png')}}">
                                                <source media="(max-width: 768px)" srcset="{{asset('images/mvSlider/icon_banner_03_sp.png')}}">   
                                            <img class="mvPicture" src="{{asset('images/mvSlider/icon_banner_03_pc.png')}}" alt="">
                                        </picture>
                                    </p>
        
                    
                                </div>
                            </div>
             

                        
                             <?php endif; ?>

                             <div class="block-banner">
                                <div class="inner-banner">
        
                                    <div class="box-text">
                                                <h1 class="title-main">この街と自分らしく<br><span class="other-col">ココ</span>で<span class="other-col">働</span>きたい！</h1>
                                                <p class="note-banner">Live True, Right Here!</p>
                                    </div> 
                                    <p class="img-icon04 icon">
                                        <picture>
                                            <source media="(min-width: 768px)" srcset="{{asset('images/mvSlider/icon_banner_04_pc.png')}}">
                                                <source media="(max-width: 768px)" srcset="{{asset('images/mvSlider/icon_banner_04_sp.png')}}">   
                                            <img class="mvPicture" src="{{asset('images/mvSlider/icon_banner_04_pc.png')}}" alt="">
                                        </picture>
                                    </p>
                                </div>
                            </div>
                   
                        </div>
                 

           
   
                          

                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        $(function() {
            var mySwiperTop = new Swiper('#mvSlider .js-mvSlider', {// Swiperオプション
                loop: true,
                effect: 'fade',// アニメーションを指定（'slide' 'fade' 'coverflow' 'flip'）
                fadeEffect: {
                    crossFade: false // Đây là thuộc tính quan trọng
                },
                speed: 5000,// 移動速度（3000=3秒）
                autoplay: {
                    delay: 5000,// スライド間の間隔（3000=3秒）
                    stopOnLastSlide: false,
                    disableOnInteraction: false,
                    reverseDirection: false
                },
                breakpoints: {
                    767: {// スマホのみ
                        speed: 115000,// 移動速度（3000=3秒）
                        autoplay: {
                            delay: 115000,// スライド間の間隔（3000=3秒）
                        }
                    }
                },
                slidesPerView: 1,
                spaceBetween: 0,
                centeredSlides: false,
                simulateTouch: false,
                autoResize: false,
                autoHeight: false,
                resizeReInit: true,
                watchOverflow: true
            });
            $('#mvSlider .mvMultiple .swiper-slide').on('touchmove', function(){
                return true;
            });
        });
    </script>
</div>

@endif
