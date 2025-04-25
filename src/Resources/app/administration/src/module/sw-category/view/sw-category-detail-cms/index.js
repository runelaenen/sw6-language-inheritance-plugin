import template from './sw-category-detail-cms.html.twig';
import './sw-category-detail-cms.scss';

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
