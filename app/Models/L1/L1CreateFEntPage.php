<?php

namespace App\Models\L1;

use App\config\Consts\LocalMenuSettings;
use App\config\Consts\TenrikuConst;
use App\config\Consts\LocalCorporationConst;
use App\Core\Logger\Logger;
use App\Models\FEnt\FEntConfig;
use App\Models\FEnt\FEntPage;
use App\Models\L1\Msg\MsgL1CreateFEntPage;
use App\Util\UtilHttpRequest;
use DateTime;
use DateTimeZone;
use ErrorException;
use Illuminate\Http\JsonResponse;

class L1CreateFEntPage extends L1Abstract
{

    /**
     * L1CreateFEntPage constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws ErrorException
     */
    protected function exec(MsgL1CreateFEntPage $msg){

        $unixTime = time();
        $timeZone = new DateTimeZone('Asia/Tokyo');
        $time = new DateTime();
        $time->setTimestamp($unixTime)->setTimezone($timeZone);
        $formattedTime = $time->format('YmdHis');
        $version = env('APP_VERSION', $formattedTime);

        $fEntPage = new FEntPage();
        $fEntPage->noindex = false;
        $fEntPage->version = $version;

        $endpoint = env('API_BASE_URL'). '/corporation';
        $token = UtilHttpRequest::getToken();
        $result = UtilHttpRequest::cUrlRequest(TenrikuConst::$HTTP_METHOD_GET, $endpoint, $token);
        if(!$result){
            Logger::errorTrace('Error API connect to:', [$endpoint]);
            $msg->_c = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
            $msg->_m = '設定情報が取得できませんでした。';
            return;
        }

        $response = json_decode($result, true);
        if($response['code'] != JsonResponse::HTTP_OK){
            $msg->_c = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
            $msg->_m = '設定情報が取得できませんでした。';
            return;
        }

        $configJson = $response['data'];
        $fEntConfig = new FEntConfig();
        $fEntConfig->clientId = $configJson['clientId'];
        $fEntConfig->corporations = $configJson['corporations'];
        $fEntConfig->frontendSettings = $configJson['frontendSettings'];
        $fEntConfig->backendSettings = $configJson['backendSettings'];

        //local環境の場合の設定書き換え
        if (env('APP_ENV') === 'local') {
            $fEntConfig->corporations[0]['tel'] = LocalCorporationConst::TEL;
            $fEntConfig->corporations[0]['fax'] = LocalCorporationConst::FAX;
            $fEntConfig->corporations[0]['zip'] = LocalCorporationConst::ZIP;
            $fEntConfig->corporations[0]['address'] = LocalCorporationConst::ADDRESS;
            $fEntConfig->corporations[0]['applyTel'] = LocalCorporationConst::APPLY_TEL;
            $fEntConfig->corporations[0]['description'] = LocalCorporationConst::DESCRIPTION;
            $fEntConfig->corporations[0]['corpFullName'] = LocalCorporationConst::CORP_FULL_NAME;
            $fEntConfig->corporations[0]['corpShortName'] = LocalCorporationConst::CORP_SHORT_NAME;

            $fEntConfig->corporations[0]['form']['entry']['type'] = "sendMail";
            $fEntConfig->corporations[0]['form']['entry']['jobId'] = null;
          
            $fEntConfig->frontendSettings['title'] = LocalCorporationConst::SITE_TITLE;
            $fEntConfig->frontendSettings['policy']['url'] = LocalCorporationConst::PRIVACY_URL;

            $headerMenuList = array();
            $footerMenuList = array();

            $localHeaderSettings = LocalMenuSettings::HEADER_LIST;
            foreach($localHeaderSettings As $index => $header) {
                $headerMenuList[$index]['url'] = $header['url'];
                $headerMenuList[$index]['class'] = $header['class'];
                $headerMenuList[$index]['label']['top']['text'] = $header['text'];
                $headerMenuList[$index]['target'] = $header['target'];

                //下部テキスト制御
                if(isset($header['bottom'])) {
                    $headerMenuList[$index]['label']['top']['class'] = 'letterTop';
                    $headerMenuList[$index]['label']['bottom']['class'] = 'letterBottom';
                    $headerMenuList[$index]['label']['bottom']['text'] = $header['bottom'];
                }

                //お気に入りリンク制御
                if(str_contains($header['class'], 'navItemFavorite') !== false) {
                    $headerMenuList[$index]['label']['top']['text'] = "";
                    $headerMenuList[$index]['label']['top']['class'] = "letterTop heartBox";
                    $headerMenuList[$index]['label']['bottom']['text'] = $header['text'];
                    $headerMenuList[$index]['label']['bottom']['class'] = "letterBottom";
                }
            }

            $localFooterSettings = LocalMenuSettings::FOOTER_LIST;
            foreach($localFooterSettings As $index => $footer) {
                $footerMenuList[$index]['url'] = $footer['url'];
                $footerMenuList[$index]['class'] = $footer['class'];
                $footerMenuList[$index]['text'] = $footer['text'];
                $footerMenuList[$index]['target'] = $footer['target'];
            }


            $fEntConfig->frontendSettings['nav'] = $headerMenuList;
            $fEntConfig->frontendSettings['footer']['nav'] = $footerMenuList;
            $fEntConfig->frontendSettings['footer']['corpInfo']['text'] = LocalCorporationConst::CORP_FULL_NAME;

        }
      
        if (env('APP_ENV') === 'local') {
            $backendjsonPath = base_path('tests/Json/backend_config.json');
            $fEntConfig->backendSettings = json_decode(file_get_contents($backendjsonPath), true);
        }

        $arrayKeyword = array();
        $corporationInfo = $fEntConfig->corporations[0];
        $arrayKeyword[] = $corporationInfo['corpFullName'] ?? '';
        $keywords = implode(",", $arrayKeyword);
        if(strpos($keywords, ',')) {
            $keywords = preg_replace(",,", ",", $keywords);
        }
        $fEntPage->keywords = $keywords;

        $title = $fEntConfig->frontendSettings['title'] ?? $corporationInfo['title'] ?? '';
        $fEntPage->title = $title . TenrikuConst::$HEADER_CST_MSG;

        $description = $corporationInfo['description'] ?? '';
        $fEntPage->description .= $description . TenrikuConst::$HEADER_CST_MSG;

        $fEntPage->fEntConfig = $fEntConfig;

        $msg->_c = JsonResponse::HTTP_OK;
        $msg->_m = 'ページ情報の生成が完了しました。';
        $msg->fEntPage = $fEntPage;
    }
}
