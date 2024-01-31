# Version main-dev-4
- [FEATURE] If Login Page is not set, then we send different text after DOI
- [FEATURE] Add MathCaptcha to RegisterEmail

# Version main-dev-3
- [BUGFIX] RegisterValidationService.php: Check for usergroup, before using intExplode

# Version main-dev-2
- [BUGFIX] locallang.xlf: Entered correct product-name
- [CHANGE] Rename Register Plugin to Subscribe
- [CHANGE] Rename formAction to formEmailAction, Rename registerAction to registerEmailAction
- [CHANGE] Rename UserValidator to RegisterEMailValidator, Rename UserValidationService to RegisterEmailValidationService
- [NEW] New Fluid Form (only Template) for Full Registration
- [NEW] Set required fields via TypoScript for registerAction
- [NEW] Extend User Model with new fields

# Version main-dev-1
- [NEW] Kickstart Extension
- [NEW] Registration Form with Validator
- [WIP] DOI Process