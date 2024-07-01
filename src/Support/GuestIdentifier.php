<?php

namespace Andrewtweber\Support;

use Illuminate\Http\Request;
use Leth\IPAddress\IP\Address;
use Leth\IPAddress\IPv6\NetworkAddress;

class GuestIdentifier
{
    public string $ip_address;

    public function __construct(string $ip_address)
    {
        $this->ip_address = $this->convertIpv6Address($ip_address);
    }

    public static function make(Request $request): GuestIdentifier
    {
        $ip = $request->getClientIp();

        return (new GuestIdentifier($ip));
    }

    /**
     * @param string   $ip_address
     * @param int|null $cidr
     *
     * @return string
     */
    public function convertIpv6Address(string $ip_address, ?int $cidr = null): string
    {
        $ip = Address::factory($ip_address);

        if ($ip instanceof \Leth\IPAddress\IPv4\Address) {
            return $ip_address;
        }

        $cidr ??= config('polls.guest_ipv6_cidr', 64);

        $network = NetworkAddress::factory($ip, $cidr);

        return (string)$network->get_network_start();
    }
}
