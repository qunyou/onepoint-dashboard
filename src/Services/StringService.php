<?php

namespace App\Services;

use Str;

/**
 * 字串輔助方法
 */
class StringService
{
    /**
     * 網址中文encode處理
     * @param  string $str         要轉換的字串
     * @param  string $replace_str 代替斜線的網址
     * @return string
     */
    static function tcEncode($str, $replace_str = '{{slash}}'){
        // 取代 base64 encode 中的斜線
        return str_replace('/', $replace_str, base64_encode($str));
    }

    /**
     * 網址中文decode處理
     * @param  string $str         要轉換的字串
     * @param  string $replace_str 代替斜線的網址
     * @return string
     */
    static function tcDecode($str, $replace_str = '{{slash}}'){
        return base64_decode(str_replace($replace_str, '/', $str));
    }

    /**
     * 產生亂數文字
     */
    static function generateRandomString($length = 10, $characters = '') {
        if (empty($characters)) {

            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            // $characters = '天地玄黃宇宙洪荒日月盈昃辰宿列張寒來暑往秋收冬藏閏餘成歲律呂調陽雲騰致雨露結為霜金生麗水玉出崑岡劍號巨闕珠稱夜光果珍李柰菜重芥薑海鹹河淡麟潛羽翔龍獅火帝鳥官人皇始制文字乃服衣裳推位讓國有虞陶唐弔民伐罪周發商湯坐朝問道垂拱平章愛育黎首臣伏戎羌遐邇壹體率賓歸王鳴鳳在竹白駒食場化被草木賴及萬方蓋此身髮四大五常恭惟鞠養豈敢毀傷女慕貞烈男效才良知過必改得能莫忘罔談彼短靡恃己長信使可復器欲難量莫悲絲染詩讚羔羊景行惟賢克念作聖德建名立形端表正空谷傳聲虛堂習聽禍因惡積福緣善慶尺璧非寶寸陰是競資父事君曰言與敬孝當竭力忠則盡命臨深履薄夙興溫凊似蘭斯馨如松之盛川流不息淵澄取映容止若思言辭安定篤初誠美慎終宜令榮業所基籍甚無竟學優登仕攝職從政存以甘棠去而益詠樂殊貴賤禮別尊卑上和下睦夫唱婦隨外受傅訓入奉母儀諸姑伯叔猶子比兒孔懷兄弟同氣連枝交友投分切磨箴規仁慈隱惻造次弗離節義廉退顛沛匪虧性靜情逸心動神疲守真志滿逐物意移堅持雅操好爵自縻都邑華夏東西二京背邙面洛浮渭據涇宮殿盤鬱樓觀飛驚圖寫禽獸畫彩仙靈丙舍傍啟甲帳對楹肆筵設席鼓瑟吹笙陞階納陛弁轉疑星右通廣內左達承明既集墳典亦聚群英杜槁鍾隸漆書壁經府羅將相路俠槐卿戶封八縣家給千兵高冠陪輦驅轂振纓世祿侈富車駕肥輕策功茂實勒碑刻銘磻溪伊尹佐時阿衡奄宅曲阜微旦孰營桓公匡合濟弱扶傾綺迴漢會說感武丁俊刈密勿多士寔寧晉楚更霸趙魏困橫假途滅虢踐土會盟何遵約法韓弊煩刑起翦頗牧用軍最精宣威沙漠馳譽丹青九州禹跡百郡秦并嶽宗泰岱禪主云亭雁門紫塞雞田赤城昆池碣石鉅野洞庭曠遠綿邈巖岫杳冥治本於農務茲稼穡俶載南畝我藝黍稷稅熟貢新勸賞黜陟孟軻敦素史魚秉直庶幾中庸勞謙謹敕聆音察理鑑貌辨色貽厥嘉猶勉其祗植省躬譏誡寵增抗極殆辱近恥臨皋幸即兩疏見機解組誰逼索居閒處沉默寂寥求古尋論散慮逍遙欣奏累遣慼謝歡招渠荷的歷園莽抽條枇杷晚翠梧桐早凋陳根委翳落葉飄颻游鵾獨運凌摩絳霄耽讀翫市寓目囊箱易輶攸畏屬耳垣牆具膳餐飯適口充腸飽飫烹宰饑厭糟糠親戚故舊老少異糧妾御績紡侍巾帷房紈扇圓潔銀燭煒煌晝眠夕寐藍筍象床絃歌酒讌接杯舉觴矯手頓足悅豫且康嫡後嗣續祭祀蒸嘗稽顙再拜悚懼恐惶牋牒簡要顧答審詳骸垢想浴執熱願涼驢騾犢特駭躍超驤誅斬賊盜捕獲叛亡布射遼丸嵇琴阮嘯恬筆倫紙鈞巧任釣釋紛利俗並皆佳妙毛施淑姿工顰妍笑年矢每催曦暉朗曜璇璣懸斡晦魄環照指薪修祜永綏吉劭矩步引領俯仰廊廟束帶矜莊徘徊瞻眺孤陋寡聞愚蒙等誚謂語助者焉哉乎也';
        }
        $charactersLength = mb_strlen($characters, 'UTF-8');
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $rand_start = rand(0, $charactersLength - 1);
            $randomString .= mb_substr($characters, $rand_start, 1, 'UTF-8');
        }
        return $randomString;
    }

    /**
     * 產生亂數段落
     */
    public function generateRandomParagraph() {
        $paragraph = rand(1, 5);
        $paragraph_str = '';
        for ($i=0; $i < $paragraph; $i++) {
            $sentence = rand(5, 20);
            $str = '<p>';
            for ($j=0; $j < $sentence; $j++) {
                $str .= $this->generateRandomString(rand(3, 15)).$this->generateRandomString(1, '，！？；');
            }
            $str .= $this->generateRandomString(rand(3, 15)).'。<p>';
            $paragraph_str .= $str;
        }
        return $paragraph_str;
    }

    /**
     * 截斷HTML文字
     */
    public static function htmlLimit($str, $limit, $suffix = '') {
        // return str_limit(Self::stripHtml($str), $limit, $suffix);

        // L6 fix
        return Str::limit(Self::stripHtml($str), $limit, $suffix);
    }

    /**
     * 清除HTML格式
     *
     * 內容如果全部都是html組成，例如全部都是 img 標籤，最後會返回 false，因為拿掉 html tag 後，就沒有任何文字了，要注意
     */
    public static function stripHtml($str) {
        return preg_replace("/&#?[a-z0-9]+;/i", "", strip_tags($str));
    }

    /**
     * 檢查editor欄位是否有內容
     */
    public function checkHtmlContent($str) {
        if (Str::length(trim($this->stripHtml($str)))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 身份證號碼檢查
     */
    static function checkTwId($id) {
        $err = '';
        $res = [
            'message' => '',
            'result' => false
        ];

        // 先將字母數字存成陣列
        $alphabet =['A'=>'10','B'=>'11','C'=>'12','D'=>'13','E'=>'14','F'=>'15','G'=>'16','H'=>'17','I'=>'34',
                    'J'=>'18','K'=>'19','L'=>'20','M'=>'21','N'=>'22','O'=>'35','P'=>'23','Q'=>'24','R'=>'25',
                    'S'=>'26','T'=>'27','U'=>'28','V'=>'29','W'=>'32','X'=>'30','Y'=>'31','Z'=>'33'];
        // 檢查字元長度
        if (strlen($id) !=10){$err = '1';}

        // 驗證英文字母正確性
        $alpha = substr($id,0,1);
        $alpha = strtoupper($alpha);
        if (!preg_match("/[A-Za-z]/",$alpha)) {
            $err = '2';
        } else {

            // 計算字母總和
            $nx = $alphabet[$alpha];
            $ns = $nx[0]+$nx[1]*9;//十位數+個位數x9
        }

        //驗證男女性別
        $gender = substr($id,1,1);
        if($gender !='1' && $gender !='2'){$err = '3';}

        // N2x8+N3x7+N4x6+N5x5+N6x4+N7x3+N8x2+N9+N10
        if ($err =='') {
            $i = 8;
            $j = 1;
            $ms =0;

            // 先算 N2x8 + N3x7 + N4x6 + N5x5 + N6x4 + N7x3 + N8x2
            while ($i >= 2) {

                // 由第j筆每次取一個數字
                $mx = substr($id,$j,1);

                // N*$i
                $my = $mx * $i;

                // ms為加總
                $ms = $ms + $my;
                $j+=1;
                $i--;
            }

            //最後再加上 N9 及 N10
            $ms = $ms + substr($id,8,1) + substr($id,9,1);

            // 最後驗證除10
            // 上方的英文數字總和 + N2~N10總和
            $total = $ns + $ms;
            if( ($total%10) !=0){$err = '4';}
        }

        // 錯誤訊息返回
        switch($err){
            case '1':$res['message'] = '字元數錯誤';break;
            case '2':$res['message'] = '英文字母錯誤';break;
            case '3':$res['message'] = '性別錯誤';break;
            case '4':$res['message'] = '驗證失敗';break;
            default:
                $res['message'] = '驗證通過';
                $res['result'] = true;
                break;
        }
        return $res;
    }

    /**
     * Email格式檢查
     */
    static function checkEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * 驗證台灣手機號碼
     */
    static function checkPhone($str) {
        if (preg_match("/^09[0-9]{2}-[0-9]{3}-[0-9]{3}$/", $str)) {
            // 09xx-xxx-xxx
            return true;
        } else if(preg_match("/^09[0-9]{2}-[0-9]{6}$/", $str)) {
            // 09xx-xxxxxx
            return true;
        } else if(preg_match("/^09[0-9]{8}$/", $str)) {
            // 09xxxxxxxx
            return true;
        } else {
            return false;
        }
    }
}
