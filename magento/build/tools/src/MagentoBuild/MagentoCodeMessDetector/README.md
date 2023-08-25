# MagentoBuild_MagentoCodeMessDetector

Copy of Magneto Code Mess Detector classes with some adjustments

## Presentation layer

Presentation layer includes:

* `Controller` or `Plugin` for Controller
* `Block` or `Plugin` for Block
* `View Model`
* `Ui Document` - extends `\MagentoBuild\Framework\View\Element\UiComponent\DataProvider\Document`
* `Ui Data Provider` - implements `\MagentoBuild\Framework\View\Element\UiComponent\DataProvider\DataProviderInterface`
* `Layout Processor`
* `Action Interface` - pattern introduced for TV project. 

## Rules

### \MagentoBuild\MagentoCodeMessDetector\Rule\Design\AllPurposeAction

Full copy of `\MagentoBuild\CodeMessDetector\Rule\Design\AllPurposeAction`

### \MagentoBuild\MagentoCodeMessDetector\Rule\Design\LongParameterList

Checking for number of parameters with ability of specifying concrete method to check or exclude list
E.g.
```xml
<ruleset>
    <rule name="ConstructorExcessiveParameterList"
          since="0.1"
          message="The {0} {1} has {2} parameters. Consider reducing the number of parameters to less than {3}."
          class="TeamMagentoViewer\MagentoCodeMessDetector\Rule\Design\LongParameterList"
          externalInfoUrl="https://phpmd.org/rules/codesize.html#excessiveparameterlist">
        <description>
            Long parameter lists can indicate that a new object should be created to
            wrap the numerous parameters.  Basically, try to group the parameters together.
        </description>
        <priority>3</priority>
        <properties>
            <property name="minimum" description="The parameter count reporting threshold" value="15"/>
            <property name="only-methods" description="Comma-separated methods for which rule will be applied" value="__construct"/>
            <property name="except-methods" description="Comma-separated methods for which rule won't applied" value=""/>
        </properties>
        <example>
            <![CDATA[
class Foo {
    public function addData(
        $p0, $p1, $p2, $p3, $p4, $p5,
        $p5, $p6, $p7, $p8, $p9, $p10) {
    }
}
            ]]>
        </example>

    </rule>
    <rule name="LongParameterList"
          since="0.1"
          message="The {0} {1} has {2} parameters. Consider reducing the number of parameters to less than {3}."
          class="TeamVieMagentower\MagentoCodeMessDetector\Rule\Design\LongParameterList"
          externalInfoUrl="https://phpmd.org/rules/codesize.html#excessiveparameterlist">
    <description>
        Long parameter lists can indicate that a new object should be created to
        wrap the numerous parameters.  Basically, try to group the parameters together.
    </description>
    <priority>3</priority>
    <properties>
        <property name="minimum" description="The parameter count reporting threshold" value="4"/>
        <property name="only-methods" description="Comma-separated methods for which rule will be applied" value=""/>
        <property name="except-methods" description="Comma-separated methods for which rule won't applied" value="__construct"/>
    </properties>
    <example>
        <![CDATA[
    class Foo {
        public function addData(
            $p0, $p1, $p2, $p3, $p4, $p5,
            $p5, $p6, $p7, $p8, $p9, $p10) {
        }
    }
                ]]>
    </example>
    
    </rule>
</ruleset>
```

### \MagentoBuild\MagentoCodeMessDetector\Rule\Design\CookieAndSessionMisuse

Implementation of `\MagentoBuild\CodeMessDetector\Rule\Design\CookieAndSessionMisuse`, additional check of using
classes with `Session` and `Cookie` in name in addition to implementation of interfaces.

### \MagentoBuild\MagentoCodeMessDetector\Rule\Design\RequestMisuse

Rule to check whether `\MagentoBuild\Framework\App\RequestInterface` is used only in Presentation layer.
