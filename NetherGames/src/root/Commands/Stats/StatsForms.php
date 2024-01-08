<?php

declare(strict_types=1);

namespace root\Commands\Stats;

use \DateTime;
use \DateTimeZone;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\player\Player;

use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;

use NetherGamesAssets\Cache;

class StatsForms
{
    private ?\NetherGamesAssets\NetherGamesAPI $netherGamesAPI = null;
    private ?Cache $cache = null;

    public function __construct()
    {
        $this->netherGamesAPI = new \NetherGamesAssets\NetherGamesAPI();
        $this->cache = new Cache();
    }

    private function getOnlineStatus($target)
    {

        if (Cache::$playerCached && Cache::$playerStatsCached['name'] === $target) {
            if (Cache::$playerStatsCached !== null) {
                $onlineState = Cache::$playerStatsCached["online"];
                $bannedState = Cache::$playerStatsCached["banned"];
                if ($onlineState === false) {
                    return "§l§cOFFLINE§r";
                } elseif ($bannedState === true) {
                    return "§l§cBANNED§r";
                } else {
                    return "§l§aONLINE§r";
                }
            }
            return "§cUNKNOWN";
        } else {
            $this->cache->cachePlayer($target);
            return $this->getOnlineStatus($target);
        }
    }


    public function statsSelectionForm(Player $player, string $target = '')
    {

        if ($target === '') {
            $target = $player->getName();
        }


        $form = new SimpleForm(function (Player $player, int $data = null) use ($target) {
            if ($data === null) {
                return true;
            }

            switch ($data) {
                case 0:
                    $this->generalStatsForm($player, $target);
                    break;
                case 1:
                    $this->gameStatsSelectionForm($player, $target);
                    break;
                case 2:
                    $this->playerInfoForm($player, $target);
                    break;
            }
        });
        $onlineStatus = $this->getOnlineStatus($target);

        $form->setTitle("[ $onlineStatus ]  " . $target . "'s Stats");
        $form->setContent("Which stats form would you like to view?");
        $form->addButton("General Stats");
        $form->addButton("Game Stats");
        $form->addButton("Player Info");
        $form->sendToPlayer($player);
        Cache::cacheFaction(true, $target); //s
        return $form;
    }

    public function generalStatsForm(Player $player, string $target = '')
    {

        if ($target === '') {
            $target = $player->getName();
        }


        $form = new CustomForm(function (Player $player, ?array $data) use ($target) {
            if ($data === null) {
                $this->statsSelectionForm($player, $target);
                return true;
            }
        });

        $onlineStatus = $this->getOnlineStatus($target);


        $form->setTitle("[ $onlineStatus ]  " . $target . "'s Stats");
        $form->addLabel("Kills: " . Cache::$playerStatsCached["kills"]);
        $form->addLabel("Deaths: " . Cache::$playerStatsCached["deaths"]);
        $form->addLabel("KDR: " . Cache::$playerStatsCached["kdr"]);
        $form->addLabel("Level: " . Cache::$playerStatsCached["level"]);
        $form->addLabel("XP: " . Cache::$playerStatsCached["xp"]);
        $form->addLabel("XP to Next Level: " . Cache::$playerStatsCached["xpToNextLevel"]);
        $this->statsSelectionForm($player, $target);
        $form->sendToPlayer($player);
    }

    public function gameStatsSelectionForm(Player $player, string $target = '')
    {


        if ($target === '') {
            $target = $player->getName();
        }

        $form = new SimpleForm(function (Player $player, int $data = null) use ($target) {
            if ($data === null) {
                $this->statsSelectionForm($player, $target);
                return true;
            }

            switch ($data) {
                case 0:
                    $this->gameStats($player, 'bw', $target);
                    break;
                case 1:
                    $this->gameStats($player, 'sw', $target);
                    break;
                case 2:
                    $this->gameStats($player, 'duels', $target);
                    break;
                case 3:
                    $this->gameStats($player, 'tb', $target);
                    break;
                case 4:
                    $this->gameStats($player, 'cq', $target);
                    break;
                case 5:
                    $this->gameStats($player, 'factions', $target);
                    break;
                case 6:
                    $this->gameStats($player, 'mm', $target);
                    break;
                case 7:
                    $this->gameStats($player, 'ac', $target);
                    break;
                case 8:
                    $this->gameStats($player, 'uhc', $target);
                    break;
                case 9:
                    $this->gameStats($player, 'sg', $target);
                    break;
            }
        });

        $onlineStatus = $this->getOnlineStatus($target);

        $form->setTitle("[ $onlineStatus ]  " . $target . "'s Stats");
        $form->setContent("Which gamemoode stats would you like to view?");
        $form->addButton("Bedwars");
        $form->addButton("Skywars");
        $form->addButton("Duels");
        $form->addButton("The Bridge");
        $form->addButton("Conquests");
        $form->addButton("Factions");
        $form->addButton("Murder Mystery");
        $form->addButton("Arcade");
        $form->addButton("UHC");
        $form->addButton("Survival Games");
        $form->sendToPlayer($player);
    }

    public function gameStats(Player $player, string $gamecode, string $target = '')
    {

        if ($target === '') {
            $target = $player->getName();
        }


        $form = new CustomForm(function (Player $player, ?array $data) use ($target) {
            if ($data === null) {
                return true;
            }
        });
        $onlineStatus = $this->getOnlineStatus($target);

        $gameStats = "";
        $data = Cache::$playerStatsCached['extra'];

        switch ($gamecode) {
            case 'bw':
                $gameStats = "Bedwars";

                $bwWins = $data['bwWins'];
                $bwBedsBroken = $data['bwBedsBroken'];
                $bwKills = $data['bwKills'];
                $bwDeaths = $data['bwDeaths'];
                $bwFinalKills = $data['bwFinalKills'];

                $form->addLabel("$gameStats Wins: " . $bwWins);
                $form->addLabel("$gameStats Beds Broken: " . $bwBedsBroken);
                $form->addLabel("$gameStats Kills: " . $bwKills);
                $form->addLabel("$gameStats Deaths: " . $bwDeaths);

                if ($bwDeaths === 0) {
                    $form->addLabel("$gameStats KDR: " . $bwKills);
                } else {
                    $form->addLabel("$gameStats KDR: " . round($bwKills / $bwDeaths, 2));
                }

                $form->addLabel("$gameStats Final Kills: " . $bwFinalKills);
                break;
            case 'sw':
                $gameStats = "Skywars";
                $wins = $data['swWins'];
                $losses = $data['swLosses'];
                $kills = $data['swKills'];
                $deaths = $data['swDeaths'];
                $coins = $data['swCoins'];

                $form->addLabel("$gameStats Wins: " . $wins);
                $form->addLabel("$gameStats Losses: " . $losses);
                if ($losses === 0) {
                    $form->addLabel("$gameStats WLR: " . $wins);
                } else {
                    $form->addLabel("$gameStats WLR: " . round($wins / $losses, 2));
                }
                $form->addLabel("$gameStats Kills: " . $kills);
                $form->addLabel("$gameStats Deaths: " . $deaths);
                if ($deaths === 0) {
                    $form->addLabel("$gameStats KDR: " . $kills);
                } else {
                    $form->addLabel("$gameStats KDR: " . round($kills / $deaths, 2));
                }
                $form->addLabel("$gameStats Coins: " . $coins);
                break;
            case 'duels':
                $gameStats = "Duels";

                $wins = $data['duelsWins'];
                $losses = $data['duelsLosses'];
                $kills = $data['duelsKills'];
                $deaths = $data['duelsDeaths'];

                $form->addLabel("$gameStats Wins: " . $wins);
                $form->addLabel("$gameStats Losses: " . $losses);
                if ($losses === 0) {
                    $form->addLabel("$gameStats WLR: " . $wins);
                } else {
                    $form->addLabel("$gameStats WLR: " . round($wins / $losses, 2));
                }
                $form->addLabel("$gameStats Kills: " . $kills);
                $form->addLabel("$gameStats Deaths: " . $deaths);
                if ($deaths === 0) {
                    $form->addLabel("$gameStats KDR: " . $deaths);
                } else {
                    $form->addLabel("$gameStats KDR: " . round($kills / $deaths, 2));
                }
                break;

            case 'tb':
                $gameStats = "Bridge";

                $wins = $data['tbWins'];
                $losses = $data['tbLosses'];
                $kills = $data['tbKills'];
                $deaths = $data['tbDeaths'];
                $goals = $data['tbGoals'];

                $form->addLabel("$gameStats Wins: " . $wins);
                $form->addLabel("$gameStats Losses: " . $losses);
                if ($losses === 0) {
                    $form->addLabel("$gameStats WLR: " . $wins);
                } else {
                    $form->addLabel("$gameStats WLR: " . round($wins / $losses, 2));
                }
                $form->addLabel("$gameStats Kills: " . $kills);
                $form->addLabel("$gameStats Deaths: " . $deaths);
                if ($deaths === 0) {
                    $form->addLabel("$gameStats KDR: " . $kills);
                } else {
                    $form->addLabel("$gameStats KDR: " . round($kills / $deaths, 2));
                }
                $form->addLabel("$gameStats Goals Scored: " . $goals);
                break;
            case 'cq':
                $gameStats = "Conquests";

                $wins = $data['cqWins'];
                $kills = $data['cqKills'];
                $deaths = $data['cqDeaths'];
                $flagsCaptured = $data['cqFlagsCaptured'];
                $flagsCollected = $data['cqFlagsCollected'];

                $form->addLabel("$gameStats Wins: " . $wins);
                $form->addLabel("$gameStats Kills: " . $kills);
                $form->addLabel("$gameStats Deaths: " . $deaths);
                if ($deaths === 0) {
                    $form->addLabel("$gameStats KDR: " . $deaths);
                } else {
                    $form->addLabel("$gameStats KDR: " . round($kills / $deaths, 2));
                }
                $form->addLabel("$gameStats Flags Captured: " . $flagsCaptured);
                $form->addLabel("$gameStats Flags Collected: " . $flagsCollected);
                break;
            case 'factions':
                $gameStats = "Factions";

                $data = Cache::$playerStatsCached['factionData'];
                $factionData = Cache::$factionStatsCached;
                $player->sendMessage($factionData);

                if ($data === null) {
                    $form->addLabel("No factions stats. Hop on to Factions to get Factions records!");
                    break;
                }

                if ($data['faction'] === null) {
                    $form->addLabel("Faction: No faction joined");
                } else {
                    $factionName = str_replace('"', '', $factionData['name']);
                    $factionStrength = $factionData['strength'];
                    $factionMotd = str_replace('"', '', $factionData['modt']);
                    $form->addLabel("Faction: " . $factionName);
                    $form->addLabel("Faction Strength: " . $factionStrength);
                    $form->addLabel("Faction MOTD: " . $factionMotd);
                }


                $xp = $data['xp'];
                $kills = $data['kills'];
                $coins = $data['coins'];
                $bounty = $data['bounty'];
                $streak = $data['streak'];
                $bestStreak = $data['bestStreak'];

                $form->addLabel("$gameStats XP: " . $xp);
                $form->addLabel("$gameStats Kills: " . $kills);
                $form->addLabel("$gameStats Coins: " . $coins);
                $form->addLabel("$gameStats Bounty: " . $bounty);
                $form->addLabel("$gameStats Streak: " . $streak);
                $form->addLabel("$gameStats Best Streak: " . $bestStreak);
                break;
            case 'mm':
                $gameStats = "Murder Mystery";

                $wins = $data['mmWins'];
                $kills = $data['mmKills'];
                $knifeKills = $data['mmKnifeKills'];
                $thrownKnifeKills = $data['mmThrowKnifeKills'];

                $form->addLabel("$gameStats Wins: " . $wins);
                $form->addLabel("$gameStats Kills: " . $kills);
                $form->addLabel("$gameStats Knife Kills: " . $knifeKills);
                $form->addLabel("$gameStats Thrown Knife Kills: " . $thrownKnifeKills);
                break;
            case 'ac':
                $gameStats = "Arcade";

                $form = new SimpleForm(function (Player $player, ?int $data = null) use ($target) {
                    if ($data === null) {
                        return true;
                    }

                    switch ($data) {
                        case 0:
                            $this->gameStats($player, 'ms', $target);
                            break;

                        case 1:
                            $this->gameStats($player, 'soccer', $target);
                            break;
                    }
                });

                $onlineStatus = $this->getOnlineStatus($target);

                $form->setTitle("[ $onlineStatus §r]  " . $target . "'s Arcade Stats Selection");
                $form->setContent("Which arcade game mode stats would you like to view?");
                $form->addButton("Momma Says");
                $form->addButton("Soccer");
                $form->sendToPlayer($player);
                return;
            case 'ms':
                $gameStats = "Momma Says";

                $wins = $data['msWins'];
                $successes = $data['msSuccesses'];
                $fails = $data['msFails'];
                $totalGamesPlayed = $successes + $fails;
                if ($totalGamesPlayed === 0) {
                    $successRate = 0;
                } else {
                    $successRate = round($successes / $totalGamesPlayed * 100, 2);
                }

                $form->addLabel("$gameStats Wins: " . $wins);
                $form->addLabel("$gameStats Successes: " . $successes);
                $form->addLabel("$gameStats Fails: " . $fails);
                $form->addLabel("$gameStats Success Rate: " . $successRate . "%%");
                break;
            case 'soccer':
                $gameStats = "Soccer";

                $wins = $data['scWins'];
                $goals = $data['scGoals'];

                $form->addLabel("$gameStats Wins: " . $wins);
                $form->addLabel("$gameStats Goals: " . $goals);
                break;
            case 'uhc':
                $gameStats = "UHC";

                $wins = $data['uhcWins'];
                $kills = $data['uhcKills'];
                $deaths = $data['uhcDeaths'];

                $form->addLabel("$gameStats Wins: " . $wins);
                $form->addLabel("$gameStats Kills: " . $kills);
                $form->addLabel("$gameStats Deaths: " . $deaths);
                if ($deaths === 0) {
                    $form->addLabel("$gameStats KDR: " . $deaths);
                } else {
                    $form->addLabel("$gameStats KDR: " . round($kills / $deaths, 2));
                }
                break;
            case 'sg':
                $gameStats = "Survival Games";

                $wins = $data['sgWins'];
                $kills = $data['sgKills'];
                $deaths = $data['sgDeaths'];

                $form->addLabel("$gameStats Wins: " . $wins);
                $form->addLabel("$gameStats Kills: " . $kills);
                $form->addLabel("$gameStats Deaths: " . $deaths);
                if ($deaths === 0) {
                    $form->addLabel("$gameStats KDR: " . $deaths);
                } else {
                    $form->addLabel("$gameStats KDR: " . round($kills / $deaths, 2));
                }
                break;
        }
        $form->setTitle("[ $onlineStatus ]  " . $target . "'s $gameStats Stats");
        $this->gameStatsSelectionForm($player, $target);
        $form->sendToPlayer($player);
    }

    public function playerInfoForm(Player $player, string $target = '')
    {

        if ($target === '') {
            $target = $player->getName();
        }


        $form = new CustomForm(function (Player $player, ?array $data) use ($target) {
            if ($data === null) {
                $this->statsSelectionForm($player, $target);
                return true;
            }
        });

        $onlineStatus = $this->getOnlineStatus($target);


        $form->setTitle("[ $onlineStatus ]  " . $target . "'s Info");
        $tier = Cache::$playerStatsCached['tier'];
        if ($tier !== null) {
            $form->addLabel("Tier: " . str_replace('"', '', $tier));
        } else {
            $form->addLabel("Tier: No tier");
        }

        $ranks = Cache::$playerStatsCached['ranks'];
        if (count($ranks) === 0 || $ranks === "§cError§r") {
            $form->addLabel("Ranks: No ranks");
        } else {
            $form->addLabel("Ranks: " . implode(', ', $ranks));
        }
        if (Cache::$playerStatsCached['online'] === false) {
            $lastSeen = Cache::$playerStatsCached['lastJoin'];
            $lastSeen = str_replace('"', '', $lastSeen);
            $lastSeen = strtotime($lastSeen);
            $lastSeen = date('j M Y \a\t H:i', $lastSeen);
            $lastSeen .= " UTC";
            $lastSeenPlace = Cache::$playerStatsCached['lastServerParsed']['pretty'];
            $form->addLabel("Last Seen: $lastSeen at $lastSeenPlace");
        }
        if (Cache::$playerStatsCached['bio'] !== "") {
            $form->addLabel("");
            $form->addLabel("§lBio:§r");
            $bio = str_replace('"', '', Cache::$playerStatsCached['bio']);
            $bio = explode('\n', $bio);
            foreach ($bio as $line) {
                $form->addLabel("§o$line §r");
            }
        }
        $this->statsSelectionForm($player, $target);
        $form->sendToPlayer($player);
    }
}
