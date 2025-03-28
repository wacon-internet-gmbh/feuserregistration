# feuserregistration Documentation
## Installation
## Legacy TYPO3
### 1a. TYPO3 Legacy
  1. Go to https://extensions.typo3.org/extension/feuserregistration
  2. Download the latest version for your TYPO3 installation
  3. Rename downloaded zip package in: feuserregistration.zip
  4. Login into your TYPO3 Backend
  5. Go to the extension manager
  6. Upload ZIP Package
  7. Activate extension

### 1b. TYPO3 Composer
1. ``composer req wacon/feuserregistration``

### 2. DB Analyzer
Execute the DB Analyzer inisde the Admin Tools -> Maintenance module

### 3. Configure extension
1. Create a folder as feuser storage (if you not have already one), for example: Frontend user
2. Create a standard page for registration
   1. Add one of these frontend plugins for registration
      1. Feuserregistration: Display a email registration form [feuserregistration_subscribe]
      2. Feuserregistration: Display a registration form [feuserregistration_register]
3. Create a standard page for verification
   1. Add the frontend plugin: Feuserregistration: Verify a registration. [feuserregistration_verify]
4. Add the TypoScript static file from Feuserregistration to your TypoSript records (for instance on your root page)
   1. Configure feuserregistration via the constant editor

## More information
https://www.wacon.de/typo3-service/feuserregistration.html (german)
