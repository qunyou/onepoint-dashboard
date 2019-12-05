<?php

namespace App\Services;

use App\Entities\MemberCart;
use App\Entities\Product;
use App\Services\FrontendService;

class ShoppingCartService
{
    /**
     * 設定運費等資料
     *
     * 目前可用值
     * shipping_fee     運費
     * free_shipping    免運費額
     */
    public static function getSetting()
    {
        $order_base_setting = FrontendService::putSetting('order');
        config(['order' => $order_base_setting]);
    }

    /**
     * 加入購物車
     */
    public static function add($id, $qty, $price, $datas = [])
    {
        // 查詢購物車資料
        $member_cart_check = MemberCart::where('session_id', session()->getId())
            ->where('product_table', $datas['product_table'])
            ->where('product_id', $id)
            ->first();
        if (!is_null($member_cart_check)) {
            $qty = $member_cart_check->qty + $qty;
        }

        // 庫存檢查
        // if ($datas['stock_quantity'] < $qty) {
        //     $qty = $datas['stock_quantity'];
        // }

        // 寫入資料庫
        $shopping_cart = [
            'session_id' => session()->getId(),
            'product_id' => $id,
            'product_table' => $datas['product_table'],
            'name' => $datas['name'],
            'qty' => $qty,
            'price' => $price,
            'sub_total' => $price * $qty,
            'img' => $datas['img'],
        ];
        // 聖州
        if (config('http_host') == 'vcard_3d') {
            $shopping_cart['year'] = $datas['year'];
            $shopping_cart['material_name'] = $datas['materialName'];
            $shopping_cart['vehicle_make_name'] = $datas['vehicleMakeName'];
            $shopping_cart['vehicle_model_name'] = $datas['vehicleModelName'];
        }
        if (isset($datas['bulk'])) {
            $shopping_cart['bulk'] = serialize($datas['bulk']);
        }
        if (!is_null($member_cart_check)) {
            MemberCart::find($member_cart_check->id)->update($shopping_cart);
        } else {
            MemberCart::create($shopping_cart);
        }
        // if (Config::get('app.debug')) {
        //     Log::info('加入購物車');
        //     Log::info($shopping_cart);
        //     Log::info(Session::get('shopping_cart', '無'));
        // }
    }

    /**
     * 移出購物車
     */
    public static function remove($id)
    {
        $member_cart = MemberCart::find($id);
        if (!is_null($member_cart)) {
            $member_cart->delete();
        }
    }

    /**
     * 修改購物數量
     */
    public static function qty($id, $qty)
    {
        $member_cart_check = MemberCart::find($id);
        if (!is_null($member_cart_check)) {

            // 查詢庫存，限制數量上限(未完成)
            $product = Product::find($member_cart_check->product_id);
            $price = $product->price;

            // 更新購物車資料
            $arr['sub_total'] = $price * $qty;
            $arr['price'] = $price;

            // Log::info('cart', $arr);

            MemberCart::find($id)->update(['qty' => $qty, 'sub_total' => $arr['sub_total']]);
            return $arr;
        }
    }

    /**
     * 修改購物數量
     */
    public static function addQty($id)
    {
        $member_cart_check = MemberCart::find($id);
        if (!is_null($member_cart_check)) {

            // 查詢庫存，限制數量上限(未完成)
            $product = Product::find($member_cart_check->product_id);
            $price = $product->price;
            $new_qty = $member_cart_check->qty + 1;

            // 更新購物車資料
            $arr['sub_total'] = $price * $new_qty;
            $arr['price'] = $price;

            // Log::info('cart', $arr);

            MemberCart::find($id)->update(['qty' => $new_qty, 'sub_total' => $arr['sub_total']]);
            return $arr;
        }
    }

    /**
     * 重整購物項目
     */
    public static function renew($inputs)
    {
        $total = 0;
        foreach ($inputs as $value) {

            // 更新購物車數量
            $arr = ShoppingCart::qty($value['id'], $value['qty']);
            $total += $arr['sub_total'];
        }
        return $total;
    }

    /**
     * 購物車資料
     *
     * total 不含運費
     */
    public static function all()
    {
        // 訂單設定
        config(['order' => FrontendService::putSetting('order')]);

        // 查詢購物車資料
        $member_cart = MemberCart::where('session_id', session()->getId())->get();

        // 購物項目筆數
        $arr['count'] = $member_cart->count();

        // 不含運費總金額
        $arr['total'] = 0;

        // 免運費額
        $arr['free_shipping'] = config('order.free_shipping');

        // 運費
        $arr['shipping_fee'] = config('order.shipping_fee');

        // 目前運費
        $arr['shipping_fee_current'] = $arr['shipping_fee'];
        $list = [];
        if ($arr['count']) {
            foreach ($member_cart as $key => $value) {
                $arr['total'] += $value['price'] * $value['qty'];
                $list[$key] = $value->toArray();
                $list[$key]['bulk'] = unserialize($value->bulk);
            }

        }
        $arr['list'] = $list;

        // 運費判斷
        if ($arr['shipping_fee'] > 0) {
            if ($arr['total'] >= $arr['free_shipping']) {
                $arr['shipping_fee_current'] = 0;
            }
        }
        return $arr;
    }

    /**
     * 清空購物車
     */
    public static function clear()
    {
        // $member_cart = MemberCart::where('session_id', session()->getId())->get();
        // if ($member_cart->count()) {
            MemberCart::where('session_id', session()->getId())->delete();
        // }
    }

    /**
     * 更新折扣碼(未完成)
     */
    public static function updateCoupon($id, $qty, $price, $datas = [])
    {
        // 查詢購物車資料
        $member_cart_check = MemberCart::where('session_id', session()->getId())
            ->where('product_table', $datas['product_table'])
            ->where('product_id', $id)
            ->first();
        if (!is_null($member_cart_check)) {
            $qty = $member_cart_check->qty + $qty;
        }

        // 庫存檢查
        if ($datas['stock_quantity'] < $qty) {
            $qty = $datas['stock_quantity'];
        }

        // 寫入資料庫
        $shopping_cart = [
            'site_id' => Session::get('site.id'),
            'session_id' => session()->getId(),
            'product_id' => $id,
            'product_table' => $datas['product_table'],
            'name' => $datas['name'],
            'qty' => $qty,
            'price' => $price,
            'sub_total' => $price * $qty,
            'img' => $datas['img'],
            // 'shipping_fee' => $datas['shipping_fee'],
            'bulk' => serialize($datas['bulk']),
        ];
        if (Auth::id()) {
            $shopping_cart['user_id'] = Auth::id();
        }
        if (!is_null($member_cart_check)) {
            MemberCart::find($member_cart_check->id)->update($shopping_cart);
        } else {
            MemberCart::create($shopping_cart);
        }
        // if (Config::get('app.debug')) {
        //     Log::info('加入購物車');
        //     Log::info($shopping_cart);
        //     Log::info(Session::get('shopping_cart', '無'));
        // }
    }

    /**
     * 購物車數量
     */
    public static function count()
    {
        $member_cart = MemberCart::select('id', 'session_id')->where('session_id', session()->getId())->get();
        return $member_cart->count();
    }
}
