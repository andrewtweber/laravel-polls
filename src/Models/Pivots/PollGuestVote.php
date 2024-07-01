<?php

namespace Andrewtweber\Models\Pivots;

use Andrewtweber\Support\GuestIdentifier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\DB;

/**
 * Class PollGuestVote
 *
 * @package Andrewtweber\Models\Pivots
 *
 * @property int    $poll_id
 * @property int    $poll_option_id
 * @property string $ip_address
 */
class PollGuestVote extends Pivot
{
    protected $table = 'poll_guest_votes';

    public $timestamps = false;

    /**
     * @param Builder         $query
     * @param GuestIdentifier $id
     */
    public function scopeForGuest(Builder $query, GuestIdentifier $id): void
    {
        $query->fromIp($id->ip_address);
    }

    /**
     * @param Builder     $query
     * @param string|null $ip_address
     */
    public function scopeFromIp(Builder $query, ?string $ip_address): void
    {
        if ($ip_address) {
            $query->where('ip_address', DB::raw("INET6_ATON('{$ip_address}')"));
        }
    }

    /**
     * @return string|null
     */
    public function getIpAddressAttribute(?string $ip_address): ?string
    {
        if ($ip_address === null) {
            return null;
        }

        return inet_ntop($ip_address);
    }

    /**
     * @param string|null $ip_address
     */
    public function setIpAddressAttribute(?string $ip_address)
    {
        $this->attributes['ip_address'] = $ip_address ? inet_pton($ip_address) : null;
    }
}
