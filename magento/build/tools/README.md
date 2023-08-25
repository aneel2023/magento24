#Magento2 Build Tools

## PHPMD

Proejct ruleset configuration `{BASE_DIR}/phpmd-ruleset.xml`

### Rules Customization

* `CyclomaticComplexity` - value is changed to `15` in order to have some buffer for old code. By default it's `10`
* `NPathComplexity` - value is changed to `300` in order to have some buffer for old code. By default it's `200`
* `BooleanArgumentFlag` - disabled. Boolean argument is ok.
* `ElseExpression` - disabled. Might be ok for some cases.
* `ExcessiveParameterList` - replaced with custom rule to have different values for `methods` and `constructor`
* `CouplingBetweenObjects` - value is changed to `20` in order to have some buffer for old code. By default it's `13`
* `DepthOfInheritance` - value is changed to `8` as per magento default. By default it's `6`
* `LongVariable` - added `subtract-suffixes` with common patterns suffixes and value is changed to `40` in order to satisfy our naming convention. By default it's `20`
* `LongClassName` - added `subtract-suffixes` with common patterns suffixes and value is changed to `50` in order to satisfy our naming convention. By default it's `20`
* `ShortVariable` - disabled. There are bunch of meaningful short variable names.
* `AllPurposeAction` - Magento rule which restricts controllers which don't implement `Http*ActionInterface`
* `CookieAndSessionMisuse` - Magento rule which restricts session or cookies directly, except few cases
* `LongParameterList` - replacement of `ExcessiveParameterList`, applicable for all functions,methods except `__constructor`. Value is `4`
* `ConstructorExcessiveParameterList` - replacement of `ExcessiveParameterList`, applicable only for `__constructor`. Value is `15`, normally should be less then `10`
