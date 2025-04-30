<?php
declare(strict_types=1);

namespace Laenen\LanguageInheritance\Core\Content\Cms\SalesChannel\LanguageInheritance;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;

interface EntityIdTranslatedSlotConfigLoaderInterface
{
    public function getEntityId(Entity $product): ?string;
}