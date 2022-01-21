<?php namespace App\Http\Controllers;

		use Session;
		use Request;
		use DB;
		use CRUDBooster;
		use App\Models\Device;
		use App\Models\Category;
		use App\Models\OpenTransaction;
		use App\Models\ClosedTransaction;

		class ApiGetLotController extends \crocodicstudio\crudbooster\controllers\ApiController {

		    function __construct() {
				$this->table       = "lots";
				$this->permalink   = "get_lot";
				$this->method_type = "get";
		    }
		

		    public function hook_before(&$postdata) {
		        //This method will be execute before run the main process

		    }

		    public function hook_query(&$query) {
		        //This method is to customize the sql query

		    }

		    public function hook_after($postdata,&$result) {
				$device = Device::select(['id', 'device_code', 'phone'])->where([
					'parking_id' => $postdata['id']
				])->first();

				$closed_trx = ClosedTransaction::where([
					'parking_id' => $postdata['id']
				])
				->where(DB::raw('DATE(stop_at)'), now()->format('Y-m-d'))
				->orderBy('stop_at', 'desc')->get();

				$open_trx = OpenTransaction::where([
					'parking_id' => $postdata['id'],
				])->get();

				$cats = [];
				$categories = Category::where('status', 'Active')->get();
				foreach($categories as $category) {
					$cats[$category->id] = 0;
					foreach($open_trx as $trx) {
						if($trx->category_id == $category->id) {
							$cats[$category->id]+=1;
						}
					}

					foreach($closed_trx as $trx) {
						if($trx->category_id == $category->id) {
							$cats[$category->id]+=1;
						}
					}
				}

				$result['data']['device'] = $device;
				$result['data']['open_trx'] = $open_trx;
				$result['data']['closed_trx'] = $closed_trx;
				$result['data']['categories'] = $cats;
				$result['data']['todayIncome'] = number_format($closed_trx->sum('total'));
				$result['data']['openBill'] = number_format($open_trx->count());
				$result['data']['closedBill'] = number_format($closed_trx->count());
		    }

		}