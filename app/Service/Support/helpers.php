<?php 

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\File;

if(! function_exists('microtime_float')){
    /**
     * Get request time of application
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function microtime_float(){
        // list($usec, $sec) = explode(" ", microtime());
        // return ((float)$usec + (float)$sec);
        return number_format(microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"], 2, '.', '');
    }
}

if(! function_exists('__v')){
    /**
     * Get theme version of application
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function __v(){
        return env('APP_THEME');
    }
}

if(! function_exists('__app_name')){
    /**
     * Get name of application
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function __app_name(){
        return config('app.name');
    }
}

if(! function_exists('__prefix')){
    /**
     * Get prefix of application
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function __prefix(){
        return env('APP_PREFIX');
    }
}

if(! function_exists('is_route')){
    /**
     * Checker routing
     * 
     * @return boolean
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function is_route($route){
        $arrayRoutes = [
            'login', 'register', 'password.request', 'password.reset', 'verification.notice', 'verification.verify', 'verification.resend', 'configuration.index', 'installation.index'
        ];
        if(in_array($route, $arrayRoutes)){
            return true;
        }
        return false;
    }
}

if(! function_exists('nestable_render')){
    /**
     * Get list nestable menu
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function nestable_render(array $data, string $list = '', $edit = null){
        $tooltip = auth()->user()->canStoreMenu() && $edit == true ? tooltip('Click to edit') : '';
        foreach ($data as $v):
            $list .= '<li class="dd-item dd3-item" data-id="'. $v['id'] .'" id="'. $v['id'] .'">';
                $list .= '<div class="dd-handle dd3-handle"></div>';
                $list .= '<div class="dd3-content to-update"><span '. $tooltip .'>'. ucwords($v[app()->getLocale().'_name']) .'</span></div>';
            if (count($v['children']) > 0):
                $list .= '<ol class="dd-list">';
                    $list = nestable_render($v['children'], $list);
                $list .= '</ol>';
            endif;
            $list .= '</li>';
        endforeach;
        return new HtmlString($list);
    }
}

if(! function_exists('callout')){
    /**
     * Get callout component
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function callout($title, $description, $type, $icon = false, $dimmis = false){
        if(!$icon){
            $mt = 'error_outline';
        }else{
            $mt = $icon;
        }
        $dimmisable = "";
        if($dimmis){
            $dimmisable = " callout-dimmis";
        }
        $html = '<div class="callout callout-' . $type . $dimmisable . '">';
            $html .= '<h4><i class="material-icons">' . $mt .'</i> ' . $title . '</h4>';
            $html .= '<p>' . ucfirst($description) . '</p>';
        $html .= '</div>';
        return new HtmlString($html);
    }
}

if(! function_exists('callout_default')){
    /**
     * Get callout component type default
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function callout_default($description, $dimmis = false, $icon = false){
        return callout('Information...', $description, 'default', $icon = false, $dimmis);
    }
}

if(! function_exists('callout_primary')){
    /**
     * Get callout component type primary
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function callout_primary($description, $dimmis = false, $icon = false){
        return callout('Information...', $description, 'primary', $icon = false);
    }
}

if(! function_exists('callout_info')){
    /**
     * Get callout component type info
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function callout_info($description, $dimmis = false, $icon = false){
        return callout('Information...', $description, 'info', $icon = false);
    }
}

if(! function_exists('callout_success')){
    /**
     * Get callout component type success
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function callout_success($description, $dimmis = false, $icon = false){
        return callout('Successfully...', $description, 'success', $icon = false);
    }
}

if(! function_exists('callout_warning')){
    /**
     * Get callout component type warning
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function callout_warning($description, $dimmis = false, $icon = false){
        return callout('Warning...', $description, 'warning', $icon = false);
    }
}

if(! function_exists('callout_danger')){
    /**
     * Get callout component type danger
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function callout_danger($description, $dimmis = false, $icon = false){
        return callout('Oops...', $description, 'danger', $icon = false);
    }
}

if (! function_exists('alert')) {
    /**
     * Get alert component
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function alert($title, $description, $type){
        $html = '<div class="alert alert-'.$type.' alert-dismissible">';
            $html .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true" '. tooltip('Remove') .'>×</button>';
            $html .= '<h4>'.$title.'</h4>';
            $html .= $description;
        $html .= '</div>';
        return new HtmlString($html);
    }
}

if(! function_exists('alert_default')){
    /**
     * Get callout component type default
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function alert_default($description){
        return alert('Information...', $description, 'default');
    }
}

if(! function_exists('alert_primary')){
    /**
     * Get callout component type primary
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function alert_primary($description){
        return alert('Information...', $description, 'primary');
    }
}

if(! function_exists('alert_info')){
    /**
     * Get callout component type info
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function alert_info($description){
        return alert('Information...', $description, 'info');
    }
}

if(! function_exists('alert_success')){
    /**
     * Get callout component type success
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function alert_success($description){
        return alert('Successfully...', $description, 'success');
    }
}

if(! function_exists('alert_warning')){
    /**
     * Get callout component type warning
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function alert_warning($description){
        return alert('Warning...', $description, 'warning');
    }
}

if(! function_exists('alert_danger')){
    /**
     * Get callout component type danger
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function alert_danger($description){
        return alert('Oops...', $description, 'danger');
    }
}

if(! function_exists('box_collapse')){
    /**
     * Get button box collapsing
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function box_collapse($tooltip){
        return new HtmlString('<button type="button" class="btn btn-box-tool" data-widget="' . $tooltip . '" data-toggle="tooltip" title="' . ucwords($tooltip) . '"><i class="fa fa-minus"></i></button>');
    }
}

if(! function_exists('box_remove')){
    /**
     * Get button box remover
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function box_remove($tooltip){
        return new HtmlString('<button type="button" class="btn btn-box-tool" data-widget="' . $tooltip . '" data-toggle="tooltip" title="' . ucwords($tooltip) . '"><i class="material-icons">clear</i></button>');
    }
}

if(! function_exists('box_footer')){
    /**
     * Get button box footer
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function box_footer(){
        return new HtmlString('<div class="box-footer"><center>.:: ' . __app_name() . ' ::.</center></div>');
    }
}

if(! function_exists('str_title')){
    /**
     * Get title from string
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function str_title($string = null){
        if(is_null($string)){
            $string = str_replace(__prefix(), '', Str::slug(Request::path()));
            return ucwords(Str::slug($string, ' '));
        }
        return ucwords(Str::slug($string, ' '));
    }
}

if(! function_exists('tooltip')){
    /**
     * Get tooltip
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function tooltip($title, $position = null){
        $placement = is_null($position) ? '' : "data-placement={$position}";
        return new HtmlString($placement.' data-toggle="tooltip" title="' . ucwords($title) . '"');
    }
}

if(! function_exists('modal')){
    /**
     * Get modal target
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function modal($target){
        return new HtmlString('data-toggle="modal" data-target="#' . $target . '"');
    }
}

if (! function_exists('loading_button')) {
    /**
     * Get button loading
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function loading_button($type, $text){
        return new HtmlString('<button type="submit" class="btn btn-'.$type.' btn-flat pull-right ld-ext-left btn-loading"><div class="ld ld-ring ld-spin-fast" style="font-size:1.5em"></div> '.ucwords($text).'</button>');
    }
}

if(! function_exists('carbon')){
    /**
     * Get carbon instance
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function carbon(){
        return new Carbon('Asia/Jakarta');
    }
}

if(! function_exists('roman')){
    /**
     * Get roman generator
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function roman($number){
        $map = [
            'M' => 1000, 
            'CM' => 900, 
            'D' => 500, 
            'CD' => 400, 
            'C' => 100, 
            'XC' => 90, 
            'L' => 50, 
            'XL' => 40, 
            'X' => 10, 
            'IX' => 9, 
            'V' => 5, 
            'IV' => 4, 
            'I' => 1
        ];
        $returnValue = '';
        while (intVal($number) > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}

if (! function_exists('email')) {
    /**
     * Get replacment email string
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function email($str){
        $user = substr_replace(explode('@', $str)[0], '*****', strlen(explode('@', $str)[0])-6, -1);
        $domain = explode('@', $str)[1];
        return "{$user}@{$domain}";
    }
}

if (! function_exists('toPrice')) {
    /**
     * Get price from string
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function toPrice($str){
        return "Rp" . number_format($str, 2, ',', '.');
    }
}

if (! function_exists('toFloat')) {
    /**
     * Get float from string
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function toFloat($str){
        return floatVal($str);
    }
}

if (! function_exists('toPriceFloat')) {
    /**
     * Get price float from string
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function toPriceFloat($str){
        return floatVal(str_replace(['Rp', '.'], ['', ''], $str));
    }
}

if (! function_exists('toPercent')) {
    /**
     * Get percent from string
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function toPercent($str){
        $value = number_format($str, 2, '.', '');
        return "{$value}%";
    }
}

if(! function_exists('lang')) {
    /**
     * Get language of application
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function lang(){
        if(app()->getLocale() == 'en'){
            return app()->getLocale() . '_US';
        }
        return app()->getLocale() . '_' . strtoupper(app()->getLocale());
    }
}

if(!function_exists('color_shuffle')){
    /**
     * Get color suffle
     * 
     * @return string
     * 
     * @author ken <wahyu.dhiraashandy8@gmail.com>
     * @since @version 0.1
     */
    function color_shuffle(){
        $shuffle = [
            'btn-black', 'btn-dark_light', 'btn-blue', 'btn-blue_light', 'btn-green', 'btn-aqua', 'btn-orange', 'btn-purple', 'btn-indigo', 'btn-pink', 'btn-fusia', 'btn-teal', 'btn-cyan', 'btn-grey', 'btn-red', 'btn-red_light', 'btn-yellow', 'btn-dark', 'btn-online', 'btn-code_blue', 'btn-code_blue_light', 'btn-batman',
        ];
        return collect($shuffle)->shuffle()->first();
    }
}