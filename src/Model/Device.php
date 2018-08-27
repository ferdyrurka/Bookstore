<?php


namespace App\Model;

/**
 * Class Device
 * @package App\Model
 */
class Device
{
    /**
     * @return array
     */
    private function devices(): array
    {
        return array(
            'iPhone' => 'iPhone',
            'iPod' => 'iPod',
            'iPad' => 'iPad',
            'Android' => 'Android',
            'Mac OS X' => 'Mac OS X',
            'Windows 10' => 'Windows NT 10',
            'Windows 8.1' => 'Windows NT 6.3',
            'Windows 8' => 'Windows NT 6.2',
            'Windows 7' => 'Windows NT 6.1',
            'Windows Vista' => 'Windows NT 6.0',
            'Windows XP' => 'Windows NT 5.1',
            'Linux' => 'Linux x86_64',
            'Fedora' => 'Fedora',
        );
    }


    /**
     * @param string $userAgent
     * @return string
     */
    public function get(string $userAgent): string
    {
        if (empty($userAgent)) {
            return 'other';
        }

        foreach ($this->devices() as $device => $needle) {
            if (strpos($userAgent, $needle) != false) {
                return $device;
            }
        }

        return 'other';
    }
}
