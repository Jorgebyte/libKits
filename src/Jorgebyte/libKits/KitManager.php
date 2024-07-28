<?php

namespace Jorgebyte\libKits;

use Jorgebyte\libKits\provider\DatabaseProvider;

class KitManager
{
    /**
     * @var array<string, Kit> List of registered kits.
     *
     * This property stores all registered kits. The array key is the kit name in lowercase, and the value is an instance of the kit.
     */
    private static array $kits = [];

    /**
     * @var array<string, string> List of registered kit classes.
     *
     * This property stores the mapping of kit names to their corresponding class names. This is useful for instantiating kits from the database.
     */
    private static array $kitClasses = [];

    /**
     * @var DatabaseProvider|null The database provider instance.
     *
     * This is the database provider used for saving and loading kits. It can be an instance of SQLiteProvider, MySQLProvider, JSONProvider, etc.
     */
    private static ?DatabaseProvider $databaseProvider = null;

    /**
     * Initializes the KitManager with a database provider.
     *
     * @param DatabaseProvider $provider The database provider to be used.
     *
     * This method initializes the KitManager with a database provider and loads all kits from the database.
     */
    public static function init(DatabaseProvider $provider): void
    {
        self::$databaseProvider = $provider;
        self::$databaseProvider->init();
        self::loadKits();
    }

    /**
     * Registers a new kit class.
     *
     * @param string $kitName The name of the kit.
     * @param string $kitClass The fully qualified class name of the kit.
     *
     * This method registers a kit class for later use. It allows KitManager to know which class to instantiate when loading a kit from the database.
     */
    public static function registerKitClass(string $kitName, string $kitClass): void
    {
        self::$kitClasses[strtolower($kitName)] = $kitClass;
    }

    /**
     * Retrieves the class name of a registered kit.
     *
     * @param string $kitName The name of the kit.
     * @return string|null The fully qualified class name of the kit, or null if not found.
     *
     * This method returns the class name of a registered kit. If the kit is not registered, it returns null.
     */
    public static function getKitClass(string $kitName): ?string
    {
        return self::$kitClasses[strtolower($kitName)] ?? null;
    }

    /**
     * Registers a new kit.
     *
     * @param Kit $kit The kit to be registered.
     *
     * This method adds a kit to the internal registry of KitManager and saves the kit to the database.
     */
    public static function registerKit(Kit $kit): void
    {
        self::$kits[strtolower($kit->getName())] = $kit;
        self::$databaseProvider?->saveKit($kit);
    }

    /**
     * Retrieves a kit by its name.
     *
     * @param string $name The name of the kit.
     * @return Kit|null The kit if found, null otherwise.
     *
     * This method returns an instance of a kit based on the provided name. If the kit is not registered, it returns null.
     */
    public static function getKit(string $name): ?Kit
    {
        return self::$kits[strtolower($name)] ?? null;
    }

    /**
     * Retrieves all registered kits.
     *
     * @return Kit[] Array of all registered kits.
     *
     * This method returns an array of all kit instances registered in the KitManager.
     */
    public static function getAllKits(): array
    {
        return self::$kits;
    }

    /**
     * Loads all kits from the database.
     *
     * This method loads all kits from the database and adds them to the internal registry of KitManager.
     */
    private static function loadKits(): void
    {
        if (self::$databaseProvider !== null) {
            $kits = self::$databaseProvider->loadKits();
            foreach ($kits as $kit) {
                self::$kits[strtolower($kit->getName())] = $kit;
            }
        }
    }
}