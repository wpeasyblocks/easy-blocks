const { __ } = wp.i18n;
const { Component } = wp.element;
const {
    RichText,
    AlignmentToolbar,
    BlockControls,
    BlockAlignmentToolbar,
    PanelColorSettings,
    InspectorControls
} = wp.editor;

const {
    PanelBody,
    ToggleControl,
    Toolbar,
} = wp.components;

import { ControlIcons } from '../../icons/control-icons';

class Edit extends Component {

    constructor( props ) {
        super( ...arguments );
        console.log('>> this.props',this.props);
    }

    render() {
        const {
            attributes: {
                btnStyle,
            },
            attributes,
            setAttributes
        } = this.props;

        const StyleControl = [
            {
                icon: ControlIcons.solid,
                title: __( 'Solid' ),
                onClick: () => setAttributes( { btnStyle: 'eb-solid' } ),
                isActive: btnStyle === 'eb-solid',
            },
            {
                icon: ControlIcons.outline,
                title: __( 'Outline' ),
                onClick: () => setAttributes( { btnStyle: 'eb-outline' } ),
                isActive: btnStyle === 'eb-outline',
            },
        ];

        console.log('>> btn style', btnStyle);
        return(
            <div>
                <BlockControls>
                    <AlignmentToolbar
                        value={ 'left' }
                        onChange={ ( alertContentAlignment ) => setAttributes( { alertContentAlignment } ) }
                    />
                    <Toolbar controls={ StyleControl } />
                </BlockControls>

                    <RichText
                        tagName="div"
                        placeholder={ __( 'Submit' ) }
                        onChange={ ( alertContent ) => setAttributes( { alertContent } ) }
                    />

            </div>
        );
    }
}

// export edit component
export default Edit;