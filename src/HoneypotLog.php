<?php

namespace Spatie\Honeypot;

use Illuminate\Database\Eloquent\Model;

class HoneypotLog extends Model
{
	protected $table = 'honeypot_logs';

	protected $fillable = [
		'method',
		'url',
		'form_payload',
		'headers'
	];
}
