<?php

declare(strict_types=1);

namespace NetherGamesAssets;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class NetherGamesAPI
{
    private $curl;

    public function __construct()
    {
        $this->curl = $this->initializeCurl();
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }

    private function initializeCurl()
    {
        $curl = curl_init();

        // Set CURL options
        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer IjEwNDYzNTcxMTg2MDI0NDk0MTMi.ZWSLCA.Mdq_--kW28iX7YlxwdVjp9lWSEk'
            ],
        ];

        // Set multiple options for the curl handle
        curl_setopt_array($curl, $options);

        return $curl;
    }

    public function getPlayerStats(string $target)
    {
        $url = 'https://api.ngmc.co/v1/players/' . $target;

        curl_setopt($this->curl, CURLOPT_URL, $url);
        $response = curl_exec($this->curl);

        if ($response === false) {
            return curl_error($this->curl);
        } else {
            return json_decode($response, true);
        }
    }


    // public function getGuildStats(bool $fromPlayer = false, string $target = "", string $guild = "")
    // {

    //     $response = curl_exec($this->curl);
    //     if ($response === false) {
    //         return curl_error($this->curl);
    //     }
    //     if ($fromPlayer) {
    //         $url = 'https://api.ngmc.co/v1/players/' . $target;
    //         curl_setopt($this->curl, CURLOPT_URL, $url);
    //         $response = curl_exec($this->curl);
    //         $decodedResponse = json_decode($response, true);
    //         if ($decodedResponse['guild'] !== null && isset($decodedResponse['guild'])) {
    //             $guild = $decodedResponse['guild'];
    //         } else {
    //             $guild = "";
    //         }
    //     }

    //     $url = 'https://api.ngmc.co/v1/guilds/' . $guild;
    //     curl_setopt($this->curl, CURLOPT_URL, $url);
    //     $response = curl_exec($this->curl);
    //     $decodedResponse = json_decode($response, true);
    //     if (isset($decodedResponse)) {
    //         return json_encode($decodedResponse);
    //     } else {
    //         return true;
    //     }
    // }


    public function getGuildStats(bool $fromPlayer = false, string $target = "", string $guild = "")
    {
        if ($fromPlayer) {
            $url = 'https://api.ngmc.co/v1/players/' . $target;
            curl_setopt($this->curl, CURLOPT_URL, $url);
            $response = curl_exec($this->curl);
            $decodedResponse = json_decode($response, true);

            if ($decodedResponse !== null && isset($decodedResponse['guild'])) {
                $guild = $decodedResponse['guild'];
            } else {
                $guild = "";
            }
        }

        // Check if a valid URL is set before making the cURL request
        if (!empty($guild)) {
            $url = 'https://api.ngmc.co/v1/guilds/' . $guild;
            curl_setopt($this->curl, CURLOPT_URL, $url);
            $response = curl_exec($this->curl);
            $decodedResponse = json_decode($response, true);

            if ($decodedResponse !== null) {
                return $decodedResponse;
            }
        }

        return [];
    }
    public function getFactionStats(bool $fromPlayer = false, string $target = "", string $faction = "")
    {
        if ($fromPlayer) {
            $url = 'https://api.ngmc.co/v1/players/' . $target;
            curl_setopt($this->curl, CURLOPT_URL, $url);
            $response = curl_exec($this->curl);
            $decodedResponse = json_decode($response, true);

            if ($decodedResponse !== null && isset($decodedResponse['factionData']['faction'])) {
                $guild = $decodedResponse['factionData']['faction'];
            } else {
                $guild = "";
            }
        }

        // Check if a valid URL is set before making the cURL request
        if (!empty($guild)) {
            $url = 'https://api.ngmc.co/v1/factions/' . $faction;
            curl_setopt($this->curl, CURLOPT_URL, $url);
            $response = curl_exec($this->curl);
            $decodedResponse = json_decode($response, true);

            if ($decodedResponse !== null) {
                return $decodedResponse;
            }
        }

        return [];
    }

    public function getPlayerName(string $target)
    {
        $playerStats = $this->getPlayerStats($target);
        if (isset($playerStats['code'])) {
            return 0;
        }
        $name = $playerStats['name'];
        return $name;
    }
}
