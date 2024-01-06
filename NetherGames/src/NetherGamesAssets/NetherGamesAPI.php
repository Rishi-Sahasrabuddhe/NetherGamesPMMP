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

    public function getFactionStats(string $factionArg0, bool $fromPlayer = false, string $target = "", string $faction = "")
    {

        $response = curl_exec($this->curl);
        if ($response === false) {
            return curl_error($this->curl);
        } else {
            if ($fromPlayer) {
                $url = 'https://api.ngmc.co/v1/players/' . $target;
                curl_setopt($this->curl, CURLOPT_URL, $url);
                $response = curl_exec($this->curl);
                $decodedResponse = json_decode($response, true);

                if (isset($decodedResponse['factionData']) && $decodedResponse['factionData'] !== null) {
                    if ($decodedResponse['factionData']['faction'] !== null && isset($decodedResponse['factionData']['faction']['name'])) {
                        $faction = $decodedResponse['factionData']['faction']['name'];
                    } else {
                        $faction = "";
                    }
                } else {
                    $faction = "";
                }
            }

            $url = 'https://api.ngmc.co/v1/factions/' . $faction;
            curl_setopt($this->curl, CURLOPT_URL, $url);
            $response = curl_exec($this->curl);
            $decodedResponse = json_decode($response, true);
            if (isset($decodedResponse[$factionArg0])) {
                return json_encode($decodedResponse[$factionArg0]);
            } else {
                return true;
            }
        }
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
