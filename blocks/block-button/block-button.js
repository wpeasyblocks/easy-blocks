const { registerBlockType } = wp.blocks;
const { __ } = wp.i18n;

// import block styles
import './styles/block-styles.scss';
import './styles/editor-styles.scss';

// icon
import { IconButton } from '../icons/Icons';
import edit from './components/Edit';

registerBlockType( 'easy-blocks/eb-button-block', {
    title: __( 'EB Button', 'easyBlocks' ),
    description: __( 'Easy Blocks - Custom Button Block.', 'easyBlocks' ),
    icon: IconButton,
    category: 'layout',
    keywords: [
        __( 'eb', 'easyBlocks' ),
        __( 'button', 'easyBlocks' ),
        __( 'easy blocks', 'easyBlocks' )
    ],
    attributes : {
        btnStyle: {
            type: 'string',
            default: 'eb-solid'
        }
    },

    edit,

    save: ( props ) => {
        return 'saved hi';
    }
} );