<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class SellerShopheader extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'seller_shopheader';
	public $timestamps = false;
	protected $fillable = array('content', 'headtype', 'headbg_img', 'shop_color', 'seller_theme', 'ru_id');
	protected $guarded = array();
}

?>
