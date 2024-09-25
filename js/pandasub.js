function hideLoadingSpinner() {
    $('.spinner-background').hide();
    $('.loadingio-spinner-spin-2by998twmg8').hide();
}
function showLoadingSpinner() {
    $('.spinner-background').show();
    $('.loadingio-spinner-spin-2by998twmg8').show();
}
function formatCost(cost) {
    return parseFloat(cost).toFixed(2);
}
$(document).ready(function() {
    $('.sendResultDataRow').on('click', function() {
        console.log(11);

        try {
            var idx = $(this).data('id');

            if (!idx) {
                throw new Error('ID 값이 올바르지 않습니다.');
            }
            var byteCount =       $('.byteCount')
            var sendContentBoxBodyp =       $('.sendContentBoxBody p')
            var rowDataSmsType =            $('.rowDataSmsType');
            var rowDataTitle =              $('.rowDataTitle');
            var rowDataUsePoint =           $('.rowDataUsePoint')
            var rowDataUseSumPoint =        $('.rowDataUseSumPoint')
            var rowDataTotSendCnt =         $('.rowDataTotSendCnt')
            var rowDataSuccesSendCnt =      $('.rowDataSuccesSendCnt')
            var rowDataFaileTotSendCnt =    $('.rowDataFaileTotSendCnt')
            var rowDataMoreTotSendCnt =     $('.rowDataMoreTotSendCnt')
            var sendContentBoxBodyImg =     $('.sendContentBoxBody img')
            showLoadingSpinner();
            $.ajax({
                url: "./send_detail.php",
                type: "GET",
                data: {
                    idx: idx,
                },
                async: true,
                dataType: "json",
                success: function(data) {
                    hideLoadingSpinner();
                    // 서버에서 데이터를 정상적으로 받았을 때
                    console.log(data);
                    sendContentBoxBodyp.html(data[0].sms_content)
                    byteCount.text(data[0].content_length + ' byte')
                    rowDataSmsType.text(data[0].sms_type)
                    rowDataTitle.text(data[0].sms_title)
                    if (data[0].sms_type === 'sms') {
                        rowDataUsePoint.text(formatCost(data[0].sms_cost));
                    } else if (data[0].sms_type === 'lms') {
                        rowDataUsePoint.text(formatCost(data[0].lms_cost));
                    } else if (data[0].sms_type === 'mms') {
                        rowDataUsePoint.text(formatCost(data[0].mms_cost));
                    }
                    if (data[0].sms_type === 'sms') {
                        rowDataUseSumPoint.text(formatCost(data[0].fail_sms_cost));
                    } else if (data[0].sms_type === 'lms') {
                        rowDataUseSumPoint.text(formatCost(data[0].fail_lms_cost));
                    } else if (data[0].sms_type === 'mms') {
                        rowDataUseSumPoint.text(formatCost(data[0].fail_mms_cost));
                    }
                    if(data[0].ffilepath){
                        sendContentBoxBodyImg.attr('src', '/upload_file/sms/img_thumb/'+data[0].file_chg);
                    }

                    rowDataTotSendCnt.text(data[0].receive_cnt_tot)
                    rowDataSuccesSendCnt.text(data[0].receive_cnt_suc)
                    rowDataFaileTotSendCnt.text(data[0].receive_cnt_fail)
                    rowDataMoreTotSendCnt.text(data[0].receive_cnt_tot - data[0].receive_cnt_suc-data[0].receive_cnt_fail)
                },
                error: function(xhr, status, error) {
                    hideLoadingSpinner();
                    // AJAX 요청 중 오류가 발생했을 때
                    console.error("AJAX 요청 중 오류 발생: ", error);
                    console.error("응답 상태: ", status);
                    console.error("서버 응답: ", xhr.responseText);
                    alert('데이터를 가져오는 중 오류가 발생했습니다. 다시 시도해 주세요.');
                },

            });
        } catch (e) {
            // JavaScript 예외 처리
            console.error("예외 발생: ", e.message);
            alert("문제가 발생했습니다: " + e.message);
        }
    });
});