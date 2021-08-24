<?php
/**
 * Progress Bars
 * 
 * Creates some progress bars
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( ! class_exists( 'avia_sc_progressbar' ) )
{
	class avia_sc_progressbar extends aviaShortcodeTemplate
	{
		/**
		 * Create the config array for the shortcode button
		 */
		function shortcode_insert_button()
		{
			$this->config['version']		= '1.0';
			$this->config['self_closing']	= 'no';
			$this->config['base_element']	= 'yes';

			$this->config['name']			= __( 'Progress Bars', 'avia_framework' );
			$this->config['tab']			= __( 'Content Elements', 'avia_framework' );
			$this->config['icon']			= AviaBuilder::$path['imagesURL'] . 'sc-progressbar.png';
			$this->config['order']			= 30;
			$this->config['target']			= 'avia-target-insert';
			$this->config['shortcode']	 	= 'av_progress';
			$this->config['shortcode_nested'] = array( 'av_progress_bar' );
			$this->config['tooltip']	 	= __( 'Create some progress bars', 'avia_framework' );
			$this->config['preview']	 	= true;
			$this->config['disabling_allowed'] = true;
			$this->config['id_name']		= 'id';
			$this->config['id_show']		= 'yes';
			$this->config['alb_desc_id']	= 'alb_description';
			$this->config['name_item']		= __( 'Progress Bar Item', 'avia_framework' );
			$this->config['tooltip_item']	= __( 'A Progress Bars Element Item', 'avia_framework' );
		}

		function extra_assets()
		{
			//load css
			wp_enqueue_style( 'avia-module-progress-bar', AviaBuilder::$path['pluginUrlRoot'] . 'avia-shortcodes/progressbar/progressbar.css', array( 'avia-layout' ), false );

			//load js
			wp_enqueue_script( 'avia-module-numbers', AviaBuilder::$path['pluginUrlRoot'] . 'avia-shortcodes/numbers/numbers.js', array( 'avia-shortcodes' ), false, true );
			wp_enqueue_script( 'avia-module-progress-bar', AviaBuilder::$path['pluginUrlRoot'] . 'avia-shortcodes/progressbar/progressbar.js', array( 'avia-shortcodes' ), false, true );
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
				
				array(
						'type' 	=> 'tab_container', 
						'nodescription' => true
					),
						
				array(
						'type' 	=> 'tab',
						'name'  => __( 'Content', 'avia_framework' ),
						'nodescription' => true
					),
				
					array(
							'type'			=> 'template',
							'template_id'	=> $this->popup_key( 'content_bars' )
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
													$this->popup_key( 'styling_general' ),
													$this->popup_key( 'styling_colors' )
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
								'template_id'	=> $this->popup_key( 'advanced_animation' )
							),
				
						array(	
								'type'			=> 'template',
								'template_id'	=> 'screen_options_toggle',
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
						'args'			=> array( 'sc' => $this )
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
			
			$this->register_modal_group_templates();
			
			/**
			 * Content Tab
			 * ===========
			 */
			
			$c = array(
						array(
							'name'			=> __( 'Add/Edit Progress Bars', 'avia_framework' ),
							'desc'			=> __( 'Here you can add, remove and edit the various progress bars.', 'avia_framework' ),
							'type'			=> 'modal_group',
							'id'			=> 'content',
							'modal_title'	=> __( 'Edit Progress Bars', 'avia_framework' ),
							'editable_item'	=> true,
							'lockable'		=> true,
							'tmpl_set_default'	=> false,
							'std'			=> array(
													array(
														'title'			=> __( 'Skill or Task', 'avia_framework' ), 
														'icon'			=> '43', 
														'progress'		=> '100', 
														'icon_select'	=> 'no'
													),
												),
							'subelements'	=> $this->create_modal()
						),
				
				);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'content_bars' ), $c );
			
			/**
			 * Styling Tab
			 * ===========
			 */
			
			$c = array(
						array(
							'name' 	=> __( 'Progress Bar Style', 'avia_framework' ),
							'desc' 	=> __( 'Choose the styling of the progress bar here', 'avia_framework' ),
							'id' 	=> 'bar_styling_secondary',
							'type' 	=> 'select',
							'std' 	=> '',
							'lockable'	=> true,
							'subtype'	=> array(
												__( 'Rounded Big Bars', 'avia_framework' )	=> '',
												__( 'Minimal Bars', 'avia_framework' )		=> 'av-small-bar'
											)
						),

						array(
							'name' 	=> __( 'Show Progress Bar percentage?', 'avia_framework' ),
							'desc' 	=> __( 'Choose if you want to show the numeric percentage of the progress bar', 'avia_framework' ),
							'id' 	=> 'show_percentage',
							'type' 	=> 'select',
							'std' 	=> '',
							'lockable'	=> true,
							'required'	=> array( 'bar_styling_secondary', 'equals', 'av-small-bar' ),
							'subtype'	=> array(
												__( 'Hide', 'avia_framework' )	=> '',
												__( 'Show', 'avia_framework' )	=> 'av-show-bar-percentage'
											)
						),		

						array(
							'name' 	=> __( 'Progress Bar Height?', 'avia_framework' ),
							'desc' 	=> __( 'Set the height of the progress bar', 'avia_framework' ),
							'id' 	=> 'bar_height',
							'type' 	=> 'select',
							'std' 	=> '10',
							'lockable'	=> true,
							'required'	=> array( 'bar_styling_secondary', 'equals',  'av-small-bar' ),
							'subtype'	=> AviaHtmlHelper::number_array( 1, 50, 1, array(), 'px' )
						),

				);
			
			$template = array(
							array(	
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'General Styling', 'avia_framework' ),
								'content'		=> $c 
							),
					);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_general' ), $template );
			
			
			$c = array(
						array(
							'name' 	=> __( 'Progress Bar Coloring', 'avia_framework' ),
							'desc' 	=> __( 'Choose the coloring of the progress bar here', 'avia_framework' ),
							'id' 	=> 'bar_styling',
							'type' 	=> 'select',
							'std' 	=> 'av-striped-bar',
							'lockable'	=> true,
							'subtype'	=> array(
												__( 'Striped', 'avia_framework' )		=> 'av-striped-bar',
												__( 'Single Color', 'avia_framework' )	=> 'av-flat-bar' 
											)
								)
				);
			
			$template = array(
							array(	
								'type'			=> 'template',
								'template_id'	=> 'toggle',
								'title'			=> __( 'Colors', 'avia_framework' ),
								'content'		=> $c 
							),
					);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'styling_colors' ), $template );
			
			/**
			 * Advanced Tab
			 * ===========
			 */
			
			$c = array(
						array(
							'name' 	=> __( 'Progress Bar Animation enabled?', 'avia_framework' ),
							'desc' 	=> __( 'Choose if you want to enable the continuous animation of the progress bar', 'avia_framework' ),
							'id' 	=> 'bar_animation',
							'type' 	=> 'select',
							'std' 	=> 'av-animated-bar',
							'lockable'	=> true,
							'required'	=> array( 'bar_styling', 'not', 'av-flat-bar' ),
							'subtype'	=> array(
												__( 'Enabled', 'avia_framework' )	=> 'av-animated-bar',
												__( 'Disabled', 'avia_framework' )	=> 'av-fixed-bar'
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
			
		}
		
		/**
		 * Creates the modal popup for a single entry
		 * 
		 * @since 4.6.4
		 * @return array
		 */
		protected function create_modal()
		{
			$elements = array(
				
				array(
						'type' 	=> 'tab_container', 
						'nodescription' => true
					),
						
				array(
						'type' 	=> 'tab',
						'name'  => __( 'Content', 'avia_framework' ),
						'nodescription' => true
					),
				
					array(
							'type'			=> 'template',
							'template_id'	=> $this->popup_key( 'modal_content_bar' )
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
							'template_id'	=> $this->popup_key( 'modal_styling_colors' )
						),
				
				array(
						'type' 	=> 'tab_close',
						'nodescription' => true
					),
				
				array(	
						'type'			=> 'template',
						'template_id'	=> 'element_template_selection_tab',
						'args'			=> array( 
												'sc'			=> $this,
												'modal_group'	=> true
											)
					),
				
				array(
						'type' 	=> 'tab_container_close',
						'nodescription' => true
					)
				
				);
			
			return $elements;
		}
		
		/**
		 * Register all templates for the modal group popup
		 * 
		 * @since 4.6.4
		 */
		protected function register_modal_group_templates()
		{
			/**
			 * Content Tab
			 * ===========
			 */
			$c = array(
						array(
							'name' 	=> __( 'Progress Bars Title', 'avia_framework' ),
							'desc' 	=> __( 'Enter the Progress Bars title here', 'avia_framework' ) ,
							'id' 	=> 'title',
							'type' 	=> 'input',
							'std' 	=> '',
							'lockable'	=> true
						),

						array(
							'name' 	=> __( 'Progress in &percnt;', 'avia_framework' ),
							'desc' 	=> __( 'Select a number between 0 and 100', 'avia_framework' ),
							'id' 	=> 'progress',
							'type' 	=> 'select',
							'std' 	=> '100',
							'lockable'	=> true,
							'subtype'	=> AviaHtmlHelper::number_array( 0, 100, 1, array(), '%' )
						),

						array(
							'name' 	=> __( 'Icon', 'avia_framework' ),
							'desc' 	=> __( 'Should an icon be displayed at the left side of the progress bar', 'avia_framework' ),
							'id' 	=> 'icon_select',
							'type' 	=> 'select',
							'std' 	=> 'no',
							'lockable'	=> true,
							'subtype'	=> array(
												__( 'No Icon', 'avia_framework' )			=> 'no',
												__( 'Yes, display Icon', 'avia_framework' )	=> 'yes'
											)
						),

						array(
							'name' 	=> __( 'List Item Icon','avia_framework' ),
							'desc' 	=> __( 'Select an icon for your list item below','avia_framework' ),
							'id' 	=> 'icon',
							'type' 	=> 'iconfont',
							'required'	=> array( 'icon_select', 'equals', 'yes' ),
							'std' 	=> '',
							'lockable'	=> true,
							'locked'	=> array( 'icon', 'font' )
						),
									
				);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'modal_content_bar' ), $c );
			
			/**
			 * Styling Tab
			 * ===========
			 */
			
			$c = array(
						array(	
							'type'			=> 'template',
							'template_id'	=> 'named_colors',
							'name'			=> __( 'Bar Color', 'avia_framework' ),
							'desc'			=> __( 'Choose a color for your progress bar here', 'avia_framework' ),
							'id'			=> 'color',
							'std'			=> 'theme-color',
							'custom'		=> false,
							'lockable'		=> true,
							'translucent'	=> array(),
							'no_alternate'	=> true
						),

				);
			
			AviaPopupTemplates()->register_dynamic_template( $this->popup_key( 'modal_styling_colors' ), $c );
			
		}

		/**
		 * Editor Sub Element - this function defines the visual appearance of an element that is displayed within a modal window and on click opens its own modal window
		 * Works in the same way as Editor Element
		 * 
		 * @param array $params this array holds the default values for $content and $args.
		 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
		 */
		function editor_sub_element( $params )
		{
			$default = array();
			$locked = array();
			$attr = $params['args'];
			Avia_Element_Templates()->set_locked_attributes( $attr, $this, $this->config['shortcode_nested'][0], $default, $locked );
			
			
			$template = $this->update_template_lockable( 'title', '{{title}}: ', $locked );
			$template_percent = $this->update_template_lockable( 'progress', '{{progress}}%', $locked );

			extract( av_backend_icon( array( 'args' => $attr ) ) ); // creates $font and $display_char if the icon was passed as param 'icon' and the font as 'font' 

			if( empty( $attr['icon_select'] ) ) 
			{
				$params['args']['icon_select'] = 'no';
				$attr['icon_select'] = 'no';
			}

			$params['innerHtml']  = '';
			$params['innerHtml'] .= "<div class='avia_title_container' data-update_element_template='yes'>";
			$params['innerHtml'] .=		'<span ' . $this->class_by_arguments_lockable( 'icon_select', $attr, $locked ) . '>';
			$params['innerHtml'] .=			'<span ' . $this->class_by_arguments_lockable( 'font', $font, $locked ) . '>';
			$params['innerHtml'] .=				'<span ' . $this->update_option_lockable( array( 'icon', 'icon_fakeArg' ), $locked ) . " class='avia_tab_icon'>{$display_char}</span>";
			$params['innerHtml'] .=			'</span>';
			$params['innerHtml'] .=			"<span {$template} >{$attr['title']}: </span>";
			$params['innerHtml'] .=			"<span {$template_percent} >{$attr['progress']}%</span>";
			$params['innerHtml'] .=		'</span>';
			$params['innerHtml'] .= '</div>';

			return $params;
		}


		/**
		 * Returns false by default.
		 * Override in a child class if you need to change this behaviour.
		 * 
		 * @since 4.2.1
		 * @param string $shortcode
		 * @return boolean
		 */
		public function is_nested_self_closing( $shortcode )
		{
			if( in_array( $shortcode, $this->config['shortcode_nested'] ) )
			{
				return true;
			}

			return false;
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
			$default = array(
						'position'				=> 'left', 
						'bar_styling'			=> 'av-striped-bar', 
						'bar_styling_secondary'	=> '',
						'show_percentage'		=> false,
						'bar_height'			=> false,
						'bar_animation'			=> 'av-animated-bar' 
					);
			
			$locked = array();
			Avia_Element_Templates()->set_locked_attributes( $atts, $this, $shortcodename, $default, $locked, $content );
			Avia_Element_Templates()->add_template_class( $meta, $atts, $default );
			
			extract( AviaHelper::av_mobile_sizes( $atts ) ); //return $av_font_classes, $av_title_font_classes and $av_display_classes 

			extract( shortcode_atts( $default, $atts, $this->config['shortcode'] ) );

			$bars = ShortcodeHelper::shortcode2array( $content );
			
			$modal = array(
						'color'			=> 'theme-color', 
						'progress'		=> '100', 
						'title'			=> '', 
						'icon'			=> '', 
						'font'			=> '', 
						'icon_select'	=> 'no'
					);
			
			foreach( $bars as &$bar )
			{
				Avia_Element_Templates()->set_locked_attributes( $bar['attr'], $this, $this->config['shortcode_nested'][0], $modal, $locked, $bar['content'] );
				$bar['attr'] = array_merge( $modal, $bar['attr'] );
			}
			
			unset( $bar );
			

			$extraClass = $bar_styling . ' ' . $bar_animation . ' ' . $bar_styling_secondary;
			$output = '';
			$bar_style = '';

			if( $bar_height && $bar_styling_secondary )
			{
				$bar_style = "style='height:{$bar_height}px;'";
			}

			if( ! empty( $bars ) )
			{
				$output .= "<div {$meta['custom_el_id']} class='avia-progress-bar-container {$av_display_classes} avia_animate_when_almost_visible {$meta['el_class']} {$extraClass}'>";

				foreach( $bars as $bar )
				{
					$display_char = av_icon( $bar['attr']['icon'], $bar['attr']['font'] );

					$output .= "<div class='avia-progress-bar {$bar['attr']['color']}-bar icon-bar-{$bar['attr']['icon_select']}'>";

					if( $bar['attr']['icon_select'] == 'yes' || $bar['attr']['title'] )
					{
						$output .= "<div class='progressbar-title-wrap'>";
						$output .=		"<div class='progressbar-icon'><span class='progressbar-char' {$display_char}></span></div>";
						$output .=		"<div class='progressbar-title'>{$bar['attr']['title']}</div>";
						$output .= '</div>';
					}

					if( $bar_styling_secondary != '' && $show_percentage )
					{ 
						$output .= "<div class='progressbar-percent' data-timer='2200'><span class='av-bar-counter __av-single-number' data-number='{$bar['attr']['progress']}'>0</span>%</div>";
					}


					$output .= 		"<div class='progress' {$bar_style}><div class='bar-outer'><div class='bar' style='width: {$bar['attr']['progress']}%' data-progress='{$bar['attr']['progress']}'></div></div></div>";
					$output .= '</div>';
				}

				$output .= '</div>';
			}

			return $output;
		}

	}
}
