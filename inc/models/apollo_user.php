<?php
namespace PawsPlus\Doorknob\Models;

class ApolloUser
{

	public function __construct( array $attributes )
	{
		$this->uuid      = $attributes['unique_id'];
		$this->id        = $attributes['id'];
		$this->time_zone = $attributes['time_zone'];
	}

}
