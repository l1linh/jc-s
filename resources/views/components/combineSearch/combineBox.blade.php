@props(['fEntSearchAxisData','fEntConfig'])

@php
    $ary = $fEntConfig->frontendSettings['quickTypes']['list']??null;
    $arrayQuickBoxItem = array();

    if($ary) {
        foreach($ary As $value) {
            $arrayQuickBoxItem[$value] = $value;
        }
    }

    /** @var $fEntSearchAxisData */
    if($fEntSearchAxisData->isCustomSearch) {
        $searchURL = Route('search.query').'?';
    }
    else {
        $searchURL = Route('search').'/';
    }
    $searchURL = Route('search.query').'?'; //複数パラメータの可能性があるので強制上書き

@endphp
@if(count($arrayQuickBoxItem) > 0)
    @if($fEntSearchAxisData->fEntSearchAxis)
    <div class="combineSearch">
        <div class="combineSearchWrapper">
            <div class="selectBox">
                <h3 class="combineSearchTitle">
                    クイックお仕事検索
                </h3>
                <div class="selectBoxInner">
                    <div class="boxFlex">
                        <x-combineSearch.list.quickPref :prefAxis="$fEntSearchAxisData->fEntSearchAxis->pref" :isCustomSearch="true" :isDispCount=false />
                        <x-combineSearch.list.quickCity :cityAxis="$fEntSearchAxisData->fEntSearchAxis->city" :isCustomSearch="true" :isDispCount=false />
                        <span id="quickSearchJobBox" class="openModal title" data-id="2">
                            <input id="quickSearchJob" readonly="readonly" value="" placeholder="全て">
                        </span>
                        {{--<x-combineSearch.list.quickSalaryKbn :isCustomSearch="true" :isDispCount=false />--}}
                        {{--<x-combineSearch.list.quickSalary :isCustomSearch="true" :isDispCount=false />--}}
                        <span id="quickSearchFeatureBox" class="openModal title" data-id="1">
                            <input id="quickSearchFeature" readonly="readonly" value="" placeholder="全て">
                        </span>
                    </div>

                    <div class="boxFlex freeword">
                        <h4>フリーワード検索</h4>
                        <span class="freeword">
                            <input id="quickSearchWord" value="" placeholder="例) 残業なし">
                        </span>
                        <span class="submitBtnWrapper">
                            <button type="submit" id="quicksearchSubmitCustom">お仕事を探す</button>
                        </span>
                    </div>
                    <input type="hidden" id="quick_search_url_custom" value="{{$searchURL}}">
                </div>

                <!-- モーダルエリアここから -->
                <section id="modalArea" class="modalArea">
                    <div id="modalBg" class="modalBg"></div>
                    <div class="modalWrapper">
                        <div id="modal-1" class="modalAxis">
                            <h2 class="axisTitle"><span><strong>こだわり</strong>を選択してください</span></h2>
                            <div class="modalContents">
                                <x-combineSearch.modal.modalSearchKodawari :fEntSearchAxisData="$fEntSearchAxisData" />
                            </div>
                            <div id="closeModal" class="closeModal">
                                ×
                            </div>
                            <div class="quickTokuchoBtns">
                                <button class="reset-quick-tokucho"><span>選択クリアして戻る</span></button>
                                <button class="quick-exec-tokucho"><span>設定する</span></button>
                            </div>
                        </div>
                        <div id="modal-2" class="modalAxis">
                            <h2 class="axisTitle"><span><strong>職種</strong>を選択してください</span></h2>
                            <div class="modalContents">
                                <x-combineSearch.modal.modalSearchJob :fEntSearchAxisData="$fEntSearchAxisData" />
                            </div>
                            <div id="closeModal" class="closeModal">
                                ×
                            </div>
                            <div class="quickSyksyBtns">
                                <button class="reset-quick-syksy"><span>選択クリアして戻る</span></button>
                                <button class="quick-exec-syksy"><span>設定する</span></button>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- モーダルエリアここまで -->
            </div>
        </div>
    </div>
    @endif
@endif
