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

### Time
Local date and time

### Google
Score result returned by Google

### UM setting
UM reCAPTCHA Score setting

### UM form
reCAPTCHA score for this form

### UM limit
Score value used to compare against Google score

### success
Google's conclusion 

### action
Google action type

### WP page  
WP page name

### UM form ID
UM Forms builder form ID

### UM mode 

### User domain
User internet provider by the gethostbyaddr function

### User IP
User IP address

### Country
User IP location according to the shortcode [cfgeo return="country"] by CF_Geoplugin 

### Browser
User browser and version according to the shortcodes [cfgeo return="browser"] [cfgeo return="browser_version"] by CF_Geoplugin

### Platform
User platform according to the shortcode [cfgeo return="platform"] by CF_Geoplugin

### username
UM username entered by the User

### error message

## Updates
None

## Installation & Updates
1. Install by downloading the plugin ZIP file via the green button and install as a new Plugin, which you upload in WordPress -> Plugins -> Add New -> Upload Plugin.
2. Activate the Plugin: Ultimate Member - Google reCAPTCHA V3 Score Log
