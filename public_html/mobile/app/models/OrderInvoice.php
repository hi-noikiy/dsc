<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class OrderInvoice extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'order_invoice';
	protected $primaryKey = 'invoice_id';
	public $timestamps = false;
	protected $fillable = array('user_id', 'inv_payee');
	protected $guarded = array();
}

?>