# Version 3.2.1
- [BUGFIX] Create hash also, when login page active

# Version 3.2.0
- [FEATURE] Add simple dashboard widget to list the latest 5 registered users

# Version 3.1.0
- [FEATURE] Add checkbox to delete existing frontend users before import
- [FEATURE] New checkbox is hidable with default on in Extension Configuration

# Version 3.0.1
- [CHANGE] Add request to AfterDoiEvent

# Version 3.0.0
- [TASK] Do not register Import module if not luxletter is installed
- [TASK] Add field pages to registration plugin
- [TASK] Migrate custom property validation in ActionController

# Version upgrade-typo314-1
- [FEATURE] Migrate email registration
- [TASK] Migrate constant editor to site set
- [TASK] Add field pages to plugins
- [TASK] Migrate Repository findBy Magic functions

# Version upgrade-typo314-0
- [TASK] Remove assign to NULL
- [TASK] Remove RenderCompileStatic from ViewHelpers
- [WIP] Migrate constant editor to site set

# Version 2.8.0
- [FEATURE] Add Event after user finished DOI

# Version 2.7.2
- [CHANGE] Import: Check for redundance in imported csv file

# Version 2.7.1
- [CHANGE] Only generate random pw once in import script to make the import much more efficient

# Version 2.7.0
- [FEATURE] Add client and serverside validation

# Version 2.6.2
- [NOTICE] Add description for file upload to explan supported file types
- [BUGFIX] Fix wrong label for target user group field

# Version 2.6.1
- [NOTICE] Change access to user for new backend module

# Version 2.6.0
- [FEATURE] Add backend module to import fe_user for luxletter. CSV and XLSX are supported. The first column of the table must be the email address. No other data is supported at the moment

# Version 2.5.0
- [FEATURE] Rework registerEmail to make it technically possible to also use a name field

# Version 2.4.2
- [BUGFIX] Correct constant editor types for boolean and uid fields
- [BUGFIX] Show only dev log in fluid, if devLog settings is enabled

# Version 2.4.1
- [BUGFIX] mb-3 on wrapper of catpcha

# Version 2.4.0
- [FEATURE] Add option to set read aloud function as icon
- [CHANGE] Optimize html structure for captcha with read aloud function

# Version 2.3.0
- [FEATURE] Add UnitTest for class check in Valditors
- [FEATURE] Add readaloud function via SpeechSynthesisUtterance
- [CHANGE] Change condition to support inheritance in Validator User Model check

# Version 2.2.0
- [FEATURE] Add option in constant editor to switch privacy checkbox label from normal to newsletter mode
- [CHANGE] Shorten privacy checkbox label

# Version 2.1.3
- [FEATURE] Add option to print out all fluid variables for logging on registration

# Version 2.1.2
- [CHANGE] Some minor CSS improvements in error list
- [BUGFIX] Add CSS var --bs-danger --bs-danger-light just in case user dont use bootstrap

# Version 2.1.1
- [NOTICE] Add extension icon

# Version 2.1.0
- [FEATURE] Implement admin info mail for registration and verification

# Version 2.0.0
- [IMPORTANT] Release 2.0.0
- [FEATURE] All TypoScript settings are now editable via constant editor
- [FEATURE] TYPO3 13 Support

# Version dev-2.0.0-2
- [BUGFIX] Exclude DoubleOptinService from Autowiring

# Version dev-2.0.0-1
- [FEATURE] TYPO3 13 Support

# Version dev-2.0.0-0
- [WIP] TYPO3 13 Support
- [FEATURE] All TypoScript settings are now editable via constant editor
- [CHANGE] We look up now all fe_users on registration to avoid conflicts with possible felogin procedures

# Version 1.3.0
- [FEATURE] Add new mode for verfication to verify by sending form instead. Useful for mailserver that opens email links for spamchecks.

# Version 1.2.2
- [BUGFIX] German translation of register.form.text.afterRegistration. deine to Ihre

# Version 1.2.1
- [BUGFIX] German translation of male is Herr

# Version 1.2.0
- [FEATURE] Add privacy and captcha field to extender registration form
- [BUGFIX] FormError Title is now p.h3 to avoid SEO conflicts
- [BUGFIX] Add missing translation for FormError Title

# Version 1.1.0
- [IMPORTANT] Stable release 1.1.0
- [IMPORTANT] Merge dev-1.1.0

# Version dev-1.1.0-1
- [WIP] Test registration by email only with new RegistrationService
- [IMPORTANT] Test registration with new RegistrationService
- [FEATURE] Add new field: salutation in register form

# Version dev-1.1.0-0
- [WIP] Test registration with new RegistrationService
- [IMPORTANT] Create RegistrationService to do all registration with it. RegistrationService can be used in other extenions as well
- [IMPORTANT] Moved Service folder to Domain, because they are all Domain related
- [NOTICE] Create new branch to extend extension with a Service class to register user with DOI process from other extensions

# Version 1.0.0
- [IMPORTANT] First Stable release
- [BUGFIX] de.locallang.xlf source-language attribute duplicate
- [FEATURE] Add Privacy Checkbox to RegisterEmail
- [BUGFIX] Correct language key, if DOI mail for none_credentials mode
- [NOTICE] For the moment the DOI mail is disabled, when no login page is set
- [FEATURE] If Login Page is not set, then we send different text after DOI
- [FEATURE] Add MathCaptcha to RegisterEmail
- [BUGFIX] RegisterValidationService.php: Check for usergroup, before using intExplode
- [BUGFIX] locallang.xlf: Entered correct product-name
- [CHANGE] Rename Register Plugin to Subscribe
- [CHANGE] Rename formAction to formEmailAction, Rename registerAction to registerEmailAction
- [CHANGE] Rename UserValidator to RegisterEMailValidator, Rename UserValidationService to RegisterEmailValidationService
- [NEW] New Fluid Form (only Template) for Full Registration
- [NEW] Set required fields via TypoScript for registerAction
- [NEW] Extend User Model with new fields
- [NEW] Kickstart Extension
- [NEW] Registration Form with Validator
- [WIP] DOI Process
