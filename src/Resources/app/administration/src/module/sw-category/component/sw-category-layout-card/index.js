import template from './sw-category-layout-card.html.twig';
import './sw-category-layout-card.scss';

export default {
    template,

    data() {
        return {
            openPageLanguageInheritanceModal: false,
        };
    },

    methods: {
        onSelectLanguage(languageId) {
            if (!this.category.customFields) {
                this.category.customFields = {};
            }
            this.category.customFields.laeLanguageInheritance = languageId;

            if (languageId === Shopware.Context.api.languageId) {
                this.category.customFields.laeLanguageInheritance = null;
            }

            this.openPageLanguageInheritanceModal = null;
        },
    },
};
