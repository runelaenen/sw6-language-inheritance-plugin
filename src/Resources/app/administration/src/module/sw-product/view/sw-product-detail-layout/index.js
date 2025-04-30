import template from './sw-product-detail-layout.html.twig';
import '../../../sw-category/view/sw-category-detail-cms/sw-category-detail-cms.scss';

export default {
    template,

    data() {
        return {
            openPageLanguageInheritanceModal: false,
        };
    },

    methods: {
        onSelectLanguage(languageId) {
            if (!this.product.customFields) {
                this.product.customFields = {};
            }
            this.product.customFields.laeLanguageInheritance = languageId;

            if (languageId === Shopware.Context.api.languageId) {
                this.product.customFields.laeLanguageInheritance = null;
            }

            this.openPageLanguageInheritanceModal = null;
        },
    },
}