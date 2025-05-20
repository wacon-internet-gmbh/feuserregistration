# Test Automation
## Testing for Developers
### GitHub actions
#### Fix permission denied message on runScript.sh
``git update-index --chmod=+x  Build/Scripts/runTests.sh``

### Unit Tests in ddev
`ddev exec php vendor/bin/phpunit -c packages/feuserregistration/Build/phpunit/UnitTests.xml`
