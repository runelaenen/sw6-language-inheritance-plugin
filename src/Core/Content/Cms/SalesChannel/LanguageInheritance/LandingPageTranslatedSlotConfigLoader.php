<?php declare(strict_types=1);

namespace Laenen\LanguageInheritance\Core\Content\Cms\SalesChannel\LanguageInheritance;

use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem('landing_page')]
readonly class LandingPageTranslatedSlotConfigLoader extends AbstractTranslatedSlotConfigLoader
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
FROM landing_page_translation
WHERE landing_page_id = :entityId
AND landing_page_version_id = :entityVersionId
AND language_id IN (:languageIds)
SQL;

        return $this->load($query, $languageIds, $entityId, $entityVersionId);
    }
}
