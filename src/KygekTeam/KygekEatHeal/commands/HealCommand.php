<?php

/*
 * Eat and heal a player instantly!
 * Copyright (C) 2020-2021 KygekTeam
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace KygekTeam\KygekEatHeal\commands;

use KygekTeam\KygekEatHeal\EatHeal;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class HealCommand extends PluginCommand {

    /** @var int[] */
    private $cooldownSelf = [];
    /** @var int[] */
    private $cooldownOther = [];

    public function __construct(string $name, EatHeal $owner) {
        parent::__construct($name, $owner);

        $desc = empty($owner->getConfig()->get("heal-desc")) ?
            "Heal yourself or a player" : $owner->getConfig()->get("heal-desc");
        $this->setDescription($desc);
        $this->setAliases($owner->getConfig()->get("heal-aliases", []));
        $this->setUsage("/heal [player]");
        $this->setPermission("kygekeatheal.heal");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
        if (!$this->testPermission($sender)) return true;

        /** @var EatHeal $owner */
        $owner = $this->getPlugin();
        $config = $owner->getConfig();
        $config->reload();

        if (!isset($args[0])) {
            if (!$sender instanceof Player) {
                $sender->sendMessage(EatHeal::PREFIX . EatHeal::INFO . "Usage: /heal <player>");
                return true;
            }

            $cooldown = $config->get("heal-cooldown-self",0);
            if ($cooldown !== 0) {
                if (isset($this->cooldownSelf[$sender->getName()]) && time() - $cooldown < $this->cooldownSelf[$sender->getName()]) {
                    $duration = $this->cooldownSelf[$sender->getName()] - (time() - $cooldown);
                    $sec = $duration <= 1 ? "second" : "seconds";
                    $sender->sendMessage(EatHeal::PREFIX . EatHeal::WARNING . "Healing yourself is currently on cooldown. Please wait " . $duration . " " . $sec . " before healing yourself again.");
                    return true;
                }
                $this->cooldownSelf[$sender->getName()] = time();
            }

            $result = $owner->healTransaction($sender);

            if ($result === true) {
                $sender->sendMessage(EatHeal::PREFIX . EatHeal::INFO . "You are already healthy!");
                return true;
            }
            if ($result === false) {
                $sender->sendMessage(EatHeal::PREFIX . EatHeal::WARNING . "You do not have enough money to heal!");
                return true;
            }

            $price = ($owner->economyEnabled && $result > 0) ?
                " for " . $owner->economyAPI->getMonetaryUnit() . $result : "";

            $sender->sendMessage(EatHeal::PREFIX . EatHeal::INFO . "You have been healed" . $price);
        } else {
            $player = $owner->getServer()->getPlayer($args[0]);

            if (is_null($player)) {
                $sender->sendMessage(EatHeal::PREFIX . EatHeal::WARNING . "Player is not online!");
                return true;
            }

            $cooldown = $config->get("heal-cooldown-other",0);
            if (($cooldown !== 0 && $sender instanceof Player) || (!$sender instanceof Player && $config->get("enable-console-cooldown", false))) {
                if (isset($this->cooldownOther[$sender->getName()]) && time() - $cooldown < $this->cooldownOther[$sender->getName()]) {
                    $duration = $this->cooldownOther[$sender->getName()] - (time() - $cooldown);
                    $sec = $duration <= 1 ? "second" : "seconds";
                    $sender->sendMessage(EatHeal::PREFIX . EatHeal::WARNING . "Healing other player is currently on cooldown. Please wait " . $duration . " " . $sec . " before healing other player again.");
                    return true;
                }
                $this->cooldownOther[$sender->getName()] = time();
            }

            if ($isPlayer = $sender instanceof Player) {
                $result = $owner->healTransaction($player, $isPlayer, $sender);
            } else {
                $result = $owner->healTransaction($player, $isPlayer);
            }

            if ($result === true) {
                $sender->sendMessage(EatHeal::PREFIX . EatHeal::INFO . $player->getName() . " is already healthy!");
                return true;
            }
            if ($result === false) {
                $sender->sendMessage(EatHeal::PREFIX . EatHeal::WARNING . "You do not have enough money to heal " . $player->getName() . "!");
                return true;
            }

            $price = ($owner->economyEnabled && $isPlayer && $result > 0) ?
                " for " . $owner->economyAPI->getMonetaryUnit() . $result : "";

            // Sends a message to healer
            $sender->sendMessage(EatHeal::PREFIX . EatHeal::INFO . "Player " . $player->getName() . " has been healed" . $price);
            // Sends a message to the player being healed
            $player->sendMessage(EatHeal::PREFIX . EatHeal::INFO . "You have been healed by " . $sender->getName());
        }

        return true;
    }

}