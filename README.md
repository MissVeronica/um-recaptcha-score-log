# UM Google reCAPTCHA V3 Score Log
Extension to Ultimate Member for logging of the UM Google reCAPTCHA V3 Scores to a file with .CSV format.

## UM Settings - Extensions - Google reCAPTCHA
1. reCAPTCHA Score Log - Enable/Disable - Click to enable score logging.
2. reCAPTCHA Score Log - Remote Host - Click for including remote Host domain name in the log file.
3. reCAPTCHA Score Log - Remote IP address - Click for including remote IP address in the log file.
4. reCAPTCHA Score Log - Username - Click for including Username in the log file.
5. reCAPTCHA Score Log - Custom final score - Enter form_id:score or mode:score one pair per line for overriding current UM score setting.

## Options
1. Additional info Country, Browser and Platform will be added to the log file if the "Geo Controller" plugin is activated
2. https://wordpress.org/plugins/cf-geoplugin/

## Log file
1. .../wp-content/g-recaptcha-v3-score-log.csv
2. CSV file headers: 'Time'
'Google score',
'UM score',
'UM page score',
'final score',
'UM page',
'UM form ID',
'UM mode',
'User domain',
'User IP',
'Country',
'Browser',
'Platform',
'username',
'error message'

## Updates
None

## Installation
1. Install by downloading the plugin ZIP file and install as a new Plugin, which you upload in WordPress -> Plugins -> Add New -> Upload Plugin.
2. Activate the Plugin: Ultimate Member - Google reCAPTCHA V3 Score Log
