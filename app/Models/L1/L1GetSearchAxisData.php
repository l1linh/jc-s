<?php

namespace App\Models\L1;

use App\Core\Logger\Logger;
use App\Models\Constant\Cst;
use App\Models\FEnt\FEntSearchAxis;
use App\Models\FEnt\FEntSearchAxisKbn;
use App\Models\FEnt\FEntSearchAxisData;
use App\Models\L1\Msg\MsgL1GetSearchAxisData;
use Symfony\Component\HttpFoundation\Response;
use App\Util\UtilHttpRequest;

/**
 * Class L1GetJobDetail
 * @package App\Models\L1
 */
class L1GetSearchAxisData extends L1Abstract
{

    /**
     * L1GetSearchAxisData constructor.
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * @param MsgL1GetSearchAxisData $msg
     * @throws \Exception
     */
    protected function exec(MsgL1GetSearchAxisData $msg)
    {
        // 初期化
        $msg->_m = null;
        $msg->_c = null;

        $fEntSearchAxisData = new FEntSearchAxisData();
        $msg->fEntSearchAxisData = $fEntSearchAxisData;

        $frontendSettings = $msg->frontendSettings;

        if(!$frontendSettings) {
            $fEntSearchAxisData->isSuccessGetAxis = false;

            $msg->_c = Cst::OUTPUT_ERROR;
            $msg->_m = '企業設定情報に不備があります。';
            $msg->fEntSearchAxisData = $fEntSearchAxisData;
            return;
        }

        //必須検索軸情報の取得
        $arrayConfigRequiredAxis = $frontendSettings['searchAxisRequiredList'];

        $arrayRequiredAxis = array();

        foreach($arrayConfigRequiredAxis As $configRequiredAxis) {

            //カスタム検索軸の場合はAPIからの取得をスキップする
            if($configRequiredAxis === 'area' || $configRequiredAxis === 'pref' || $configRequiredAxis === 'city') {
                if($frontendSettings['isCustomArea']) {
                    continue;
                }
            }
            if($configRequiredAxis === 'jobbc' || $configRequiredAxis === 'job') {
                if($frontendSettings['isCustomJob']) {
                    continue;
                }
            }

            $arrayRequiredAxis[] = $configRequiredAxis;
        }


        //APIからの検索軸取得
        $searchAxisApiResult = array();
        $token = UtilHttpRequest::getToken();

        foreach($arrayRequiredAxis As $requiredAxis) {

            // endpointの指定
            $endpoint = env('API_BASE_URL') . "/schema/search/" . $requiredAxis;
            //---CURL Request
            $result = UtilHttpRequest::cUrlRequest("GET", $endpoint, $token);

            //JSONが存在しない場合、あるいはresponseのcode,messageが存在しない場合は$resultがfalseで返ってくる
            if(!$result) {
                Logger::errorTrace('Error API connect to:', [$endpoint]);
                $fEntSearchAxisData->fEntSearchAxis = $requiredAxis;
                $fEntSearchAxisData->isSuccessGetAxis = false;
                $fEntSearchAxisData->isCustomSearch = false;

                $msg->_c = Cst::OUTPUT_ERROR;
                $msg->_m = '検索軸データに不備があります。axis_name=' . $requiredAxis;
                $msg->fEntSearchAxisData = $fEntSearchAxisData;
                return;
            }

            $ary = json_decode($result);

            if($ary->code != Response::HTTP_OK) {

                $fEntSearchAxisData->fEntSearchAxis = $requiredAxis;
                $fEntSearchAxisData->isSuccessGetAxis = false;
                $fEntSearchAxisData->isCustomSearch = false;

                $msg->_c = Cst::OUTPUT_ERROR;
                $msg->_m = '検索軸データに不備があります。axis_name=' . $requiredAxis;
                $msg->fEntSearchAxisData = $fEntSearchAxisData;
                return;
            }

            $searchAxisApiResult[$requiredAxis] = ($ary->data->$requiredAxis) ?? array();
        }

        $arraySearchAxis = array();
        $fEntSearchAxis = new FEntSearchAxis();

        //一時格納用配列の初期化処理
        foreach($fEntSearchAxis as $key => $value) {
            $arraySearchAxis[$key] = $value;
        }

        $koyParams = array(
            '1' => 'rs',
            '2' => 'ks',
            '3' => 'ap',
            '4' => 'hs',
            '5' => 'sy',
            '6' => 'th',
            '7' => 'js',
            '8' => 'is',
            '9' => 'gi',
            '99' => 'ss',
        );

        //APIからの取得検索軸の格納 start

        if(count($searchAxisApiResult)>0 && count($arrayRequiredAxis)>0) {

            foreach($arrayRequiredAxis As $requiredAxis) {
                foreach ($searchAxisApiResult as $axisName => $arrayAxisObject) {

                        if ($axisName !== $requiredAxis) {
                            continue;
                        }

                        switch ($axisName) {
                            case ('city') :

                                $parent = array_column($arrayAxisObject, 'parent');
                                $value = array_column($arrayAxisObject, 'value');
                                array_multisort($parent, SORT_ASC, $value, SORT_ASC, $arrayAxisObject);

                                foreach ($arrayAxisObject as $index => $objectItems) {

                                    $fEntSearchAxisKbn = new FEntSearchAxisKbn();

                                    $fEntSearchAxisKbn->type = 'city';
                                    $fEntSearchAxisKbn->name = $objectItems->name;
                                    if ($objectItems->parent && $objectItems->value) {
                                        $fEntSearchAxisKbn->value = $objectItems->parent . $objectItems->value;
                                    } else {
                                        $arraySearchAxis[$axisName] = null;
                                        break;
                                    }
                                    $fEntSearchAxisKbn->cnt = $objectItems->cnt;
                                    $fEntSearchAxisKbn->parent = $objectItems->parent;

                                    $arraySearchAxis[$axisName][] = $fEntSearchAxisKbn;
                                }
                                break;

                            case('pref') :

                                $value = array_column($arrayAxisObject, 'value');
                                array_multisort($value, SORT_ASC, $arrayAxisObject);

                                //子要素(市区町村)の配列をあらかじめ作成
                                $tmpArrayChildren = array();
                                if($searchAxisApiResult['city']??null && count($searchAxisApiResult['city'])>0) {

                                    $parent = array_column($searchAxisApiResult['city'], 'parent');
                                    $value = array_column($searchAxisApiResult['city'], 'value');
                                    array_multisort($parent, SORT_ASC, $value, SORT_ASC, $searchAxisApiResult['city']);

                                    foreach($searchAxisApiResult['city'] As $childrenObject) {
                                        if (($childrenObject->parent??null)) {
                                            $tmpArrayChildren[$childrenObject->parent][] = $childrenObject;
                                        }
                                    }
                                }

                                foreach ($arrayAxisObject as $index => $objectItems) {

                                    $fEntSearchAxisKbn = new FEntSearchAxisKbn();

                                    $fEntSearchAxisKbn->type = 'area';
                                    $fEntSearchAxisKbn->name = $objectItems->name;
                                    $fEntSearchAxisKbn->value = $objectItems->value;
                                    $fEntSearchAxisKbn->cnt = $objectItems->cnt;
                                    $fEntSearchAxisKbn->parent = $objectItems->parent;

                                    if($objectItems->children ?? null) {
                                        $arrayChildren = array();
                                        foreach ($objectItems->children as $childrenObject) {

                                            $fEntSearchAxisChildrenKbn = new FEntSearchAxisKbn();

                                            $fEntSearchAxisChildrenKbn->type = 'city';
                                            $fEntSearchAxisChildrenKbn->name = $childrenObject->name;
                                            if ($childrenObject->parent && $childrenObject->value) {
                                                $fEntSearchAxisChildrenKbn->value = $childrenObject->parent . $childrenObject->value;
                                            } else {
                                                $arraySearchAxis[$axisName] = null;
                                                break 2;
                                            }
                                            $fEntSearchAxisChildrenKbn->cnt = $childrenObject->cnt;
                                            $fEntSearchAxisChildrenKbn->parent = $childrenObject->parent;
                                            $arrayChildren[] = $fEntSearchAxisChildrenKbn;
                                        }
                                        $fEntSearchAxisKbn->children = $arrayChildren;
                                    }
                                    else {
                                        if(count($tmpArrayChildren)>0) {
                                            $arrayChildren = array();
                                            foreach($tmpArrayChildren As $parentValue => $childrenObject) {
                                                if((int)$parentValue === (int)$objectItems->value) {
                                                    foreach($childrenObject As $children) {
                                                        $fEntSearchAxisChildrenKbn = new FEntSearchAxisKbn();
                                                        $fEntSearchAxisChildrenKbn->type = 'city';
                                                        $fEntSearchAxisChildrenKbn->name = $children->name;
                                                        if ($children->parent && $children->value) {
                                                            $fEntSearchAxisChildrenKbn->value = $children->parent . $children->value;
                                                        } else {
                                                            $arraySearchAxis[$axisName] = null;
                                                            break 3;
                                                        }
                                                        $fEntSearchAxisChildrenKbn->cnt = $children->cnt;
                                                        $fEntSearchAxisChildrenKbn->parent = $children->parent;
                                                        $arrayChildren[] = $fEntSearchAxisChildrenKbn;
                                                    }
                                                }
                                            }
                                            $fEntSearchAxisKbn->children = $arrayChildren;
                                        }
                                    }

                                    $arraySearchAxis[$axisName][] = $fEntSearchAxisKbn;
                                }
                                break;

                            case('koy') :

                                $value = array_column($arrayAxisObject, 'value');
                                array_multisort($value, SORT_ASC, $arrayAxisObject);

                                foreach ($arrayAxisObject as $index => $objectItems) {

                                    $fEntSearchAxisKbn = new FEntSearchAxisKbn();

                                    $fEntSearchAxisKbn->type = 'koy';
                                    $fEntSearchAxisKbn->name = $objectItems->name;
                                    $fEntSearchAxisKbn->value = $koyParams[$objectItems->value];
                                    $fEntSearchAxisKbn->cnt = $objectItems->cnt;
                                    $fEntSearchAxisKbn->parent = $objectItems->parent ?? null;

                                    $arraySearchAxis[$axisName][$index] = $fEntSearchAxisKbn;
                                }
                                break;

                            default :

                                $value = array_column($arrayAxisObject, 'value');

                                if($axisName == 'job') {
                                    $parent = array_column($arrayAxisObject, 'parent');
                                    array_multisort($parent, SORT_ASC, $value, SORT_ASC, $arrayAxisObject);
                                }
                                else {
                                    array_multisort($value, SORT_ASC, $arrayAxisObject);
                                }

                                $tmpArrayChildren = array();

                                if($axisName == 'area') {
                                    //子要素(都道府県)の配列をあらかじめ作成
                                    if($searchAxisApiResult['pref']??null && count($searchAxisApiResult['pref'])>0) {

                                        $parent = array_column($searchAxisApiResult['pref'], 'parent');
                                        $value = array_column($searchAxisApiResult['pref'], 'value');
                                        array_multisort($parent, SORT_ASC, $value, SORT_ASC, $searchAxisApiResult['pref']);

                                        foreach($searchAxisApiResult['pref'] As $childrenObject) {
                                            if (($childrenObject->parent??null)) {
                                                $tmpArrayChildren[$childrenObject->parent][] = $childrenObject;
                                            }
                                        }
                                    }
                                }

                                if($axisName == 'jobbc') {
                                    //子要素(都道府県)の配列をあらかじめ作成
                                    if($searchAxisApiResult['job']??null && count($searchAxisApiResult['job'])>0) {

                                        $parent = array_column($searchAxisApiResult['job'], 'parent');
                                        $value = array_column($searchAxisApiResult['job'], 'value');
                                        array_multisort($parent, SORT_ASC, $value, SORT_ASC, $searchAxisApiResult['job']);

                                        foreach($searchAxisApiResult['job'] As $childrenObject) {
                                            if (($childrenObject->parent??null)) {
                                                if($childrenObject->parent === 8 || $childrenObject->parent === 9) {
                                                    $childrenObject->parent = 9999; //製造・技術に統一
                                                }
                                                $tmpArrayChildren[$childrenObject->parent][] = $childrenObject;
                                            }
                                        }
                                    }
                                }

                                foreach ($arrayAxisObject as $index => $objectItems) {

                                    $fEntSearchAxisKbn = new FEntSearchAxisKbn();

                                    if($axisName == 'area') {
                                        $fEntSearchAxisKbn->type = 'bc';
                                    }
                                    else {
                                        $fEntSearchAxisKbn->type = $axisName;
                                    }

                                    if($axisName == 'jobbc') {
                                        if($objectItems->value === 8 || $objectItems->value === 9) { //製造・技術
                                            $objectItems->value = 9999; //親要素統一

                                            $objectItems->name = '製造・技術';
                                        }
                                    }

                                    $fEntSearchAxisKbn->name = $objectItems->name;
                                    $fEntSearchAxisKbn->value = $objectItems->value;
                                    $fEntSearchAxisKbn->cnt = $objectItems->cnt;
                                    $fEntSearchAxisKbn->parent = $objectItems->parent ?? null;

                                    if ($objectItems->children ?? null) {
                                        $arrayChildren = array();
                                        foreach ($objectItems->children as $childrenObject) {

                                            $fEntSearchAxisChildrenKbn = new FEntSearchAxisKbn();

                                            if($axisName == 'jobbc') {
                                                $fEntSearchAxisChildrenKbn->type = 'job';
                                            }
                                            elseif($axisName == 'area') {
                                                $fEntSearchAxisChildrenKbn->type = 'area'; //都道府県検索用のtype
                                            }
                                            else {
                                                $fEntSearchAxisKbn->type = $axisName;
                                            }
                                            $fEntSearchAxisChildrenKbn->name = $childrenObject->name;
                                            $fEntSearchAxisChildrenKbn->value = $childrenObject->value;
                                            $fEntSearchAxisChildrenKbn->cnt = $childrenObject->cnt;
                                            $fEntSearchAxisChildrenKbn->parent = $childrenObject->parent;
                                            $arrayChildren[] = $fEntSearchAxisChildrenKbn;
                                        }
                                        $fEntSearchAxisKbn->children = $arrayChildren;
                                    }
                                    else {
                                        if(count($tmpArrayChildren)>0) {

                                            $arrayChildren = array();
                                            foreach($tmpArrayChildren As $parentValue => $childrenObject) {
                                                if((int)$parentValue === (int)$objectItems->value) {
                                                    foreach($childrenObject As $children) {
                                                        $fEntSearchAxisChildrenKbn = new FEntSearchAxisKbn();
                                                        if($axisName == 'jobbc') {
                                                        $fEntSearchAxisChildrenKbn->type = 'job';
                                                        }
                                                        elseif($axisName == 'area') {
                                                        $fEntSearchAxisChildrenKbn->type = 'area'; //都道府県検索用のtype
                                                        }
                                                        else {
                                                            $fEntSearchAxisKbn->type = $axisName;
                                                        }
                                                        $fEntSearchAxisChildrenKbn->name = $children->name;
                                                        $fEntSearchAxisChildrenKbn->value = $children->value;
                                                        $fEntSearchAxisChildrenKbn->cnt = $children->cnt;
                                                        $fEntSearchAxisChildrenKbn->parent = $children->parent;
                                                        $arrayChildren[] = $fEntSearchAxisChildrenKbn;
                                                    }
                                                }
                                            }
                                            $fEntSearchAxisKbn->children = $arrayChildren;
                                        }
                                    }
                                    $arraySearchAxis[$axisName][$index] = $fEntSearchAxisKbn;
                                }
                                break;
                        }

                        if($axisName === 'jobbc') {
                            //完全一致している配列がある場合は削除
                            $tmpArray = array();
                            $newArray = array();

                            foreach($arraySearchAxis[$axisName] as $index => $axisList){
                                if(!in_array($axisList->value , $tmpArray)) {
                                    $tmpArray[] = $axisList->value;
                                    $newArray[] = $axisList;
                                }
                            }
                            $arraySearchAxis[$axisName] = $newArray; //再格納
                        }

                        //APIから取得する必須検索軸の判定
                        if($arraySearchAxis[$axisName] === null && (count($arrayAxisObject) > 0)) {
                            $fEntSearchAxisData->fEntSearchAxis = $fEntSearchAxis;
                            $fEntSearchAxisData->isSuccessGetAxis = false;
                            $fEntSearchAxisData->isCustomSearch = false;

                            $msg->_c = Cst::OUTPUT_ERROR;
                            $msg->_m = '検索軸データに不備があります。axis_name=' . $axisName;
                            $msg->fEntSearchAxisData = $fEntSearchAxisData;
                            return;
                        }
                    }
            }
        }
        //APIからの取得検索軸の格納 end

        //カスタム検索軸の格納　start

        $isCustomSearch = false;
        $isCustomArea = false;
        $isCustomJob = false;

        //APIから取得済みの検索軸は判定から除外する
        $arrayCustomRequiredAxis = array_diff($arrayConfigRequiredAxis, $arrayRequiredAxis);

        if(count($arrayCustomRequiredAxis)>0) {
            foreach($arrayCustomRequiredAxis As $customRequiredAxis) {

                if($customRequiredAxis !== 'area' && $customRequiredAxis !== 'pref' && $customRequiredAxis !== 'city' &&
                    $customRequiredAxis !== 'jobbc' && $customRequiredAxis !== 'job')
                {
                    continue;
                }

                $customSectionsList = array();
                $axisType = null;

                //独自エリア判定
                if($customRequiredAxis === 'area' || $customRequiredAxis === 'pref' || $customRequiredAxis === 'city') {
                    if($frontendSettings['isCustomArea']) {

                        $customAreaSections = $frontendSettings['customAreaSections']??null;

                        if($customAreaSections && count($customAreaSections)>0) {

                            foreach($customAreaSections As $customArea) {
                                $axisType = $customArea['type']??null;

                                if(($axisType !== 'area' && $axisType !== 'pref' && $axisType !== 'city')) {
                                    $fEntSearchAxisData->fEntSearchAxis = $fEntSearchAxis;
                                    $fEntSearchAxisData->isSuccessGetAxis = false;
                                    $fEntSearchAxisData->isCustomSearch = true;
                                    $fEntSearchAxisData->isCustomArea = true;

                                    $msg->_c = Cst::OUTPUT_ERROR;
                                    $msg->_m = 'カスタムエリア検索軸データのTypeに不備があります。';
                                    $msg->fEntSearchAxisData = $fEntSearchAxisData;
                                    return;
                                }
                                $customSectionsList[$axisType] = $customArea['list']??null;
                            }
                        }
                        $isCustomSearch = true;
                        $isCustomArea = true;
                    }
                }
                //独自職種判定
                if($customRequiredAxis === 'jobbc' || $customRequiredAxis === 'job') {
                    if($frontendSettings['isCustomJob']) {

                        $customJobSections = $frontendSettings['customJobSections']??null;

                        if($customJobSections && count($customJobSections)>0) {

                            foreach($customJobSections As $customJob) {
                                $axisType = $customJob['type']??null;

                                if(($axisType !== 'jobbc' && $axisType !== 'job')) {
                                    $fEntSearchAxisData->fEntSearchAxis = $fEntSearchAxis;
                                    $fEntSearchAxisData->isSuccessGetAxis = false;
                                    $fEntSearchAxisData->isCustomSearch = true;
                                    $fEntSearchAxisData->isCustomJob = true;

                                    $msg->_c = Cst::OUTPUT_ERROR;
                                    $msg->_m = 'カスタム職種検索軸データのTypeに不備があります。';
                                    $msg->fEntSearchAxisData = $fEntSearchAxisData;
                                    return;
                                }
                                $customSectionsList[$axisType] = $customJob['list']??null;
                            }
                        }
                        $isCustomSearch = true;
                        $isCustomJob = true;
                    }
                }

                //格納処理
                foreach($customSectionsList As $customAxisName => $customSections) {
                    if($customSections && count($customSections)>0) {
                        foreach($customSections As $index => $axisItems) {

                            if(!((isset($axisItems['type'])&&$axisItems['type']) &&
                                (isset($axisItems['name'])&&$axisItems['name']) &&
                                (isset($axisItems['value'])&&$axisItems['value']))) {
                                //カスタム検索軸の情報が正常に取得できないためエラーを返す

                                $fEntSearchAxisData->fEntSearchAxis = $fEntSearchAxis;
                                $fEntSearchAxisData->isSuccessGetAxis = false;
                                $fEntSearchAxisData->isCustomSearch = true;

                                $msg->_c = Cst::OUTPUT_ERROR;
                                $msg->_m = 'カスタム検索軸データの親要素に不備があります。axis_name=' . $customAxisName;
                                $msg->fEntSearchAxisData = $fEntSearchAxisData;
                                return;
                            }

                            if($customAxisName !== 'city' && $customAxisName !== 'job') {
                                if(!(isset($axisItems['children'])&&$axisItems['children'])) {
                                    //子要素の値が必要だが取得できないためエラーを返す

                                    $fEntSearchAxisData->fEntSearchAxis = $fEntSearchAxis;
                                    $fEntSearchAxisData->isSuccessGetAxis = false;
                                    $fEntSearchAxisData->isCustomSearch = true;

                                    $msg->_c = Cst::OUTPUT_ERROR;
                                    $msg->_m = 'カスタム検索軸データの子要素が存在しません。axis_name=' . $customAxisName;
                                    $msg->fEntSearchAxisData = $fEntSearchAxisData;
                                    return;
                                }
                            }

                            $fEntSearchAxisKbn = new FEntSearchAxisKbn();

                            $fEntSearchAxisKbn->type = $axisItems['type'];
                            $fEntSearchAxisKbn->name = $axisItems['name'];
                            $fEntSearchAxisKbn->value = $axisItems['value'];

                            if($axisItems['children']??null) {
                                $arrayChildren = array();
                                foreach ($axisItems['children'] as $childrenItems) {

                                    if(!((isset($childrenItems['type'])&&$childrenItems['type']) &&
                                        (isset($childrenItems['name'])&&$childrenItems['name']) &&
                                        (isset($childrenItems['value'])&&$childrenItems['value']))) {
                                        //カスタム検索軸の情報が正常に取得できないためエラーを返す

                                        $fEntSearchAxisData->fEntSearchAxis = $fEntSearchAxis;
                                        $fEntSearchAxisData->isSuccessGetAxis = false;
                                        $fEntSearchAxisData->isCustomSearch = true;

                                        $msg->_c = Cst::OUTPUT_ERROR;
                                        $msg->_m = 'カスタム検索軸データの子要素に不備があります。axis_name=' . $customAxisName;
                                        $msg->fEntSearchAxisData = $fEntSearchAxisData;
                                        return;
                                    }

                                    $fEntSearchAxisChildrenKbn = new FEntSearchAxisKbn();

                                    $fEntSearchAxisChildrenKbn->type = $childrenItems['type'];
                                    $fEntSearchAxisChildrenKbn->name = $childrenItems['name'];
                                    $fEntSearchAxisChildrenKbn->value = $childrenItems['value'];
                                    $arrayChildren[] = $fEntSearchAxisChildrenKbn;
                                }
                                $fEntSearchAxisKbn->children = $arrayChildren;
                            }

                            $arraySearchAxis[$customAxisName][$index] = $fEntSearchAxisKbn;
                        }
                    }
                    else {
                        if($isCustomSearch) {
                            //カスタム検索のフラグが立っているのに軸の情報が取れていないためエラーを返す

                            $fEntSearchAxisData->fEntSearchAxis = $fEntSearchAxis;
                            $fEntSearchAxisData->isSuccessGetAxis = false;
                            $fEntSearchAxisData->isCustomSearch = true;

                            $msg->_c = Cst::OUTPUT_ERROR;
                            $msg->_m = 'カスタム検索軸データが存在しません。axis_name=' . $customAxisName;
                            $msg->fEntSearchAxisData = $fEntSearchAxisData;
                            return;
                        }
                    }
                }
            }
        }

        //カスタム検索軸の格納 end

        $fEntSearchAxis->area = $arraySearchAxis['area']??null;
        $fEntSearchAxis->pref = $arraySearchAxis['pref']??null;
        $fEntSearchAxis->city = $arraySearchAxis['city']??null;
        $fEntSearchAxis->jobbc = $arraySearchAxis['jobbc']??null;
        $fEntSearchAxis->job = $arraySearchAxis['job']??null;
        $fEntSearchAxis->koy = $arraySearchAxis['koy']??null;
        $fEntSearchAxis->tokucho = $arraySearchAxis['tokucho']??null;
        $fEntSearchAxis->rosen = $arraySearchAxis['rosen']??null;


        //こだわり検索軸の格納 start (必要に応じて) //todo 今回は「特徴」の名称

        $fEntSearchAxis->kodawari = array();
//
        $kodawariParamList = array(

            //人気の特徴
            ["name" => "未経験OK", "value" => "未経験OK", "parent" => 1],
            ["name" => "20・30代活躍中", "value" => "20・30代活躍中", "parent" => 1],
            ["name" => "中高年活躍中", "value" => "中高年活躍中", "parent" => 1],
            ["name" => "車・バイクOK", "value" => "車・バイクOK", "parent" => 1],
            ["name" => "髪型・髪色自由", "value" => "髪型・髪色自由", "parent" => 1],
            ["name" => "髭・ネイル・ピアスOK", "value" => "髭・ネイル・ピアスOK", "parent" => 1],
            ["name" => "服装自由", "value" => "服装自由", "parent" => 1],
            ["name" => "キャンペーン対象求人", "value" => "キャンペーン対象求人", "parent" => 1],

            //勤務形態
            ["name" => "土日休み", "value" => "土日休み", "parent" => 2],
            ["name" => "平日休み", "value" => "平日休み", "parent" => 2],
            ["name" => "２交替・３交替", "value" => "２交替・３交替", "parent" => 2],
            ["name" => "転勤なし", "value" => "転勤なし", "parent" => 2],
            ["name" => "朝遅め", "value" => "朝遅め", "parent" => 2],
            ["name" => "早朝", "value" => "早朝", "parent" => 2],
            ["name" => "夕方以降", "value" => "夕方以降", "parent" => 2],
            ["name" => "深夜勤務", "value" => "深夜勤務", "parent" => 2],
            ["name" => "残業なし", "value" => "残業なし", "parent" => 2],
            ["name" => "残業少なめ", "value" => "残業少なめ", "parent" => 2],
            ["name" => "残業多め（20時間以上）", "value" => "残業多め", "parent" => 2],
            ["name" => "時短勤務OK", "value" => "時短勤務OK", "parent" => 2],
            ["name" => "長期", "value" => "長期", "parent" => 2],
            ["name" => "短期", "value" => "短期", "parent" => 2],
            ["name" => "期間限定", "value" => "期間限定", "parent" => 2],
            ["name" => "社員登用あり", "value" => "社員登用あり", "parent" => 2],
            ["name" => "紹介予定派遣", "value" => "紹介予定派遣", "parent" => 2],
            ["name" => "直接雇用実績あり", "value" => "直接雇用実績あり", "parent" => 2],
            ["name" => "副業・ＷワークOK", "value" => "副業・ＷワークOK", "parent" => 2],

            //福利厚生・待遇
            ["name" => "高時給（時給1,300円以上）", "value" => "高時給", "parent" => 3],
            ["name" => "資格取得支援制度あり", "value" => "資格取得支援制度あり", "parent" => 3],
            ["name" => "制服あり", "value" => "制服あり", "parent" => 3],
            ["name" => "交通費支給", "value" => "交通費支給", "parent" => 3],
            ["name" => "給与前払い制度あり", "value" => "給与前払い制度あり", "parent" => 3],
            ["name" => "研修制度あり", "value" => "研修制度あり", "parent" => 3],
            ["name" => "資格・スキルが活かせる", "value" => "資格・スキルが活かせる", "parent" => 3],

        );

        foreach($kodawariParamList As $index => $params) {
            $fEntSearchAxisKbn = new FEntSearchAxisKbn();
            $fEntSearchAxisKbn->type = "bk";
            $fEntSearchAxisKbn->name = $params["name"];
            $fEntSearchAxisKbn->value = $params["value"];
            $fEntSearchAxisKbn->cnt = null;
            $fEntSearchAxisKbn->parent = $params["parent"];
            $fEntSearchAxisKbn->children = null;

            $fEntSearchAxis->kodawari[$index] = $fEntSearchAxisKbn;
        }


        //こだわり検索軸の格納 end


        $fEntSearchAxisData->fEntSearchAxis = $fEntSearchAxis;
        $fEntSearchAxisData->isSuccessGetAxis = true;
        $fEntSearchAxisData->isCustomSearch = $isCustomSearch;
        $fEntSearchAxisData->isCustomArea = $isCustomArea;
        $fEntSearchAxisData->isCustomJob = $isCustomJob;

        $msg->_c = Response::HTTP_OK;
        $msg->_m = '検索軸の取得に成功しました';
        $msg->fEntSearchAxisData = $fEntSearchAxisData;

    }
}
