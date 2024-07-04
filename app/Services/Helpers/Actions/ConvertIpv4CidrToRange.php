<?php

namespace App\Services\Helpers\Actions;

class ConvertIpv4CidrToRange
{
    public function execute(string $ipv4Network): array
    {
        if ($ip = strpos($ipv4Network,'/')) {
            $n_ip = (1<<(32-substr($ipv4Network,1+$ip)))-1;
            $ip_dec = ip2long(substr($ipv4Network,0,$ip));
        } else {
            $n_ip = 0;
            $ip_dec = ip2long($ipv4Network);
        }

        $ip_min=$ip_dec&~$n_ip;
        $ip_max=$ip_min+$n_ip;

        $result = [$ip_min,$ip_max];

        $fullRange = [];
        for($ip_dec=$result[0];$ip_dec<=$result[1];$ip_dec++) {
            $fullRange[$ip_dec]=long2ip($ip_dec);
        }

        return $fullRange;
    }
}
