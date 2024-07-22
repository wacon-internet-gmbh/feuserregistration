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