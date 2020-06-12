<?php
namespace Onepoint\Dashboard\Services;

use App\Entities\Order;
use App\Entities\Vcard\VcardOrder;

class CouponService
{
    /**
     * 檢查是折扣碼否可用
     * $coupon   Object  with('vcard_coupon_category)的查詢結果
     */
    static public function check($coupon, $order_table = 'orders')
    {
        $coupons_arr = [
            'result' => false,
            'id' => 0,
            'coupon_code' => '',
            'coupon_type' => '',
            'coupon_discount' => 0,
            'price_limit' => 0,
        ];
        if (!is_null($coupon)) {
            if ($order_table == 'orders') {
                $order_entity = new Order;
            } else {
                $order_entity = new VcardOrder;
            }

            // 使用方法 enum('重複使用','僅使用一次','會員一次性折扣')
            switch ($coupon->coupon_use_type) {
                case '僅使用一次':
                    $order = $order_entity->where('coupon_id', $coupon->id)->where('status', '購物完成')->first();
                    if (is_null($order)) {
                        $avaliable = true;
                    }
                    break;

                // vcard 無此選項
                case '會員一次性折扣':
                    if ($member_id > 0) {
                        $order = $order_entity->where('coupon_id', $coupon->id)->where('member_id', $member_id)->where('status', '購物完成')->first();
                        if (is_null($order)) {
                            $avaliable = true;
                        }
                    }
                    break;

                default:

                    // 重複使用
                    $avaliable = true;
                    break;
            }
            if ($avaliable) {

                // 檢查啟用期間
                $coupon_date_check = false;
                if ($coupon->vcard_coupon_category->discount_forever == '啟用') {
                    $coupon_date_check = true;
                } else {

                    // 檢查開始日期
                    if ($coupon->vcard_coupon_category->start_at == '0000-00-00' || $coupon->vcard_coupon_category->start_at <= date('Y-m-d', time())) {

                        // 檢查結束日期
                        if ($coupon->vcard_coupon_category->end_at == '0000-00-00' || $coupon->vcard_coupon_category->end_at >= date('Y-m-d', time())) {
                            $coupon_date_check = true;
                        }
                    }
                }
                if ($coupon_date_check) {
                    $coupons_arr = [
                        'result' => true,
                        'id' => $coupon->id,
                        'coupon_code' => $coupon->coupon_code,
                        'coupon_type' => $coupon->vcard_coupon_category->coupon_type,
                        'coupon_discount' => $coupon->vcard_coupon_category->discount,
                        'price_limit' => $coupon->vcard_coupon_category->price_limit,
                    ];
                } else {
                    $avaliable = false;
                }
            }
        }
        return $coupons_arr;
    }
}
