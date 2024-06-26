     window.addEventListener('load', function() {
            var allElements = document.getElementsByTagName('*');
            Array.prototype.forEach.call(allElements, function(el) {
                var includePath = el.dataset.includePath;
                if (includePath) {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            el.outerHTML = this.responseText;
                        }
                    };
                    xhttp.open('GET', includePath, true);
                    xhttp.send();
                }
            });
    });



$(function(){
   var $firstmenu = $('.center_nav > ul > li.dep1'),
       $header = $('.subdeps01');
    $firstmenu.mouseenter(function(){
       $header.stop().animate({height:'80px'},200);
    })
    .mouseleave(function(){
        $header.stop().animate({height:'0px'},200);
    })
    
    
});

$(function(){
   var $firstmenu = $('.center_nav > ul > li.dep2'),
       $header = $('.subdeps02');
    $firstmenu.mouseenter(function(){
       $header.stop().animate({height:'80px'},200);
    })
    .mouseleave(function(){
        $header.stop().animate({height:'0px'},200);
    })
    
    
});

$(function(){
   var $firstmenu = $('.center_nav > ul > li.dep3'),
       $header = $('.subdeps03');
    $firstmenu.mouseenter(function(){
       $header.stop().animate({height:'80px'},200);
    })
    .mouseleave(function(){
        $header.stop().animate({height:'0px'},200);
    })
    
    
});

$(function(){
   var $firstmenu = $('.center_nav > ul > li.dep4'),
       $header = $('.subdeps04');
    $firstmenu.mouseenter(function(){
       $header.stop().animate({height:'80px'},200);
    })
    .mouseleave(function(){
        $header.stop().animate({height:'0px'},200);
    })
    
    
});

$(function(){
   var $firstmenu = $('.center_nav > ul > li.dep5'),
       $header = $('.subdeps05');
    $firstmenu.mouseenter(function(){
       $header.stop().animate({height:'80px'},200);
    })
    .mouseleave(function(){
        $header.stop().animate({height:'0px'},200);
    })
    
    
});

$(function(){
   var $firstmenu = $('.center_nav > ul > li.dep6'),
       $header = $('.subdeps06');
    $firstmenu.mouseenter(function(){
       $header.stop().animate({height:'80px'},200);
    })
    .mouseleave(function(){
        $header.stop().animate({height:'0px'},200);
    })
    
    
});



$(function() {
    $(".login_btn").click(function () {
    	if($('.login_box').hasClass('atv')) {

                $('.login_box').removeClass('atv');

                

          } else {

                $('.login_box').addClass('atv');


          }
    });
    
    
    
});

// 텍스트 파일 읽기
function readText(callback){
    const target = event.target;
    // 선택된 파일 참조
    const files = target.files;
    // 배열 타입이므로 0번째 파일 참조
    const file = files[0]

    // FileReader 인스턴스 생성
    const reader = new FileReader();
    // 읽기 작업 완료
    reader.addEventListener('load', () => {
        // 요소에 결과 출력
        // pEl.textContent = reader.result;
        callback(reader.result);
    });
    // 텍스트 파일 형식으로 읽어오기
    reader.readAsText(file);
}

// 엑셀 파일 읽기
function readExcel(callback) {
    let input = event.target;
    let reader = new FileReader();
    reader.onload = function () {
        let data = reader.result;
        let workBook = XLSX.read(data, { type: 'binary' });
        workBook.SheetNames.forEach(function (sheetName) {
            // console.log('SheetName: ' + sheetName);
            let rows = XLSX.utils.sheet_to_json(workBook.Sheets[sheetName]);
            callback(rows);
        })
    };
    reader.readAsBinaryString(input.files[0]);
}

// 핸드폰 번호 정규식
function isHpFormat(hp){
	var patternPhone = /^(?:(010-\d{4})|(01[1|6|7|8|9]-\d{3,4}))-(\d{4})$/;

   //둘중에 하나골라 쓰면 된다.
   console.log(patternPhone.test(hp))
    if(!patternPhone.test(hp)) return false;
    return true;
}

