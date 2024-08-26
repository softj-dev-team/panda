<?
include "../../pro_inc/include_default.php"; // 공통함수 인클루드 
include "../include/admin_header.php"; // 관리자페이지 헤더
exit;
/* INIcancel.php
 *
 * 이미 승인된 지불을 취소한다.
 * 은행계좌 이체 , 무통장입금은 이 모듈을 통해 취소 불가능.
 *  [은행계좌이체는 상점정산 조회페이지 (https://iniweb.inicis.com)를 통해 취소 환불 가능하며, 무통장입금은 취소 기능이 없습니다.]  
 *  
 * Date : 2007/09
 * Author : ts@inicis.com
 * Project : INIpay V5.0 for PHP
 * 
 * http://www.inicis.com
 * Copyright (C) 2007 Inicis, Co. All rights reserved.
 */


	/**************************
	 * 1. 라이브러리 인클루드 *
	 **************************/
	require(INIPAY_HOME."/libs/INILib.php");
	
	/***************************************
	 * 2. INIpay41 클래스의 인스턴스 생성 *
	 ***************************************/
	$inipay = new INIpay50;
	
	/*********************
	 * 3. 취소 정보 설정 *
	 *********************/
  	$inipay->SetField("inipayhome", INIPAY_HOME); // 이니페이 홈디렉터리(상점수정 필요)
  	$inipay->SetField("type", "cancel");                            // 고정 (절대 수정 불가)
  	$inipay->SetField("debug", "true");                             // 로그모드("true"로 설정하면 상세로그가 생성됨.)
	$inipay->SetField("mid", "INIpayTest");                                 // 상점아이디
 	/**************************************************************************************************
     * admin 은 키패스워드 변수명입니다. 수정하시면 안됩니다. 1111의 부분만 수정해서 사용하시기 바랍니다.
     * 키패스워드는 상점관리자 페이지(https://iniweb.inicis.com)의 비밀번호가 아닙니다. 주의해 주시기 바랍니다.
     * 키패스워드는 숫자 4자리로만 구성됩니다. 이 값은 키파일 발급시 결정됩니다.
     * 키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오.
     **************************************************************************************************/
	$inipay->SetField("admin", "1111");                            
	$inipay->SetField("tid", "INIMX_DBNKINIpayTest20160510154232531938");                                 // 취소할 거래의 거래아이디
	$inipay->SetField("cancelmsg", iconv("UTF-8", "EUC-KR", $cancel_memo));                           // 취소사유

	/****************
	 * 4. 취소 요청 *
	 ****************/
	$inipay->startAction();
	
	
	/****************************************************************
	 * 5. 취소 결과                                           	*
	 *                                                        	*
	 * 결과코드 : $inipay->getResult('ResultCode') ("00"이면 취소 성공)  	*
	 * 결과내용 : $inipay->getResult('ResultMsg') (취소결과에 대한 설명) 	*
	 * 취소날짜 : $inipay->getResult('CancelDate') (YYYYMMDD)          	*
	 * 취소시각 : $inipay->getResult('CancelTime') (HHMMSS)            	*
	 * 현금영수증 취소 승인번호 : $inipay->getResult('CSHR_CancelNum')    *
	 * (현금영수증 발급 취소시에만 리턴됨)                          * 
	 ****************************************************************/

	echo $inipay->getResult('ResultCode');

