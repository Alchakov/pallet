<?php
/**
 * Theme Customizer
 */
function plt_option($name, $default = false) {
	$options = ( get_option( 'plt_option' ) ) ? get_option( 'plt_option' ) : null;
	if ( isset( $options[ $name ] ) ) {
		return apply_filters( 'plt_option_$name', $options[ $name ] );
	}
	return apply_filters( 'plt_option_$name', $default );
}

function plt_customize_register( $wp_customize ) {
	class PLT_Customize_Textarea_Control extends WP_Customize_Control {
	    public $type = 'textarea';
	    public function render_content() {
	        ?>
	        <label>
	        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	        <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
	        </label>
	        <?php
	    }
	}

	class PLT_Customize_Infotext_Control extends WP_Customize_Control {
	    public $type = 'infotext';
	    public function render_content() {
	        ?>
	        <p class="description"><?php echo( $this->label ); ?></p>
	        <?php
	    }
	}
	
	$wp_customize->get_setting( 'blogname' )->transport = 'refresh';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'refresh';
	
	
	/********************  Контактная ифнормация  ******************************************/
	$wp_customize->add_section( 'plt_contact_section', array(
		'title'		=> __('Контакты', 'plt'),
		'priority'	=> 50
	));  	
		
	$wp_customize->add_setting( 'plt_option[phone]', array(
		'default'			=> '',
		'capability'		=> 'edit_theme_options',
		'transport'			=> 'refresh',
		//'sanitize_callback'	=> 'sanitize_text_field',
		'type' => 'option'
	));
	$wp_customize->add_control( new PLT_Customize_Textarea_Control($wp_customize, 'phone', array(
		'label' => 'Телефон',
		'section' => 'plt_contact_section',
		'settings' => 'plt_option[phone]',
		'type'		=> 'text',
	)));
	
	$wp_customize->add_setting( 'plt_option[email]', array(
		'default'			=> '',
		'capability'		=> 'edit_theme_options',
		'transport'			=> 'refresh',
		'sanitize_callback'	=> 'sanitize_text_field',
		'type' => 'option'
	));
	$wp_customize->add_control( new WP_Customize_Control($wp_customize, 'email', array(
		'label' => 'E-mail',
		'section' => 'plt_contact_section',
		'settings' => 'plt_option[email]',
		'type'		=> 'text',
	)));	
	
	$wp_customize->add_setting( 'plt_option[address]', array(
		'default'			=> '',
		'capability'		=> 'edit_theme_options',
		'transport'			=> 'refresh',
		//'sanitize_callback'	=> 'sanitize_text_field',
		'type' => 'option'
	));
	$wp_customize->add_control( new WP_Customize_Control($wp_customize, 'address', array(
		'label' => 'Адрес',
		'section' => 'plt_contact_section',
		'settings' => 'plt_option[address]',
		'type'		=> 'text',
	)));	
	
	$wp_customize->add_setting( 'plt_option[timetable]', array(
		'default'			=> '',
		'capability'		=> 'edit_theme_options',
		'transport'			=> 'refresh',
		'type' => 'option'
	));
	$wp_customize->add_control( new PLT_Customize_Textarea_Control($wp_customize, 'timetable', array(
		'label' => 'График работы',
		'section' => 'plt_contact_section',
		'settings' => 'plt_option[timetable]',
		'type'		=> 'text',
	)));
	
	$wp_customize->add_setting( 'plt_option[company_info]', array(
		'default'			=> '',
		'capability'		=> 'edit_theme_options',
		'transport'			=> 'refresh',
		'type' => 'option'
	));
	$wp_customize->add_control( new PLT_Customize_Textarea_Control($wp_customize, 'company_info', array(
		'label' => 'Реквизиты',
		'section' => 'plt_contact_section',
		'settings' => 'plt_option[company_info]',
		'type'		=> 'text',
	)));
	
	$wp_customize->selective_refresh->add_partial( 'plt_option[phone]', array(
		'selector' => '.box-contact--phone',		
	) );
	$wp_customize->selective_refresh->add_partial( 'plt_option[email]', array(
		'selector' => '.box-contact--email'		
	) );
	$wp_customize->selective_refresh->add_partial( 'plt_option[address]', array(
		'selector' => '.box-contact--address'		
	) );
	$wp_customize->selective_refresh->add_partial( 'plt_option[timetable]', array(
		'selector' => '.box-contact--timetable'		
	) );
	$wp_customize->selective_refresh->add_partial( 'plt_option[company_info]', array(
		'selector' => '.box-company-info'		
	) );
	$wp_customize->selective_refresh->add_partial( 'social_vk', array(
		'selector' => '.box-social'		
	) );
	
	
	
	/********************  Соц. сети  ******************************************/	
	$wp_customize->add_section( 'plt_social_section', array(
		'title'		=> 'Соц. сети',
		'priority'	=> 60		
	));  
	
	$socials = PLT_Other::social_list();	
	foreach ($socials as $key => $value) {
		$wp_customize->add_setting( 'social_'.$key, array(
			'default'			=> '',
			'capability'		=> 'edit_theme_options',
			'transport' 		=> 'refresh',
			'sanitize_callback'	=> 'esc_url_raw'
		));
		$wp_customize->add_control( 
			new WP_Customize_Control( 
				$wp_customize, 'social_'.$key, array(
					'label'		=> $value.' URL',
					'section'	=> 'plt_social_section',
					'settings'	=> 'social_'.$key,
					'type'		=> 'text',
				) 
			) 
		);
	}  		
	
}
add_action( 'customize_register', 'plt_customize_register' );