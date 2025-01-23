<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoCategoryNewsSorting\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;

class CategorySortingModuleSettingMigration extends AbstractMigration
{
    public function __construct(private readonly Connection $db)
    {
    }

    public function shouldRun(): bool
    {
        $schemaManager = $this->db->createSchemaManager();

        if (!$schemaManager->tablesExist(['tl_module'])) {
            return false;
        }

        $columns = $schemaManager->listTableColumns('tl_module');

        if (!isset($columns['news_order']) || !isset($columns['news_enablecategorysorting'])) {
            return false;
        }

        return (bool) $this->db->fetchOne("SELECT TRUE FROM tl_module WHERE news_order = 'category_sorting'");
    }

    public function run(): MigrationResult
    {
        $this->db->update('tl_module', ['news_order' => 'order_date_desc', 'news_enableCategorySorting' => 1], ['news_order' => 'category_sorting']);

        return $this->createResult(true);
    }
}
