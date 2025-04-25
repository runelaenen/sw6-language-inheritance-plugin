<?php declare(strict_types=1);

namespace Laenen\LanguageInheritance\Core\Content\Cms\SalesChannel;

use Laenen\LanguageInheritance\Core\Content\Cms\SalesChannel\LanguageInheritance\AbstractTranslatedSlotConfigLoader;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\EntityResolverContext;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Content\Cms\SalesChannel\SalesChannelCmsPageLoaderInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;
use Symfony\Component\HttpFoundation\Request;

#[AsDecorator(\Shopware\Core\Content\Cms\SalesChannel\SalesChannelCmsPageLoader::class)]
class SalesChannelCmsPageLoader implements SalesChannelCmsPageLoaderInterface
{
    /**
     * @var array<string, AbstractTranslatedSlotConfigLoader>
     */
    private array $translatedSlotConfigLoaders;

    /**
     * @param iterable<string, AbstractTranslatedSlotConfigLoader> $translatedSlotConfigLoaders
     */
    public function __construct(
        #[AutowireDecorated]
        private readonly SalesChannelCmsPageLoaderInterface $decorated,
        #[AutowireIterator(AbstractTranslatedSlotConfigLoader::class, indexAttribute: 'key')]
        iterable $translatedSlotConfigLoaders
    ) {
        $this->translatedSlotConfigLoaders = $translatedSlotConfigLoaders instanceof \Traversable ?
            iterator_to_array($translatedSlotConfigLoaders) :
            $translatedSlotConfigLoaders;
    }

    /**
     * {@inheritdoc}
     */
    public function load(
        Request $request,
        Criteria $criteria,
        SalesChannelContext $context,
        ?array $config = null,
        ?ResolverContext $resolverContext = null
    ): EntitySearchResult {
        if ($resolverContext) {
            $config = $this->handleConfigInheritance($config, $resolverContext);
        }

        return $this->decorated->load($request, $criteria, $context, $config, $resolverContext);
    }

    /**
     * @param array<string, array<mixed>> $config
     *
     * @return array<string, array<mixed>>
     */
    private function handleConfigInheritance(?array $config, ResolverContext $resolverContext): ?array
    {
        // Handle full page inheritance
        if ($languageId = $this->hasFullPageInheritance($resolverContext)) {
            $overrides = $this->loadConfigs([$languageId], $resolverContext);

            return $overrides[$languageId] ?? $config;
        }

        // Handle block-based inheritance
        if (empty($config)) {
            return $config;
        }
        $languagesToLoad = [];
        foreach ($config as $slotId => $slotConfig) {
            $languageId = $slotConfig['laeLanguageInheritance']['value'] ?? null;
            if (!$languageId || !Uuid::isValid($languageId)) {
                continue;
            }

            $languagesToLoad[$slotId] = $languageId;
        }

        if (empty($languagesToLoad)) {
            return $config;
        }

        $languageIds = array_values($languagesToLoad);
        $overrides = $this->loadConfigs($languageIds, $resolverContext);

        foreach ($languagesToLoad as $slotId => $languageId) {
            $config[$slotId] = $overrides[$languageId][$slotId] ?? $config[$slotId];
        }

        return $config;
    }

    /**
     * @param array<string> $languageIds
     *
     * @return array<string, array<mixed>>
     */
    private function loadConfigs(array $languageIds, ResolverContext $resolverContext): array
    {
        if (!$resolverContext instanceof EntityResolverContext) {
            return [];
        }

        $type = $resolverContext->getDefinition()->getEntityName();
        if (!\array_key_exists($type, $this->translatedSlotConfigLoaders)) {
            return [];
        }
        /** @var AbstractTranslatedSlotConfigLoader $config */
        $config = $this->translatedSlotConfigLoaders[$type];

        return $config->get(
            $languageIds,
            $resolverContext->getEntity()->getUniqueIdentifier(),
            $resolverContext->getEntity()->getVersionId()
        );
    }

    private function hasFullPageInheritance(ResolverContext $resolverContext): ?string
    {
        if (!$resolverContext instanceof EntityResolverContext) {
            return null;
        }

        $entity = $resolverContext->getEntity();

        if (!method_exists($entity, 'getCustomFields')) {
            return null;
        }

        $customFields = $entity->getCustomFields() ?? [];

        return $customFields['laeLanguageInheritance'] ?? null;
    }
}
