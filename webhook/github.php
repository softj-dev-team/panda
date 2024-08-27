<?php
//26
$payload = file_get_contents('php://input');

// 시크릿 키가 설정된 경우 서명 검증
$secret = '!1qazsoftj'; // 설정한 시크릿 키
$signature = 'sha1=' . hash_hmac('sha1', $payload, $secret);

if (hash_equals($signature, $_SERVER['HTTP_X_HUB_SIGNATURE'])) {
    // 서명이 유효한 경우
    $data = json_decode($payload, true);

    // 웹훅 이벤트 이름 가져오기
    $event = $_SERVER['HTTP_X_GITHUB_EVENT'];

    // 이벤트에 따라 처리할 로직 작성
    switch ($event) {
        case 'push':
            // 예: 푸시 이벤트 처리
            file_put_contents('file/push_log.txt', print_r($data, true), FILE_APPEND);
            // Shell 스크립트를 실행하여 git pull 수행
            $output = shell_exec('/usr/bin/sudo /home/asssahcom9/webhook/git_pull.sh 2>&1');
            file_put_contents('file/git_pull_log.txt', $output, FILE_APPEND);

            break;
        case 'pull_request':
            // 예: 풀 리퀘스트 이벤트 처리
            file_put_contents('pr_log.txt', print_r($data, true), FILE_APPEND);
            break;
        // 다른 이벤트들에 대한 처리 로직 추가 가능
        default:
            // 기타 이벤트 처리
            file_put_contents('other_events_log.txt', print_r($data, true), FILE_APPEND);
            break;
    }

    // 응답
    http_response_code(200);
    echo 'Webhook received';
} else {
    // 서명이 유효하지 않은 경우
    http_response_code(403);
    echo 'Invalid signature';
}
?>
