<?php

namespace Jorgebyte\libKits;

use pocketmine\item\Item;
use pocketmine\player\Player;

abstract class Kit
{
    protected string $name;
    protected array $items = [];
    protected string $prefix;
    protected array $armor = [];
    protected int $price;
    protected ?string $permission;
    protected int $cooldown;

    /**
     * Kit constructor.
     *
     * @param string $name The name of the kit.
     * @param string $prefix The prefix for the kit.
     * @param int $price The price of the kit.
     * @param ?string $permission The permission required to use the kit.
     * @param int $cooldown The cooldown time for the kit.
     */
    public function __construct(
        string $name,
        string $prefix,
        int $price,
        ?string $permission,
        int $cooldown
    ) {
        $this->name = $name;
        $this->prefix = $prefix;
        $this->price = $price;
        $this->permission = $permission;
        $this->cooldown = $cooldown;
        $this->defineItems();
    }

    /**
     * Adds items to the kit.
     *
     * @param Item ...$items The items to be added.
     * @return void
     */
    public function addItem(Item ...$items): void
    {
        $this->items = array_merge($this->items, $items);
    }

    /**
     * Sets the armor for the kit.
     *
     * @param Item|null $helmet The helmet item.
     * @param Item|null $chestplate The chestplate item.
     * @param Item|null $leggings The leggings item.
     * @param Item|null $boots The boots item.
     * @return void
     */
    public function setArmor(?Item $helmet = null, ?Item $chestplate = null, ?Item $leggings = null, ?Item $boots = null): void
    {
        $this->armor = [
            "helmet" => $helmet,
            "chestplate" => $chestplate,
            "leggings" => $leggings,
            "boots" => $boots
        ];
    }

    /**
     * Gives the kit items to a player.
     *
     * @param Player $player The player to receive the items.
     * @return void
     */
    public function giveToPlayer(Player $player): void
    {
        $player->getInventory()->addItem(...$this->items);

        foreach ($this->armor as $type => $armorItem) {
            if ($armorItem !== null) {
                $method = "set" . ucfirst($type);
                $player->getArmorInventory()->$method($armorItem);
            }
        }
    }

    /**
     * Gets the name of the kit.
     *
     * @return string The name of the kit.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets the prefix of the kit.
     *
     * @return string The prefix of the kit.
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Gets the items of the kit.
     *
     * @return array The items of the kit.
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Gets the armor of the kit.
     *
     * @return array The armor items of the kit.
     */
    public function getArmor(): array
    {
        return $this->armor;
    }

    /**
     * Gets the price of the kit.
     *
     * @return int The price of the kit.
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * Gets the permission required for the kit.
     *
     * @return ?string The permission required for the kit.
     */
    public function getPermission(): ?string
    {
        return $this->permission;
    }

    /**
     * Gets the cooldown time for the kit.
     *
     * @return int The cooldown time for the kit.
     */
    public function getCooldown(): int
    {
        return $this->cooldown;
    }

    /**
     * Defines the items for the kit.
     * This method must be implemented by subclasses.
     *
     * @return void
     */
    abstract public function defineItems(): void;
}