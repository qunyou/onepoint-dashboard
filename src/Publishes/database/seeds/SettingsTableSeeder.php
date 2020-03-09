<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * 全域
         */
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'file_name',
            'title' => '主導覽區塊 Logo',
            'description' => '',
            'setting_key' => 'top_logo',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'file_name',
            'title' => '頁尾 Logo',
            'description' => '',
            'setting_key' => 'footer_logo',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'editor',
            'title' => '頁尾資訊',
            'description' => '',
            'setting_key' => 'footer_info',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => '版權宣告',
            'description' => '',
            'setting_key' => 'footer_copyright',
            'setting_value' => '&copy;' . date('Y') . ' All rights reserved.',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => '網站標題',
            'description' => '瀏覽器分頁所顯示的標題',
            'setting_key' => 'html_title',
            'setting_value' => '逸點設計',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => '網頁關鍵字',
            'description' => '用半型逗號分隔每個詞',
            'setting_key' => 'meta_keywords',
            'setting_value' => '輕鬆架站,網頁設計,網頁製作,快速架站,網站建立,企業形象網站,創業Star up',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => '網頁敘述',
            'description' => '搜尋引擎搜尋結果列表下方顯示的網站說明',
            'setting_key' => 'meta_description',
            'setting_value' => '簡單易上手，可使用自有網址，自訂性高，價格透明實惠，適合低預算、想評估網站效果者或創業初期者使用。',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => '網站名稱',
            'description' => '使用於FB分享、通知信件等需出現網站名稱的場合',
            'setting_key' => 'web_name',
            'setting_value' => '逸點設計',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => 'Facebook 網址',
            'description' => '',
            'setting_key' => 'facebook_url',
            'setting_value' => 'https://www.facebook.com',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => 'twitter 網址',
            'description' => '',
            'setting_key' => 'twitter_url',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => 'tumblr 網址',
            'description' => '',
            'setting_key' => 'tumblr_url',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => 'youtube 網址',
            'description' => '',
            'setting_key' => 'youtube_url',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => 'google 網址',
            'description' => '',
            'setting_key' => 'google_url',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => 'instagram 網址',
            'description' => '',
            'setting_key' => 'instagram_url',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => '電話',
            'description' => '網頁下方及聯絡表單顯示的電話',
            'setting_key' => 'global_tel',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => '地址',
            'description' => '網頁下方及聯絡表單顯示的地址',
            'setting_key' => 'global_address',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => 'Email',
            'description' => '網頁下方及聯絡表單顯示的Email',
            'setting_key' => 'global_email',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => '管理者信箱',
            'description' => '訂購通知、聯絡表單通知將會寄送至此信箱',
            'setting_key' => 'admin_email',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => 'Facebook 分享標題',
            'description' => '網址分享至 Facebook 時顯示的標題',
            'setting_key' => 'og_title',
            'setting_value' => '逸點設計',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'str',
            'title' => 'Facebook 分享說明',
            'description' => '網址分享至 Facebook 時顯示的簡短說明',
            'setting_key' => 'og_description',
            'setting_value' => '簡單易上手，可使用自有網址，自訂性高，價格透明實惠，適合低預算、想評估網站效果者或創業初期者使用。',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'file_name',
            'title' => 'Facebook 分享縮圖',
            'description' => '網址分享至 Facebook 時顯示的縮圖',
            'setting_key' => 'og_image',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'text',
            'title' => '全站 head 附加內容',
            'description' => '可放置 Google analytics 追蹤碼，或是搭配 Facebook 顧客洽談外掛程式的 Facebook SDK ，以及其他 Javascript 內容',
            'setting_key' => 'global_head_append_string',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'text',
            'title' => '全站 body 標籤開始後附加內容',
            'description' => '可放置再行銷追蹤碼等相關內容',
            'setting_key' => 'global_body_start_append_string',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'text',
            'title' => '全站 body 標籤結束前附加內容',
            'description' => '頁尾追加內容或modal等應用',
            'setting_key' => 'global_body_end_append_string',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'text',
            'title' => '全站Javascript區塊追加內容',
            'description' => 'Javascript區塊追加內容',
            'setting_key' => 'global_js_append_string',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'global',
            'type' => 'file_name',
            'title' => 'Favicon',
            'description' => '網址前的小圖示',
            'setting_key' => 'favicon',
            'setting_value' => '',
        ]);
        
        /**
         * 訂單
         */
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'order',
            'type' => 'num',
            'title' => '運費',
            'description' => '購物基本運費',
            'setting_key' => 'shipping_fee',
            'setting_value' => '120',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'order',
            'type' => 'num',
            'title' => '免運費額',
            'description' => '購物滿額可免運費',
            'setting_key' => 'free_shipping',
            'setting_value' => '1200',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'order',
            'type' => 'text',
            'title' => '運費說明',
            'description' => '顯示於購物車頁面',
            'setting_key' => 'shipping_description',
            'setting_value' => '購物滿 1200 元，即可免運費',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'order',
            'type' => 'str',
            'title' => '運費編號',
            'description' => '供訂單運費項目識別用',
            'setting_key' => 'order_shipping_item_code',
            'setting_value' => 'shipping-fee',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'order',
            'type' => 'file_name',
            'title' => '麵包屑清單背景圖',
            'description' => '',
            'setting_key' => 'order_banner',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'order',
            'type' => 'text',
            'title' => '購物完成說明',
            'description' => '顯示於購物完成頁面',
            'setting_key' => '感謝您的訂購，以下是您的訂單內容。',
            'setting_value' => '',
        ]);

        /**
         * 貨到付款
         */
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'cash_on_delivery',
            'type' => 'num',
            'title' => '啟用貨到付款付款方式',
            'description' => '1為啟用，0為停用',
            'setting_key' => 'cash_on_delivery_enable',
            'setting_value' => 0,
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'cash_on_delivery',
            'type' => 'str',
            'title' => '付款方式名稱',
            'description' => '結帳時顯示於付款方式下拉選單中',
            'setting_key' => 'cash_on_delivery_display_name',
            'setting_value' => '貨到付款',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'cash_on_delivery',
            'type' => 'text',
            'title' => '貨到付款付款說明',
            'description' => '顯示於訂單確認頁面',
            'setting_key' => 'cash_on_delivery_description',
            'setting_value' => '貨運人員將於貨物到達時向您收款',
        ]);

        /**
         * ATM
         */
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'atm',
            'type' => 'num',
            'title' => '啟用ATM付款方式',
            'description' => '1為啟用，0為停用',
            'setting_key' => 'atm_enable',
            'setting_value' => 1,
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'atm',
            'type' => 'num',
            'title' => 'ATM付款說明',
            'description' => '顯示於訂單確認頁面',
            'setting_key' => 'atm_description',
            'setting_value' => '銀行代號：000
            匯款帳號：000000000000',
        ]);

        /**
         * 藍新虛擬帳號
         */
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'neweb_virtual_account',
            'type' => 'num',
            'title' => '啟用藍新虛擬帳號付款方式',
            'description' => '1為啟用，0為停用',
            'setting_key' => 'neweb_virtual_account_enable',
            'setting_value' => 0,
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'neweb_virtual_account',
            'type' => 'str',
            'title' => '付款方式名稱',
            'description' => '結帳時顯示於付款方式下拉選單中',
            'setting_key' => 'neweb_virtual_account_display_name',
            'setting_value' => '藍新虛擬帳號',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'neweb_virtual_account',
            'type' => 'editor',
            'title' => '藍新虛擬帳號付款說明',
            'description' => '顯示於訂單確認頁面',
            'setting_key' => 'neweb_virtual_account_description',
            'setting_value' => '藍新虛擬帳號付款說明',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'neweb_virtual_account',
            'type' => 'editor',
            'title' => '藍新線上刷卡付款說明',
            'description' => '顯示於訂單確認頁面',
            'setting_key' => 'neweb_credit_card_description',
            'setting_value' => '藍新線上刷卡付款說明',
        ]);

        /**
         * 聯合信用卡
         */
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'nccc',
            'type' => 'num',
            'title' => '啟用聯合信用卡付款方式',
            'description' => '1為啟用，0為停用',
            'setting_key' => 'nccc_enable',
            'setting_value' => 0,
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'nccc',
            'type' => 'str',
            'title' => '付款方式名稱',
            'description' => '結帳時顯示於付款方式下拉選單中',
            'setting_key' => 'nccc_display_name',
            'setting_value' => '線上刷卡',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'nccc',
            'type' => 'str',
            'title' => '特店代號(MerchantID)',
            'description' => '聯合信用卡核發的特店代號(MerchantID)',
            'setting_key' => 'nccc_MerchantID',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'nccc',
            'type' => 'str',
            'title' => '聯合信用卡端末機代號',
            'description' => '',
            'setting_key' => 'nccc_MerchantID',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'nccc',
            'type' => 'str',
            'title' => '聯合信用卡MacKey',
            'description' => '',
            'setting_key' => 'nccc_MacKey',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'nccc',
            'type' => 'str',
            'title' => '聯合信用卡付款網址',
            'description' => '',
            'setting_key' => 'nccc_Url',
            'setting_value' => 'https://nccnet-ec.nccc.com.tw/merchant/HPPRequest',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'nccc',
            'type' => 'str',
            'title' => '聯合信用卡付款說明',
            'description' => '顯示於訂單確認頁面',
            'setting_key' => 'nccc_Description',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'nccc',
            'type' => 'str',
            'title' => '環境設定',
            'description' => '1為正式環境，0為測試環境',
            'setting_key' => 'nccc_Env',
            'setting_value' => '',
        ]);
        
        /**
         * 產品
         */
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'product',
            'type' => 'file_name',
            'title' => '產品麵包屑清單背景圖',
            'description' => '',
            'setting_key' => 'product_banner',
            'setting_value' => '',
        ]);
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'product',
            'type' => 'editor',
            'title' => '產品說明下方共用文字',
            'description' => '',
            'setting_key' => 'product_detail_share_text',
            'setting_value' => '',
        ]);

        /**
         * 文章
         */
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'article',
            'type' => 'file_name',
            'title' => '文章麵包屑清單背景圖',
            'description' => '',
            'setting_key' => 'article_banner',
            'setting_value' => '',
        ]);

        /**
         * 會員
         */
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'member',
            'type' => 'file_name',
            'title' => '會員麵包屑清單背景圖',
            'description' => '',
            'setting_key' => 'member_banner',
            'setting_value' => '',
        ]);

        /**
         * 聯絡表單
         */
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'contact',
            'type' => 'file_name',
            'title' => '麵包屑清單背景圖',
            'description' => '',
            'setting_key' => 'contact_banner',
            'setting_value' => '',
        ]);

        /**
         * 最新消息
         */
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'news',
            'type' => 'file_name',
            'title' => '新聞麵包屑清單背景圖',
            'description' => '',
            'setting_key' => 'news_banner',
            'setting_value' => '',
        ]);

        /**
         * 部落格
         */
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'blog',
            'type' => 'file_name',
            'title' => '部落格麵包屑清單背景圖',
            'description' => '',
            'setting_key' => 'blog_banner',
            'setting_value' => '',
        ]);

        /**
         * 相簿
         */
        DB::table('settings')->insert([
            'sort' => 1,
            'model' => 'album',
            'type' => 'file_name',
            'title' => '相簿麵包屑清單背景圖',
            'description' => '',
            'setting_key' => 'album_banner',
            'setting_value' => '',
        ]);
    }
}