<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ZcGoods extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'zc_goods';
	public $timestamps = false;
	protected $fillable = array('pid', 'limit', 'backer_num', 'price', 'shipping_fee', 'content', 'img', 'return_time', 'backer_list');
	protected $guarded = array();
}

?>
