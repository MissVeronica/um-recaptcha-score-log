# UM Google reCAPTCHA V3 Score Log
Extension to Ultimate Member for logging of the UM Google reCAPTCHA V3 Scores to a file with CSV format.

## UM Settings - Extensions - Google reCAPTCHA
reCAPTCHA Score Log CSV file settings
1. Enable/Disable - Tick to enable reCAPTCHA score logging to a CSV file.
2. User host - Tick for including User host domain name.
3. User IP address - Tick for including User IP address.
4. Username - Tick for including username.
5. Decimal numbers with comma - Tick for converting dot to comma in decimal numbers if required the spreadsheet calculations.
6. CSV file delimiter - Select the field delimiter to be used. Default is Tab.

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
UM reCAPTCHA score global setting

### UM form
UM reCAPTCHA score setting for this form being used instead of the global setting

### UM limit
Score value used

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
User internet provider by the gethostbyaddr function and IP address

### User IP
User IP address

### Country
User IP location according to the shortcode [cfgeo return="country"] by CF_Geoplugin 

### Browser
User browser and version according to the shortcodes [cfgeo return="browser"] [cfgeo return="browser_version"] by CF_Geoplugin

### Platform
User platform according to the shortcode [cfgeo return="platform"] by CF_Geoplugin

### username
UM username/user_login entered by the User

### error message

## UM reCAPTCHA
Required version 2.3.8 or later

## Updates
None

## Installation & Updates
1. Install by downloading the plugin ZIP file via the green button and install as a new Plugin, which you upload in WordPress -> Plugins -> Add New -> Upload Plugin.
2. Activate the Plugin: Ultimate Member - Google reCAPTCHA V3 Score Log
