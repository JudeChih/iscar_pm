<?php

namespace App\Http\Controllers\ViewControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
	public function index() {

		$scm_r = new \App\Repositories\ICR_ShopCouponData_mRepository;
		$sd_r = new \App\Repositories\ICR_ShopDataRepository;
		$coupondata = $scm_r->getAllData();
		foreach ($coupondata as $val) {
			$dt = \Carbon\Carbon::parse($val['last_update_date']);
			$dt = $dt->toW3cString();
			$val['last_update_date'] = $dt;
		}
		$shopdata = $sd_r->getDataByActiveStatus();
		foreach ($shopdata as $val) {
			$dt = \Carbon\Carbon::parse($val['last_update_date']);
			$dt = $dt->toW3cString();
			$val['last_update_date'] = $dt;
		}
        $otherdata['coupon_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/pm/shopcoupon-info';
        $otherdata['shop_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/pm/branch-info';
        return response()->view('/sitemap/sitemap', [
	        'shopdata' => $shopdata,
	        'coupondata' => $coupondata,
	        'otherdata' => $otherdata,
	    ])->header('Content-Type', 'text/xml;charset=utf-8');
	}

}
