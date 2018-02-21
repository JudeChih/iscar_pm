<?php

namespace App\Repositories;

use App\Models\IsCarRegionList;
use DB;

class IsCarRegionListRepository  {

	/**
	 * 透過會員提供的郵寄區號取得店家所在的縣市，並且取得該縣市的編號
	 * @param  [type] $sd_zipcode [description]
	 */
	public function getCityCode($sd_zipcode){
		return IsCarRegionList::where('rl_zip',$sd_zipcode)->get();
	}

}