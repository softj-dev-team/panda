var anmation = bodymovin.loadAnimation({
    container : document.getElementById('animContainer'),
    renderer : 'svg',
    loop : false,
    autoplay : true,
    //path: 'https://assets8.lottiefiles.com/packages/lf20_b32f3vtz.json' // 2-1. url
    path: '../landinglottie.json' // 2-2. 다운받아서 사용.
});