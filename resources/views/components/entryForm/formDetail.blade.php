@props(['page','entryFormList'=>array(),'fEntUserApplyInfo','fEntApplyMasters','fEntJobDetail'])

<div class="form-inner">
    <section class="mod_jobDetailJob">
        <section class="mod_apply">
          
            <form method="post" action="{{$page->action}}" class="form01">
                @csrf

                <x-molecules.input id="agree" type="hidden" name="agree" :value="old('agree' , $agree ?? '')" />

                <input type="hidden" id="open" value="1">

                @php
                    $isOpen = true;
                @endphp

                @if(isset($entryFormList['profile']) && count($entryFormList['profile'])>0)
                    <x-entryForm.form.profile :fEntUserApplyInfo="$fEntUserApplyInfo" :fEntApplyMasters="$fEntApplyMasters" :items="$entryFormList['profile']" :isOpen=$isOpen />
                @endif

                {{--            <x-entryForm.form.privacyPolicy agreeId="agree_flg2" :fEntJobDetail="$fEntJobDetail" :fEntConfig="$page->fEntConfig" />--}}

                @php
                    $isOpen = false;
                @endphp

                @if(isset($entryFormList['skill']) && count($entryFormList['skill'])>0)
                    <x-entryForm.form.skill :fEntUserApplyInfo="$fEntUserApplyInfo" :fEntApplyMasters="$fEntApplyMasters" :items="$entryFormList['skill']" :isOpen=$isOpen />
                @endif

                @if(isset($entryFormList['career']) && count($entryFormList['career'])>0)
                    <x-entryForm.form.career :fEntUserApplyInfo="$fEntUserApplyInfo" :fEntApplyMasters="$fEntApplyMasters" :items="$entryFormList['career']" :isOpen=$isOpen />
                @endif

                @if(isset($entryFormList['pr']) && count($entryFormList['pr'])>0)
                    <x-entryForm.form.jikoPr :fEntUserApplyInfo="$fEntUserApplyInfo" :fEntApplyMasters="$fEntApplyMasters" :items="$entryFormList['pr']" :isOpen=$isOpen />
                @endif

                @if(isset($entryFormList['others']) && count($entryFormList['others'])>0)
                    <x-entryForm.form.others :fEntUserApplyInfo="$fEntUserApplyInfo" :fEntApplyMasters="$fEntApplyMasters" :items="$entryFormList['others']" :isOpen=$isOpen />
                @endif

            </form>
        </section><!-- .mod_apply -->

        <script>
            function clickAgree(){
                if ($('#agree').val() == '1') {
                    $('#agree').val('');
                    $('.agree').removeClass('is-checked');
                    $('button[type="submit"]').attr('disabled','disabled');
                    $('button[type="submit"]').addClass('locked');
                } else {
                    $('#agree').val('1');
                    $('.agree').addClass('is-checked');
                    $('button[type="submit"]').removeAttr('disabled');
                    $('button[type="submit"]').removeClass('locked');
                }
            }
            $(function(){
                document.getElementById('agree_flg1').addEventListener('click', clickAgree);
                // document.getElementById('agree_flg2').addEventListener('click', clickAgree);
            })
        </script>

    </section>
</div>
