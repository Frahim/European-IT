<?php
/**
 * COLUMNS
 * 
 * Shortcode which creates columns for better content separation
 */

 // Don't load directly
if ( ! defined( 'ABSPATH' ) ) { die('-1'); }



if ( ! class_exists( 'avia_sc_columns' ) )
{
	class avia_sc_columns extends aviaShortcodeTemplate
	{

		/**
		 *
		 * @var string 
		 */
		static protected $extraClass = '';
		
		/**
		 *
		 * @var int|float 
		 */
		static protected $calculated_size = 0;
		
		/**
		 *
		 * @var array 
		 */
		static protected $first_atts = array(); 
		
		/**
		 *
		 * @var array 
		 */
		static protected $size_array = array(	
						'av_one_full' 		=> 1.0, 
						'av_one_half' 		=> 0.5, 
						'av_one_third' 		=> 0.33, 
						'av_one_fourth' 	=> 0.25, 
						'av_one_fifth' 		=> 0.2, 
						'av_two_third' 		=> 0.66, 
						'av_three_fourth' 	=> 0.75, 
						'av_two_fifth' 		=> 0.4, 
						'av_three_fifth' 	=> 0.6, 
						'av_four_fifth' 	=> 0.8
					);

		/**
		 * Holds an array or shortcode => column name
		 * 
		 * @since 4.8
		 * @var array 
		 */
		static protected $columns_array = array(
						'av_one_full'		=> '1/1', 
						'av_one_half'		=> '1/2', 
						'av_one_third'		=> '1/3', 
						'av_one_fourth'		=> '1/4', 
						'av_one_fifth'		=> '1/5', 
						'av_two_third'		=> '2/3', 
						'av_three_fourth'	=> '3/4', 
						'av_two_fifth'		=> '2/5', 
						'av_three_fifth'	=> '3/5', 
						'av_four_fifth'		=> '4/5'
					);
			
		/**
		 * This constructor is implicity called by all derived classes
		 * To avoid duplicating code we put this in the constructor.
		 * 
		 * Attention: shortcode_insert_button() is called from base constructor
		 * 
		 * @since 4.2.1
		 * @param AviaBuilder $builder
		 */
		public function __construct( $builder ) 
		{
			parent::__construct( $builder );

			$this->config['version']			= '1.0';
			$this->config['type']				= 'layout';
			$this->config['self_closing']		= 'no';
			$this->config['contains_content']	= 'yes';
			$this->config['contains_text']		= 'no';
			$this->config['first_in_row']		= 'first';
			$this->config['duplicate_template']	= avia_sc_columns::$columns_array;
		}
			

		/**
		 * Create the config array for the shortcode button
		 */
		function shortcode_insert_button()
		{
			$this->config['name']			= '1/1';
			$this->config['icon']			= AviaBuilder::$path['imagesURL'] . 'sc-full.png';
			$this->config['tab']			= __( 'Layout Elements', 'avia_framework' );
			$this->config['order']			= 100;
			$this->config['target']			= 'avia-section-drop';
			$this->config['shortcode']		= 'av_one_full';
			$this->config['html_renderer']	= false;
			$this->config['tooltip']		= __( 'Creates a single full width column', 'avia_framework' );
			$this->config['drag-level']		= 2;
			$this->config['drop-level']		= 2;
			$this->config['tinyMCE']		= array( 
													'instantInsert' => '[av_one_full first]Add Content here[/av_one_full]' 
												);
			$this->config['id_name']		= 'id';
			$this->config['id_show']		= 'yes';
			$this->config['aria_label']		= 'yes';
			
			$this->config['base_element']	= 'yes';
			$this->config['name_template']	= __( 'Column Template', 'avia_framework' ) . ' ' . $this->config['name'];
		}
		
		/**
		 * Popup Elements
		 *
		 * If this function is defined in a child class the element automatically gets an edit button, that, when pressed
		 * opens a modal window that allows to edit the element properties
		 *
		 * @return void
		 */
		function popup_elements()
		{
			$this->elements = array(
				
				array(						/*	stores the 'first' variable that removes margin from the first column	*/
						'id'	=> 0,
						'std'	=> '',
						'type'	=> 'hidden'
					),
					
				array(
						'type' 	=> 'tab_container', 
						'nodescription' => true
					),
					
				array(
						'type' 	=> 'tab',
						'name'  => __( 'Row Settings' , 'avia_framework' ),
						'nodescription' => true
					),
					
					array(
							'name' 	=> __( 'Row Settings', 'avia_framework' ),
							'desc' 	=> __( 'Row Settings apply to all columns in this row but can only be set in the first column', 'avia_framework' ),
							'type' 	=> 'heading',
							'description_class' => 'av-builder-note av-notice',
							'required'	=> array( '0', 'equals', '' ),
						),
					
					array(
							'name' 	=> __( 'Row Settings', 'avia_framework' ),
							'desc' 	=> __( 'These setting apply to all columns in this row and can only be set in the first column.', 'avia_framework' )
									 .'<br/><strong>'
									 . __( 'Please note:', 'avia_framework' )
									 .'</strong> '
									 . __( 'If you move another column into first position you will need to re-apply these settings.', 'avia_framework' ),
							'type' 	=> 'heading',
							'description_class' => 'av-builder-note av-notice column-settings-desc',
							'required'	=> array( '0', 'not', '' ),
						),
					
					array(
							'type'			=> 'template',
							'template_id'	=> 'toggle_container',
							'templates_include'	=> array( 
													$this->popup_key( 'layout_row_settings' ),
													$this->popup_key( 'styling_margins' ),
													$this->popup_key( 'row_screen_options' ),

												),
							'nodescription' => true
						),
						
				array(
						'type' 	=> 'tab_close',
						'nodescription' => true
					),
						
				array(
						'type' 	=> 'tab',
						'name'  => __( 'Styling', 'avia_framework' ),
						'nodescription' => true
					),
				
					array(
							'type'			=> 'template',
							'template_id'	=> 'toggle_container',
							'templates_include'	=> array( 
													$this->popup_key( 'styling_borders' ),
													$this->popup_key( 'styling_padding' ),
													$this->popup_key( 'styling_box_shadow' ),
													$this->popup_key( 'styling_background' ),
													$this->popup_key( 'layout_highlight' )
												),
							'nodescription' => true
						),
				
				array(
						'type' 	=> 'tab_close',
						'nodescription' => true
					),
				
				array(
						'type' 	=> 'tab',
						'name'  => __( 'Advanced', 'avia_framework' ),
						'nodescription' => true
					),
				
					array(
							'type' 	=> 'toggle_container',
							'nodescription' => true
						),

						array(
								'type'			=> 'template',
								'template_id'	=> $this->popup_key( 'advanced_animation' ),
							),
						
						array(
								'type'			=> 'template',
								'template_id'	=> $this->popup_key( 'layout_column_link' ),
							),
						
						array(	
								'type'			=> 'template',
								'template_id'	=> 'columns_visibility_toggle',
								'lockable'		=> true
							),
				
						array(	
								'type'			=> 'template',
								'template_id'	=> 'developer_options_toggle',
								'args'			=> array( 'sc' => $this )
							),
				
					array(
							'type' 	=> 'toggle_container_close',
							'nodescription' => true
						),
				
				array(
						'type' 	=> 'tab_close',
						'nodescription' => true
					),
				
				array(	
						'type'			=> 'template',
						'template_id'	=> 'element_template_selection_tab',
						'args'			=> array( 'sc'	=> $this )
					),

				array(
						'type' 	=> 'tab_container_close',
						'nodescription' => true
					)
			);
		}
		
		/**
		 * Create and register templates for easier maintainance
		 * 
		 * @since 4.6.4
		 */
		protected function register_dynamic_templates()
		{
			/**
			 * Layout Tab
			 * ============
			 */
			
			$c = array(
					
						array(
							'name' 	=> __( 'Equal Height Columns', 'avia_framework' ),
							'desc' 	=> __( 'Columns in this row can either have a height based on their content or all be of equal height based on the largest column ', 'avia_framework' ),
							'id' 	=> 'min_height',
							'type' 	=> 'select',
							'std' 	=> '',
							'lockable'	=> true,
							'required'	=> array( '0', 'not', '' ),
							'subtype'	=> array(
												__( 'Individual height', 'avia_framework' )	=> '',
												__( 'Equal height', 'avia_framework' )		=> 'av-equal-height-column',
											)
						),
					
						array(
							'name' 	=> __( 'Vertical Alignment','avia_framework' ),
							'desc' 	=> __( 'If a column is larger than its content, were do you want to align the content vertically?', 'avia_framework' ),
							'id' 	=> 'vertical_alignment',
							'type' 	=> 'select',
							'std' 	=> 'av-align-top',
							'lockable'	=> true,
							'required'	=> array( 'min_height', 'not', '' ),
							'subtype'	=> array(
												__( 'Top', 'avia_framework' )		=> 'av-align-top',
												__( 'Middle', 'avia_framework' )	=> 'av-align-middle',
												__( 'Bottom', 'avia_framework' )	=> 'av-align-bottom',
							)
						),
		
						array(
							'name' 	=> __( 'Space between columns','avia_framework' ),
							'desc' 	=> __( 'You can remove the default space between columns here.', 'avia_framework' ),
							'id' 	=> 'space',
							'type' 	=> 'select',
							'std' 	=> '',
							'lockable'	=> true,
							'required'	=> array( '0', 'not', '' ),
							'subtype'	=> array(
												__( 'Space between columns', 'avia_framework' )		=> '',
												__( 'No space between columns', 'avia_framework' )	=> 'no_margin',
							)
						),

						array(
							'name' 	=> __( 'Row Box-Shadow', 'avia_framework' ),
							'desc' 	=> __( 'Add a box-shadow to the row', 'avia_framework' ),
							'id' 	=> 'row_boxshadow',
							'type' 	=> 'checkbox',
							'std' 	=> '',
							'lockable'	=> true,
							'required'	=> array( 'min_height', 'not', '' )
						),

						array(
							'name' 	=> __( 'Row Box-Shadow Color', 'avia_framework' ),
							'desc' 	=> __( 'Set a color for the box-shadow', 'avia_framework' ),
							'id' 	=> 'row_boxshadow_color',
							'type' 	=> 'colorpicker',
							'rgba' 	=> true,
							'std' 	=> '',
							'lockable'	=> true,
							'required'	=> array( 'row_boxshadow', 'not', '' )
						),

						array(
							'name' 	=> __( 'Row Box-Shadow Width', 'avia_framework' ),
							'desc' 	=> __( 'Set the width of the box-shadow', 'avia_framework' ),
							'id' 	=> 'row_boxshadow_width',
							'type' 	=> 'select',
							'std' 	=> '10',
							'lockable'	=> true,
							'required'	=> array( 'row_boxshadow', 'not', '' ),
							'subtype'	=> AviaHtmlHelper::number_array( 1, 40, 1, array(), 'px' )
						)

				);
			
			$template = array(
							array(	
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Row Layout', 'avia_framework' ),
								'content'		=> $c
							),
					);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'layout_row_settings' ), $template );
			
			$c = array(
						array(	
							'type'			=> 'template',
							'template_id'	=> 'linkpicker_toggle',
							'name'			=> __( 'Column Link', 'avia_framework' ),
							'desc'			=> __( 'Select where this column should link to', 'avia_framework' ),
							'subtypes'		=> array( 'no', 'manually', 'single', 'taxonomy' ),
							'no_toggle'		=> true,
							'lockable'		=> true
						),
				
						array(
							'name'		=> __( 'Hover Effect', 'avia_framework' ),
							'desc'		=> __( 'Choose if you want to have a hover effect on the column', 'avia_framework' ),
							'id'		=> 'link_hover',
							'type'		=> 'select',
							'std'		=> '',
							'lockable'	=> true,
							'required'	=> array( 'link', 'not', '' ),
							'subtype'	=> array(
												__( 'No', 'avia_framework' )	=> '',
												__( 'Yes', 'avia_framework' )	=> 'opacity80'
											)
						),
					
						array(
							'name' 			=> __( 'Title Attribut', 'avia_framework' ),
							'desc' 			=> __( 'Add a title attribut for screen reader', 'avia_framework' ),
							'id' 			=> 'title_attr',
							'type' 			=> 'input',
							'std' 			=> '',
							'container_class' => 'av_half av_half_first',
							'lockable'		=> true,
							'required'		=> array( 'link', 'not', '' )							
						),


						array(
							'name' 			=> __( 'Alt Attribut', 'avia_framework' ),
							'desc' 			=> __( 'Add an alt attribut for screen reader','avia_framework' ),
							'id' 			=> 'alt_attr',
							'type' 			=> 'input',
							'std' 			=> '',
							'container_class' => 'av_half',
							'lockable'		=> true,
							'required'		=> array( 'link', 'not', '' )
						)
				);
			
			$template = array(
							array(	
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Column Link', 'avia_framework' ),
								'content'		=> $c
							),
					);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'layout_column_link' ), $template );
			
			$c = array(
						array(
							'name' 	=> __( 'Highlight Column', 'avia_framework' ),
							'desc' 	=> __( 'Highlight this column by making it slightly bigger', 'avia_framework' ),
							'id' 	=> 'highlight',
							'type' 	=> 'checkbox',
							'std' 	=> '',
							'lockable'	=> true
						),

						array(
							'name' 	=> __( 'Highlight - Column Scaling', 'avia_framework' ),
							'desc' 	=> __( 'How much should the highlighted column be increased in size?', 'avia_framework' ),
							'id' 	=> 'highlight_size',
							'type' 	=> 'select',
							'std' 	=> '',
							'lockable'	=> true,
							'required'	=> array( 'highlight', 'not', '' ),
							'subtype'	=> AviaHtmlHelper::number_array( 1.1, 1.6, 0.1, array() ),
						)
				);
			
			$template = array(
							array(	
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Highlight', 'avia_framework' ),
								'content'		=> $c
							),
					);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'layout_highlight' ), $template );
			
			/**
			 * Styling Tab
			 * ============
			 */
			
			$c = array(
						array(
							'name' 	=> __( 'Border','avia_framework' ),
							'desc' 	=> __( 'Set the border of the column here', 'avia_framework' ),
							'id' 	=> 'border',
							'type' 	=> 'select',
							'std' 	=> '',
							'lockable'	=> true,
							'subtype'	=> AviaHtmlHelper::number_array( 1, 40, 1, array( __( 'None', 'avia_framework' ) => '' ) , 'px' )
						),
						
						array(	
							'name' 	=> __( 'Border Color', 'avia_framework' ),
							'desc' 	=> __( 'Set a border color for this column', 'avia_framework' ),
							'id' 	=> 'border_color',
							'type' 	=> 'colorpicker',
							'rgba' 	=> true,
							'std' 	=> '',
							'lockable'	=> true,
							'required'	=> array( 'border', 'not', '' )
						),
						
						array(	
							'name' 	=> __( 'Border Radius', 'avia_framework' ),
							'desc' 	=> __( 'Set the border radius of the column', 'avia_framework' ),
							'id' 	=> 'radius',
							'type' 	=> 'multi_input',
							'sync' 	=> true,
							'std' 	=> '0px',
							'lockable'	=> true,
							'multi'		=> array(	
											'top'		=> __( 'Top-Left-Radius', 'avia_framework' ), 
											'right'		=> __( 'Top-Right-Radius', 'avia_framework' ), 
											'bottom'	=> __( 'Bottom-Right-Radius', 'avia_framework' ),
											'left'		=> __( 'Bottom-Left-Radius', 'avia_framework' )
										)
						)
				);
			
			$template = array(
							array(	
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Borders', 'avia_framework' ),
								'content'		=> $c 
							),
					);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_borders' ), $template );
			
			$c = array(
						array(	
							'name' 	=> __( 'Inner Padding', 'avia_framework' ),
							'desc' 	=> __( 'Set the distance from the column content to the border here. Both pixel and &percnt; based values are accepted. eg: 30px, 5&percnt;', 'avia_framework' ),
							'id' 	=> 'padding',
							'type' 	=> 'multi_input',
							'sync' 	=> true,
							'std' 	=> '0px',
							'lockable'	=> true,
							'multi'		=> array(	
											'top'		=> __( 'Padding-Top', 'avia_framework' ), 
											'right'		=> __( 'Padding-Right', 'avia_framework' ), 
											'bottom'	=> __( 'Padding-Bottom', 'avia_framework' ),
											'left'		=> __( 'Padding-Left', 'avia_framework' )
										)
						)
				
				);
			
			$template = array(
							array(	
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Padding', 'avia_framework' ),
								'content'		=> $c 
							),
					);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_padding' ), $template );
			
			$c = array(
						array(
							'name' 	=> __( 'Custom top and bottom margin', 'avia_framework' ),
							'desc' 	=> __( 'If checked allows you to set a custom top and bottom margin. Otherwise the margin is calculated by the theme based on surrounding elements', 'avia_framework' ),
							'id' 	=> 'custom_margin',
							'type' 	=> 'checkbox',
							'std' 	=> '',
							'lockable'	=> true,
							'required'	=> array( '0', 'not', '' ),
						),
					
						array(	
							'name' 	=> __( 'Custom top and bottom margin', 'avia_framework' ),
							'desc' 	=> __( 'Set a custom top or bottom margin. Both pixel and &percnt; based values are accepted. eg: 30px, 5&percnt;. <br /><br /><strong>Limitation:</strong> Negative values cannot be used out of the box when using individual height columms. This will need additional custom CSS rules specific for your site to avoid breaking of layout.', 'avia_framework' ),
							'id' 	=> 'margin',
							'type' 	=> 'multi_input',
							'sync' 	=> true,
							'std' 	=> '0px',
							'lockable'	=> true,
							'required'	=> array( 'custom_margin', 'not', '' ),
							'multi'		=> array(	
											'top'		=> __( 'Margin-Top', 'avia_framework' ), 
											'bottom'	=> __( 'Margin-Bottom', 'avia_framework' )
										)
						)
				);
			
			$template = array(
							array(	
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Margins', 'avia_framework' ),
								'content'		=> $c
							),
					);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_margins' ), $template );
			
			$c = array(
						array(
							'name' 	=> __( 'Column Box-Shadow', 'avia_framework' ),
							'desc' 	=> __( 'Add a box-shadow to the column','avia_framework' ),
							'id' 	=> 'column_boxshadow',
							'type' 	=> 'checkbox',
							'std' 	=> '',
							'lockable'	=> true,
					   ),

						array(
							'name' 	=> __( 'Column Box-Shadow Color', 'avia_framework' ),
							'desc' 	=> __( 'Set a color for the box-shadow', 'avia_framework' ),
							'id' 	=> 'column_boxshadow_color',
							'type' 	=> 'colorpicker',
							'rgba' 	=> true,
							'std' 	=> '',
							'lockable'	=> true,
							'required'	=> array( 'column_boxshadow', 'not', '' )
						),

						array(
							'name' 	=> __( 'Column Box-Shadow Width', 'avia_framework' ),
							'desc' 	=> __( 'Set the width of the box-shadow', 'avia_framework' ),
							'id' 	=> 'column_boxshadow_width',
							'type' 	=> 'select',
							'std' 	=> '10',
							'lockable'	=> true,
							'required'	=> array( 'column_boxshadow', 'not', '' ),
							'subtype'	=> AviaHtmlHelper::number_array( 1, 40, 1, array(), 'px' )
						),
				);
			
			$template = array(
							array(	
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Box Shadow', 'avia_framework' ),
								'content'		=> $c 
							),
					);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_box_shadow' ), $template );
			
			$c = array(
						array(
							'name' 	=> __( 'Background', 'avia_framework' ),
							'desc' 	=> __( 'Select the type of background for the column.', 'avia_framework' ),
							'id' 	=> 'background',
							'type' 	=> 'select',
							'std' 	=> 'bg_color',
							'lockable'	=> true,
							'subtype'	=> array(
											__( 'Background Color', 'avia_framework' )		=> 'bg_color',
											__( 'Background Gradient', 'avia_framework' )	=> 'bg_gradient',
										)
						),

						array(
							'name' 	=> __( 'Custom Background Color', 'avia_framework' ),
							'desc' 	=> __( 'Select a custom background color for this cell here. Leave empty for default color', 'avia_framework' ),
							'id' 	=> 'background_color',
							'type' 	=> 'colorpicker',
							'rgba' 	=> true,
							'std' 	=> '',
							'lockable'	=> true,
							'required'	=> array( 'background', 'equals', 'bg_color' )
						),

						array(
							'name' 	=> __( 'Background Gradient Color 1', 'avia_framework' ),
							'desc' 	=> __( 'Select the first color for the gradient.', 'avia_framework' ),
							'id' 	=> 'background_gradient_color1',
							'type' 	=> 'colorpicker',
							'rgba' 	=> true,
							'std' 	=> '',
							'container_class' => 'av_third av_third_first',
							'lockable'	=> true,
							'required'	=> array( 'background', 'equals', 'bg_gradient' )
						),
				
						array(
							'name' 	=> __( 'Background Gradient Color 2', 'avia_framework' ),
							'desc' 	=> __( 'Select the second color for the gradient.', 'avia_framework' ),
							'id' 	=> 'background_gradient_color2',
							'type' 	=> 'colorpicker',
							'rgba' 	=> true,
							'std' 	=> '',
							'container_class' => 'av_third',
							'lockable'	=> true,
							'required'	=> array( 'background', 'equals', 'bg_gradient' )
						),

						array(
							'name' 	=> __( 'Background Gradient Direction', 'avia_framework' ),
							'desc' 	=> __( 'Define the gradient direction', 'avia_framework' ),
							'id' 	=> 'background_gradient_direction',
							'type' 	=> 'select',
							'std'	=> 'vertical',
							'container_class' => 'av_third',
							'lockable'	=> true,
							'required'	=> array( 'background', 'equals', 'bg_gradient' ),
							'subtype'	=> array(
											__( 'Vertical','avia_framework' )	=> 'vertical',
											__( 'Horizontal','avia_framework' )	=> 'horizontal',
											__( 'Radial','avia_framework' )		=> 'radial',
											__( 'Diagonal Top Left to Bottom Right', 'avia_framework' )	=> 'diagonal_tb',
											__( 'Diagonal Bottom Left to Top Right', 'avia_framework' )	=> 'diagonal_bt',
										)
						),
						
						array(
							'name' 	=> __( 'Custom Background Image', 'avia_framework' ),
							'desc' 	=> __( "Either upload a new, or choose an existing image from your media library. Leave empty if you don't want to use a background image", 'avia_framework' ),
							'id' 	=> 'src',
							'type' 	=> 'image',
							'title' => __( 'Insert Image', 'avia_framework' ),
							'button' => __( 'Insert', 'avia_framework' ),
							'std' 	=> '',
							'lockable'	=> true,
							'locked'	=> array( 'src', 'attachment', 'attachment_size' )
						),
					
					/*
						array(
							'name' 	=> __( 'Background Attachment', 'avia_framework' ),
							'desc' 	=> __( 'Background can either scroll with the page or be fixed', 'avia_framework' ),
							'id' 	=> 'background_attachment',
							'type' 	=> 'select',
							'std' 	=> 'scroll',
							'required'	=> array( 'src', 'not', '' ),
							'subtype'	=> array(
											__( 'Scroll', 'avia_framework' )	=> 'scroll',
											__( 'Fixed', 'avia_framework' )		=> 'fixed',
							)
						),
*/
						array(
							'type'			=> 'template',
							'template_id'	=> 'background_image_position',
							'lockable'		=> true
						)
				
				);
			
			$template = array(
							array(	
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Background', 'avia_framework' ),
								'content'		=> $c 
							),
					);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_background' ), $template );
			
			/**
			 * Adcanced Tab
			 * ============
			 */
			
			$c = array(
						array(
							'name'	=> __( 'Animation','avia_framework' ),
							'desc'	=> __( 'Set an animation for this element. The animation will be shown once the element appears first on screen. Animations only work in modern browsers and only on desktop computers to keep page rendering as fast as possible.', 'avia_framework' ),
							'id'	=> 'animation',
							'type'	=> 'select',
							'std'	=> '',
							'lockable'	=> true,
							'subtype'	=> array(
												__( 'None', 'avia_framework' ) => '',
							
												__( 'Fade Animations', 'avia_framework' ) => array(
															__('Fade in', 'avia_framework' )	=> 'fade-in',
															__('Pop up', 'avia_framework' )		=> 'pop-up',
														),
												__( 'Slide Animations', 'avia_framework' ) => array(
															__( 'Top to Bottom', 'avia_framework' )	=> 'top-to-bottom',
															__( 'Bottom to Top', 'avia_framework' )	=> 'bottom-to-top',
															__( 'Left to Right', 'avia_framework' )	=> 'left-to-right',
															__( 'Right to Left', 'avia_framework' )	=> 'right-to-left',
														),
												__( 'Rotate',  'avia_framework' ) => array(
															__( 'Full rotation', 'avia_framework' )			=> 'av-rotateIn',
															__( 'Bottom left rotation', 'avia_framework' )	=> 'av-rotateInUpLeft',
															__( 'Bottom right rotation', 'avia_framework' )	=> 'av-rotateInUpRight',
														)	
											)
						)
				);
			
			$template = array(
							array(	
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Animation', 'avia_framework' ),
								'content'		=> $c 
							),
					);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'advanced_animation' ), $template );
			
			
			$c = array(
						array(	
							'name' 	=> __( 'Fullwidth Break Point', 'avia_framework' ),
							'desc' 	=> __( 'The columns in this row will switch to fullwidth at this screen width ', 'avia_framework' ),
							'id' 	=> 'mobile_breaking',
							'type' 	=> 'select',
							'std' 	=> '',
							'lockable'	=> true,
							'required'	=> array( '0', 'not', '' ),
							'subtype'	=> array(	
												__( 'On mobile devices (at a screen width of 767px or lower)', 'avia_framework' )	=> '',
												__( 'On tablets (at a screen width of 989px or lower)', 'avia_framework' )			=> 'av-break-at-tablet',
											)
						)
				);
			
			$template = array(
							array(	
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Row Screen Options', 'avia_framework' ),
								'content'		=> $c
							),
					);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'row_screen_options' ), $template );
		}


		/**
		 * Editor Element - this function defines the visual appearance of an element on the AviaBuilder Canvas
		 * Most common usage is to define some markup in the $params['innerHtml'] which is then inserted into the drag and drop container
		 * Less often used: $params['data'] to add data attributes, $params['class'] to modify the className
		 *
		 *
		 * @param array $params this array holds the default values for $content and $args.
		 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
		 */
		public function editor_element( $params )
		{
			$default = array();
			$locked = array();
			$attr = $params['args'];
			$content = $params['content'];
			Avia_Element_Templates()->set_locked_attributes( $attr, $this, $this->config['shortcode'], $default, $locked, $content );
			
			$args = $attr;			//		=>  extract( $params );
			$data = isset( $params['data'] ) && is_array( $params['data'] ) ? $params['data'] : array();
			$data_locked = array();
			$extraClass = '';

			$name = $this->config['shortcode'];
			$drag = $this->config['drag-level'];
			$drop = $this->config['drop-level'];

			$size = avia_sc_columns::$columns_array;

			$data['shortcodehandler'] 	= $this->config['shortcode'];
			$data['modal_title'] 		= __( 'Edit Column', 'avia_framework' );
			$data['modal_ajax_hook'] 	= $this->config['shortcode'];
			$data['dragdrop-level']		= $this->config['drag-level'];
			$data['allowed-shortcodes'] = $this->config['shortcode'];
			$data['closing_tag']		= $this->is_self_closing() ? 'no' : 'yes';
			$data['base_shortcode']		= $this->config['shortcode'];
			$data['element_title']		= $this->config['name'];
			$data['element_tooltip']	= $this->config['tooltip'];
				
			if( ! empty( $this->config['modal_on_load'] ) )
			{
				$data['modal_on_load'] 	= $this->config['modal_on_load'];
			}
			
			foreach( $locked as $key => $value ) 
			{
				$data_locked[ 'locked_' . $key ] = $value;
			}

			// add background color or gradient to indicator
			$el_bg = '';

			if( empty( $args['background'] ) || ( $args['background'] == 'bg_color' ) )
			{
				$el_bg = ! empty( $args['background_color'] ) ? " background:{$args['background_color']};" : '';
			}
			else 
			{
				if( $args['background_gradient_color1'] && $args['background_gradient_color2'] ) 
				{
					$el_bg = "background:linear-gradient({$args['background_gradient_color1']},{$args['background_gradient_color2']});";
				}
			}
			
			$data_locked['initial_el_bg'] = $el_bg;
			$data_locked['initial_layout_element_bg'] = $this->get_bg_string( $args );
			
			$dataString  = AviaHelper::create_data_string( $data );
			$dataStringLocked = AviaHelper::create_data_string( $data_locked );

			$extraClass .= isset( $args['element_template'] ) && $args['element_template'] > 0 ? ' element_template_selected' : '  no_element_template';
			$extraClass	.= isset( $args[0] ) && $args[0] == 'first' ? ' avia-first-col' : '';
			

			$output  = "<div class='avia_layout_column avia_layout_column_no_cell avia_pop_class avia-no-visual-updates {$name} {$extraClass} av_drag' {$dataString} data-width='{$name}'>";
			
			$output .=		'<div class="avia_data_locked_container" ' . $dataStringLocked . ' data-update_element_template="yes"></div>';
			
			$output .=		"<div class='avia_sorthandle menu-item-handle'>";

			$output .=			"<a class='avia-smaller avia-change-col-size' href='#smaller' title='" . __( 'Decrease Column Size', 'avia_framework' ) . "'>-</a>";
			$output .=			"<span class='avia-col-size'>{$size[$name]}</span>";
			$output .=			"<a class='avia-bigger avia-change-col-size'  href='#bigger' title='" . __( 'Increase Column Size', 'avia_framework' ) . "'>+</a>";
			$output .=			"<a class='avia-delete'  href='#delete' title='" . __( 'Delete Column', 'avia_framework' ) . "'>x</a>";
			$output .=			"<a class='avia-save-element'  href='#save-element' title='" . __( 'Save Element as Template', 'avia_framework' ) . "'>+</a>";
			//$output .=		"<a class='avia-new-target'  href='#new-target' title='" . __( 'Move Element', 'avia_framework' ) . "'>+</a>";
			$output .=			"<a class='avia-clone'  href='#clone' title='" . __( 'Clone Column', 'avia_framework' ) . "' >" . __( 'Clone Column', 'avia_framework' ) . '</a>';
			$output .=			"<span class='avia-element-bg-color' style='{$el_bg}'></span>";
			
			if( ! empty( $this->config['popup_editor'] ) )
			{
				$output .=		"<a class='avia-edit-element'  href='#edit-element' title='" . __( 'Edit Column', 'avia_framework' ) . "'>" . __( 'edit', 'avia_framework' ) . '</a>';
			}

			$output .=		'</div>';
			$output .=		"<div class='avia_inner_shortcode avia_connect_sort av_drop ' data-dragdrop-level='{$drop}'>";
			$output .=			"<textarea data-name='text-shortcode' cols='20' rows='4'>" . ShortcodeHelper::create_shortcode_by_array( $name, $content, $params['args'] ) . '</textarea>';
				
			if( $content )
			{
				$content = $this->builder->do_shortcode_backend( $content );
			}
			
			$output .=		$content;
			$output .=		'</div>';
			$output .=		"<div class='avia-layout-element-bg' style='{$data_locked['initial_layout_element_bg']}'></div>";
			$output .= '</div>';

			return $output;
		}
			
		/**
		 * Frontend Shortcode Handler
		 *
		 * @param array $atts array of attributes
		 * @param string $content text within enclosing form of shortcode element
		 * @param string $shortcodename the shortcode found, when == callback name
		 * @return string $output returns the modified html string
		 */
		function shortcode_handler( $atts, $content = '', $shortcodename = '', $meta = '' )
		{
			global $avia_config;
			
			$default = array(
						'padding'				=> '',
						'background'		                => '',
						'background_color'	            	=> '',
						'background_gradient_color1'		=> '',
						'background_gradient_color2'	   	=> '',
						'background_gradient_direction'	   	=> '',
						'background_position' 	=> '',
						'background_repeat' 	=> '',
						'background_attachment' => '',
						'fetch_image'			=> '',
						'attachment_size'		=> '',
						'attachment'			=> 'scroll',
						'radius'				=> '',
						'space'					=> '',
						'border'				=> '',
						'border_color'			=> '',
						'border_style'			=> 'solid',
						'column_boxshadow'		=> '',
						'column_boxshadow_color'=> 'rgba(0,0,0,0.1)',
						'column_boxshadow_width'=> '10px',
						'row_boxshadow'			=> '',
						'row_boxshadow_color'   => 'rgba(0,0,0,0.1)',
						'row_boxshadow_width'   => '10px',
						'margin'				=> '',
						'custom_margin'			=> '',
						'min_height'			=> '',
						'vertical_alignment'	=> 'av-align-top',
						'animation'				=> '',
						'link'					=> '',
						'linktarget'			=> '',
						'link_hover'			=> '',
						'title_attr'			=> '',
						'alt_attr'				=> '',
						'mobile_display'		=> '',
						'mobile_breaking'		=> '',
						'highlight'				=> '',
						'highlight_size'		=> '',
					);
			
			
			//	we skip $content override as we only allow styling of column to be locked
			$locked = array();
			Avia_Element_Templates()->set_locked_attributes( $atts, $this, $shortcodename, $default, $locked );
			Avia_Element_Templates()->add_template_class( $meta, $atts, $default );

			
			$first = ( isset( $atts[0]) && trim( $atts[0]) == 'first' ) ? 'first' : '';

			$atts = shortcode_atts( $default, $atts, $this->config['shortcode'] );

			$avia_config['current_column'] = $shortcodename;

			if( $first )
			{
				avia_sc_columns::$first_atts = $atts;
			}

			$extraClass	 = '';
			$outer_style = '';
			$inner_style = '';
			$margin_style = '';
			$output		 = '';
			$extra_table_class = '';
			$anim_class  = empty( $atts['animation'] ) ? '' : ' av-animated-generic ' . $atts['animation'] . ' ';
			$extraClass .= $anim_class;
			$extraClass .= empty( $atts['mobile_display'] ) ? '' : ' ' . $atts['mobile_display'] . ' ';


			if( ! empty( $atts['attachment'] ) )
			{
				$src = wp_get_attachment_image_src( $atts['attachment'], $atts['attachment_size'] );
				if( ! empty( $src[0] ) ) 
				{
					$atts['fetch_image'] = $src[0];
				}
			}

			if( $atts['background_repeat'] == 'stretch' )
			{
				$extraClass .= ' avia-full-stretch';
				$atts['background_repeat'] = 'no-repeat';
			}

			if( $atts['background_repeat'] == 'contain' )
			{
				$extraClass .= ' avia-full-contain';
				$atts['background_repeat'] = 'no-repeat';
			}

			if( ! empty( avia_sc_columns::$first_atts['space'] ) )
			{
				$extraClass .= ' ' . avia_sc_columns::$first_atts['space'];
			}

			if( ! empty( avia_sc_columns::$first_atts['mobile_breaking'] ) )
			{
				$extraClass .= ' '.avia_sc_columns::$first_atts['mobile_breaking'];
				$extra_table_class = ' av-break-at-tablet-table';
			}

				
			if( ! empty( avia_sc_columns::$first_atts['min_height'] ) )
			{
				$extraClass .= ' flex_column_table_cell';
				$extraClass .= ' ' . avia_sc_columns::$first_atts['min_height'] . ' ' . avia_sc_columns::$first_atts['vertical_alignment'];
			}
			else
			{
				$extraClass .= ' flex_column_div';
			}

			$margins = '';
			$margin_style = '';

			if( ! empty( avia_sc_columns::$first_atts['custom_margin'] ) )
			{
				$explode_margin = explode( ',', avia_sc_columns::$first_atts['margin'] );
				if( count( $explode_margin ) <= 1 )
				{
					$explode_margin[1] = $explode_margin[0];
				}

				$atts['margin-top'] = $explode_margin[0];
				$atts['margin-bottom'] = $explode_margin[1];

				$margins .= AviaHelper::style_string( $atts, 'margin-top' );
				$margins .= AviaHelper::style_string( $atts, 'margin-bottom' );

				if( ! empty( avia_sc_columns::$first_atts['min_height'] ) )
				{
					$margin_style = $margins;
				}
				else
				{
					$outer_style .= $margins;
				}
			}


			$row_boxshadow_style = '';

			if( ! empty($atts['row_boxshadow'] ) )
			{
				if( array_key_exists( 'row_boxshadow_width', $atts ) && array_key_exists( 'row_boxshadow_color', $atts ) ) 
				{
					if( $atts['row_boxshadow_width'] !== '' && $atts['row_boxshadow_color'] !== '' ) 
					{
						$row_boxshadow_style .= 'box-shadow: 0 0 ' . $atts['row_boxshadow_width'] . 'px 0 ' . $atts['row_boxshadow_color'] . '; ';
					}
				}
			}
			/*
			if( ! empty( $atts['boxshadow'] ) )
			{

				if (array_key_exists('boxshadow_width',$atts) && array_key_exists('boxshadow_color',$atts)) {
					if ($atts['boxshadow_width'] !== '' && $atts['boxshadow_color'] !== '') {
						$row_boxshadow_style .= 'box-shadow: 0 0 '.$atts['boxshadow_width'].'px 0 '.$atts['boxshadow_color'].'; ';
					}
				}

			}
			*/

			$row_style = AviaHelper::style_string( $margin_style . $row_boxshadow_style );

			$explode_padding = explode( ',', $atts['padding'] );
			if( count( $explode_padding ) > 1 )
			{
				$atts['padding'] = '';
				foreach( $explode_padding as $value )
				{
					if( empty( $value ) ) 
					{
						$value = '0';
					}

					$atts['padding'] .= $value .' ';
				}
			}

			if( $atts['padding'] == '0px' || $atts['padding'] == '0' || $atts['padding'] == '0%' )
			{
				$extraClass .= ' av-zero-column-padding';
				$atts['padding'] = '';
			}
				
				
			$explode_radius = explode( ',', $atts['radius'] );
			if( count( $explode_radius ) > 1 )
			{
				$atts['radius'] = '';
				foreach( $explode_radius as $value )
				{
					if( empty( $value ) ) 
					{
						$value = '0';
					}

					$atts['radius'] .= $value . ' ';
				}
			}


			// background image, color and gradient
			$bg_image = '';

			if( ! empty( $atts['fetch_image'] ) )
			{
				$bg_image = 'url('.$atts['fetch_image'].') '.$atts['background_position'].' '.$atts['background_repeat'].' '.$atts['background_attachment'];
			}

			$has_bg_color_or_gradient = false;


			if( $atts['background'] == 'bg_color' )
			{
				$bg_string = '';

				if( $atts['background_color'] )
				{
					$bg_string .= $bg_image . ' ' . $atts['background_color'];
					$has_bg_color_or_gradient = true;
				}
				$atts['background_string'] = $bg_string;
				$outer_style .= AviaHelper::style_string( $atts, 'background_string', 'background' );
			}
			// assemble gradient declaration
			else 
			{
				if ( $atts['background_gradient_color1'] && $atts['background_gradient_color2'] )
				{
					$has_bg_color_or_gradient = true;
					$gradient_val = '';

					// add image string if available
					if( $bg_image )
					{
						$gradient_val .= $bg_image.', ';
					}

					switch ( $atts['background_gradient_direction'] ) 
					{
						case 'vertical':
							$gradient_val .= 'linear-gradient(';
							break;
						case 'horizontal':
							$gradient_val .= 'linear-gradient(to right,';
							break;
						case 'radial':
							$gradient_val .= 'radial-gradient(';
							break;
						case 'diagonal_tb':
							$gradient_val .= 'linear-gradient(to bottom right,';
							break;
						case 'diagonal_bt':
							$gradient_val .= 'linear-gradient(45deg,';
							break;
					}

					$gradient_val .= $atts['background_gradient_color1'] . ',' . $atts['background_gradient_color2'] . ')';

					// fallback background color for IE9
					if( $atts['background_color'] == '' ) 
					{
						$outer_style .= AviaHelper::style_string( $atts, 'background_gradient_color1', 'background-color' );
					}

					$atts['background_string'] = $gradient_val;
					$outer_style .= AviaHelper::style_string( $atts, 'background_string', 'background' );
				}
			}

			if( ! $has_bg_color_or_gradient ) 
			{
				$atts['background_string'] = $bg_image;
				$outer_style .= AviaHelper::style_string( $atts, 'background_string', 'background' );
			}


			if( ! empty( $atts['border'] ) )
			{
				$outer_style .= AviaHelper::style_string( $atts, 'border', 'border-width', 'px' );
				$outer_style .= AviaHelper::style_string( $atts, 'border_color', 'border-color' );
				$outer_style .= AviaHelper::style_string( $atts, 'border_style', 'border-style' );
			}

			if( ! empty( $atts['column_boxshadow'] ) )
			{
				if( array_key_exists( 'column_boxshadow_width', $atts ) && array_key_exists( 'column_boxshadow_color', $atts ) ) 
				{
					if( $atts['column_boxshadow_width'] !== '' && $atts['column_boxshadow_color'] !== '' ) 
					{
						$outer_style .= 'box-shadow: 0 0 ' . $atts['column_boxshadow_width'] . 'px 0 ' . $atts['column_boxshadow_color'] . '; ';
					}
				}
			}

			if( ! empty( $atts['highlight'] ) )
			{
				if( array_key_exists( 'highlight_size', $atts ) ) 
				{
					$highlight_size = $atts['highlight_size'];
					$outer_style .= "-webkit-transform: scale({$highlight_size}); -ms-transform: scale({$highlight_size}); transform: scale({$highlight_size}); z-index: 4;";
				}
			}


			$outer_style .= AviaHelper::style_string( $atts, 'padding' );
			$outer_style .= AviaHelper::style_string( $atts, 'background_color', 'background-color' );
			$outer_style .= AviaHelper::style_string( $atts, 'radius', 'border-radius' );
			$outer_style  = AviaHelper::style_string( $outer_style );

				
			if( $first )
			{	
				avia_sc_columns::$calculated_size = 0;

				if( ! empty( $meta['siblings']['prev']['tag'] ) && in_array( $meta['siblings']['prev']['tag'], array( 'av_one_full', 'av_one_half', 'av_one_third', 'av_two_third', 'av_three_fourth', 'av_one_fourth', 'av_one_fifth', 'av_textblock' ) ) )
				{
					avia_sc_columns::$extraClass = 'column-top-margin';
				}
				else
				{
					avia_sc_columns::$extraClass = '';
				}
			}

			
			if( ! empty( avia_sc_columns::$first_atts['min_height'] ) && avia_sc_columns::$calculated_size == 0 )
			{
				$output .= "<div class='flex_column_table " . avia_sc_columns::$first_atts['min_height'] . "-flextable " . avia_sc_columns::$first_atts['mobile_breaking'] . "-flextable' {$row_style}>";
			}	

			if( ! $first && empty( avia_sc_columns::$first_atts['space'] ) && ! empty( avia_sc_columns::$first_atts['min_height'] ) )
			{
				$output .= "<div class='av-flex-placeholder'></div>";
			}

			avia_sc_columns::$calculated_size += avia_sc_columns::$size_array[ $this->config['shortcode'] ];

			$link = AviaHelper::get_url( $atts['link'] );
			$link_data = '';
			$screen_reader_link = '';
			if( ! empty( $link ) )
			{
				$extraClass .= ' avia-link-column av-column-link';
				if( ! empty( $atts['link_hover'] ) )
				{
					$extraClass .= ' avia-link-column-hover';
				}

				$screen_reader = '';

				$link_data .= ' data-link-column-url="' . esc_attr( $link ) . '" ';

				if( ( strpos( $atts['linktarget'], '_blank' ) !== false ) )
				{
					$link_data .=  ' data-link-column-target="_blank" ';
					$screen_reader .= ' target="_blank" ';
				}

				//	we add this, but currently not supported in js
				if( strpos( $atts['linktarget'], 'nofollow' ) !== false )
				{
					$link_data .= ' data-link-column-rel="nofollow" ';
					$screen_reader .= ' rel="nofollow" ';
				}

				if( ! empty( $atts['title_attr'] ) )
				{
					$screen_reader .= ' title="' . esc_attr( $atts['title_attr'] ) . '"';
				}

				if( ! empty( $atts['alt_attr'] ) )
				{
					$screen_reader .= ' alt="' . esc_attr( $atts['alt_attr'] ) . '"';
				}

				/**
				 * Add an invisible link also for screen readers
				 */				
				$screen_reader_link .=	'<a class="av-screen-reader-only" href=' . esc_attr( $link ) . " {$screen_reader}" . '>';
				$screen_reader_link .=		AviaHelper::get_screen_reader_url_text( $atts['link'] );
				$screen_reader_link .=	'</a>';
			}

			$aria_label = ! empty( $meta['aria_label'] ) ? " aria-label='{$meta['aria_label']}' " : '';

			$output .= '<div class="flex_column ' . $shortcodename . ' ' . $extraClass . ' ' . $first.' ' . $meta['el_class'] . ' ' . avia_sc_columns::$extraClass . '" ' . $outer_style . $link_data . $meta['custom_el_id'] . $aria_label . '>';
			$output .= $screen_reader_link;
			//if the user uses the column shortcode without the layout builder make sure that paragraphs are applied to the text
			$content = ( empty( $avia_config['conditionals']['is_builder_template'] ) ) ? ShortcodeHelper::avia_apply_autop( ShortcodeHelper::avia_remove_autop( $content ) ) : ShortcodeHelper::avia_remove_autop( $content, true );

			$output .= trim( $content ) . '</div>';
	
				
			$force_close = false;

			if( isset( $meta['siblings'] ) && isset($meta['siblings']['next'] ) && isset( $meta['siblings']['next']['tag'] ) )
			{
				if( ! array_key_exists( $meta['siblings']['next']['tag'], avia_sc_columns::$size_array ) )
				{
					$force_close = true;
				}
			}
				
			/**
			 * check if row will break into next column 
			 */
			if( ( false === $force_close ) && ! empty( avia_sc_columns::$first_atts['min_height'] ) && ( 'av-equal-height-column' ==  avia_sc_columns::$first_atts['min_height'] ) )
			{
				if( ! isset( $meta['siblings']['next']['tag'] ) )
				{
					$force_close = true;
				}
				else if( ( avia_sc_columns::$calculated_size + avia_sc_columns::$size_array[ $meta['siblings']['next']['tag'] ] ) > 1.0 )
				{
					$force_close = true;
				}
			}

			if( ! empty( avia_sc_columns::$first_atts['min_height'] ) && ( avia_sc_columns::$calculated_size >= 0.95 || $force_close ) )
			{
				$output .= "</div><!--close column table wrapper. Autoclose: {$force_close} -->";
				avia_sc_columns::$calculated_size = 0;
			}

			unset( $avia_config['current_column'] );

			return $output;
		}
		
		/**
		 * Returns the width of the column. As this is the base class for all columns we only need to implement it here.
		 * 
		 * @since 4.2.1
		 * @return float
		 */
		public function get_element_width()
		{
			return isset( avia_sc_columns::$size_array[ $this->config['shortcode'] ] ) ? avia_sc_columns::$size_array[ $this->config['shortcode'] ] : 1.0;
		}

		
		/**
		 * 
		 * @param array $args
		 * @return string
		 */
		protected function get_bg_string( array $args )
		{
			$style = '';

			if( ! empty( $args['attachment'] ) )
			{
				$image = false;
				$src = wp_get_attachment_image_src( $args['attachment'], $args['attachment_size'] );
				if( ! empty( $src[0] ) ) 
				{
					$image = $src[0];
				}

				if( $image )
				{
//					$bg = ! empty( $args['background_color'] ) ? $args['background_color'] : 'transparent'; 
					$bg = 'transparent';
					$pos = ! empty( $args['background_position'] )  ? $args['background_position'] : 'center center';
					$repeat = ! empty( $args['background_repeat'] ) ? $args['background_repeat'] : 'no-repeat';
					$extra = '';

					if( $repeat == 'stretch' )
					{
						$repeat = 'no-repeat';
						$extra = 'background-size: cover;';
					}

					if( $repeat == 'contain' )
					{
						$repeat = 'no-repeat';
						$extra = 'background-size: contain;';
					}

					$style = "background: $bg url($image) $repeat $pos; $extra";
				}
			}

			return $style;
		}

	}
}



if ( ! class_exists( 'avia_sc_columns_one_half' ) )
{
	class avia_sc_columns_one_half extends avia_sc_columns
	{

		function shortcode_insert_button()
		{
			$this->config['name']		= '1/2';
			$this->config['icon']		= AviaBuilder::$path['imagesURL'] . 'sc-half.png';
			$this->config['tab']		= __( 'Layout Elements', 'avia_framework' );
			$this->config['order']		= 90;
			$this->config['target']		= 'avia-section-drop';
			$this->config['shortcode'] 	= 'av_one_half';
			$this->config['html_renderer'] 	= false;
			$this->config['tooltip'] 	= __( 'Creates a single column with 50&percnt; width', 'avia_framework' );
			$this->config['drag-level'] = 2;
			$this->config['drop-level'] = 2;
			$this->config['tinyMCE'] 	= array( 
												'name' => '1/2 + 1/2', 
												'instantInsert' => '[av_one_half first]Add Content here[/av_one_half]\n\n\n[av_one_half]Add Content here[/av_one_half]' 
											);
			$this->config['id_name']	= 'id';
			$this->config['id_show']	= 'yes';
			$this->config['aria_label']	= 'yes';
			
			$this->config['base_element']	= 'yes';
			$this->config['name_template']	= __( 'Column Template', 'avia_framework' ) . ' ' . $this->config['name'];
		}
	}
}


if ( ! class_exists( 'avia_sc_columns_one_third' ) )
{
	class avia_sc_columns_one_third extends avia_sc_columns
	{

		function shortcode_insert_button()
		{
			$this->config['name']		= '1/3';
			$this->config['icon']		= AviaBuilder::$path['imagesURL'] . 'sc-third.png';
			$this->config['tab']		= __( 'Layout Elements', 'avia_framework' );
			$this->config['order']		= 80;
			$this->config['target']		= 'avia-section-drop';
			$this->config['shortcode'] 	= 'av_one_third';
			$this->config['html_renderer'] 	= false;
			$this->config['tooltip'] 	= __( 'Creates a single column with 33&percnt; width', 'avia_framework' );
			$this->config['drag-level'] = 2;
			$this->config['drop-level'] = 2;
			$this->config['tinyMCE'] 	= array(
												'name'			=> '1/3 + 1/3 + 1/3',
												'instantInsert'	=> '[av_one_third first]Add Content here[/av_one_third]\n\n\n[av_one_third]Add Content here[/av_one_third]\n\n\n[av_one_third]Add Content here[/av_one_third]'
											);
			$this->config['id_name']	= 'id';
			$this->config['id_show']	= 'yes';
			$this->config['aria_label']	= 'yes';
			
			$this->config['base_element']	= 'yes';
			$this->config['name_template']	= __( 'Column Template', 'avia_framework' ) . ' ' . $this->config['name'];
		}
	}
}

if ( ! class_exists( 'avia_sc_columns_two_third' ) )
{
	class avia_sc_columns_two_third extends avia_sc_columns
	{

		function shortcode_insert_button()
		{
			$this->config['name']		= '2/3';
			$this->config['icon']		= AviaBuilder::$path['imagesURL'] . 'sc-two_third.png';
			$this->config['tab']		= __( 'Layout Elements', 'avia_framework' );
			$this->config['order']		= 70;
			$this->config['target']		= 'avia-section-drop';
			$this->config['shortcode'] 	= 'av_two_third';
			$this->config['html_renderer'] 	= false;
			$this->config['tooltip'] 	= __( 'Creates a single column with 67&percnt; width', 'avia_framework' );
			$this->config['drag-level'] = 2;
			$this->config['drop-level'] = 2;
			$this->config['tinyMCE'] 	= array(
												'name'			=> '2/3 + 1/3',
												'instantInsert'	=> '[av_two_third first]Add 2/3 Content here[/av_two_third]\n\n\n[av_one_third]Add 1/3 Content here[/av_one_third]'
											);
			$this->config['id_name']	= 'id';
			$this->config['id_show']	= 'yes';
			$this->config['aria_label']	= 'yes';
			
			$this->config['base_element']	= 'yes';
			$this->config['name_template']	= __( 'Column Template', 'avia_framework' ) . ' ' . $this->config['name'];
		}
	}
}

if ( ! class_exists( 'avia_sc_columns_one_fourth' ) )
{
	class avia_sc_columns_one_fourth extends avia_sc_columns
	{

		function shortcode_insert_button()
		{
			$this->config['name']		= '1/4';
			$this->config['icon']		= AviaBuilder::$path['imagesURL'] . 'sc-fourth.png';
			$this->config['tab']		= __( 'Layout Elements', 'avia_framework' );
			$this->config['order']		= 60;
			$this->config['target']		= 'avia-section-drop';
			$this->config['shortcode'] 	= 'av_one_fourth';
			$this->config['tooltip'] 	= __( 'Creates a single column with 25&percnt; width', 'avia_framework' );
			$this->config['html_renderer'] 	= false;
			$this->config['drag-level'] = 2;
			$this->config['drop-level'] = 2;
			$this->config['tinyMCE'] 	= array(
												'name'			=> '1/4 + 1/4 + 1/4 + 1/4',
												'instantInsert'	=> '[av_one_fourth first]Add Content here[/av_one_fourth]\n\n\n[av_one_fourth]Add Content here[/av_one_fourth]\n\n\n[av_one_fourth]Add Content here[/av_one_fourth]\n\n\n[av_one_fourth]Add Content here[/av_one_fourth]'
											);
			$this->config['id_name']	= 'id';
			$this->config['id_show']	= 'yes';
			$this->config['aria_label']	= 'yes';
			
			$this->config['base_element']	= 'yes';
			$this->config['name_template']	= __( 'Column Template', 'avia_framework' ) . ' ' . $this->config['name'];
		}
	}
}

if ( ! class_exists( 'avia_sc_columns_three_fourth' ) )
{
	class avia_sc_columns_three_fourth extends avia_sc_columns
	{

		function shortcode_insert_button()
		{
			$this->config['name']		= '3/4';
			$this->config['icon']		= AviaBuilder::$path['imagesURL'] . 'sc-three_fourth.png';
			$this->config['tab']		= __( 'Layout Elements', 'avia_framework' );
			$this->config['order']		= 50;
			$this->config['target']		= 'avia-section-drop';
			$this->config['shortcode'] 	= 'av_three_fourth';
			$this->config['tooltip'] 	= __( 'Creates a single column with 75&percnt; width', 'avia_framework' );
			$this->config['html_renderer'] 	= false;
			$this->config['drag-level'] = 2;
			$this->config['drop-level'] = 2;
			$this->config['tinyMCE'] 	= array(
												'name'			=> '3/4 + 1/4',
												'instantInsert'	=> '[av_three_fourth first]Add 3/4 Content here[/av_three_fourth]\n\n\n[av_one_fourth]Add 1/4 Content here[/av_one_fourth]'
											);
			$this->config['id_name']	= 'id';
			$this->config['id_show']	= 'yes';
			$this->config['aria_label']	= 'yes';
			
			$this->config['base_element']	= 'yes';
			$this->config['name_template']	= __( 'Column Template', 'avia_framework' ) . ' ' . $this->config['name'];
		}
	}
}

if ( ! class_exists( 'avia_sc_columns_one_fifth' ) )
{
	class avia_sc_columns_one_fifth extends avia_sc_columns
	{

		function shortcode_insert_button()
		{
			$this->config['name']		= '1/5';
			$this->config['icon']		= AviaBuilder::$path['imagesURL'] . 'sc-fifth.png';
			$this->config['tab']		= __( 'Layout Elements', 'avia_framework' );
			$this->config['order']		= 40;
			$this->config['target']		= 'avia-section-drop';
			$this->config['shortcode'] 	= 'av_one_fifth';
			$this->config['html_renderer'] 	= false;
			$this->config['tooltip'] 	= __( 'Creates a single column with 20&percnt; width', 'avia_framework' );
			$this->config['drag-level'] = 2;
			$this->config['drop-level'] = 2;
			$this->config['tinyMCE'] 	= array(
												'name'			=> '1/5 + 1/5 + 1/5 + 1/5 + 1/5',
												'instantInsert'	=> '[av_one_fifth first]1/5[/av_one_fifth]\n\n\n[av_one_fifth]2/5[/av_one_fifth]\n\n\n[av_one_fifth]3/5[/av_one_fifth]\n\n\n[av_one_fifth]4/5[/av_one_fifth]\n\n\n[av_one_fifth]5/5[/av_one_fifth]'
											);
			$this->config['id_name']	= 'id';
			$this->config['id_show']	= 'yes';
			$this->config['aria_label']	= 'yes';
			
			$this->config['base_element']	= 'yes';
			$this->config['name_template']	= __( 'Column Template', 'avia_framework' ) . ' ' . $this->config['name'];
		}
	}
}

if ( ! class_exists( 'avia_sc_columns_two_fifth' ) )
{
	class avia_sc_columns_two_fifth extends avia_sc_columns
	{

		function shortcode_insert_button()
		{
			$this->config['name']		= '2/5';
			$this->config['icon']		= AviaBuilder::$path['imagesURL'] . 'sc-two_fifth.png';
			$this->config['tab']		= __( 'Layout Elements', 'avia_framework' );
			$this->config['order']		= 39;
			$this->config['target']		= 'avia-section-drop';
			$this->config['shortcode'] 	= 'av_two_fifth';
			$this->config['html_renderer'] 	= false;
			$this->config['tooltip'] 	= __( 'Creates a single column with 40&percnt; width', 'avia_framework' );
			$this->config['drag-level'] = 2;
			$this->config['drop-level'] = 2;
			$this->config['tinyMCE'] 	= array(
												'name'			=> '2/5',
												'instantInsert'	=> '[av_two_fifth first]2/5[/av_two_fifth]'
											);
			$this->config['id_name']	= 'id';
			$this->config['id_show']	= 'yes';
			$this->config['aria_label']	= 'yes';
			
			$this->config['base_element']	= 'yes';
			$this->config['name_template']	= __( 'Column Template', 'avia_framework' ) . ' ' . $this->config['name'];
		}
	}
}

if ( ! class_exists( 'avia_sc_columns_three_fifth' ) )
{
	class avia_sc_columns_three_fifth extends avia_sc_columns
	{

		function shortcode_insert_button()
		{
			$this->config['name']		= '3/5';
			$this->config['icon']		= AviaBuilder::$path['imagesURL'] . 'sc-three_fifth.png';
			$this->config['tab']		= __( 'Layout Elements', 'avia_framework' );
			$this->config['order']		= 38;
			$this->config['target']		= 'avia-section-drop';
			$this->config['shortcode'] 	= 'av_three_fifth';
			$this->config['html_renderer'] 	= false;
			$this->config['tooltip'] 	= __( 'Creates a single column with 60&percnt; width', 'avia_framework' );
			$this->config['drag-level'] = 2;
			$this->config['drop-level'] = 2;
			$this->config['tinyMCE'] 	= array(
												'name'			=> '3/5',
												'instantInsert'	=> '[av_three_fifth first]3/5[/av_three_fifth]'
											);
			$this->config['id_name']	= 'id';
			$this->config['id_show']	= 'yes';
			$this->config['aria_label']	= 'yes';
			
			$this->config['base_element']	= 'yes';
			$this->config['name_template']	= __( 'Column Template', 'avia_framework' ) . ' ' . $this->config['name'];
		}
	}
}

if ( ! class_exists( 'avia_sc_columns_four_fifth' ) )
{
	class avia_sc_columns_four_fifth extends avia_sc_columns
	{

		function shortcode_insert_button()
		{
			$this->config['name']		= '4/5';
			$this->config['icon']		= AviaBuilder::$path['imagesURL'] . 'sc-four_fifth.png';
			$this->config['tab']		= __( 'Layout Elements', 'avia_framework' );
			$this->config['order']		= 37;
			$this->config['target']		= 'avia-section-drop';
			$this->config['shortcode'] 	= 'av_four_fifth';
			$this->config['html_renderer'] 	= false;
			$this->config['tooltip'] 	= __( 'Creates a single column with 80&percnt; width', 'avia_framework' );
			$this->config['drag-level'] = 2;
			$this->config['drop-level'] = 2;
			$this->config['tinyMCE'] 	= array(
												'name'			=> '4/5',
												'instantInsert'	=> '[av_four_fifth first]4/5[/av_four_fifth]'
											);
			$this->config['id_name']	= 'id';
			$this->config['id_show']	= 'yes';
			$this->config['aria_label']	= 'yes';
			
			$this->config['base_element']	= 'yes';
			$this->config['name_template']	= __( 'Column Template', 'avia_framework' ) . ' ' . $this->config['name'];
		}
	}
}


