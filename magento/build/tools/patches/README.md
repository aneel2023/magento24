# BUILD TOOLS PATCHES

## VSTS_59408_PHPMD_PHP81_Compability.patch

package: `phpmd/phpmd`

affected version: `2.12.0`

### ISSUE

There is an error in pipeline

> Deprecated: str_replace(): Passing null to parameter #2 ($replace) of type array|string is deprecated in /builds/magento/magento/magento2-shop/build/tools/vendor/phpmd/phpmd/src/main/php/PHPMD/Renderer/HTMLRenderer.php on line 490

### Solution

replace `null` with `''` in \PHPMD\Renderer\HTMLRenderer::highlightFile
