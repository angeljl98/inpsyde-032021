## Synopsis

This library will show a de-activation survey dialog when the user clicks "deactivate" on plugins.php on any plugins you've bound the library to. The form data will be posted to solbox.dev

## Installation

- Copy the files into your project (I recommend using the path /lib/solbox/plugin-deactivation-survey)
- Include deactivate-feedback-form.php in your plugin
- Run the code below with your plugins slug

## Usage

	add_filter('solbox_deactivate_feedback_form_plugins', function($plugins) {
	
		$plugins[] = (object)array(
			'slug'		=> 'wp-google-maps',
			'version'	=> '7.0.6'
		);
	
		return $plugins;
	
	});

## Notes

You can include any other arbitrary data you might want to store in the object you pass to the $plugins array in the example above
