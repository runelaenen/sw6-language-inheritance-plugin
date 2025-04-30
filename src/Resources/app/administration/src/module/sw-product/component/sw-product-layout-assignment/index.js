import template from './sw-product-layout-assignment.html.twig';
import './sw-product-layout-assignment.scss';

const {mapState} = Shopware.Component.getComponentHelper();

export default {
    template,

    computed: {
        ...mapState('swProductDetail', [
            'product',
        ]),
    },

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