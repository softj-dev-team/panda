
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
function closeAllPopup() {
    $('.popup-layer').hide();
}
$(document).on('click','#downloadExcelSucc',function() {
    var idx =$(this).attr('data-id');
    window.location.href=`/kakao/index.php?route=excelDownload&idx=${idx}&downloadSuccess=false`
});
$(document).on('click','#downloadExcelFail',function() {
    var idx =$(this).attr('data-id');
    window.location.href=`/kakao/index.php?route=excelDownload&idx=${idx}&downloadSuccess=true`
});
$(document).ready(function() {
    $('.sendResultDataRow').on('click', function() {


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
            var downloadExcelSucc =         $('#downloadExcelSucc')
            var downloadExcelFail =         $('#downloadExcelFail')
            showLoadingSpinner();


            $.ajax({
                url: '/kakao/index.php?route=getSendListDetail',
                type: "GET",
                data: {
                    idx: idx,
                },
                async: true,
                dataType: "json",
                success: function(response) {
                    hideLoadingSpinner();
                    // 서버에서 데이터를 정상적으로 받았을 때
                    console.log(response.data);
                    var smsSave =response.data.smsSave[0]
                    var sum =response.data.sum[0]
                    var contentWithLineBreaks =smsSave.sms_content.replace(/\n/g, '<br>');
                    sendContentBoxBodyp.html(contentWithLineBreaks);
                    downloadExcelSucc.attr('data-id',smsSave.save_idx)
                    downloadExcelFail.attr('data-id',smsSave.save_idx)
                    byteCount.text(smsSave.sms_content_length + ' byte');
                    rowDataSmsType.text(smsSave.sms_type)
                    rowDataTitle.text(smsSave.sms_title)

                    if (smsSave.sms_type === 'sms') {
                        rowDataUsePoint.text(formatCost(sum.sms_cost));
                    } else if (smsSave.sms_type === 'lms') {
                        rowDataUsePoint.text(formatCost(sum.lms_cost));
                    } else if (smsSave.sms_type=== 'mms') {
                        rowDataUsePoint.text(formatCost(sum.mms_cost));
                    }
                    if (smsSave.sms_type === 'sms') {
                        rowDataUseSumPoint.text(formatCost(sum.success_sms_cost));
                    } else if (smsSave.sms_type=== 'lms') {
                        rowDataUseSumPoint.text(formatCost(sum.success_lms_cost));
                    } else if (smsSave.sms_type === 'mms') {
                        rowDataUseSumPoint.text(formatCost(sum.success_mms_cost));
                    }
                    if(smsSave.file_chg){
                        sendContentBoxBodyImg.show();
                        sendContentBoxBodyImg.attr('src', '/upload_file/sms/img_thumb/'+smsSave.file_chg);
                        // 이미지 로딩 실패 시 404 처리
                        sendContentBoxBodyImg.on('error', function() {
                            // 이미지 로딩 실패 시, 이미지 숨기기 또는 대체 이미지 표시
                            sendContentBoxBodyImg.hide();  // 이미지 숨기기
                            // 혹은 대체 이미지를 설정하고 싶다면
                            // sendContentBoxBodyImg.attr('src', '/path/to/default_image.jpg');
                        });
                    }else{
                        sendContentBoxBodyImg.hide();
                    }

                    rowDataTotSendCnt.text(Number(smsSave.tot_cnt).toLocaleString())
                    rowDataSuccesSendCnt.text(Number(sum.receive_cnt_suc).toLocaleString())
                    rowDataFaileTotSendCnt.text(Number(sum.receive_cnt_fail).toLocaleString())
                    rowDataMoreTotSendCnt.text(Number(sum.receive_cnt_tot - sum.receive_cnt_suc-sum.receive_cnt_fail).toLocaleString())
                    $('#sendListPopupLayer').show()

                    var popupTable = new Tabulator("#data-table", {
                        data: response.data.saveCall, // table.getData()로 가져온 데이터를 사용
                        layout: "fitColumns",
                        pagination: "true",  // 로컬 페이징
                        paginationSize: 10,   // 20건씩 표시
                        selectable: true,  // 다중 선택 가능
                        selectableRangeMode: "drag",  // 마우스 드래그로 범위 선택
                        selectablePersistence: false,  // 페이징 변경 후에도 선택 상태 유지
                        selectableRollingSelection:true,
                        selectableCheck: function(row) {
                            // 모든 행을 선택 가능하게 설정
                            return true;
                        },
                        columns: [
                            { title: "전송일시", field: "work_date", sorter: "string" },
                            { title: "발신번호", field: "cell_send", sorter: "string" },
                            { title: "수신번호", field: "cell", sorter: "string" },
                            { title: "통신사", field: "isp", sorter: "string" },
                            { title: "결과코드", field: "status", sorter: "string" },

                        ],
                    });
                    $(document).on('click','#searchBt',function (){
                        popupTable.setFilter(filter,'like', valueEl.value);
                    })
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