<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class Suppliers extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'suppliers';
	protected $primaryKey = 'suppliers_id';
	public $timestamps = false;
	protected $fillable = array('suppliers_name', 'suppliers_desc', 'is_check');
	protected $guarded = array();
}

?>
