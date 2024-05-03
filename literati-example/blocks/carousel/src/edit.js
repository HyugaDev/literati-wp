import { __ } from "@wordpress/i18n";
import { useSelect } from "@wordpress/data";
import { useEntityProp } from "@wordpress/core-data";
import { TextControl, PanelBody, PanelRow } from "@wordpress/components";
import { useBlockProps, InspectorControls, RichText } from "@wordpress/block-editor"; // Removed unnecessary semicolon


import "./editor.scss";

export default function Edit() {
	const blockProps = useBlockProps();
	const postType = useSelect(
		( select ) => select( 'core/editor' ).getCurrentPostType(),
		[]
	);
	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );
	const timer = meta[ 'promotion_timer' ];

	const updateTimerMetaValue = ( newValue ) => {
		setMeta( { ...meta, promotion_timer: newValue } );
	};


  return (
    <div {...blockProps}> {/* Changed the wrapper element from <p> to <div> */}
      {__("Carousel Timer", "literati-example-carousel")}
      <>
			<InspectorControls>
				<PanelBody 
					title={ __( 'Carousel Timer' )}
					initialOpen={true}
				>
					<PanelRow>
						<fieldset>
							<TextControl
								label={ __( 'Write custom timer' ) }
								value={ timer }
								onChange={ updateTimerMetaValue }
							/>
						</fieldset>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>

				<TextControl
					label="Write custom timer"
					value={ timer }
					onChange={ updateTimerMetaValue }
				/>
			</div>
		</>
    </div>
  );
}