# libKits

It is a small library for creating kits and is easy to use.


# Examples

## Create a kit
```php
class KitExample extends Kit
{
    public function __construct()
    {
        parent::__construct("kitexample", // name to get the kit
         "[KitExample]", // prefix i.e. a second name
         100, // price, used to obtain the quantity of the price
         "kit.example", // is the kit permission
         100 // cooldown use it to get the time
            );
        $this->defineItems();
    }

    public function defineItems(): void
    {
        // defines the items that the kit will have
        $this->addItem(
            VanillaItems::DIAMOND_SWORD()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), 2)),
            VanillaItems::DIAMOND_PICKAXE()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), 3)),
            VanillaItems::DIAMOND_PICKAXE()
        );
        // defines the armor that the kit will have (the armor will be put on automatically when obtaining the kit)
        $this->setArmor(
            VanillaItems::IRON_HELMET()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 3)),
            VanillaItems::IRON_CHESTPLATE()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 3)),
            VanillaItems::IRON_LEGGINGS()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 3)),
            VanillaItems::IRON_BOOTS()->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(), 3))
        );
    }
}
```
## Register Kit
```php
public function onEnable(): void
    {
        // saves the kits' base data
        KitManager::init(new SQLiteProvider($this->getDataFolder() . "database.db"));
        // register the kit
        KitManager::registerKit(new KitExample());
    }
```
## Give a Kit
```php
// get kit name
$kit = KitManager::getKit("kitexample");
// give the kit to the player
$kit->giveToPlayer($player);
```

# Contact
[![Discord Presence](https://lanyard.cnrad.dev/api/1165097093480853634?theme=dark&bg=005cff&animated=false&hideDiscrim=true&borderRadius=30px&idleMessage=Hello%20boys%20and%20girls)](https://discord.com/users/1165097093480853634)
