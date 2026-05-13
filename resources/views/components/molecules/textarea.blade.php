@props([
'value',
'for',
'placeholder',
'helperText'
])


@php
    $textareaHtml = '';
    $textareaTag = '<textarea name="'. $for . '" placeholder="'. $placeholder . '"/>'. $value . "</textarea>";
    $textareaHtml.= $textareaTag;


    if (strlen($helperText)>0) {
        $helperTextTag = '<span class="helperText">' . $helperText .  '</span>';
        $textareaHtml.= $helperTextTag;
    }

@endphp

<div class="textarea-wrapper">
    {!! $textareaHtml ?? '' !!}
</div>
