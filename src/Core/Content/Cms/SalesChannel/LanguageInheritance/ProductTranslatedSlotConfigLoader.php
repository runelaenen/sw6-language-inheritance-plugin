<?php declare(strict_types=1);

namespace Laenen\LanguageInheritance\Core\Content\Cms\SalesChannel\LanguageInheritance;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem('product')]
readonly class ProductTranslatedSlotConfigLoader extends AbstractTranslatedSlotConfigLoader implements EntityIdTranslatedSlotConfigLoaderInterface
{
    /**
     * @param array<string> $languageIds
     *
     * @return array<string, array<mixed>>
     */
    public function get(array $languageIds, string $entityId, ?string $entityVersionId = null): array
    {
        $query = <<<SQL
SELECT LOWER(HEX(language_id)) as languageId,
       slot_config as slotConfig
FROM product_translation
WHERE product_id = :entityId
AND product_version_id = :entityVersionId
AND language_id IN (:languageIds)
SQL;

        return $this->load($query, $languageIds, $entityId, $entityVersionId);
    }

    public function getEntityId(Entity $product): ?string
    {
        if (!$product instanceof ProductEntity) {
            return null;
        }

        return $product->getParentId() ?? $product->getId();
    }
}
