<?php

/*
 +----------------------------------------------------------------------+
 | Zephir Language                                                      |
 +----------------------------------------------------------------------+
 | Copyright (c) 2013-2015 Zephir Team                                  |
 +----------------------------------------------------------------------+
 | This source file is subject to version 1.0 of the MIT license,       |
 | that is bundled with this package in the file LICENSE, and is        |
 | available through the world-wide-web at the following url:           |
 | http://www.zephir-lang.com/license                                   |
 |                                                                      |
 | If you did not receive a copy of the MIT license and are unable      |
 | to obtain it through the world-wide-web, please send a note to       |
 | license@zephir-lang.com so we can mail you a copy immediately.       |
 +----------------------------------------------------------------------+
*/

namespace Zephir;

use Zephir\HeadersManager;

/**
 * ClassDefinition
 *
 * Represents a class/interface and their properties and methods
 */
class ClassDefinition
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type = 'class';

    /**
     * @var string
     */
    protected $extendsClass;

    /**
     * @var array
     */
    protected $interfaces;

    /**
     * @var bool
     */
    protected $final;

    /**
     * @var bool
     */
    protected $abstract;

    /**
     * @var bool
     */
    protected $external = false;

    /**
     * @var ClassDefinition
     */
    protected $extendsClassDefinition;

    /**
     * @var ClassDefinition[]
     */
    protected $implementedInterfaceDefinitions;

    /**
     * @var ClassProperty[]
     */
    protected $properties = array();

    /**
     * @var ClassConstant[]
     */
    protected $constants = array();

    /**
     * @var ClassMethod[]
     */
    protected $methods = array();

    /**
     * @var int
     */
    protected $dependencyRank = 0;

    protected $originalNode;

    /**
     * @var EventsManager
     */
    protected $eventsManager;

    protected $isInternal = false;

    /**
     * @var AliasManager
     */
    protected $_aliasManager = null;

    /**
     * ClassDefinition
     *
     * @param string $namespace
     * @param string $name
     */
    public function __construct($namespace, $name)
    {
        $this->namespace = $namespace;
        $this->name = $name;

        $this->eventsManager = new EventsManager();
    }

    /**
     * Sets if the class is internal or not
     *
     * @param boolean $isInternal
     */
    public function setIsInternal($isInternal)
    {
        $this->isInternal = $isInternal;
    }

    /**
     * Returns whether the class is internal or not
     *
     * @return bool
     */
    public function isInternal()
    {
        return $this->isInternal;
    }

    /**
     * Sets whether the class is external or not
     *
     * @param boolean $isExternal
     */
    public function setIsExternal($isExternal)
    {
        $this->external = $isExternal;
    }

    /**
     * Returns whether the class is internal or not
     *
     * @return bool
     */
    public function isExternal()
    {
        return $this->external;
    }

    /**
     * Get eventsManager for class definition
     *
     * @return EventsManager
     */
    public function getEventsManager()
    {
        return $this->eventsManager;
    }

    /**
     * Set the class' type (class/interface)
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Returns the class type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the class name without namespace
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Check if the class definition correspond to an interface
     *
     * @return boolean
     */
    public function isInterface()
    {
        return $this->type == 'interface';
    }

    /**
     * Sets if the class is final
     *
     * @param boolean $final
     */
    public function setIsFinal($final)
    {
        $this->final = (bool) $final;
    }

    /**
     * Sets if the class is final
     *
     * @param boolean $abstract
     */
    public function setIsAbstract($abstract)
    {
        $this->abstract = (bool) $abstract;
    }

    /**
     * Checks whether the class is abstract or not
     *
     * @return boolean
     */
    public function isAbstract()
    {
        return $this->abstract;
    }

    /**
     * Checks whether the class is abstract or not
     *
     * @return boolean
     */
    public function isFinal()
    {
        return $this->final;
    }

    /**
     * Returns the class name including its namespace
     *
     * @return string
     */
    public function getCompleteName()
    {
        return $this->namespace . '\\' . $this->name;
    }

    /**
     * Return the class namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Set the original node where the class was declared
     *
     * @param array $originalNode
     */
    public function setOriginalNode(array $originalNode)
    {
        $this->originalNode = $originalNode;
    }

    /**
     * Sets the extended class
     *
     * @param string $extendsClass
     */
    public function setExtendsClass($extendsClass)
    {
        $this->extendsClass = $extendsClass;
    }

    /**
     * Sets the implemented interfaces
     *
     * @param array $implementedInterfaces
     */
    public function setImplementsInterfaces(array $implementedInterfaces)
    {
        $interfaces = array();
        foreach ($implementedInterfaces as $implementedInterface) {
            $interfaces[] = $implementedInterface['value'];
        }

        $this->interfaces = $interfaces;
    }

    /**
     * Returns the extended class
     *
     * @return string
     */
    public function getExtendsClass()
    {
        return $this->extendsClass;
    }

    /**
     * Returns the implemented interfaces
     *
     * @return array
     */
    public function getImplementedInterfaces()
    {
        return $this->interfaces;
    }

    /**
     * Sets the class definition for the extended class
     *
     * @param $classDefinition
     */
    public function setExtendsClassDefinition(ClassDefinition $classDefinition)
    {
        $this->extendsClassDefinition = $classDefinition;
    }

    /**
     * Returns the class definition related to the extended class
     *
     * @return ClassDefinition
     */
    public function getExtendsClassDefinition()
    {
        return $this->extendsClassDefinition;
    }

    /**
     * Sets the class definition for the implemented interfaces
     *
     * @param ClassDefinition[] $implementedInterfaceDefinitions
     */
    public function setImplementedInterfaceDefinitions(array $implementedInterfaceDefinitions)
    {
        $this->implementedInterfaceDefinitions = $implementedInterfaceDefinitions;
    }

    /**
     * Returns the class definition for the implemented interfaces
     *
     * @return ClassDefinition[]
     */
    public function getImplementedInterfaceDefinitions()
    {
        return $this->implementedInterfaceDefinitions;
    }

    /**
     * Calculate the dependency rank of the class based on its dependencies
     *
     */
    public function getDependencies()
    {
        $dependencies = array();
        if ($this->extendsClassDefinition) {
            $classDefinition = $this->extendsClassDefinition;
            if (method_exists($classDefinition, 'increaseDependencyRank')) {
                $dependencies[] = $classDefinition;
            }
        }

        if ($this->implementedInterfaceDefinitions) {
            foreach ($this->implementedInterfaceDefinitions as $interfaceDefinition) {
                if (method_exists($interfaceDefinition, 'increaseDependencyRank')) {
                    $dependencies[] = $interfaceDefinition;
                }
            }
        }
        return $dependencies;
    }

    /**
     * A class definition calls this method to mark this class as a dependency of another
     *
     * @param int $rank
     */
    public function increaseDependencyRank($rank)
    {
        $this->dependencyRank += ($rank + 1);
    }

    /**
     * Returns the dependency rank for this class
     *
     * @return int
     */
    public function getDependencyRank()
    {
        return $this->dependencyRank;
    }

    /**
     * Adds a property to the definition
     *
     * @param ClassProperty $property
     * @throws CompilerException
     */
    public function addProperty(ClassProperty $property)
    {
        if (isset($this->properties[$property->getName()])) {
            throw new CompilerException("Property '" . $property->getName() . "' was defined more than one time", $property->getOriginal());
        }

        $this->properties[$property->getName()] = $property;
    }

    /**
     * Adds a constant to the definition
     *
     * @param ClassConstant $constant
     * @throws CompilerException
     */
    public function addConstant(ClassConstant $constant)
    {
        if (isset($this->constants[$constant->getName()])) {
            throw new CompilerException("Constant '" . $constant->getName() . "' was defined more than one time");
        }

        $this->constants[$constant->getName()] = $constant;
    }

    /**
     * Checks if a class definition has a property
     *
     * @param string $name
     * @return boolean
     */
    public function hasProperty($name)
    {
        if (isset($this->properties[$name])) {
            return true;
        } else {
            $extendsClassDefinition = $this->extendsClassDefinition;
            if ($extendsClassDefinition) {
                if ($extendsClassDefinition->hasProperty($name)) {
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * Returns a method definition by its name
     *
     * @param string string
     * @return boolean|ClassProperty
     */
    public function getProperty($propertyName)
    {
        if (isset($this->properties[$propertyName])) {
            return $this->properties[$propertyName];
        }

        $extendsClassDefinition = $this->extendsClassDefinition;
        if ($extendsClassDefinition) {
            if ($extendsClassDefinition->hasProperty($propertyName)) {
                return $extendsClassDefinition->getProperty($propertyName);
            }
        }
        return false;
    }

    /**
     * Checks if class definition has a property
     *
     * @param string $name
     */
    public function hasConstant($name)
    {
        if (isset($this->constants[$name])) {
            return true;
        }
        /**
         * @todo add code to check if constant is defined in interfaces
         */
        return false;
    }

    /**
     * Returns a constant definition by its name
     *
     * @param string $constantName
     * @return bool|ClassConstant
     */
    public function getConstant($constantName)
    {
        if (!is_string($constantName)) {
            throw new \InvalidArgumentException('$constantName must be string type');
        }

        if (empty($constantName)) {
            throw new \InvalidArgumentException('$constantName must not be empty: ' . $constantName);
        }

        if (isset($this->constants[$constantName])) {
            return $this->constants[$constantName];
        }

        /**
         * @todo add code to get constant from interfaces
         */
        return false;
    }

    /**
     * Adds a method to the class definition
     *
     * @param ClassMethod $method
     * @param array $statement
     */
    public function addMethod(ClassMethod $method, $statement = null)
    {
        $methodName = strtolower($method->getName());
        if (isset($this->methods[$methodName])) {
            throw new CompilerException("Method '" . $method->getName() . "' was defined more than one time", $statement);
        }

        $this->methods[$methodName] = $method;
    }

    /**
     * Returns all properties defined in the class
     *
     * @return ClassProperty[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Returns all constants defined in the class
     *
     * @return ClassConstant[]
     */
    public function getConstants()
    {
        return $this->constants;
    }

    /**
     * Returns all methods defined in the class
     * @return ClassMethod[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * Checks if the class implements an specific name
     *
     * @param string string
     * @return boolean
     */
    public function hasMethod($methodName)
    {
        $methodNameLower = strtolower($methodName);
        foreach ($this->methods as $name => $method) {
            if ($methodNameLower == $name) {
                return true;
            }
        }

        $extendsClassDefinition = $this->extendsClassDefinition;
        if ($extendsClassDefinition) {
            if ($extendsClassDefinition->hasMethod($methodName)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns a method by its name
     *
     * @param string string
     * @return boolean|ClassMethod
     */
    public function getMethod($methodName)
    {
        $methodNameLower = strtolower($methodName);
        foreach ($this->methods as $name => $method) {
            if ($methodNameLower == $name) {
                return $method;
            }
        }

        $extendsClassDefinition = $this->extendsClassDefinition;
        if ($extendsClassDefinition) {
            if ($extendsClassDefinition->hasMethod($methodName)) {
                return $extendsClassDefinition->getMethod($methodName);
            }
        }
        return false;
    }

    /**
     * Set a method and its body
     *
     * @param $methodName
     * @param ClassMethod $method
     */
    public function setMethod($methodName, ClassMethod $method)
    {
        $this->methods[$methodName] = $method;
    }

    /**
     * Sets class methods externally
     *
     * @param array $methods
     */
    public function setMethods($methods)
    {
        $this->methods = $methods;
    }

    /**
     * Tries to find the most similar name
     *
     * @param string $methodName
     * @return string|boolean
     */
    public function getPossibleMethodName($methodName)
    {
        $methodNameLower = strtolower($methodName);

        foreach ($this->methods as $name => $method) {
            if (metaphone($methodNameLower) == metaphone($name)) {
                return $method->getName();
            }
        }

        $extendsClassDefinition = $this->extendsClassDefinition;
        if ($extendsClassDefinition) {
            return $extendsClassDefinition->getPossibleMethodName($methodName);
        }

        return false;
    }

    /**
     * Returns the name of the zend_class_entry according to the class name
     *
     * @param CompilationContext $compilationContext
     * @return string
     */
    public function getClassEntry(CompilationContext $compilationContext = null)
    {
        if ($this->external) {
            if (!is_object($compilationContext)) {
                throw new Exception('A compilation context is required');
            }

            /**
             * Automatically add the external header
             */
            $compilationContext->headersManager->add($this->getExternalHeader(), HeadersManager::POSITION_LAST);
        }
        return strtolower(str_replace('\\', '_', $this->namespace) . '_' . $this->name) . '_ce';
    }

    /**
     * Returns a valid namespace to be used in C-sources
     *
     * @return string
     */
    public function getCNamespace()
    {
        return str_replace('\\', '_', $this->namespace);
    }

    /**
     * Returns a valid namespace to be used in C-sources
     *
     * @return string
     */
    public function getNCNamespace()
    {
        return str_replace('\\', '\\\\', $this->namespace);
    }

    /**
     * Class name without namespace prefix for class registration
     *
     * @param string $namespace
     * @return string
     */
    public function getSCName($namespace)
    {
        return str_replace($namespace . "_", "", strtolower(str_replace('\\', '_', $this->namespace) . '_' . $this->name));
    }

    /**
     * Returns an absolute location to the class header
     *
     * @return string
     */
    public function getExternalHeader()
    {
        $parts = explode('\\', $this->namespace);
        return 'ext/' . strtolower($parts[0] . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $this->namespace) . DIRECTORY_SEPARATOR . $this->name) . '.zep';
    }

    /**
     * Checks if a class implements an interface
     *
     * @param ClassDefinition $classDefinition
     * @param ClassDefinition $interfaceDefinition
     * @throws CompilerException
     */
    public function checkInterfaceImplements(ClassDefinition $classDefinition, ClassDefinition $interfaceDefinition)
    {
        foreach ($interfaceDefinition->getMethods() as $method) {
            if (!$classDefinition->hasMethod($method->getName())) {
                throw new CompilerException("Class " . $classDefinition->getCompleteName() . " must implement method: " . $method->getName() . " defined on interface: " . $interfaceDefinition->getCompleteName());
            }

            if ($method->hasParameters()) {
                $implementedMethod = $classDefinition->getMethod($method->getName());
                if ($implementedMethod->getNumberOfRequiredParameters() > $method->getNumberOfRequiredParameters() || $implementedMethod->getNumberOfParameters() < $method->getNumberOfParameters()) {
                    throw new CompilerException("Class " . $classDefinition->getCompleteName() . "::" . $method->getName() . "() does not have the same number of required parameters in interface: " . $interfaceDefinition->getCompleteName());
                }
            }
        }
    }

    /**
     * Pre-compiles a class/interface gathering method information required by other methods
     *
     * @param CompilationContext $compilationContext
     * @throws CompilerException
     */
    public function preCompile(CompilationContext $compilationContext)
    {
        /**
         * Pre-Compile methods
         */
        foreach ($this->methods as $method) {
            if ($this->getType() == 'class' && !$method->isAbstract()) {
                $method->preCompile($compilationContext);
            }
        }
    }

    /**
     * Compiles a class/interface
     *
     * @param CompilationContext $compilationContext
     * @throws CompilerException
     */
    public function compile(CompilationContext $compilationContext)
    {
        /**
         * Sets the current object as global class definition
         */
        $compilationContext->classDefinition = $this;

        /**
         * Get the global codePrinter
         */
        $codePrinter = $compilationContext->codePrinter;

        /**
         * The ZEPHIR_INIT_CLASS defines properties and constants exported by the class
         */
        $codePrinter->output('ZEPHIR_INIT_CLASS(' . $this->getCNamespace() . '_' . $this->getName() . ') {');
        $codePrinter->outputBlankLine();

        $codePrinter->increaseLevel();

        /**
         * Method entry
         */
        $methods = &$this->methods;

        if (count($methods)) {
            $methodEntry = strtolower($this->getCNamespace()) . '_' . strtolower($this->getName()) . '_method_entry';
        } else {
            $methodEntry = 'NULL';
        }

        $namespace = str_replace('\\', '_', $compilationContext->config->get('namespace'));

        $flags = '0';
        if ($this->isAbstract()) {
            $flags = 'ZEND_ACC_EXPLICIT_ABSTRACT_CLASS';
        }
        if ($this->isFinal()) {
            if ($flags == '0') {
                $flags = 'ZEND_ACC_FINAL_CLASS';
            } else {
                $flags .= '|ZEND_ACC_FINAL_CLASS';
            }
        }

        /**
         * Register the class with extends + interfaces
         */
        $classExtendsDefinition = null;
        if ($this->extendsClass) {
            $classExtendsDefinition = $this->extendsClassDefinition;
            if (!$classExtendsDefinition->isInternal()) {
                $classEntry = $classExtendsDefinition->getClassEntry($compilationContext);
            } else {
                $classEntry = $this->getClassEntryByClassName($classExtendsDefinition->getName(), $compilationContext);
            }

            if ($this->getType() == 'class') {
                $codePrinter->output('ZEPHIR_REGISTER_CLASS_EX(' . $this->getNCNamespace() . ', ' . $this->getName() . ', ' . $namespace . ', ' . strtolower($this->getSCName($namespace)) . ', ' . $classEntry . ', ' . $methodEntry . ', ' . $flags . ');');
                $codePrinter->outputBlankLine();
            } else {
                $codePrinter->output('ZEPHIR_REGISTER_INTERFACE_EX(' . $this->getNCNamespace() . ', ' . $this->getName() . ', ' . $namespace . ', ' . strtolower($this->getSCName($namespace)) . ', ' . $classEntry . ', ' . $methodEntry . ');');
                $codePrinter->outputBlankLine();
            }
        } else {
            if ($this->getType() == 'class') {
                $codePrinter->output('ZEPHIR_REGISTER_CLASS(' . $this->getNCNamespace() . ', ' . $this->getName() . ', ' . $namespace . ', ' . strtolower($this->getSCName($namespace)) . ', ' . $methodEntry . ', ' . $flags . ');');
            } else {
                $codePrinter->output('ZEPHIR_REGISTER_INTERFACE(' . $this->getNCNamespace() . ', ' . $this->getName() . ', ' . $namespace . ', ' . strtolower($this->getSCName($namespace)) . ', ' . $methodEntry . ');');
            }
            $codePrinter->outputBlankLine();
        }

        $needBreak = true;

        /**
         * @todo Remove after removing support for php 5.3
         */
        $currentClassHref = & $this;

        $this->eventsManager->listen('setMethod', function (ClassMethod $method) use (&$methods, &$currentClassHref, $compilationContext, &$needBreak) {
            $needBreak = false;
            $methods[$method->getName()] = $method;
            $compilationContext->classDefinition->setMethods($methods);
        });

        /**
         * Compile properties
         * @var $property ClassProperty
         */
        foreach ($this->getProperties() as $property) {
            $docBlock = $property->getDocBlock();
            if ($docBlock) {
                $codePrinter->outputDocBlock($docBlock, true);
            }

            $property->compile($compilationContext);
            $codePrinter->outputBlankLine();
        }

        if (!$needBreak) {
            $compilationContext->codePrinter->clear();
            $currentClassHref->compile($compilationContext);
            return;
        }

        /**
         * Compile constants
         * @var $constant ClassConstant
         */
        foreach ($this->getConstants() as $constant) {
            $docBlock = $constant->getDocBlock();
            if ($docBlock) {
                $codePrinter->outputDocBlock($docBlock, true);
            }

            $constant->compile($compilationContext);
            $codePrinter->outputBlankLine();
        }

        /**
         * Implemented interfaces
         */
        $interfaces = $this->interfaces;
        $compiler = $compilationContext->compiler;

        if (is_array($interfaces)) {
            $codePrinter->outputBlankLine(true);

            foreach ($interfaces as $interface) {
                /**
                 * Try to find the interface
                 */
                $classEntry = false;

                if ($compiler->isInterface($interface)) {
                    $classInterfaceDefinition = $compiler->getClassDefinition($interface);
                    $classEntry = $classInterfaceDefinition->getClassEntry($compilationContext);
                } else {
                    if ($compiler->isInternalInterface($interface)) {
                        $classInterfaceDefinition = $compiler->getInternalClassDefinition($interface);
                        $classEntry = $this->getClassEntryByClassName($classInterfaceDefinition->getName(), $compilationContext);
                    }
                }

                if (!$classEntry) {
                    if ($compiler->isClass($interface)) {
                        throw new CompilerException("Cannot locate interface " . $interface . " when implementing interfaces on " . $this->getCompleteName() . '. ' . $interface . ' is currently a class', $this->originalNode);
                    } else {
                        throw new CompilerException("Cannot locate interface " . $interface . " when implementing interfaces on " . $this->getCompleteName(), $this->originalNode);
                    }
                }

                /**
                 * We don't check if abstract classes implement the methods in their interfaces
                 */
                if (!$this->isAbstract() && !$this->isInterface()) {
                    $this->checkInterfaceImplements($this, $classInterfaceDefinition);
                }

                $codePrinter->output('zend_class_implements(' . $this->getClassEntry() . ' TSRMLS_CC, 1, ' . $classEntry . ');');
            }
        }

        if (!$this->isAbstract() && !$this->isInterface()) {
            /**
             * Interfaces in extended classes may have
             */
            if ($classExtendsDefinition) {
                if (!$classExtendsDefinition->isInternal()) {
                    $interfaces = $classExtendsDefinition->getImplementedInterfaces();
                    if (is_array($interfaces)) {
                        foreach ($interfaces as $interface) {
                            $classInterfaceDefinition = null;
                            if ($compiler->isInterface($interface)) {
                                $classInterfaceDefinition = $compiler->getClassDefinition($interface);
                            } else {
                                if ($compiler->isInternalInterface($interface)) {
                                    $classInterfaceDefinition = $compiler->getInternalClassDefinition($interface);
                                }
                            }

                            if ($classInterfaceDefinition) {
                                $this->checkInterfaceImplements($this, $classInterfaceDefinition);
                            }
                        }
                    }
                }
            }
        }

        $codePrinter->output('return SUCCESS;');

        $codePrinter->outputBlankLine();
        $codePrinter->decreaseLevel();

        $codePrinter->output('}');
        $codePrinter->outputBlankLine();

        /**
         * Compile methods
         */
        foreach ($methods as $method) {
            $docBlock = $method->getDocBlock();
            if ($docBlock) {
                $codePrinter->outputDocBlock($docBlock);
            }

            if ($this->getType() == 'class') {
                $codePrinter->output('PHP_METHOD(' . $this->getCNamespace() . '_' . $this->getName() . ', ' . $method->getName() . ') {');
                $codePrinter->outputBlankLine();

                if (!$method->isAbstract()) {
                    $method->compile($compilationContext);
                }

                $codePrinter->output('}');
                $codePrinter->outputBlankLine();
            } else {
                $codePrinter->output('ZEPHIR_DOC_METHOD(' . $this->getCNamespace() . '_' . $this->getName() . ', ' . $method->getName() . ');');
                $codePrinter->outputBlankLine();
            }
        }

        /**
         * Check whether classes must be exported
         */
        $exportClasses = $compilationContext->config->get('export-classes', 'extra');
        if ($exportClasses) {
            $exportAPI = 'extern ZEPHIR_API';
        } else {
            $exportAPI = 'extern';
        }

        /**
         * Create a code printer for the header file
         */
        $codePrinter = new CodePrinter();

        $codePrinter->outputBlankLine();
        $codePrinter->output($exportAPI . ' zend_class_entry *' . $this->getClassEntry() . ';');
        $codePrinter->outputBlankLine();

        $codePrinter->output('ZEPHIR_INIT_CLASS(' . $this->getCNamespace() . '_' . $this->getName() . ');');
        $codePrinter->outputBlankLine();

        if ($this->getType() == 'class') {
            if (count($methods)) {
                foreach ($methods as $method) {
                    $codePrinter->output('PHP_METHOD(' . $this->getCNamespace() . '_' . $this->getName() . ', ' . $method->getName() . ');');
                }
                $codePrinter->outputBlankLine();
            }
        }

        /**
         * Create argument info
         */
        foreach ($methods as $method) {
            $parameters = $method->getParameters();
            if (count($parameters)) {
                $codePrinter->output('ZEND_BEGIN_ARG_INFO_EX(arginfo_' . strtolower($this->getCNamespace() . '_' . $this->getName() . '_' . $method->getName()) . ', 0, 0, ' . $method->getNumberOfRequiredParameters() . ')');
                foreach ($parameters->getParameters() as $parameter) {
                    switch ($parameter['data-type']) {

                        case 'array':
                            $codePrinter->output("\t" . 'ZEND_ARG_ARRAY_INFO(0, ' . $parameter['name'] . ', ' . (isset($parameter['default']) ? 1 : 0) . ')');
                            break;

                        case 'variable':
                            if (isset($parameter['cast'])) {
                                switch ($parameter['cast']['type']) {
                                    case 'variable':
                                        $value = $parameter['cast']['value'];
                                        $codePrinter->output("\t" . 'ZEND_ARG_OBJ_INFO(0, ' . $parameter['name'] . ', ' . Utils::escapeClassName($compilationContext->getFullName($value)) . ', ' . (isset($parameter['default']) ? 1 : 0) . ')');
                                        break;

                                    default:
                                        throw new Exception('Unexpected exception');
                                }
                            } else {
                                $codePrinter->output("\t" . 'ZEND_ARG_INFO(0, ' . $parameter['name'] . ')');
                            }
                            break;

                        default:
                            $codePrinter->output("\t" . 'ZEND_ARG_INFO(0, ' . $parameter['name'] . ')');
                            break;
                    }
                }
                $codePrinter->output('ZEND_END_ARG_INFO()');
                $codePrinter->outputBlankLine();
            }
        }

        if (count($methods)) {
            $codePrinter->output('ZEPHIR_INIT_FUNCS(' . strtolower($this->getCNamespace() . '_' . $this->getName()) . '_method_entry) {');
            foreach ($methods as $method) {
                $parameters = $method->getParameters();
                if ($this->getType() == 'class') {
                    if (count($parameters)) {
                        $codePrinter->output("\t" . 'PHP_ME(' . $this->getCNamespace() . '_' . $this->getName() . ', ' . $method->getName() . ', arginfo_' . strtolower($this->getCNamespace() . '_' . $this->getName() . '_' . $method->getName()) . ', ' . $method->getModifiers() . ')');
                    } else {
                        $codePrinter->output("\t" . 'PHP_ME(' . $this->getCNamespace() . '_' . $this->getName() . ', ' . $method->getName() . ', NULL, ' . $method->getModifiers() . ')');
                    }
                } else {
                    if ($method->isStatic()) {
                        if (count($parameters)) {
                            $codePrinter->output("\t" . 'ZEND_FENTRY(' . $method->getName() . ', NULL, arginfo_' . strtolower($this->getCNamespace() . '_' . $this->getName() . '_' . $method->getName()) . ', ZEND_ACC_STATIC|ZEND_ACC_ABSTRACT|ZEND_ACC_PUBLIC)');
                        } else {
                            $codePrinter->output("\t" . 'ZEND_FENTRY(' . $method->getName() . ', NULL, NULL, ZEND_ACC_STATIC|ZEND_ACC_ABSTRACT|ZEND_ACC_PUBLIC)');
                        }
                    } else {
                        if (count($parameters)) {
                            $codePrinter->output("\t" . 'PHP_ABSTRACT_ME(' . $this->getCNamespace() . '_' . $this->getName() . ', ' . $method->getName() . ', arginfo_' . strtolower($this->getCNamespace() . '_' . $this->getName() . '_' . $method->getName()) . ')');
                        } else {
                            $codePrinter->output("\t" . 'PHP_ABSTRACT_ME(' . $this->getCNamespace() . '_' . $this->getName() . ', ' . $method->getName() . ', NULL)');
                        }
                    }
                }
            }
            $codePrinter->output('  PHP_FE_END');
            $codePrinter->output('};');
        }

        $compilationContext->headerPrinter = $codePrinter;
    }

    /**
     * @return AliasManager
     */
    public function getAliasManager()
    {
        return $this->_aliasManager;
    }

    /**
     * @param AliasManager $aliasManager
     */
    public function setAliasManager(AliasManager $aliasManager)
    {
        $this->_aliasManager = $aliasManager;
    }

    /**
     * Convert Class/Interface name to C ClassEntry
     *
     * @param  string $className
     * @param  CompilationContext $compilationContext
     * @param  boolean $check
     * @return string
     * @throws CompilerException
     */
    public function getClassEntryByClassName($className, CompilationContext $compilationContext, $check = true)
    {
        switch (strtolower($className)) {

            /**
             * Zend classes
             */
            case 'exception':
                $classEntry = 'zend_exception_get_default(TSRMLS_C)';
                break;

            /**
             * Zend interfaces (Zend/zend_interfaces.h)
             */
            case 'iterator':
                $classEntry = 'zend_ce_iterator';
                break;

            case 'arrayaccess':
                $classEntry = 'zend_ce_arrayaccess';
                break;

            case 'serializable':
                $classEntry = 'zend_ce_serializable';
                break;

            case 'iteratoraggregate':
                $classEntry = 'zend_ce_aggregate';
                break;

            /**
             * SPL Exceptions
             */
            case 'logicexception':
                $compilationContext->headersManager->add('ext/spl/spl_exceptions');
                $classEntry = 'spl_ce_LogicException';
                break;

            case 'badfunctioncallexception':
                $compilationContext->headersManager->add('ext/spl/spl_exceptions');
                $classEntry = 'spl_ce_BadFunctionCallException';
                break;

            case 'badmethodcallexception':
                $compilationContext->headersManager->add('ext/spl/spl_exceptions');
                $classEntry = 'spl_ce_BadMethodCallException';
                break;

            case 'domainexception':
                $compilationContext->headersManager->add('ext/spl/spl_exceptions');
                $classEntry = 'spl_ce_DomainException';
                break;

            case 'invalidargumentexception':
                $compilationContext->headersManager->add('ext/spl/spl_exceptions');
                $classEntry = 'spl_ce_InvalidArgumentException';
                break;

            case 'lengthexception':
                $compilationContext->headersManager->add('ext/spl/spl_exceptions');
                $classEntry = 'spl_ce_LengthException';
                break;

            case 'outofrangeexception':
                $compilationContext->headersManager->add('ext/spl/spl_exceptions');
                $classEntry = 'spl_ce_OutOfRangeException';
                break;

            case 'runtimeexception':
                $compilationContext->headersManager->add('ext/spl/spl_exceptions');
                $classEntry = 'spl_ce_RuntimeException';
                break;

            case 'outofboundsexception':
                $compilationContext->headersManager->add('ext/spl/spl_exceptions');
                $classEntry = 'spl_ce_OutOfBoundsException';
                break;

            case 'overflowexception':
                $compilationContext->headersManager->add('ext/spl/spl_exceptions');
                $classEntry = 'spl_ce_OverflowException';
                break;

            case 'rangeexception':
                $compilationContext->headersManager->add('ext/spl/spl_exceptions');
                $classEntry = 'spl_ce_RangeException';
                break;

            case 'underflowexception':
                $compilationContext->headersManager->add('ext/spl/spl_exceptions');
                $classEntry = 'spl_ce_UnderflowException';
                break;

            case 'unexpectedvalueexception':
                $compilationContext->headersManager->add('ext/spl/spl_exceptions');
                $classEntry = 'spl_ce_UnexpectedValueException';
                break;

            /**
             * SPL Iterators Interfaces (spl/spl_iterators.h)
             */
            case 'recursiveiterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_RecursiveIterator';
                break;

            case 'recursiveiteratoriterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_RecursiveIteratorIterator';
                break;

            case 'recursivetreeiterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_RecursiveTreeIterator';
                break;

            case 'filteriterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_FilterIterator';
                break;

            case 'recursivefilteriterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_RecursiveFilterIterator';
                break;

            case 'parentiterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_ParentIterator';
                break;

            case 'seekableiterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_SeekableIterator';
                break;

            case 'limititerator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_LimitIterator';
                break;

            case 'cachingiterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_CachingIterator';
                break;

            case 'recursivecachingiterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_RecursiveCachingIterator';
                break;

            case 'outeriterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_OuterIterator';
                break;

            case 'iteratoriterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_IteratorIterator';
                break;

            case 'norewinditerator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_NoRewindIterator';
                break;

            case 'infiniteiterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_InfiniteIterator';
                break;

            case 'emptyiterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_EmptyIterator';
                break;

            case 'appenditerator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_AppendIterator';
                break;

            case 'regexiterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_RegexIterator';
                break;

            case 'recursiveregexiterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_RecursiveRegexIterator';
                break;

            case 'directoryiterator':
                $compilationContext->headersManager->add('ext/spl/spl_directory');
                $classEntry = 'spl_ce_DirectoryIterator';
                break;

            case 'filesystemiterator':
                $compilationContext->headersManager->add('ext/spl/spl_directory');
                $classEntry = 'spl_ce_FilesystemIterator';
                break;

            case 'recursivedirectoryiterator':
                $compilationContext->headersManager->add('ext/spl/spl_directory');
                $classEntry = 'spl_ce_RecursiveDirectoryIterator';
                break;

            case 'globiterator':
                $compilationContext->headersManager->add('ext/spl/spl_directory');
                $classEntry = 'spl_ce_GlobIterator';
                break;

            case 'splfileobject':
                $compilationContext->headersManager->add('ext/spl/spl_directory');
                $classEntry = 'spl_ce_SplFileObject';
                break;

            case 'spltempfileobject':
                $compilationContext->headersManager->add('ext/spl/spl_directory');
                $classEntry = 'spl_ce_SplTempFileObject';
                break;

            case 'countable':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_Countable';
                break;

            case 'callbackfilteriterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_CallbackFilterIterator';
                break;

            case 'recursivecallbackfilteriterator':
                $compilationContext->headersManager->add('ext/spl/spl_iterators');
                $classEntry = 'spl_ce_RecursiveCallbackFilterIterator';
                break;

            case 'arrayobject':
                $compilationContext->headersManager->add('ext/spl/spl_array');
                $classEntry = 'spl_ce_ArrayObject';
                break;

            case 'splfixedarray':
                $compilationContext->headersManager->add('ext/spl/spl_fixedarray');
                $classEntry = 'spl_ce_SplFixedArray';
                break;

            case 'splpriorityqueue':
                $compilationContext->headersManager->add('ext/spl/spl_heap');
                $classEntry = 'spl_ce_SplPriorityQueue';
                break;

            case 'splfileinfo':
                $compilationContext->headersManager->add('ext/spl/spl_directory');
                $classEntry = 'spl_ce_SplFileInfo';
                break;

            case 'splheap':
                $compilationContext->headersManager->add('ext/spl/spl_heap');
                $classEntry = 'spl_ce_SplHeap';
                break;

            case 'splminheap':
                $compilationContext->headersManager->add('ext/spl/spl_heap');
                $classEntry = 'spl_ce_SplMinHeap';
                break;

            case 'splmaxheap':
                $compilationContext->headersManager->add('ext/spl/spl_heap');
                $classEntry = 'spl_ce_SplMaxHeap';
                break;

            case 'splstack':
                $compilationContext->headersManager->add('ext/spl/spl_dllist');
                $classEntry = 'spl_ce_SplStack';
                break;

            case 'splqueue':
                $compilationContext->headersManager->add('ext/spl/spl_dllist');
                $classEntry = 'spl_ce_SplQueue';
                break;

            case 'spldoublylinkedlist':
                $compilationContext->headersManager->add('ext/spl/spl_dllist');
                $classEntry = 'spl_ce_SplDoublyLinkedList';
                break;

            case 'stdclass':
                $classEntry = 'zend_standard_class_def';
                break;

            case 'closure':
                $compilationContext->headersManager->add('Zend/zend_closures');
                $classEntry = 'zend_ce_closure';
                break;

            case 'pdo':
                $compilationContext->headersManager->add('ext/pdo/php_pdo_driver');
                $classEntry = 'php_pdo_get_dbh_ce()';
                break;

            case 'pdostatement':
                $compilationContext->headersManager->add('kernel/main');
                $classEntry = 'zephir_get_internal_ce(SS("pdostatement") TSRMLS_CC)';
                break;

            case 'pdoexception':
                $compilationContext->headersManager->add('ext/pdo/php_pdo_driver');
                $classEntry = 'php_pdo_get_exception()';
                break;

            case 'datetime':
                $compilationContext->headersManager->add('ext/date/php_date');
                $classEntry = 'php_date_get_date_ce()';
                break;

            case 'datetimezone':
                $compilationContext->headersManager->add('ext/date/php_date');
                $classEntry = 'php_date_get_timezone_ce()';
                break;

            // Reflection
            /*case 'reflector':
                $compilationContext->headersManager->add('ext/reflection/php_reflection');
                $classEntry = 'reflector_ptr';
                break;
            case 'reflectionexception':
                $compilationContext->headersManager->add('ext/reflection/php_reflection');
                $classEntry = 'reflection_exception_ptr';
                break;
            case 'reflection':
                $compilationContext->headersManager->add('ext/reflection/php_reflection');
                $classEntry = 'reflection_ptr';
                break;
            case 'reflectionfunctionabstract':
                $compilationContext->headersManager->add('ext/reflection/php_reflection');
                $classEntry = 'reflection_function_abstract_ptr';
                break;
            case 'reflectionfunction':
                $compilationContext->headersManager->add('ext/reflection/php_reflection');
                $classEntry = 'reflection_function_ptr';
                break;
            case 'reflectionparameter':
                $compilationContext->headersManager->add('ext/reflection/php_reflection');
                $classEntry = 'reflection_parameter_ptr';
                break;
            case 'reflectionclass':
                $compilationContext->headersManager->add('ext/reflection/php_reflection');
                $classEntry = 'reflection_class_ptr';
                break;
            case 'reflectionobject':
                $compilationContext->headersManager->add('ext/reflection/php_reflection');
                $classEntry = 'reflection_object_ptr';
                break;
            case 'reflectionmethod':
                $compilationContext->headersManager->add('ext/reflection/php_reflection');
                $classEntry = 'reflection_method_ptr';
                break;
            case 'reflectionproperty':
                $compilationContext->headersManager->add('ext/reflection/php_reflection');
                $classEntry = 'reflection_property_ptr';
                break;
            case 'reflectionextension':
                $compilationContext->headersManager->add('ext/reflection/php_reflection');
                $classEntry = 'reflection_extension_ptr';
                break;
            case 'reflectionzendextension':
                $compilationContext->headersManager->add('ext/reflection/php_reflection');
                $classEntry = 'reflection_zend_extension_ptr';
                break;*/

            default:
                if (!$check) {
                    throw new CompilerException('Unknown class entry for "' . $className . '"');
                } else {
                    $classEntry = 'zephir_get_internal_ce(SS("' . Utils::escapeClassName(strtolower($className)) . '") TSRMLS_CC)';
                }
        }

        return $classEntry;
    }

    /**
     * Builds a class definition from reflection
     *
     * @param \ReflectionClass $class
     */
    public static function buildFromReflection(\ReflectionClass $class)
    {
        $classDefinition = new ClassDefinition($class->getNamespaceName(), $class->getName());

        $methods = $class->getMethods();
        if (count($methods) > 0) {
            foreach ($methods as $method) {
                $parameters = array();

                foreach ($method->getParameters() as $row) {
                    $parameters[] = array(
                        'type' => 'parameter',
                        'name' => $row->getName(),
                        'const' => 0,
                        'data-type' => 'variable',
                        'mandatory' => !$row->isOptional()
                    );
                }

                $classMethod = new ClassMethod($classDefinition, array(), $method->getName(), new ClassMethodParameters(
                    $parameters
                ));
                $classMethod->setIsStatic($method->isStatic());
                $classMethod->setIsInternal(true);
                $classDefinition->addMethod($classMethod);
            }
        }

        $constants = $class->getConstants();
        if (count($constants) > 0) {
            foreach ($constants as $constantName => $constantValue) {
                $type = self::_convertPhpConstantType(gettype($constantValue));
                $classConstant = new ClassConstant($constantName, array('value' => $constantValue, 'type' => $type), null);
                $classDefinition->addConstant($classConstant);
            }
        }

        $classDefinition->setIsInternal(true);

        return $classDefinition;
    }

    private static function _convertPhpConstantType($phpType)
    {
        $map = array(
            'boolean' => 'bool',
            'integer' => 'int',
            'double' => 'double',
            'string' => 'string',
            'NULL' => 'null',
        );

        if (!isset($map[$phpType])) {
            throw new CompilerException("Cannot parse constant type '$phpType'");
        }

        return $map[$phpType];
    }
}
