$(function() {

    function initQuick() {
        $('.combineSearch .selectBoxInner .child input[type=checkbox]').removeAttr('checked').prop('checked', false).change(); //選択状態初期化
        $('.combineSearch .modalWrapper .child input[type=checkbox]').removeAttr('checked').prop('checked', false).change(); //選択状態初期化
        $('.combineSearch .selectBoxInner select').prop("selectedIndex", 0).change(); //一括初期化
        $('.combineSearch .selectBoxInner #quickSearchCity').addClass('locked');
        $('.combineSearch .selectBoxInner #quickSearchCity').attr('disabled', 'disabled');
        $('.combineSearch .selectBoxInner #quickSearchSalary').addClass('locked');
        $('.combineSearch .selectBoxInner #quickSearchSalary').attr('disabled', 'disabled');

        $(' #modal-1 .quick-exec-tokucho').addClass('locked');
        $(' #modal-1 .quick-exec-tokucho').attr('disabled', 'disabled');

        $(' #modal-2 .quick-exec-syksy').addClass('locked');
        $(' #modal-2 .quick-exec-syksy').attr('disabled', 'disabled');

        $(".combineSearch #quickSearchJob").val('');
        $(".combineSearch #quickSearchFeature").val('');
        $(".combineSearch #quickSearchWord").val('');

        $('#modalArea .modalWrapper .modalAxis').removeClass('active');
    }

    function initDetailCriteria() {
        $('#modalDetail .modalDetailContents .child input[type=checkbox]').removeAttr('checked').prop('checked', false).change();
        $('#modalDetail .modalDetailContents .salaryAxis #quickSearchSalaryKbn').prop("selectedIndex", 0).change();
        $('#modalDetail .modalDetailContents .salaryAxis #quickSearchSalary').prop("selectedIndex", 0).change();
        $('#modalDetail .modalDetailContents .salaryAxis #quickSearchSalary').addClass('locked');
        $('#modalDetail .modalDetailContents .salaryAxis #quickSearchSalary').attr('disabled', 'disabled');
        $('#modalDetail .modalDetailContents .freewordAxis #modalDetailSearchWord').val('');
        $('#modalDetail .modalDetailContents .searchListWrapper').removeClass('active');
        $('#modalDetail .modalDetailContents .parent').removeClass('active');
        $('#modalDetail .modalDetailContents .child').removeClass('selected');
    }

    // //初期表示処理
    $(document).ready(function() {
        initQuick();
        initDetailCriteria();
    });

    //モーダル開制御
    $('.openModal').click(function(){
        if(window.innerWidth < (parseInt(import.meta.env.VITE_BREAK_POINT)+1)) {
            $('#headerMenu .headerNav').css({'display': 'none'});
        }

        let targetId = $(this).data('id');
        let modalId = "modal-" + targetId;

        $('#modalArea').fadeIn();
        $('.modalAxis').each(function() {
            $(this).css({'display': 'none'});
        });
        let targetModal = $('#'+modalId);

        targetModal.css({'display': 'block'});
        targetModal.addClass('active');
        $(this).parents('body').addClass('modalOn');//背景要素を固定
    });

    //特徴モーダル閉制御
    $('#modal-1 .quick-exec-tokucho').click(function(){

        if(window.innerWidth < (parseInt(import.meta.env.VITE_BREAK_POINT)+1)) {
            $('#headerMenu .headerNav').css({'display': ''});
        }

        $(this).parents('body').removeClass('modalOn');//背景要素固定を解除
        $('#modal-1').removeClass('active');
        $('#modalArea').fadeOut();

        reflectTokuchoNameToInput();
    });

    $('#modal-1 .reset-quick-tokucho').click(function(){

        if(window.innerWidth < (parseInt(import.meta.env.VITE_BREAK_POINT)+1)) {
            $('#headerMenu .headerNav').css({'display': ''});
        }

        $(this).parents('body').removeClass('modalOn');//背景要素固定を解除
        $('#modal-1').removeClass('active');
        $('#modalArea').fadeOut();

        //特徴　初期化
        $('#modal-1 .modalContents .childrenBox .child').each(function() {
            $(this).find('input[type=checkbox]').removeAttr('checked').prop('checked', false).change();
            $(this).removeClass('selected');
        });

        $(".combineSearch #quickSearchFeature").val('');
    });

    //職種モーダル閉制御
    $('#modal-2 .quick-exec-syksy').click(function(){

        if(window.innerWidth < (parseInt(import.meta.env.VITE_BREAK_POINT)+1)) {
            $('#headerMenu .headerNav').css({'display': ''});
        }

        $(this).parents('body').removeClass('modalOn');//背景要素固定を解除
        $('#modal-2').removeClass('active');
        $('#modalArea').fadeOut();

        reflectJobNameToInput();
    });

    $('#modal-2 .reset-quick-syksy').click(function(){

        if(window.innerWidth < (parseInt(import.meta.env.VITE_BREAK_POINT)+1)) {
            $('#headerMenu .headerNav').css({'display': ''});
        }

        $(this).parents('body').removeClass('modalOn');//背景要素固定を解除
        $('#modal-2').removeClass('active');
        $('#modalArea').fadeOut();

        //職種　初期化
        $('#modal-2 .modalContents .childrenBox .child').each(function() {
            $(this).find('input[type=checkbox]').removeAttr('checked').prop('checked', false).change();
            $(this).removeClass('selected');
        });

        $(".combineSearch #quickSearchJob").val('');
    });

    //モーダル共通閉制御
    $('#closeModal , #modalBg').click(function(){
        if(window.innerWidth < (parseInt(import.meta.env.VITE_BREAK_POINT)+1)) {
            $('#headerMenu .headerNav').css({'display': ''});
        }

        $(this).parents('body').removeClass('modalOn');//背景要素固定を解除

        let targetModal = $('.modalAxis.active');
        let id = targetModal.attr('id');

        targetModal.removeClass('active');
        $('#modalArea').fadeOut();

        if(id === 'modal-1') {
            reflectTokuchoNameToInput();
        }
        else if (id === 'modal-2') {
            reflectJobNameToInput();
        }
    });

    //職種　検索条件に格納
    function reflectJobNameToInput () {
        let targetSyksyList = $("#modal-2 .modalContents .childrenBox");

        let array_syksy_name = [];
        let text_syksy_name = '';
        targetSyksyList.find('.child input[type=checkbox]:checked').each(function() {
            array_syksy_name.push($(this).closest("label").text().trim());
        });

        if(array_syksy_name.length !== 0) {
            text_syksy_name= array_syksy_name.join('、 ');
        }
        $(".combineSearch #quickSearchJob").val(text_syksy_name);
    }

    //特徴　検索条件に格納
    function reflectTokuchoNameToInput () {
        let targetTokuchoList = $("#modal-1 .modalContents .childrenBox");

        let array_tokucho_name = [];
        let text_tokucho_name = '';
        targetTokuchoList.find('.child input[type=checkbox]:checked').each(function() {
            array_tokucho_name.push($(this).closest("label").text().trim());
        });
        if(array_tokucho_name.length !== 0) {
            text_tokucho_name= array_tokucho_name.join('、 ');
        }
        $(".combineSearch #quickSearchFeature").val(text_tokucho_name);
    }

    //quicksearchのSubmit
    function quickSearchSubmit() {
        let val = '';
        let data_arys = [];
        let search_url = $("#quick_search_url").val();
        if($('#quickSearchArea').val() && $('#quickSearchArea').val() != '-1'){
            let quick_area = $('#quickSearchArea').val();
            quick_area = encodeURIComponent(quick_area);
            data_arys.push(quick_area);
        }
        if($('#quickSearchSyksy').val() && $('#quickSearchSyksy').val() != '-1'){
            let quick_syksy = $('#quickSearchSyksy').val();
            quick_syksy = encodeURIComponent(quick_syksy);
            data_arys.push(quick_syksy);
        }
        if($('#quickSearchKoy').val() && $('#quickSearchKoy').val() != '-1'){
            let quick_koy = $('#quickSearchKoy').val();
            quick_koy = encodeURIComponent(quick_koy);
            data_arys.push(quick_koy);
        }
        if($('#quickSearchWord').val()){
            let quick_word = $('#quickSearchWord').val();
            quick_word = 'kw_' + encodeURIComponent(quick_word);
            data_arys.push(quick_word);
        }
        val = data_arys.join('/');
        if(val){
            location.href = search_url + val;
        }else{
            location.href = search_url + 'custom?';
        }
        return false;
    }

    //quickSearchのSubmit(カスタム検索用)
    function quickCustomSearchSubmit() {
        let val = '';
        let data_arys = [];
        let search_url = $("#quick_search_url_custom").val();

        let quick_area = null; //初期化
        //エリアが選択されている場合
        if($('#quickSearchArea').val() && $('#quickSearchArea').val() != '-1'){
            quick_area = $('#quickSearchArea').val();
        }
        //都道府県が選択されている場合
        if($('#quickSearchPref').val() && $('#quickSearchPref').val() != '-1'){
            quick_area = $('#quickSearchPref').val(); //上書き
        }
        //市区町村が選択されている場合
        if($('#quickSearchCity').val() && $('#quickSearchCity').val() != '-1'){
            quick_area = $('#quickSearchCity').val(); //上書き
        }

        if(quick_area) {
            quick_area = encodeURI(quick_area);
            data_arys.push(quick_area);
        }

        let targetSyksyList = $("#modal-2 .modalContents .childrenBox");
        let Syksy = targetSyksyList.find('.child input[type=checkbox]:checked').map(function() { return this.value; });
        if (Syksy.length > 0) {
            let quick_syksy = 'job='+ encodeURIComponent($.makeArray(Syksy).join('[]'));
            data_arys.push(quick_syksy);
        }

        if($('.selectBoxInner #quickSearchSalaryKbn').val() && $('.selectBoxInner #quickSearchSalaryKbn').val() != '-1'){
            let quick_salary_kbn = $('.selectBoxInner #quickSearchSalaryKbn').val();
            quick_salary_kbn = encodeURI(quick_salary_kbn);
            data_arys.push(quick_salary_kbn);
        }

        if($('.selectBoxInner #quickSearchSalary').val() && $('.selectBoxInner #quickSearchSalary').val() != '-1'){
            let quick_salary = $('.selectBoxInner #quickSearchSalary').val();
            quick_salary = encodeURI(quick_salary);
            data_arys.push(quick_salary);
        }

        let targetTokuchoList = $("#modal-1 .modalContents .childrenBox");
        let Tokucho = targetTokuchoList.find('.child input[type=checkbox]:checked').map(function() { return this.value; });
        if (Tokucho.length > 0) {
            // let quick_tokucho = 'tokucho='+ encodeURIComponent($.makeArray(Tokucho).join('[]'));
            let quick_tokucho = 'bk='+ encodeURIComponent($.makeArray(Tokucho).join(' '));
            data_arys.push(quick_tokucho);
        }

        if($('#quickSearchWord').val()) {
            let quick_word = $('#quickSearchWord').val();
            quick_word = 'kw=' + encodeURIComponent(quick_word);
            data_arys.push(quick_word);
        }

        val = data_arys.join('&');

        //URL
            location.href = search_url + val;
            return false;
    }

    //quicksearchのSubmitボタン
    $('#quicksearchSubmit').click(function() {
        quickSearchSubmit();
    });

    //quicksearchのSubmitボタン(カスタム検索用)
    $('#quicksearchSubmitCustom').click(function() {
        quickCustomSearchSubmit();
    });

    //quickSearchbox text enter-key
    $("#quickSearchWord").on("keypress", function(event) {
        if(event.key === 'Enter' && $('#quickSearchWord').val()){
            //enter-key
            if(document.getElementById("quicksearchSubmit") != null) {
                quickSearchSubmit(); //通常軸で検索
                return false;
            }
            else if (document.getElementById("quicksearchSubmitCustom") != null) {
                quickCustomSearchSubmit(); //カスタム軸で検索
                return false;
            }
            else {
                return true;
            }
        }else{
            return true;
        }
    });

    //エリア要素選択時
    $('.combineSearch .selectBoxInner #quickSearchArea').change(function() {
        $('.combineSearch .selectBoxInner #quickSearchPref').prop("selectedIndex", 0).change();
        if($(this).val() && $(this).val() != '-1') {
            let target_id = $(this).val().slice(3); //エリアid取得

            $('.combineSearch .selectBoxInner #quickSearchPref').find('option.parent').each(function() {
                let parent = $(this).attr('data-parent');
                $(this).closest('span.hide').contents().unwrap();
                if (target_id === parent) {
                    //
                } else {
                    $(this).wrap('<span class="hide" style="display: none"></span>');
                }
            });
            $('.combineSearch .selectBoxInner #quickSearchPref').removeClass('locked');
            $('.combineSearch .selectBoxInner #quickSearchPref').removeAttr('disabled');
        }
        else {
            $('.combineSearch .selectBoxInner #quickSearchPref').addClass('locked');
            $('.combineSearch .selectBoxInner #quickSearchPref').attr('disabled','disabled');
        }
    });

    //都道府県要素選択時
    $('.combineSearch .selectBoxInner #quickSearchPref').change(function() {
        $('.combineSearch .selectBoxInner #quickSearchCity').prop("selectedIndex", 0).change();
        if($(this).val() && $(this).val() != '-1') {
            let target_id = $(this).val().slice(5); //都道府県id取得

            $('.combineSearch .selectBoxInner #quickSearchCity').find('option.parent').each(function() {
                let parent = $(this).attr('data-parent');
                $(this).closest('span.hide').contents().unwrap();
                if (target_id === parent) {
                    //
                } else {
                    $(this).wrap('<span class="hide" style="display: none"></span>');
                }
            });
            $('.combineSearch .selectBoxInner #quickSearchCity').removeClass('locked');
            $('.combineSearch .selectBoxInner #quickSearchCity').removeAttr('disabled');
        }
        else {
            $('.combineSearch .selectBoxInner #quickSearchCity').addClass('locked');
            $('.combineSearch .selectBoxInner #quickSearchCity').attr('disabled','disabled');
        }
    });

    //職種分類要素選択時
    $('.combineSearch .selectBoxInner #quickSearchSyksyGroup').change(function() {
        $('.combineSearch .selectBoxInner #quickSearchSyksy').prop("selectedIndex", 0).change();
        if($(this).val() && $(this).val() != '-1') {
            let target_id = $(this).val().slice(6); //都道府県id取得

            $('.combineSearch .selectBoxInner #quickSearchSyksy').find('option.parent').each(function() {
                let parent = $(this).attr('data-parent');
                $(this).closest('span.hide').contents().unwrap();
                if (target_id === parent) {
                    //
                } else {
                    $(this).wrap('<span class="hide" style="display: none"></span>');
                }
            });
            $('.combineSearch .selectBoxInner #quickSearchSyksy').removeClass('locked');
            $('.combineSearch .selectBoxInner #quickSearchSyksy').removeAttr('disabled');
        }
        else {
            $('.combineSearch .selectBoxInner #quickSearchSyksy').addClass('locked');
            $('.combineSearch .selectBoxInner #quickSearchSyksy').attr('disabled','disabled');
        }
    });

    //給与分類要素選択時
    $('.combineSearch .selectBoxInner #quickSearchSalaryKbn').change(function() {
        $('.combineSearch .selectBoxInner #quickSearchSalary').prop("selectedIndex", 0).change();
        if($(this).val() && $(this).val() != '-1') {
            let target_id = $(this).val().slice(6);

            $('.combineSearch .selectBoxInner #quickSearchSalary').find('option.parent').each(function() {
                let parent = $(this).attr('data-parent');
                $(this).closest('span.hide').contents().unwrap();
                if (target_id === parent) {
                    //
                } else {
                    $(this).wrap('<span class="hide" style="display: none"></span>');
                }
            });
            $('.combineSearch .selectBoxInner #quickSearchSalary').removeClass('locked');
            $('.combineSearch .selectBoxInner #quickSearchSalary').removeAttr('disabled');
        }
        else {
            $('.combineSearch .selectBoxInner #quickSearchSalary').addClass('locked');
            $('.combineSearch .selectBoxInner #quickSearchSalary').attr('disabled','disabled');
        }
    });

    //特徴モーダル制御
    $('#modal-1 .modalContents .childrenBox input[type="checkbox"]').change(function() {
        let tokucho_quick = $('#modal-1 .modalContents .childrenBox input[type="checkbox"]:checked').map(function() {return this.value; });
        if(tokucho_quick.length === 0) {
            $(' #modal-1 .quick-exec-tokucho').addClass('locked');
            $(' #modal-1 .quick-exec-tokucho').attr('disabled', 'disabled');
        }
        else {
            $(' #modal-1 .quick-exec-tokucho').removeClass('locked');
            $(' #modal-1 .quick-exec-tokucho').removeAttr('disabled');
        }
    });

    //職種モーダル制御
    $('#modal-2 .modalContents .childrenBox input[type="checkbox"]').change(function() {
        let syksy_quick = $('#modal-2 .modalContents .childrenBox input[type="checkbox"]:checked').map(function() {return this.value; });
        if(syksy_quick.length === 0) {
            $(' #modal-2 .quick-exec-syksy').addClass('locked');
            $(' #modal-2 .quick-exec-syksy').attr('disabled', 'disabled');
        }
        else {
            $(' #modal-2 .quick-exec-syksy').removeClass('locked');
            $(' #modal-2 .quick-exec-syksy').removeAttr('disabled');
        }
    });

    //モーダル内 要素クリック時
    $('#modal-1 .modalContents .childrenBox .child input[type=checkbox], #modal-2 .modalContents .childrenBox .child input[type=checkbox]').change(function() {
        $(this).closest('.child').removeClass('selected'); //初期化
        if($(this).prop('checked')) {
            $(this).closest('.child').addClass('selected');
        }

        let wholeCount = $(this).closest('.parent').find('.child input[type=checkbox]').length;
        let checkedCount = $(this).closest('.parent').find('.child input[type=checkbox]:checked').length;

        let targetParent = $(this).closest('.parent').find('.title input[type=checkbox]');
        targetParent.closest('.title').removeClass('selected'); //初期化
        targetParent.prop('checked', false).change();

        if(wholeCount === checkedCount) {
            parentCheck(targetParent);
        }
    });

    //モーダル内 「すべて選択」クリック時
    $('.combineSearch #modalArea .childrenBox li.title label').click(function(e){
        e.preventDefault();
        let target = $(this).find('input[type=checkbox]');
        let isChecked = target.prop('checked');

        if(isChecked === false) {
            target.prop('checked',true).change();
        }
        else {
            target.prop('checked', false).change();
        }
        selectAll(target);
    });

    function selectAll(e) {

        let chk = e.prop('checked');

        e.closest('.title').removeClass('selected'); //初期化
        if(chk === true) {
            e.closest('.title').addClass('selected');
        }

        e.closest('.parent').find('.child input[type=checkbox]').each(function() {
            childCheck($(this),chk);
        });
    }

    function childCheck(e,isCheck) {
        e.prop('checked', isCheck).change();
    }

    function parentCheck(parent) {
        parent.closest('.title').addClass('selected');
        parent.prop('checked', true).change();
    }
});





