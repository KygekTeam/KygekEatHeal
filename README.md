# KygekEatHeal

[![Poggit](https://poggit.pmmp.io/shield.dl.total/KygekEatHeal)](https://poggit.pmmp.io/p/KygekEatHeal)
[![Discord](https://img.shields.io/discord/735439472992321587.svg?label=&logo=discord&logoColor=ffffff&color=7389D8&labelColor=6A7EC2)](https://discord.gg/CXtqUZv)

A PocketMine-MP plugin that can heal and feed yourself or other players. EconomyAPI is supported for paid heals and feeds.

# Features

- EconomyAPI support for paid heals and feeds
- Customizeable heal or feed amount (points)
- Heal or feed another player
- Offline player detection
- Full or healthy detection (so you won't waste money!)
- Customizeable heal and feed cooldown
- Command descrption can be changed
- Supports command aliases
- Automatic plugin updates checker
- Automatic configuration files reloading
- Automatic configuration file updater

# How to Install

1. Download the latest version (It is recommended to always download the latest version for the best experience, except you're having compatibility issues).
2. Place the `KygekEatHeal.phar` file into the plugins folder.
3. Restart or start your server.
4. Done!

# Commands & Permissions

| Command | Default Description | Permission | Default |
| --- | --- | --- | --- |
| `/eat` | Eat or feed a player | `kygekeatheal.eat` | true |
| `/heal` | Heal yourself or a player | `kygekeatheal.heal` | true |

Permission `kygekeatheal` can be used to allow all commands (Default is `true`).

Command description can be changed in `config.yml`. You can also add command aliases in `config.yml`.

Use `-{COMMAND-PERMISSION}` to blacklist the command permissions to groups/users in PurePerms (e.g. `-kygekeatheal.eat`).

# Upcoming Features

- Customizeable messages
- And much more...

# Additional Notes

KygekEatHeal plugin is made by KygekTeam and licensed under **GPL-3.0**.

- Join our Discord server [here](https://discord.gg/CXtqUZv) for latest updates from KygekTeam.
- If you found bugs or want to give suggestions, please visit [here](https://github.com/KygekTeam/KygekEatHeal/issues) or join our Discord server.
- We accept all contributions! If you want to contribute please make a pull request in [here](https://github.com/KygekTeam/KygekEatHeal/pulls).

# Other Versions

- [Nukkit](https://github.com/KygekTeam/KygekEatHeal-Nukkit)
