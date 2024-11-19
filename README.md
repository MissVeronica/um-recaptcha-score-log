# UM Google reCAPTCHA V3 Score Log
Extension to Ultimate Member for logging of the UM Google reCAPTCHA V3 Scores to a file with .CSV format.

## UM Settings - Extensions - Google reCAPTCHA
1. Enable/Disable - Tick to enable score logging.
2. User Remote Host - Tick for including User remote Host domain name in the log file.
3. User Remote IP address - Tick for including User remote IP address in the log file.
4. Username - Tick for including Username in the log file.
5. Custom final score - Enter form_id:score or mode:score one pair per line for overriding current UM score setting.

## Options
1. Additional info Country, Browser and Platform will be added to the log file if the "Geo Controller" plugin is activated
2. The "Geo Controller" plugin's shortcodes will be used by the plugin to add these fields to the log file.
3. https://wordpress.org/plugins/cf-geoplugin/

## Log file
1. .../wp-content/uploads/ultimatemember/um-recaptcha-score/g-recaptcha-v3-score-log.csv
2. CSV file headers:

<code>Time  Google score  UM score  UM page score  final score  UM page  UM form ID  UM mode  User domain  User IP  Country  Browser  Platform  username  error message</code>

## Updates
None

## Installation & Updates
1. Install by downloading the plugin ZIP file via the green button and install as a new Plugin, which you upload in WordPress -> Plugins -> Add New -> Upload Plugin.
2. Activate the Plugin: Ultimate Member - Google reCAPTCHA V3 Score Log
