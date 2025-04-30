<?php declare(strict_types=1);

namespace Laenen\LanguageInheritance\Core\Content\Cms\SalesChannel\LanguageInheritance;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag]
abstract readonly class AbstractTranslatedSlotConfigLoader
{
    public function __construct(
        private Connection $connection
    ) {
    }

    /**
     * @param array<string> $languageIds
     *
     * @return array<string, array<mixed>>
     */
    abstract public function get(array $languageIds, string $entityId, ?string $entityVersionId = null): array;

    /**
     * @param array<string> $languageIds
     *
     * @return array<string, array<mixed>>
     */
    protected function load(string $query, array $languageIds, string $entityId, ?string $entityVersionId = null): array
    {
        $params = [
            'entityId' => Uuid::fromHexToBytes($entityId),
            'languageIds' => Uuid::fromHexToBytesList($languageIds),
        ];
        if ($entityVersionId) {
            $params['entityVersionId'] = Uuid::fromHexToBytes($entityVersionId);
        }

        $translations = $this->connection->fetchAllKeyValue(
            $query,
            $params,
            [
                'languageIds' => ArrayParameterType::BINARY,
            ]
        );

        foreach ($translations as $languageId => $slotConfig) {
            try {
                $translations[$languageId] = json_decode($slotConfig ?? 'null', true, 512, \JSON_THROW_ON_ERROR) ?? [];
            } catch (\JsonException) {
                // ignore...
            }
        }

        return $translations;
    }
}
