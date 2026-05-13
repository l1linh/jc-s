<?php
namespace App\config\Consts;

class LocalMenuSettings
{
    const HEADER_LIST = array(
        [
            "text" => "ココジョブについて",
            "url" => "/service",
            "class" => "",
            "target" => "",
        ],
        [
            "text" => "充実のサポート体制",
            "url" => "/support",
            "class" => "",
            "target" => "",
        ],
        [
            "text" => "スタッフの声",
            "url" => "/message",
            "class" => "",
            "target" => "",
        ],
        [
            "text" => "応募の流れとFAQ",
            "url" => "/step",
            "class" => "",
            "target" => "",
        ],
        [
            "text" => "簡単登録",
            "url" => "/entry",
            "class" => "navItemBg navItemEntry",
            "target" => "",
        ],
        [
            "text" => "お気に入り",
            "url" => "/favorite",
            "class" => "navItemBg navItemFavorite",
            "target" => "",
        ],
        [
            "text" => "求人検索",
            "url" => "/search",
            "class" => "navItemBg navItemSearch",
            "target" => "",
        ],
        [
            "text" => "運営会社",
            "url" => LocalCorporationConst::CORPORATION_URL,
            "class" => "SPdisp",
            "target" => "_blank",
        ],
        [
            "text" => "会社概要",
            "url" => LocalCorporationConst::ABSTRACT_URL,
            "class" => "SPdisp",
            "target" => "_blank",
        ],
    );

    const FOOTER_LIST = array(
        [
            "text" => "TOPページ",
            "url" => "/",
            "class" => "one",
            "target" => "",
        ],
        [
            "text" => "求人検索",
            "url" => "/search",
            "class" => "one",
            "target" => "",
        ],
        [
            "text" => "お気に入り",
            "url" => "/favorite",
            "class" => "one end",
            "target" => "",
        ],
        [
            "text" => "ココジョブについて",
            "url" => "/service",
            "class" => "two",
            "target" => "",
        ],
        [
            "text" => "充実のサポート体制",
            "url" => "/support",
            "class" => "two",
            "target" => "",
        ],
        [
            "text" => "スタッフの声",
            "url" => "/message",
            "class" => "two",
            "target" => "",
        ],
        [
            "text" => "応募の流れとFAQ",
            "url" => "/step",
            "class" => "two",
            "target" => "",
        ],
        [
            "text" => "簡単登録",
            "url" => "/entry",
            "class" => "two end",
            "target" => "",
        ],
        [
            "text" => "運営会社",
            "url" => LocalCorporationConst::CORPORATION_URL,
            "class" => "remoteNav",
            "target" => "_blank",
        ],
        [
            "text" => "会社概要",
            "url" => LocalCorporationConst::ABSTRACT_URL,
            "class" => "remoteNav",
            "target" => "_blank",
        ],
    );
}
