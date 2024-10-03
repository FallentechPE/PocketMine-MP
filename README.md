<p align="center">
	<a href="https://pmmp.io">
		<!--[if IE]>
			<img src="https://github.com/pmmp/PocketMine-MP/blob/stable/.github/readme/pocketmine.png" alt="The PocketMine-MP logo" title="PocketMine" loading="eager" />
		<![endif]-->
		<picture>
			<source srcset="https://raw.githubusercontent.com/pmmp/PocketMine-MP/stable/.github/readme/pocketmine-dark-rgb.gif" media="(prefers-color-scheme: dark)">
			<img src="https://raw.githubusercontent.com/pmmp/PocketMine-MP/stable/.github/readme/pocketmine-rgb.gif" loading="eager" />
		</picture>
	</a><br>
	<b>A highly customisable, open source server software for Minecraft: Bedrock Edition written in PHP</b>
</p>

<p align="center">
	<a href="https://github.com/pmmp/PocketMine-MP/actions/workflows/main.yml"><img src="https://github.com/pmmp/PocketMine-MP/workflows/CI/badge.svg" alt="CI" /></a>
	<a href="https://github.com/pmmp/PocketMine-MP/releases/latest"><img alt="GitHub release (latest SemVer)" src="https://img.shields.io/github/v/release/pmmp/PocketMine-MP?label=release&sort=semver"></a>
	<a href="https://discord.gg/bmSAZBG"><img src="https://img.shields.io/discord/373199722573201408?label=discord&color=7289DA&logo=discord" alt="Discord" /></a>
	<br>
	<a href="https://github.com/pmmp/PocketMine-MP/releases"><img alt="GitHub all releases" src="https://img.shields.io/github/downloads/pmmp/PocketMine-MP/total?label=downloads%40total"></a>
	<a href="https://github.com/pmmp/PocketMine-MP/releases/latest"><img alt="GitHub release (latest by SemVer)" src="https://img.shields.io/github/downloads/pmmp/PocketMine-MP/latest/total?sort=semver"></a>
</p>

## What is THIS?
A fork of PocketMine-MP implementing items, blocks, stale PRs, and other features and QOL API additions. This is not a stable version of PocketMine, and there will be bugs. Items and blocks for the majority have functionality, however some do not. The only PRs added are ones that are not BC breaking. This will remain a version of PM-5 and should be a drop in replacement. If something is merged to the main branch of PocketMine, it will be changed here, hopefully with any fixes. The aim of this fork is to provide a "beta" version of PocketMine will untested, but functional changes that would usually take longer being to be added. PRs and issues are welcome however I will not be able to fix every bug. No composer libraries will be forked and the original PMMP version will be used.

**Do not ask for support for this version of PocketMine in their main Discord server. Any questions can be directed to Joshy3282 on Discord. Additionally, please do not bug any actual PocketMine developers about this either.**

Additions
> Blocks: azalea, flowering azalea, target, moss block, moss carpet, scaffolding, composter, beacon, \
> Items: end crystal, lingering potions, goat horns, end crystal\
> https://github.com/pmmp/PocketMine-MP/pull/4652 
> https://github.com/pmmp/PocketMine-MP/pull/4677 
> https://github.com/pmmp/PocketMine-MP/pull/4687 
> https://github.com/pmmp/PocketMine-MP/pull/4715
> https://github.com/pmmp/PocketMine-MP/pull/4742
> https://github.com/pmmp/PocketMine-MP/pull/5095
> https://github.com/pmmp/PocketMine-MP/pull/5122
> https://github.com/pmmp/PocketMine-MP/pull/5232
> https://github.com/pmmp/PocketMine-MP/pull/5276
> https://github.com/pmmp/PocketMine-MP/pull/5333
> https://github.com/pmmp/PocketMine-MP/pull/5392
> https://github.com/pmmp/PocketMine-MP/pull/5397
> https://github.com/pmmp/PocketMine-MP/pull/5427
> https://github.com/pmmp/PocketMine-MP/pull/5455
> https://github.com/pmmp/PocketMine-MP/pull/5467
> https://github.com/pmmp/PocketMine-MP/pull/5497
> https://github.com/pmmp/PocketMine-MP/pull/5502
> https://github.com/pmmp/PocketMine-MP/pull/5581
> https://github.com/pmmp/PocketMine-MP/pull/5583
> https://github.com/pmmp/PocketMine-MP/pull/5800
> https://github.com/pmmp/PocketMine-MP/pull/5809
> https://github.com/pmmp/PocketMine-MP/pull/5838
> https://github.com/pmmp/PocketMine-MP/pull/5861
> https://github.com/pmmp/PocketMine-MP/pull/5864
> https://github.com/pmmp/PocketMine-MP/pull/5906
> https://github.com/pmmp/PocketMine-MP/pull/5913
> https://github.com/pmmp/PocketMine-MP/pull/5923
> https://github.com/pmmp/PocketMine-MP/pull/5964
> https://github.com/pmmp/PocketMine-MP/pull/5988
> https://github.com/pmmp/PocketMine-MP/pull/6013
> https://github.com/pmmp/PocketMine-MP/pull/6030
> https://github.com/pmmp/PocketMine-MP/pull/6063
> https://github.com/pmmp/PocketMine-MP/pull/6064
> https://github.com/pmmp/PocketMine-MP/pull/6076
> https://github.com/pmmp/PocketMine-MP/pull/6187


## What is this?
PocketMine-MP is a highly customisable server software for Minecraft: Bedrock Edition, built from scratch in PHP, with over 10 years of history.

If you're looking to create a Minecraft: Bedrock server with **custom functionality**, look no further.

- 🧩 **Powerful plugin API** - extend and customise gameplay as you see fit
- 🗺️ **Rich ecosystem** and **large developer community** - find plugins easily and learn to develop your own
- 🌐 **Multi-world support** - offer a more varied game experience to players without transferring them to other server nodes
- 🏎️ **Performance** - get 100+ players onto one server (depending on hardware and plugins)
- ⤴️ **Continuously updated** - new Minecraft versions are usually supported within days

## :x: PocketMine-MP is NOT a vanilla Minecraft server software.
**It is poorly suited to hosting vanilla survival servers.**
It doesn't have many features from the vanilla game, such as vanilla world generation, redstone, mob AI, and various other things.

If you just want to play **vanilla survival multiplayer**, consider using the [official Minecraft: Bedrock server software](https://minecraft.net/download/server/bedrock) instead of PocketMine-MP.

If that's not an option for you, you may be able to add some of PocketMine-MP's missing features using plugins from [Poggit](https://poggit.pmmp.io/plugins), or write plugins to implement them yourself.

## Getting Started
- [Documentation](http://pmmp.readthedocs.org/)
- [Installation instructions](https://pmmp.readthedocs.io/en/rtfd/installation.html)
- [Docker image](https://github.com/pmmp/PocketMine-MP/pkgs/container/pocketmine-mp)
- [Plugin repository](https://poggit.pmmp.io/plugins)

## Community & Support
Join our [Discord](https://discord.gg/bmSAZBG) server to chat with other users and developers.

You can also post questions on [StackOverflow](https://stackoverflow.com/tags/pocketmine) under the tag `pocketmine`.

## Developing Plugins
If you want to write your own plugins, the following resources may be useful.
Don't forget you can always ask our community if you need help.

 * [Developer documentation](https://devdoc.pmmp.io) - General documentation for PocketMine-MP plugin developers
 * [Latest release API documentation](https://apidoc.pmmp.io) - Doxygen API documentation generated for each release
 * [Latest bleeding-edge API documentation](https://apidoc-dev.pmmp.io) - Doxygen API documentation generated weekly from `major-next` branch
 * [DevTools](https://github.com/pmmp/DevTools/) - Development tools plugin for creating plugins
 * [ExamplePlugin](https://github.com/pmmp/ExamplePlugin/) - Example plugin demonstrating some basic API features

## Contributing to PocketMine-MP
PocketMine-MP accepts community contributions! The following resources will be useful if you want to contribute to PocketMine-MP.
 * [Building and running PocketMine-MP from source](BUILDING.md)
 * [Contributing Guidelines](CONTRIBUTING.md)

## Donate
PocketMine-MP is free, but it requires a lot of time and effort from unpaid volunteers to develop. Donations enable us to keep delivering support for new versions and adding features your players love.

You can support development using the following methods:

- [Patreon](https://www.patreon.com/pocketminemp)
- Bitcoin (BTC): `171u8K9e4FtU6j3e5sqNoxKUgEw9qWQdRV`
- Stellar Lumens (XLM): `GAAC5WZ33HCTE3BFJFZJXONMEIBNHFLBXM2HJVAZHXXPYA3HP5XPPS7T`

Thanks for your support!

## Licensing information
This project is licensed under LGPL-3.0. Please see the [LICENSE](/LICENSE) file for details.

pmmp/PocketMine are not affiliated with Mojang. All brands and trademarks belong to their respective owners. PocketMine-MP is not a Mojang-approved software, nor is it associated with Mojang.
