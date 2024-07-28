<?php

namespace Jorgebyte\libKits\provider;

use Jorgebyte\libKits\KitManager;
use SQLite3;
use Jorgebyte\libKits\Kit;

class SQLiteProvider implements DatabaseProvider
{
    private SQLite3 $db;
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function init(): void
    {
        $this->db = new SQLite3($this->path);
        $this->db->exec("CREATE TABLE IF NOT EXISTS kits (name TEXT PRIMARY KEY, prefix TEXT, price INTEGER, permission TEXT, cooldown INTEGER)");
    }

    public function saveKit(Kit $kit): void
    {
        $stmt = $this->db->prepare("INSERT OR REPLACE INTO kits (name, prefix, price, permission, cooldown) VALUES (:name, :prefix, :price, :permission, :cooldown)");
        $stmt->bindValue(':name', $kit->getName(), SQLITE3_TEXT);
        $stmt->bindValue(':prefix', $kit->getPrefix(), SQLITE3_TEXT);
        $stmt->bindValue(':price', $kit->getPrice(), SQLITE3_INTEGER);
        $stmt->bindValue(':permission', $kit->getPermission(), SQLITE3_TEXT);
        $stmt->bindValue(':cooldown', $kit->getCooldown(), SQLITE3_INTEGER);
        $stmt->execute();
    }

    public function loadKits(): array
    {
        $result = $this->db->query("SELECT * FROM kits");
        $kits = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $kitName = strtolower($row['name']);
            $kitClass = KitManager::getKitClass($kitName);
            if ($kitClass !== null && is_subclass_of($kitClass, Kit::class)) {
                $kits[] = new $kitClass(
                    $row['name'],
                    $row['prefix'],
                    $row['price'],
                    $row['permission'],
                    $row['cooldown']
                );
            }
        }
        return $kits;
    }
}