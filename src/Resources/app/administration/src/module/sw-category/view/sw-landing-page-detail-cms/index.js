import template from './sw-landing-page-detail-cms.html.twig';
import '../sw-category-detail-cms/sw-category-detail-cms.scss';

export default {
    template,

    data() {
        return {
            openPageLanguageInheritanceModal: false,
        };
    },

    methods: {
        onSelectLanguage(languageId) {
            if (!this.landingPage.customFields) {
                this.landingPage.customFields = {};
            }
            this.landingPage.customFields.laeLanguageInheritance = languageId;

            if (languageId === Shopware.Context.api.languageId) {
                this.landingPage.customFields.laeLanguageInheritance = null;
            }

            this.openPageLanguageInheritanceModal = null;
        },
    },
};
